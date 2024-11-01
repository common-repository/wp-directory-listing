<?php
/**
 * Single Directory - Ratings
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;


if( $directory->has_rating() ) : ?>

	<div class="wpdl-sidebar-section directory-rating">
		<div class="wpdl-sidebar-label"><?php _e('Ratings', TTDD ); ?></div>
		<div class="wpdl-sidebar-data">

            <?php echo $directory->get_rating_html(); ?>
            <?php echo $directory->get_rating_count_html(); ?>

        </div>
	</div>

<?php endif; ?>