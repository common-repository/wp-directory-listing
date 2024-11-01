<?php
/**
 * Form Template - Login
 *
 * @author        Pluginrox
 * @copyright     2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

$args = array(
	'redirect' => isset( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : wpdl_get_page_permalink( 'myaccount' ),
);


?>

<h2 class="wpdl-login-title"><?php esc_html_e( 'Login', TTDD ); ?></h2>

<div class="wpdl-form wpdl-form-login">

	<?php wp_login_form( $args ); ?>

</div>

