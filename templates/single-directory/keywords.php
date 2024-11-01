<?php
/**
 *
 * Single Directory - Keywords
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;

?>


<?php if( $directory->get_keywords() ) : ?>


<div class="wpdl-sidebar-section directory-keywords">
	<div class="wpdl-sidebar-label"><?php _e('Keywords', TTDD ); ?></div>
	<div class="wpdl-sidebar-data">

		<?php foreach ( $directory->get_keywords() as $keyword ) : ?>

			<span class="meta-item"><?php echo $keyword->name; ?></span>

		<?php endforeach; ?>

	</div>
</div>


<?php endif; ?>