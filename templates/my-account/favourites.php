<?php
/**
 * My Account Page - Favourites
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wpdl;

$user_favourites_query = $wpdl->get_user_favourites();

?>

    <div class="wpdl-directories-meta">

        <span class="item-count"><?php printf( __( '<strong>%s</strong> directory items found', TTDD ), $user_favourites_query->found_posts ); ?></span>

        <a href="<?php echo esc_url( wpdl_get_page_permalink( 'archive' ) ); ?>"
           class="wpdl-btn"><?php esc_html_e( 'View All Directory', TTDD ); ?></a>

    </div>


<?php if ( $user_favourites_query->have_posts() ) : ?>

    <table class="wpdl-table">
        <thead>
        <tr>
            <th><?php esc_html_e( 'ID', TTDD ); ?></th>
            <th><?php esc_html_e( 'Title', TTDD ); ?></th>
            <th><?php esc_html_e( 'Published', TTDD ); ?></th>
        </tr>
        </thead>

        <tbody>

		<?php while ( $user_favourites_query->have_posts() ) : $user_favourites_query->the_post(); ?>

            <tr>
                <td><a href="<?php echo get_the_permalink(); ?>"><?php the_ID() ?></a></td>
                <td><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></td>
                <td><?php echo get_the_date( 'F j, Y' ); ?></td>
            </tr>

		<?php endwhile; ?>

        </tbody>

    </table>

<?php endif; ?>


    <div class="wpdl-pagination paginate">
		<?php echo wpdl_pagination( $user_favourites_query ); ?>
    </div>


<?php wp_reset_query(); ?>