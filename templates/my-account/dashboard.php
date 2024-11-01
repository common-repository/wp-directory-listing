<?php
/**
 * My Account Page - Dashboard
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


global $current_user, $wpdl;

$user_directory_query = $wpdl->get_user_directories();

?>

<div class="wpdl-dashboard">

	<div class="wpdl-dashboard-single">
		<div class="wpdl-dashboard-label"><?php esc_html_e('Username', TTDD ); ?></div>
		<div class="wpdl-dashboard-value"><?php echo $current_user->user_login; ?></div>
	</div>

	<div class="wpdl-dashboard-single">
		<div class="wpdl-dashboard-label"><?php esc_html_e('Full Name', TTDD ); ?></div>
		<div class="wpdl-dashboard-value"><?php echo $current_user->display_name; ?></div>
	</div>

	<div class="wpdl-dashboard-single">
		<div class="wpdl-dashboard-label"><?php esc_html_e('Email Address', TTDD ); ?></div>
		<div class="wpdl-dashboard-value"><?php echo $current_user->user_email; ?></div>
	</div>

	<hr>

	<div class="wpdl-dashboard-single">
		<div class="wpdl-dashboard-label"><?php esc_html_e('Total Items', TTDD ); ?></div>
		<div class="wpdl-dashboard-value"><?php printf( __('<strong>%s</strong> Directory items found', TTDD ), $user_directory_query->found_posts ); ?></div>
	</div>

</div>