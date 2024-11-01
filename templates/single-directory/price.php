<?php
/**
 * Single Directory - Price
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<?php if ( $directory->has_price() ) : ?>

    <div class="wpdl-sidebar-section directory-price">
        <div class="wpdl-sidebar-label"><?php _e( 'Price', TTDD ); ?></div>
        <div class="wpdl-sidebar-data"><?php echo $directory->get_full_price(); ?></div>
    </div>

<?php endif; ?>