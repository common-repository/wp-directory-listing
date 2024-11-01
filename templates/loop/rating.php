<?php
/**
 * Directory Archive - Rating
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<div class="directory-item-rating">

	<?php echo $directory->get_rating_html(); ?>
	<?php echo $directory->get_rating_count_html(); ?>

</div>
