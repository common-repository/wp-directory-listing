<?php
/**
 * Directory Archive - Price
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<?php if ( $directory->has_price() ) : ?>


    <div class="directory-item-price">
        <strong class="directory-item-price-label"><?php esc_html_e( 'Price', TTDD ); ?></strong>
        <span class="directory-item-price-value"><?php echo wp_kses_post( $directory->get_full_price() ); ?></span>
    </div>


<?php endif; ?>