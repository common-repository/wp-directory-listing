<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Single Directory Item
 *
 * @hook wpdl_single_directory_summary
 *
 * @see wpdl_template_single_directory_title() 10
 * @see wpdl_template_single_directory_head_meta() 15
 * @see wpdl_template_single_directory_gallery() 20
 * @see wpdl_template_single_directory_sidebar() 25
 */

add_action( 'wpdl_single_directory_summary', 'wpdl_template_single_directory_title', 10 );
add_action( 'wpdl_single_directory_summary', 'wpdl_template_single_directory_head_meta', 15 );
add_action( 'wpdl_single_directory_summary', 'wpdl_template_single_directory_gallery', 20 );
add_action( 'wpdl_single_directory_summary', 'wpdl_template_single_directory_sidebar', 25 );
add_action( 'wpdl_single_directory_summary', 'wpdl_template_single_directory_tabs', 30 );



/**
 * Single Directory Item Sidebar
 *
 * @hook wpdl_single_directory_sidebar
 *
 * @see wpdl_template_single_directory_price() 10
 * @see wpdl_template_single_directory_categories() 15
 * @see wpdl_template_single_directory_keywords() 20
 * @see wpdl_template_single_directory_share() 25
 * @see wpdl_template_single_directory_rating() 30
 */

add_action( 'wpdl_single_directory_sidebar', 'wpdl_template_single_directory_price', 10 );
add_action( 'wpdl_single_directory_sidebar', 'wpdl_template_single_directory_categories', 15 );
add_action( 'wpdl_single_directory_sidebar', 'wpdl_template_single_directory_keywords', 20 );
add_action( 'wpdl_single_directory_sidebar', 'wpdl_template_single_directory_share', 25 );
add_action( 'wpdl_single_directory_sidebar', 'wpdl_template_single_directory_rating', 30 );



/**
 * Directory Archive Item
 *
 * @hook wpdl_directory_archive_item
 *
 * @see wpdl_directory_archive_item_thumbnail() 10
 * @see wpdl_directory_archive_item_title() 15
 * @see wpdl_directory_archive_item_date() 20
 * @see wpdl_directory_archive_item_price() 25
 * @see wpdl_directory_archive_item_rating() 30
 */

add_action( 'wpdl_directory_archive_item', 'wpdl_directory_archive_item_thumbnail', 10 );
add_action( 'wpdl_directory_archive_item', 'wpdl_directory_archive_item_title', 15 );
add_action( 'wpdl_directory_archive_item', 'wpdl_directory_archive_item_date', 20 );
add_action( 'wpdl_directory_archive_item', 'wpdl_directory_archive_item_price', 25 );
add_action( 'wpdl_directory_archive_item', 'wpdl_directory_archive_item_rating', 30 );



/**
 * Before Directory Archive
 *
 * @hook wpdl_before_directory_archive
 *
 * @see wpdl_directory_archive_results_count() 10
 * @see wpdl_directory_archive_sorting() 15
 */

add_action( 'wpdl_before_directory_archive', 'wpdl_directory_archive_results_count', 10 );
add_action( 'wpdl_before_directory_archive', 'wpdl_directory_archive_sorting', 15 );



/**
 * After Directory Archive
 *
 * @hook wpdl_after_directory_archive
 *
 * @see wpdl_directory_archive_pagination() 10
 */

add_action( 'wpdl_after_directory_archive', 'wpdl_directory_archive_pagination', 10 );




/**
 * Before My Account Page Content
 *
 * @hook wpdl_before_myaccount
 *
 * @see wpdl_myaccount_navigation() 10
 */

add_action( 'wpdl_before_myaccount', 'wpdl_myaccount_navigation', 10 );



/**
 * My Account Content
 *
 * @hook wpdl_myaccount_content
 *
 * @see wpdl_myaccount_content_dynamic() 10
 */

add_action( 'wpdl_myaccount_content', 'wpdl_myaccount_content_dynamic', 10 );






