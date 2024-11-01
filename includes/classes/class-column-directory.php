<?php
/**
 * Column Class - Directory
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


class WPDL_Column_directory {

	public function __construct() {

		add_action( 'manage_directory_posts_columns', array( $this, 'add_columns' ), 16, 1 );
		add_action( 'manage_directory_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );

		add_filter( 'parse_query', array( $this, 'apply_filter_options' ), 10, 1 );
	}

	function apply_filter_options( $query ) {

		global $pagenow;

		if ( is_admin() && 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && 'directory' == $_GET['post_type'] && isset( $_GET['_l'] ) && $query->is_main_query() ) {

			$meta_query = $query->get( 'meta_query' );

			if ( ! is_array( $meta_query ) ) {
				$meta_query = array_filter( (array) $meta_query );
			}

			$meta_query[] = array(
				'key'     => '_dir_location',
				'value'   => sanitize_text_field( $_GET['_l'] ),
				'compare' => '=',
			);

			$query->set( 'meta_query', $meta_query );
		}

		return $query;
	}

	public function add_columns( $columns ) {

		$new = array(
			'cb'            => $columns['cb'],
			'title'         => esc_html__( 'Item Title', TTDD ),
			'wpdl_type'     => esc_html__( 'Item Type', TTDD ),
			'wpdl_location' => esc_html__( 'Location', TTDD ),
			'wpdl_author'   => esc_html__( 'Author', TTDD ),
			'wpdl_date'     => esc_html__( 'Published Date', TTDD ),
		);

		return apply_filters( 'wpdl_filters_columns_directory', $new );
	}

	public function custom_columns_content( $column, $post_id ) {

		global $wpdl;

		$directory = wpdl_get_directory( $post_id );

		switch ( $column ) {

			case 'wpdl_type':

				esc_attr_e( $directory->get_acquisition_type() );
				break;

			case 'wpdl_location':

				$location_id  = $directory->get_location( false );
				$_loc_states  = get_post_meta( $location_id, '_loc_states', true );
				$_loc_country = get_post_meta( $location_id, '_loc_country', true );

				printf( '<a href="post.php?post=%s&action=edit">%s</a>', $location_id, get_the_title( $location_id ) );
				printf( '<div class="row-actions"><span style="color: #696969;"><i>%s, %s</i></span></div>', $wpdl->get_state( $_loc_states ), $wpdl->get_country( $_loc_country ) );
				break;

			case 'wpdl_author':

				$author = $directory->get_author();

				printf( '<a href="user-edit.php?user_id=%s">%s</a>', $author->ID, $author->display_name );
				break;

			case 'wpdl_date':

				printf( __( 'Created <em>%s ago</em>', TTDD ), human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) );

				printf( '<div class="row-actions"><span class="edit" style="color: #696969;">%s</span></div>', get_the_date() );

				break;
		}
	}
}

new WPDL_Column_directory();