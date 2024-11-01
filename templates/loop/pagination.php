<?php
/**
 * Directory Archive - Pagination
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory, $wp_query;

if( $wp_query->get( 'show_pagination' ) == 'yes' ) : ?>

    <div class="wpdl-pagination paginate">
        <?php echo wpdl_pagination(); ?>
    </div>

<?php endif; ?>
