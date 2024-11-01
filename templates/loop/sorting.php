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

global $directory, $wp_query, $wpdl;

$sort = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : '';

?>

<?php if ( $wp_query->have_posts() && get_query_var( 'show_sorting' ) != 'no' ) : ?>

    <form class="directory-archive-sorting" action="">

        <label for="directory-sorting"><?php esc_html_e( 'Sort by', TTDD ); ?></label>

        <select id="directory-sorting" name="sort">

            <?php foreach ( $wpdl->get_sorting_parameters() as $key => $label ) : ?>

                <?php printf( '<option %s value="%s">%s</option>', $sort == $key ? 'selected' : '', $key, $label ); ?>

            <?php endforeach; ?>

        </select>

    </form>

<?php endif; ?>





