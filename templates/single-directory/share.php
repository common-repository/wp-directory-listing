<?php
/**
 * Single Directory - Share
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;


if( $directory->get_share() ) : ?>

<div class="wpdl-sidebar-section directory-share">
	<div class="wpdl-sidebar-label"><?php _e('Share', TTDD ); ?></div>
	<div class="wpdl-sidebar-data"><?php echo $directory->get_share_html(); ?></div>
</div>

<?php endif; ?>