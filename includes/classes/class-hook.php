<?php
/**
 * All Actions
 *
 * @author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


class WPDL_Actions {

	public function __construct() {

		add_action( 'init', array( $this, 'register_custom_sizes' ) );
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_action( 'wpdl_before_reviews', array( $this, 'after_review_submitted' ) );

		add_filter( 'wpdl_filters_directory_archive_query', array( __CLASS__, 'directory_archive_query' ), 10, 1 );

		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'ob_start' ) );
			add_action( 'wp_footer', array( $this, 'ob_end' ) );
			add_action( 'the_content', array( $this, 'print_notice_and_content' ), 1, 1 );
			add_action( 'body_class', array( $this, 'add_body_class' ) );

			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 10 );
			add_filter( 'the_title', array( $this, 'myaccount_page_title' ), 10, 2 );

			add_action( 'wp_head', array( $this, 'process_form_submission' ) );
		}
	}


	public function process_form_submission() {

		global $wpdl;

		/**
		 * Check New Directory Submission
		 *
		 * @action wpdl_new_directory_nonce_action
		 * @name wpdl_new_directory_nonce
		 */

		if ( isset( $_POST['wpdl_new_directory_nonce'] ) && wp_verify_nonce( $_POST['wpdl_new_directory_nonce'], 'wpdl_new_directory_nonce_action' ) ) {

			/**
			 * Execute before adding new directory
			 *
			 * @hook wpdl_before_new_directory_submission
			 */

			do_action( 'wpdl_before_new_directory_submission' );

			$new_directory_ID = wp_insert_post( array(
				'post_type'    => 'directory',
				'post_title'   => isset( $_POST['directory_title'] ) ? sanitize_text_field( $_POST['directory_title'] ) : '',
				'post_content' => isset( $_POST['directory_details'] ) ? wp_kses_post( $_POST['directory_details'] ) : '',
				'post_status'  => 'publish',
			) );

			$wpdl->Post_meta->save_directory( $new_directory_ID );

			/**
			 * Execute after adding new directory
			 *
			 * @hook wpdl_after_new_directory_submission
			 */

			do_action( 'wpdl_after_new_directory_submission', $new_directory_ID );

			/**
			 * Redirect to My Directories Page
			 */

			wp_safe_redirect( wpdl_get_directory_submission_url() );
			exit;
		}
	}


	public function myaccount_page_title( $title, $post ) {
		global $wp_query;

		if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_page() && get_post_type( $post ) == 'page' && wpdl_is_myaccount_page() ) {
			$endpoint       = wpdl_get_current_endpoint();
			$endpoint_title = $this->get_endpoint_title( $endpoint );
			$title          = $endpoint_title ? $endpoint_title : $title;

			/**
			 * Check for New Directory Page
			 */

			if ( wpdl_is_page( 'new_directory' ) ) {
				$title = esc_html( 'New Directory', TTDD );
			}


			remove_filter( 'the_title', 'myaccount_page_title' );
		}

		return $title;
	}


	/**
	 * Return Endpoint Title Dynamically
	 *
	 * @filter wpdl_filters_endpoint_title
	 *
	 * @param string $endpoint
	 *
	 * @return mixed|void
	 */

	public function get_endpoint_title( $endpoint = 'dashboard' ) {

		global $wpdl;

		$navs = $wpdl->get_myaccount_navigation();

		if ( isset( $navs[ $endpoint ] ) ) {
			$title = $navs[ $endpoint ];
		} else {
			$title = __( 'My Account', TTDD );
		}

		if ( ! is_user_logged_in() && $endpoint == 'dashboard' ) {
			$title = __( 'Login / Register', TTDD );
		}

		return apply_filters( 'wpdl_filters_endpoint_title', $title, $endpoint );
	}


	/**
	 * Add query vars.
	 *
	 * @param array $vars Query vars.
	 *
	 * @return array
	 */

	public function add_query_vars( $vars ) {

		global $wpdl;

		foreach ( $wpdl->get_query_vars() as $key => $var ) {
			$vars[] = $key;
		}

		return $vars;
	}


	/**
	 * Register Custom Endpoints
	 */

	public function add_endpoints() {

		global $wpdl;

		foreach ( $wpdl->get_query_vars() as $key => $var ) {
			if ( ! empty( $var ) ) {
				add_rewrite_endpoint( $var, EP_PERMALINK | EP_PAGES );
			}
		}
	}


	/**
	 * Filter Directory Archive Query
	 *
	 * @param $args
	 *
	 * @return mixed
	 */

	public static function directory_archive_query( $args ) {

		/**
		 * Sorting items
		 */

		if ( isset( $_GET['sort'] ) ) {
			switch ( sanitize_text_field( $_GET['sort'] ) ) {

				case 'ascending':
					$args['orderby'] = array(
						'date' => 'ASC',
						'ID'   => 'ASC',
					);
					break;

				case 'descending':
					$args['orderby'] = array(
						'date' => 'DESC',
						'ID'   => 'ASC',
					);
					break;

				case 'price_low_to_high':
					$args['orderby']  = array(
						'meta_value_num' => 'ASC',
						'ID'             => 'ASC',
					);
					$args['meta_key'] = '_dir_price';
					break;

				case 'price_high_to_low':
					$args['orderby']  = array(
						'meta_value_num' => 'DESC',
						'ID'             => 'ASC',
					);
					$args['meta_key'] = '_dir_price';

					break;

				case 'best_rated':
					$args['order']    = 'ASC';
					$args['order_by'] = 'comment_count';
					break;
			}
		}

		return $args;
	}


	/**
	 * Action - After Review Submitted
	 *
	 * @param $directory Single Directory Object
	 * @param $posted_data array
	 *
	 * @hooked_from  wpdl_after_review_submitted 10
	 */

	public function after_review_submitted() {

		if ( ! is_singular( 'directory' ) || ! isset( $_POST['wpdl_review_form_name'] ) || ! wp_verify_nonce( $_POST['wpdl_review_form_name'], 'wpdl_review_form_nonce' ) ) {
			return;
		}

		$review_rating  = isset( $_POST['wpdl_directory_review_rating'] ) ? sanitize_text_field( $_POST['wpdl_directory_review_rating'] ) : 0;
		$review_details = isset( $_POST['wpdl_directory_review_details'] ) ? sanitize_text_field( $_POST['wpdl_directory_review_details'] ) : '';

		if ( $review_rating == 0 || empty( $review_details ) ) {
			return;
		}

		$directory        = wpdl_get_directory();
		$current_user     = wp_get_current_user();
		$comment_approved = get_option( 'comment_approved', true );
		$comment_approved = empty( $comment_approved ) ? true : $comment_approved;

		$data = array(
			'comment_post_ID'      => $directory->get_id(),
			'comment_author'       => $current_user->user_login,
			'comment_author_email' => $current_user->user_email,
			'comment_content'      => $review_details,
			'comment_type'         => '',
			'comment_parent'       => 0,
			'user_id'              => $current_user->ID,
			'comment_date'         => current_time( 'mysql' ),
			'comment_approved'     => $comment_approved,
		);

		do_action( 'wpdl_before_review_submit' );

		$comment_ID = wp_insert_comment( $data );

		do_action( 'wpdl_after_review_submit', $comment_ID, $directory );

		update_comment_meta( $comment_ID, 'wpdl_review_rating', $review_rating );

		wp_redirect( $directory->get_permalink() );
		exit();
	}


	/**
	 * Register Custom Image Sizes
	 */

	public function register_custom_sizes() {

		add_image_size( 'wpdl_archive_thumb', 300, 200, true );
		add_image_size( 'wpdl_featured_image', 800, 600, true );
		add_image_size( 'wpdl_image_long', 465, 420, true );
		add_image_size( 'wpdl_gallery_image', 400, 300, true );
		add_image_size( 'wpdl_gallery_preview', 100, 75, true );
	}

	public function add_body_class( $classes ) {

		global $directory;

		if ( is_singular( 'directory' ) && ! $directory->has_gallery() ) {
			$classes[] = 'has-no-gallery';
		}

		return $classes;
	}


	/**
	 * Manage and Display Notice in front end
	 *
	 * @param $content
	 *
	 * @return string
	 */

	public function print_notice_and_content( $content ) {

		ob_start();

		printf( '<div class="wpdl-notice-wrap">' );

		do_action( 'wpdl_notice' );

		printf( '</div>' );

		if ( wpdl_is_page( 'myaccount' ) ) {

			remove_filter( 'the_content', 'wpautop' );
			echo do_shortcode( '[wpdl_my_account]' );
		}

		if ( wpdl_is_page( 'directory_archive' ) ) {
			remove_filter( 'the_content', 'wpautop' );
			echo do_shortcode( '[directory_archive]' );
		}

		return ob_get_clean() . $content;
	}


	/**
	 * Return Buffered Content
	 *
	 * @param $buffer
	 *
	 * @return mixed
	 */

	public function ob_callback( $buffer ) {
		return $buffer;
	}


	/**
	 * Start of Output Buffer
	 */

	public function ob_start() {
		ob_start( array( $this, 'ob_callback' ) );
	}


	/**
	 * End of Output Buffer
	 */

	public function ob_end() {
		if ( ob_get_length() ) {
			ob_end_flush();
		}
	}
}

new WPDL_Actions();
