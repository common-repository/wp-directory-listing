<?php
/**
 * Column Class - Location
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


class WPDL_Column_Location {

	public function __construct() {

		add_action( 'manage_location_posts_columns', array( $this, 'add_columns' ), 16, 1 );
		add_action( 'manage_location_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 10, 2 );
	}

	public function add_columns( $columns ) {

		$new = array(
			'cb'            => $columns['cb'],
			'title'         => esc_html__( 'Location Title', TTDD ),
			'loc_dir_count' => esc_html__( 'Directory Items', TTDD ),
			'loc_state'     => esc_html__( 'State', TTDD ),
			'loc_country'   => esc_html__( 'Country', TTDD ),
			'loc_date'      => esc_html__( 'Published Date', TTDD ),
		);

		return apply_filters( 'wpdl_filters_columns_location', $new );
	}

	public function custom_columns_content( $column, $post_id ) {

		global $wpdl;

		switch ( $column ) {

			case 'loc_dir_count':

				$dir_counts = get_posts( array(
					'post_type'      => 'directory',
					'posts_per_page' => - 1,
					'meta_query'     => array(
						array(
							'key'     => '_dir_location',
							'value'   => $post_id,
							'compare' => '=',
						)
					),
					'fields' => 'ids',
				) );

				if( ! empty( $dir_counts ) ) {
					_e( sprintf('<span><b>%s</b> Directory Items found</span>', count( $dir_counts ) ), TTDD );
					printf( '<div class="row-actions"><span class="edit" style="color: #696969;"><a href="edit.php?post_type=directory&_l=%s">%s</a></span></div>', esc_attr( $post_id ), esc_html__('View all', TTDD ) );
				}

				break;

			case 'loc_state':

				$_loc_states = get_post_meta( $post_id, '_loc_states', true );

				printf( '<span>%s</span>', $wpdl->get_state( $_loc_states ) );
				break;

			case 'loc_country':

				$_loc_country = get_post_meta( $post_id, '_loc_country', true );

				printf( '<span>%s</span>', $wpdl->get_country( $_loc_country ) );
				break;

			case 'loc_date':

				printf( __( 'Created <em>%s ago</em>', TTDD ), human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) );
				printf( '<div class="row-actions"><span class="edit" style="color: #696969;">%s</span></div>', get_the_date() );

				break;
		}
	}

	public function remove_row_actions( $actions ) {
		global $post;

		if ( $post->post_type === 'location' ) {
			unset( $actions['inline hide-if-no-js'] );
		}

		return $actions;
	}

}

new WPDL_Column_Location();