<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) exit;


class WPDL_Template_loader{

	public static function init() {

		add_filter( 'template_redirect', array( __CLASS__, 'wpdl_theme_init' ) );
	}

	/**
	 * Calling Single Directory page templates
	 *
	 * @see theme_single_directory_page_init()
	 */

	public static function wpdl_theme_init(){

		self::theme_single_directory_page_init();
	}

	/**
	 * Removing title from Single Directory
	 * Changing content of Single Directory
	 *
	 * @see wpdl_single_directory_title_filter() - 10
	 * @see wpdl_single_directory_content_filter() - 10
	 */

	public static function theme_single_directory_page_init(){

		global $directory;

		$directory = wpdl_get_directory();

		if( ! get_theme_support( 'wpdl' ) ) {
			add_filter( 'the_title', array( __CLASS__, 'wpdl_single_directory_title_filter' ), 10 );
			add_filter( 'the_content', array( __CLASS__, 'wpdl_single_directory_content_filter' ), 10 );
			add_filter( 'comments_template', array( __CLASS__, 'wpdl_single_directory_comments_template_filter' ), 10 );
			add_filter( 'post_thumbnail_html', array( __CLASS__, 'wpdl_single_directory_thumbnail_filter' ), 10 );
		}
	}

	public static function wpdl_single_directory_thumbnail_filter( $html ){

		global $wp_query;

		if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_singular( 'directory' ) ) {
			return '';
		}

		return $html;
	}

	/**
	 * Filtering Comments of Single Directory
	 *
	 * @param $comments_template
	 *
	 * @return string
	 */

	public static function wpdl_single_directory_comments_template_filter( $comments_template ) {

		global $wp_query;

		if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_singular( 'directory' ) ) {

			return WPDL_PLUGIN_DIR . 'templates/single-directory-comments.php';
		}

		return $comments_template;
	}


	/**
	 * Filtering Title of Single Directory
	 *
	 * @param $title
	 *
	 * @return string
	 */

	public static function wpdl_single_directory_title_filter( $title ) {

		global $wp_query;

		if ( ! is_null( $wp_query ) && ! is_admin() && is_main_query() && in_the_loop() && is_singular( 'directory' ) ) {

			$title = '';
			remove_filter( 'the_title', 'wpdl_single_directory_title_filter' );
		}

		return $title;
	}

	/**
	 * Filtering content of Single Directory Item inside loop
	 *
	 * @param $content
	 *
	 * @return string
	 */

	public static function wpdl_single_directory_content_filter( $content ){

		// Remove the filter we're in to avoid nested calls.
		remove_filter( 'the_content', array( __CLASS__, 'wpdl_single_directory_content_filter' ) );

		if ( is_singular( array( 'directory' ) ) ) {
			$content = do_shortcode( '[directory_page id="' . get_the_ID() . '"]' );
		}

		return $content;
	}
}


add_action( 'init', array( 'WPDL_Template_loader', 'init' ) );

