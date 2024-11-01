<?php
/**
 *
 * Single Directory - Gallery
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;


?>

<div class="wpdl-directory-gallery">

    <div class="wpdl-directory-gallery-images">

        <?php foreach( $directory->get_gallery_images() as $image_url ) : ?>

        <div class="wpdl-directory-gallery-image-single">
            <img src="<?php echo $image_url; ?>">
        </div>

        <?php endforeach; ?>

    </div>

    <?php if( count( $gallery_thumbs = $directory->get_gallery_images( 'wpdl_gallery_preview' ) ) > 1 ) :  ?>

    <div class="wpdl-directory-gallery-navs">

	    <?php foreach( $gallery_thumbs as $image_url ) : ?>

            <div class="wpdl-directory-gallery-nav-single">
                <img src="<?php echo $image_url; ?>">
            </div>

	    <?php endforeach; ?>

    </div>

    <?php endif; ?>

</div>

