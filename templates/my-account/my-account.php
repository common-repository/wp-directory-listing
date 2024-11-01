<?php
/**
 * My Account Page
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
 * Before My Account
 *
 * @hooked wpdl_myaccount_navigation 10
 */
do_action( 'wpdl_before_myaccount' );

?>


<div class="wpdl-myaccount-content">

	<?php
	/**
	 * My Account content
     *
	 * @hooked
	 */
	do_action( 'wpdl_myaccount_content' );
	?>

</div>


<?php
/**
 * After My Account
 *
 * @hooked
 */
do_action( 'wpdl_after_myaccount' );
