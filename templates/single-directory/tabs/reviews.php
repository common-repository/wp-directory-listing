<?php
/**
 *
 * Single Directory - Tabs - Reviews
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;


?>

<?php do_action( 'wpdl_before_reviews' ); ?>

<div class="wpdl-directory-reviews">

    <?php foreach( $directory->get_reviews() as $review ) : ?>

	    <?php wpdl_get_template( 'single-directory/review-single.php', $review ); ?>

    <?php endforeach; ?>

</div>

<?php do_action( 'wpdl_after_reviews' ); ?>

<?php do_action( 'wpdl_before_directory_review_form_wrapper' ); ?>

<div class="wpdl-directory-review-form">

	<?php wpdl_get_template( 'single-directory/review-form.php' ); ?>

</div>

<?php do_action( 'wpdl_after_directory_review_form_wrapper' ); ?>
