<?php
/**
 * My Account Page - Dashboard
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


printf( __( '<p>Do you really want to logout from %s</p>', TTDD ), get_bloginfo('name') );
printf( __( '<a href="%s" class="wpdl-btn">Yes, Logout</a>', TTDD ), wp_logout_url( wpdl_get_page_permalink('myaccount') ) );

