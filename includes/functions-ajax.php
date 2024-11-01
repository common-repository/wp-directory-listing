<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! function_exists( 'wpdl_fav_button_clicked' ) ) {
	/**
	 * Change Favourite Status for single directory
	 */
	function wpdl_fav_button_clicked() {

		$directory_id = isset( $_POST['directory_id'] ) ? sanitize_text_field( $_POST['directory_id'] ) : '';
		$status       = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

		if( $status == 'fav' ) {
			$status = 'unfav';
		} else if( $status == 'unfav' ) {
			$status = 'fav';
		} else {
			$status = '';
		}

		if ( empty( $directory_id ) || empty( $status ) ) {
			wp_send_json_error( __( 'Invalid data provided', TTDD ) );
		}

		$directory       = wpdl_get_directory( $directory_id );
		$curr_status     = $directory->get_favourite_status();
		$favourite_users = $directory->get_meta( 'wpdl_favourite_users', array(), false );
		$curr_user_id    = get_current_user_id();

		if ( $curr_status == $status ) {
			wp_send_json_error( __( 'No change required', TTDD ) );
		}

		if ( $status == 'fav' ) {
			// Do Unfav

			if ( ! in_array( $curr_user_id, $favourite_users ) ) {
				add_post_meta( $directory_id, 'wpdl_favourite_users', $curr_user_id );
			}
		}

		if ( $status == 'unfav' ) {
			// Do Fav
			delete_post_meta( $directory_id, 'wpdl_favourite_users', $curr_user_id );
		}

		wp_send_json_success( $status );
	}
}
add_action( 'wp_ajax_wpdl_fav_button_clicked', 'wpdl_fav_button_clicked' );


if ( ! function_exists( 'ajax_wpdl_add_meta_field' ) ) {
	function ajax_wpdl_add_meta_field() {

		$group_id = isset( $_POST['group_id'] ) ? sanitize_text_field( $_POST['group_id'] ) : '';

		if ( empty( $group_id ) ) {
			wp_send_json_error( __( 'Invalid group', TTDD ) );
		}

		wp_send_json_success( wpdl_add_meta_field( $group_id ) );
	}
}
add_action( 'wp_ajax_wpdl_add_meta_field', 'ajax_wpdl_add_meta_field' );


if ( ! function_exists( 'ajax_wpdl_add_meta_group' ) ) {
	function ajax_wpdl_add_meta_group() {

		wp_send_json_success( wpdl_add_meta_group() );
	}
}
add_action( 'wp_ajax_wpdl_add_meta_group', 'ajax_wpdl_add_meta_group' );


