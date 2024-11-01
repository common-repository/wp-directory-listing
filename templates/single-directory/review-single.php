<?php
/**
 * Single Directory - Single Review Item
 *
 * $args is inherited from the reviews.php and it's a Comment Object
 *
 * @author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $directory;

$comment_author = get_user_by( 'login', $args->comment_author );
$review_rating  = get_comment_meta( $args->comment_ID, 'wpdl_review_rating', true );
$review_rating  = empty( $review_rating ) ? 0 : (int) $review_rating;

?>

<div class="review-single">

    <div class="review-single-img">
        <i class="fa fa-quote-right"></i>
        <img src="<?php echo esc_url( get_avatar_url( $comment_author->user_email ) ); ?>" alt="<?php esc_html_e( $comment_author->display_name ); ?>">
    </div>

    <div class="review-single-details">

        <p><?php esc_html_e( $args->comment_content ); ?></p>

        <div class="review-single-author"><?php esc_html_e( $comment_author->display_name ); ?></div>

        <span class="review-single-rating">

            <?php for( $i = 0; $i < 5; ++$i ) { ?>

                <i class="rating-icon <?php esc_attr_e( $i < $review_rating ? 'rating-fill' : '' ); ?> fa fa-star"></i>

            <?php } ?>

        </span>

    </div>

</div>
