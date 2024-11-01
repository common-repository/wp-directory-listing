<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * wpdl_template_single_directory_title()
 *
 * @hooked_from wpdl_single_directory_summary - 10
 */

if( ! function_exists( 'wpdl_template_single_directory_title' ) ) {
	function wpdl_template_single_directory_title() {
		wpdl_get_template( 'single-directory/title.php' );
	}
}


/**
 * wpdl_template_single_directory_head_meta()
 *
 * @hooked_from wpdl_single_directory_summary - 15
 */

if( ! function_exists( 'wpdl_template_single_directory_head_meta' ) ) {
	function wpdl_template_single_directory_head_meta() {
		wpdl_get_template( 'single-directory/head-meta.php' );
	}
}


/**
 * wpdl_template_single_directory_gallery()
 *
 * @hooked_from wpdl_single_directory_summary - 20
 */

if( ! function_exists( 'wpdl_template_single_directory_gallery' ) ) {
	function wpdl_template_single_directory_gallery() {
		wpdl_get_template( 'single-directory/gallery.php' );
	}
}


/**
 * wpdl_template_single_directory_sidebar()
 *
 * @hooked_from wpdl_single_directory_summary - 25
 */

if( ! function_exists( 'wpdl_template_single_directory_sidebar' ) ) {
	function wpdl_template_single_directory_sidebar() {
		wpdl_get_template( 'single-directory/sidebar.php' );
	}
}



/**
 * wpdl_template_single_directory_price()
 *
 * @hooked_from wpdl_single_directory_sidebar - 10
 */

if( ! function_exists( 'wpdl_template_single_directory_price' ) ) {
	function wpdl_template_single_directory_price() {
		wpdl_get_template( 'single-directory/price.php' );
	}
}

/**
 * wpdl_template_single_directory_categories()
 *
 * @hooked_from wpdl_single_directory_sidebar - 15
 */

if( ! function_exists( 'wpdl_template_single_directory_categories' ) ) {
	function wpdl_template_single_directory_categories() {
		wpdl_get_template( 'single-directory/categories.php' );
	}
}


/**
 * wpdl_template_single_directory_keywords()
 *
 * @hooked_from wpdl_single_directory_sidebar - 20
 */

if( ! function_exists( 'wpdl_template_single_directory_keywords' ) ) {
	function wpdl_template_single_directory_keywords() {
		wpdl_get_template( 'single-directory/keywords.php' );
	}
}


/**
 * wpdl_template_single_directory_share()
 *
 * @hooked_from wpdl_single_directory_sidebar - 25
 */

if( ! function_exists( 'wpdl_template_single_directory_share' ) ) {
	function wpdl_template_single_directory_share() {
		wpdl_get_template( 'single-directory/share.php' );
	}
}


/**
 * wpdl_template_single_directory_rating()
 *
 * @hooked_from wpdl_single_directory_sidebar - 30
 */

if( ! function_exists( 'wpdl_template_single_directory_rating' ) ) {
	function wpdl_template_single_directory_rating() {
		wpdl_get_template( 'single-directory/rating.php' );
	}
}



/**
 * wpdl_template_single_directory_tabs()
 *
 * @hooked_from wpdl_single_directory_summary - 30
 */

if( ! function_exists( 'wpdl_template_single_directory_tabs' ) ) {
	function wpdl_template_single_directory_tabs() {
		wpdl_get_template( 'single-directory/tabs/tabs.php' );
	}
}


/**
 * Output Directory Tab - Description
 */

if ( ! function_exists( 'wpdl_directory_tab_description' ) ) {
	function wpdl_directory_tab_description() {
		wpdl_get_template( 'single-directory/tabs/description.php' );
	}
}

/**
 * Output Directory Tab - Additional Information
 */

if ( ! function_exists( 'wpdl_directory_tab_additional_information' ) ) {
	function wpdl_directory_tab_additional_information() {
		wpdl_get_template( 'single-directory/tabs/additional-information.php' );
	}
}

/**
 * Output Directory Tab - Reviews
 */

if ( ! function_exists( 'wpdl_directory_tab_reviews' ) ) {
	function wpdl_directory_tab_reviews() {
		wpdl_get_template( 'single-directory/tabs/reviews.php' );
	}
}


/**
 * Display Directory Class in Directory Archive Loop
 *
 * @return mixed | attr
 */

if( ! function_exists( 'wpdl_directory_class' ) ) {
	function wpdl_directory_class( $class = '', $directory_id = null ){

		echo 'class="' . esc_attr( join( ' ', wpdl_get_directory_class( $class, $directory_id ) ) ) . '"';
	}
}



/**
 * Return Classes for Single Directory Item
 *
 * @param string $class
 * @param null $directory_id
 *
 * @return array
 */

if( ! function_exists( 'wpdl_get_directory_class' ) ) {
	function wpdl_get_directory_class( $class = '', $directory_id = null ) {

		if ( is_a( $directory_id, 'WPDL_Directory' ) ) {
			$directory    = $directory_id;
			$directory_id = $directory->get_id();
			$post         = get_post( $directory_id );
		} else {
			$post      = get_post( $directory_id );
			$directory = wpdl_get_directory( $post->ID );
		}

		$classes = array();

		if ( $class ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_map( 'esc_attr', $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		if ( ! $post || ! $directory ) {
			return $classes;
		}

		$classes[] = 'post-' . $post->ID;
		if ( ! is_admin() ) {
			$classes[] = $post->post_type;
		}
		$classes[] = 'type-' . $post->post_type;
		$classes[] = 'status-' . $post->post_status;

		// Post format.
		if ( post_type_supports( $post->post_type, 'post-formats' ) ) {
			$post_format = get_post_format( $post->ID );

			if ( $post_format && ! is_wp_error( $post_format ) ) {
				$classes[] = 'format-' . sanitize_html_class( $post_format );
			} else {
				$classes[] = 'format-standard';
			}
		}

		// Post requires password.
		$post_password_required = post_password_required( $post->ID );
		if ( $post_password_required ) {
			$classes[] = 'post-password-required';
		} elseif ( ! empty( $post->post_password ) ) {
			$classes[] = 'post-password-protected';
		}

		// Post thumbnails.
		if ( current_theme_supports( 'post-thumbnails' ) && ! empty( $directory->get_thumbnail_url() ) && ! is_attachment( $post ) && ! $post_password_required ) {
			$classes[] = 'has-post-thumbnail';
		}

		// Sticky for Sticky Posts.
		if ( is_sticky( $post->ID ) ) {
			if ( is_home() && ! is_paged() ) {
				$classes[] = 'sticky';
			} elseif ( is_admin() ) {
				$classes[] = 'status-sticky';
			}
		}

		// Hentry for hAtom compliance.
		$classes[] = 'hentry';

		// Check Featured
        if( $directory->is_featured() ) {
            $classes[] = 'featured-listing';
        }

		return array_filter( array_unique( apply_filters( 'post_class', $classes, $class, $post->ID ) ) );
	}
}



/**
 * Display Directory Archive Classes
 *
 */

if( ! function_exists( 'wpdl_directory_archive_class' ) ) {
	function wpdl_directory_archive_class( $classes = array() ) {

		global $wpdl, $wp_query;

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		// Column Settings
		$items_per_row = $wp_query->get('items_per_row' );
		$items_per_row = empty( $items_per_row ) ? $wpdl->get_directory_items_per_row() : $items_per_row;

		$classes[] = sprintf( 'directory-column-%s', $items_per_row );

		echo apply_filters('wpdl_filters_directory_archive_class', esc_attr( join( ' ', $classes ) ) );
	}
}



/**
 * wpdl_directory_archive_item_thumbnail()
 *
 * @hooked_from wpdl_directory_archive_item - 10
 */

if( ! function_exists( 'wpdl_directory_archive_item_thumbnail' ) ) {
	function wpdl_directory_archive_item_thumbnail( $args = '' ) {
		wpdl_get_template( 'loop/thumbnail.php', $args );
	}
}


/**
 * wpdl_directory_archive_item_title()
 *
 * @hooked_from wpdl_directory_archive_item - 15
 */

if( ! function_exists( 'wpdl_directory_archive_item_title' ) ) {
	function wpdl_directory_archive_item_title() {
		wpdl_get_template( 'loop/title.php' );
	}
}

/**
 * wpdl_directory_archive_item_date()
 *
 * @hooked_from wpdl_directory_archive_item - 20
 */

if( ! function_exists( 'wpdl_directory_archive_item_date' ) ) {
	function wpdl_directory_archive_item_date() {
		wpdl_get_template( 'loop/date.php' );
	}
}


/**
 * wpdl_directory_archive_item_price()
 *
 * @hooked_from wpdl_directory_archive_item - 25
 */

if( ! function_exists( 'wpdl_directory_archive_item_price' ) ) {
	function wpdl_directory_archive_item_price() {
		wpdl_get_template( 'loop/price.php' );
	}
}



if( ! function_exists( 'wpdl_directory_archive_item_rating' ) ) {
	/**
	 * wpdl_directory_archive_item_rating()
	 *
	 * @hooked_from wpdl_directory_archive_item - 30
	 */
	function wpdl_directory_archive_item_rating() {
		wpdl_get_template( 'loop/rating.php' );
	}
}

if( ! function_exists( 'wpdl_directory_archive_item_author' ) ) {
	function wpdl_directory_archive_item_author(){
		wpdl_get_template( 'loop/author.php' );
	}
}

if( ! function_exists( 'wpdl_directory_archive_item_excerpt' ) ) {
	function wpdl_directory_archive_item_excerpt(){
		wpdl_get_template( 'loop/excerpt.php' );
	}
}


/**
 * wpdl_directory_archive_results_count()
 *
 * @hooked_from wpdl_before_directory_archive - 10
 */

if( ! function_exists( 'wpdl_directory_archive_results_count' ) ) {
	function wpdl_directory_archive_results_count() {
		wpdl_get_template( 'loop/results-count.php' );
	}
}

/**
 * wpdl_directory_archive_sorting()
 *
 * @hooked_from wpdl_before_directory_archive - 10
 */

if( ! function_exists( 'wpdl_directory_archive_sorting' ) ) {
	function wpdl_directory_archive_sorting() {
		wpdl_get_template( 'loop/sorting.php' );
	}
}


/**
 * wpdl_directory_archive_pagination()
 *
 * @hooked_from wpdl_after_directory_archive - 10
 */

if( ! function_exists( 'wpdl_directory_archive_pagination' ) ) {
	function wpdl_directory_archive_pagination() {
		wpdl_get_template( 'loop/pagination.php' );
	}
}



/**
 * wpdl_myaccount_navigation()
 *
 * @hooked_from wpdl_before_myaccount - 10
 */

if( ! function_exists( 'wpdl_myaccount_navigation' ) ) {
	function wpdl_myaccount_navigation() {
		wpdl_get_template( 'my-account/navigation.php' );
	}
}



/**
 * wpdl_myaccount_content_dynamic()
 *
 * @hooked_from wpdl_myaccount_content - 10
 */

if( ! function_exists( 'wpdl_myaccount_content_dynamic' ) ) {
	function wpdl_myaccount_content_dynamic() {

		/**
		 * Check is New Directory
		 */

		if( wpdl_is_page('new_directory') ) {
			wpdl_get_template( 'form/new-directory.php' );
			return;
		}

		wpdl_get_template( 'my-account/'. wpdl_get_current_endpoint() .'.php' );
	}
}


