<?php
/**
 * Directory Archive - Excerpt
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<p class="directory-item-excerpt"><?php echo esc_html( $directory->get_short_description() ); ?></p>