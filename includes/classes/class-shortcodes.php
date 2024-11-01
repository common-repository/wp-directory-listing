<?php
/**
 * Shortcode Class for Directory Listing
 *
 * @author        Pluginrox
 * @copyright    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WPDL_Shortcodes {

	public static $shortcodes = array();

	public function __construct() {

		self::init();
		self::add_shortcodes();
	}

	public static function init() {

		self::$shortcodes = array(
			'directory_page'        => __CLASS__ . '::directory_page',
			'directory_archive'     => __CLASS__ . '::directory_archive',
			'wpdl_my_account'       => __CLASS__ . '::my_account',
			'wpdl_featured_listing' => __CLASS__ . '::featured_listing',
		);
	}


	/**
     * Display Featured Directory Items
     *
     * @shortcode [wpdl_featured_listing items="10" show_sorting="yes" show_count="yes" show_pagination="yes" ]
     *
	 * @param array $atts
	 */
	public static function featured_listing( $atts = array() ) {

	    global $wpdl;

		$args = array(
			'post_type'       => 'directory',
			'posts_per_page'  => isset( $atts['items'] ) ? $atts['items'] : $wpdl->get_directory_items_per_page(),
			'meta_query'      => array(
				array(
					'key'     => '_dir_featured',
					'value'   => 'yes',
					'compare' => 'LIKE',
				)
			),
			'paged'           => ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1,
			'show_sorting'    => isset( $atts['show_sorting'] ) ? $atts['show_sorting'] : 'yes',
			'show_count'      => isset( $atts['show_count'] ) ? $atts['show_count'] : 'yes',
			'show_pagination' => isset( $atts['show_pagination'] ) ? $atts['show_pagination'] : '',
			'fields'          => 'ids',
		);

		wpdl_get_template( 'content-directory-archive.php', $args );
    }


	/**
	 * Shortcode for Displaying My Account
	 *
	 * @shortcode [wpdl_my_account]
	 * @param $atts
	 *
	 * @return false|string
	 */

	public static function my_account( $atts ) {

		ob_start();

		if ( ! is_user_logged_in() ) {

			wpdl_get_template( 'form/login.php' );

			if ( true === get_option( 'users_can_register', false ) ) {
				wpdl_get_template( 'form/register.php' );
			}

			return ob_get_clean();
		}

		wpdl_get_template( 'my-account/my-account.php' );

		return ob_get_clean();
	}


	/**
	 * Shortcode for Displaying Directory Archive
	 *
	 * @shortcode [directory_archive]
	 * @param $atts
	 *
	 * @return false|string
	 */

	public static function directory_archive( $atts ) {

		$atts = (array) $atts;
		$atts = array_filter( $atts );

		global $wpdl;

		$defaults = array(
			'post_type'       => 'directory',
			'posts_per_page'  => $wpdl->get_directory_items_per_page(),
			'post_status'     => ( ! empty( $atts['status'] ) ) ? $atts['status'] : 'publish',
			'paged'           => ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1,
			'show_sorting'    => 'yes',
			'show_count'      => 'yes',
			'show_pagination' => 'yes',
		);

		$args = apply_filters( 'wpdl_filters_directory_archive_query', array_merge( $defaults, $atts ) );

		ob_start();

		wpdl_get_template( 'content-directory-archive.php', $args );

		return ob_get_clean();
	}


	/**
	 * Shortcode for Displaying Single Directory Page
	 *
	 * @shortcode [directory_page]
	 * @param $atts
	 *
	 * @return false|string
	 */

	public static function directory_page( $atts ) {

		if ( empty( $atts ) || ! isset( $atts['id'] ) ) {
			return '';
		}

		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'directory',
			'post_status'         => ( ! empty( $atts['status'] ) ) ? $atts['status'] : 'publish',
			'ignore_sticky_posts' => 1,
		);

		if ( isset( $atts['id'] ) ) {
			$args['p'] = absint( $atts['id'] );
		}

		$single_directory = new WP_Query( $args );

		global $wp_query;

		$previous_wp_query = $wp_query;
		$wp_query = $single_directory;

		ob_start();

		while ( $single_directory->have_posts() ) {
			$single_directory->the_post();
			?>
            <div class="single-directory" data-directory-id="<?php echo esc_attr( get_the_ID() ); ?>">
				<?php wpdl_get_template_part( 'content', 'single-directory' ); ?>
            </div>
			<?php
		}

		$wp_query = $previous_wp_query;
		wp_reset_postdata();

		return ob_get_clean();
	}


	/**
	 * Add Shortcode
	 */

	public static function add_shortcodes() {
		foreach ( self::$shortcodes as $shortcode => $function ) {

			add_shortcode( apply_filters( "wpdl_filters_shortcode_{$shortcode}", $shortcode ), $function );
		}
	}

}

new WPDL_Shortcodes();