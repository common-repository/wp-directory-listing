<?php
/**
 *
 * Single Directory - Review Form
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $directory;

?>

<h4><?php esc_html_e('Create a new review', TTDD); ?></h4>

<?php do_action( 'wpdl_before_directory_review_form' ); ?>

<?php
    if( ! is_user_logged_in() ) {

	    wpdl_print_notice( sprintf('You need to login to write a review. <a href="%s">login Here</a>', wp_login_url( get_permalink() ) ), 'warning' );
	    return;
    }

?>

<form class="wpdl-form" action="<?php echo esc_url( $directory->get_permalink() ); ?>" method="post">

    <div class='wpdl-form-group'>
        <label for="review"><?php esc_html_e( 'Your Rating', TTDD); ?></label>
        <input required type="hidden" value="" name="wpdl_directory_review_rating">
        <ul class='wpdl-rating-field'>
            <li class='star' data-value='1'><i class="fa fa-star"></i></li>
            <li class='star' data-value='2'><i class="fa fa-star"></i></li>
            <li class='star' data-value='3'><i class="fa fa-star"></i></li>
            <li class='star' data-value='4'><i class="fa fa-star"></i></li>
            <li class='star' data-value='5'><i class="fa fa-star"></i></li>
        </ul>
    </div>

	<?php do_action( 'wpdl_directory_review_form' ); ?>

    <div class="wpdl-form-group">
        <label for="review"><?php esc_html_e( 'Review content', TTDD); ?></label>
        <textarea required name="wpdl_directory_review_details" rows="5" id="review" class="wpdl-form-field" placeholder="<?php esc_html_e('Your Review', TTDD); ?>"></textarea>
    </div>

    <?php wp_nonce_field( 'wpdl_review_form_nonce', 'wpdl_review_form_name' ); ?>

    <button type="submit" class="wpdl-btn"><?php esc_html_e( 'Send Message', TTDD ); ?></button>

</form>

<?php do_action( 'wpdl_after_directory_review_form' ); ?>
