<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


/**
 * Hook: wpdl_before_single_directory.
 *
 */

do_action( 'wpdl_before_single_directory' );

?>
	<div id="directory-<?php the_ID(); ?>">

		<?php
		/**
		 * Hook: wpdl_before_single_directory_summary.
		 */
		do_action( 'wpdl_before_single_directory_summary' );
		?>

		<div class="summary entry-summary">
			<?php
			/**
			 * Hook: wpdl_single_directory_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'wpdl_single_directory_summary' );
			?>
		</div>

		<?php
		/**
		 * Hook: wpdl_after_single_directory_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'wpdl_after_single_directory_summary' );
		?>
	</div>

<?php do_action( 'wpdl_after_single_directory' ); ?>