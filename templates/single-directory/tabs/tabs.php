<?php
/**
 *
 * Single Directory - Tabs
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $wpdl;

$tabs = $wpdl->get_directory_tabs();

if ( ! empty( $tabs ) ) : ?>

	<div class="wpdl-tabs">

		<ul class="wpdl-tabs-head" role="tablist">

			<?php $i = 0; foreach ( $tabs as $key => $tab ) : $i++; ?>

				<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo $i == 1 ? 'active' : ''; ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a data-target="tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'wpdl_filters_directory_tab_title' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>

			<?php endforeach; ?>

		</ul>

		<?php $i = 0; foreach ( $tabs as $key => $tab ) : $i++; ?>
			<div class="wpdl-tabs-panel wpdl-tabs-panel-<?php echo esc_attr( $key ); ?> <?php echo $i == 1 ? 'active' : ''; ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
			</div>
		<?php endforeach; ?>

	</div>

<?php endif; ?>

