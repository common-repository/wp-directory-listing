<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdl;

$wp_settings           = new WP_Settings();
$meta_fields_directory = isset( $this->meta_fields['directory'] ) ? $this->meta_fields['directory'] : array();


$wp_settings->generate_fields( $meta_fields_directory, $post->ID );

foreach ( $wpdl->get_meta_fields_data() as $meta_field ) {

	$group_title = isset( $meta_field['group_name'] ) ? $meta_field['group_name'] : '';
	$options     = isset( $meta_field['options'] ) ? $meta_field['options'] : '';

	printf( '<h3>%s %s</h3>', esc_html__( 'Meta Fields for', TTDD ), $group_title );

	$wp_settings->generate_fields( array( array( 'options' => $options ) ), $post->ID );
}
