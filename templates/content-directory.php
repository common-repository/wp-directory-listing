<?php
/**
 * Single Directory Listing inside Loop of Directory Archive
 *
 * @author Plutinrox
 * @copyright 2018 Pluginrox
 */

if( ! defined( 'ABSPATH' ) ) exit;


global $directory, $wp_query;

if( empty( $directory ) ) return;

?>

<li id="directory-<?php the_ID(); ?>" <?php wpdl_directory_class(); ?>>

	<?php

	/**
	 * Hook: wpdl_before_directory_archive_item
	 *
	 * @hooked
	 */
	do_action( 'wpdl_before_directory_archive_item' );


	/**
	 * Hook: wpdl_directory_archive_item
	 *
	 * @hooked wpdl_directory_archive_item_thumbnail 10
     * @hooked wpdl_directory_archive_item_title 15
     * @hooked wpdl_directory_archive_item_date 20
     * @hooked wpdl_directory_archive_item_price 25
     * @hooked wpdl_directory_archive_item_rating 30
	 */

	do_action( 'wpdl_directory_archive_item' );


	/**
	 * Hook: wpdl_after_directory_archive_item
	 *
	 * @hooked
	 */
	do_action( 'wpdl_after_directory_archive_item' );

	?>

</li>
