<?php
/**
 * Directory Archive - Published Date
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<div class="directory-item-publish-date">
    <strong><?php esc_html_e('Published ', TTDD ); ?></strong>
    <span><?php echo $directory->get_published_date(); ?></span>
</div>
