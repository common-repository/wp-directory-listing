<?php
/**
 *
 * Single Directory - Tabs - Additional Information
 *
 * @Author 		Pluginrox
 * @Copyright: 	2018 Pluginrox
 */

if ( ! defined('ABSPATH')) exit;  // if direct access

global $directory;


?>


<table class="wpdl-table">



	<?php foreach ( $directory->get_meta_data() as $group_name => $meta_fields ) : ?>

        <tr>
            <th colspan="2"><?php esc_html_e( $group_name ); ?></th>
        </tr>

        <?php foreach ( $meta_fields as $meta_data ) : ?>

        <?php if( isset( $meta_data['value'] ) && is_array( $meta_data['value'] ) ) : $meta_value = implode( ', ', $meta_data['value'] ); ?>
        <?php else : $meta_value = $meta_data['value']; ?>
        <?php endif; ?>

        <tr class="directory-meta-single">
            <td class="wpdl-sidebar-label"><?php echo isset( $meta_data['label'] ) ? $meta_data['label'] : ''; ?></td>
            <td class="wpdl-sidebar-data"><?php echo $meta_value; ?></td>
        </tr>

        <?php endforeach; ?>

    <?php endforeach; ?>

</table>


