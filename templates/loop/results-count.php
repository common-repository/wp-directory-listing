<?php
/**
 * Directory Archive - Results Count
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory, $wp_query;

?>

<?php if ( $wp_query->have_posts() && get_query_var( 'show_count' ) != 'no' ) : ?>

    <div class="directory-results-count">
        <h5><?php esc_html_e( sprintf( '%s directory out of %s items found on this page', $wp_query->post_count, $wp_query->found_posts ) ); ?></h5>
    </div>

<?php endif; ?>
