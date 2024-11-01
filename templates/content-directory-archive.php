<?php
/**
 * Directory Archive Template
 *
 * $args inherited applying all filters
 *
 * @author Plutinrox
 * @copyright 2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args['paged'] ) ) {
	$args['paged'] = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
}

$args['items_per_row'] = isset( $args['items_per_row'] ) ? $args['items_per_row'] : '';

$directory_archive = new WP_Query( $args );

global $wp_query;

// Backup query object so following loops think this is a product page.
$previous_wp_query = $wp_query;
// @codingStandardsIgnoreStart
$wp_query = $directory_archive;
// @codingStandardsIgnoreEnd

/**
 * Before Directory Archive
 *
 */
do_action( 'wpdl_before_directory_archive' );

if ( $directory_archive->have_posts() ) :

	wpdl_get_template( 'loop/start.php' );

	while ( $directory_archive->have_posts() ) : $directory_archive->the_post();

		global $directory;

		$directory = wpdl_get_directory();

		wpdl_get_template_part( 'content', 'directory' );

	endwhile;

	wpdl_get_template( 'loop/end.php' );

else :

	wpdl_get_template( 'loop/no-item.php' );

endif;

/**
 * After Directory Archive
 *
 */
do_action( 'wpdl_after_directory_archive' );


// Restore $previous_wp_query and reset post data.
// @codingStandardsIgnoreStart
$wp_query = $previous_wp_query;
// @codingStandardsIgnoreEnd
wp_reset_postdata();
wp_reset_query();
