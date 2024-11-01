<?php
/**
 *
 * Single Directory - Head Meta data
 * For Sale | Buy
 * Uploaded by | Owner
 * Uploaded Date with Time
 * Item Location
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;

$directory_author   = $directory->get_author();

?>

<p class="directory-head-meta">


	<span class="listing-for"><?php echo $directory->get_acquisition_type(); ?></span>

	<span class="listing-author">
		<?php _e('By ', TTDD ); ?>
		<a href="<?php echo $directory_author->ID; ?>"><?php echo $directory_author->display_name; ?></a>
	</span>

	<span class="listing-time">
		<time><?php echo $directory->get_published_date(); ?></time>
	</span>

</p>
