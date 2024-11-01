<?php
/**
 * Directory Archive - No Item Found
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<div class="no-directory-item alert alert-warning">
    <span><?php esc_html_e( 'No Directory Item Found !', TTDD ); ?></span>
</div>
