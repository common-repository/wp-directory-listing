<?php
/**
 *
 * Single Directory - Sidebar
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;

?>


<?php do_action( 'wpdl_before_single_directory_sidebar'); ?>

<div class="wpdl-directory-sidebar">

	<?php do_action( 'wpdl_single_directory_sidebar'); ?>

</div>

<?php do_action( 'wpdl_after_single_directory_sidebar'); ?>
