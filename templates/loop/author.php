<?php
/**
 * Directory Archive - Author
 *
 * @author        Pluginrox
 * @copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $directory;

$author = $directory->get_author();

?>

<div class="directory-item-author">
	<div class="author-img">
		<?php echo get_avatar( $author->user_email, 60 ) ?>
		<div class="author-status">
			<i class="fa fa-check" aria-hidden="true"></i>
		</div>
	</div>
	<div class="author-name">
		<span><?php echo esc_html( $author->display_name ); ?></span>
	</div>
</div>