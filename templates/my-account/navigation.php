<?php
/**
 * My Account - Navigation
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


?>

<ul class="wpdl-myaccount-navigation">
	<?php foreach ( wpdl_get_myaccount_navigation() as $endpoint => $nav_label ) : ?>
        <li class="<?php echo wpdl_get_myaccount_nav_item_classes( $endpoint ); ?>">
            <a href="<?php echo esc_url( wpdl_get_myaccount_endpoint_url( $endpoint ) ); ?>">
                <?php echo wpdl_myaccount_nav_icons( $endpoint ); ?> <?php echo esc_html( $nav_label ); ?>
            </a>
        </li>
	<?php endforeach; ?>
</ul>
