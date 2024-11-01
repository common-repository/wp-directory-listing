<?php
/**
 * Directory Archive - Thumbnail
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

$size             = empty( $size ) ? 'wpdl_archive_thumb' : $size;
$favourite_status = $directory->get_favourite_status();


?>

<div class="directory-item-thumbnail">

    <?php if( $directory->is_featured() ) : ?>
        <div class="directory-item-featured">
            <span aria-label="<?php esc_html_e( 'Featured Listing', 'softly' ); ?>" class="tt--hint tt--top">
                <i class="fa fa-heart" aria-hidden="true"></i>
            </span>
        </div>
    <?php endif; ?>

    <a href="<?php echo esc_url( $directory->get_permalink() ); ?>">
        <img src="<?php echo esc_url( $directory->get_thumbnail_url( $size ) ); ?>">
    </a>

    <div status="<?php echo esc_attr( $favourite_status ); ?>"
         directory_id="<?php echo esc_attr( $directory->get_id() ); ?>"
         class="wpdl-fav-button fav-btn <?php echo esc_attr( $favourite_status ); ?>">
        <i class="fa fa-heart<?php echo ( $favourite_status == 'fav' ) ? '' : esc_attr( '-o' ); ?>"
           aria-hidden="true"></i>
    </div>

</div>

