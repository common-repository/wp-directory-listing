<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdl;

$wp_settings          = new WP_Settings();
$meta_fields_location = isset( $this->meta_fields['location'] ) ? $this->meta_fields['location'] : array();

$wp_settings->generate_fields( $meta_fields_location, $post->ID );
