<?php
/**
 *
 * Single Directory - Categories
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;

?>


<?php if( $directory->get_categories() ) : ?>


<div class="wpdl-sidebar-section directory-categories">
	<div class="wpdl-sidebar-label"><?php _e('Categories', TTDD ); ?></div>
	<div class="wpdl-sidebar-data">

		<?php foreach ( $directory->get_categories() as $category ) : ?>

			<span class="meta-item"><?php echo $category->name; ?></span>

		<?php endforeach; ?>

	</div>
</div>


<?php endif; ?>