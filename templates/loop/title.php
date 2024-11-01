<?php
/**
 * Directory Archive - Title
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

?>

<h4 class="directory-item-title">
    <a href="<?php echo esc_url( $directory->get_permalink() ); ?>">
		<?php the_title(); ?>
    </a>
</h4>