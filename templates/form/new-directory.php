<?php
/**
 * Form Template - New Directory
 *
 * @author        Pluginrox
 * @copyright     2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wpdl;

$wp_settings = new WP_Settings();


?>

<form class="wpdl-form" action="" method="post">

    <div class="wpdl-form-field">
        <label for="directory_title"><?php esc_html_e( 'Directory Title', TTDD ); ?></label>
        <input type="text" required="required" name="directory_title" id="directory_title"
               placeholder="<?php esc_html_e( 'Directory title here', TTDD ); ?>">
    </div>


    <div class="wpdl-form-field">
        <label for="directory_details"><?php esc_html_e( 'Directory Details', TTDD ); ?></label>
		<?php
		wp_editor( '', 'directory_details',
			apply_filters( 'wpdl_filters_new_directory_editor_settings', array(
				'media_buttons' => false,
				'editor_height' => '150',
				'tinymce'       => false,
			) )
		);
		?>
    </div>


    <div class="wpdl-tabs">

        <ul class="wpdl-tabs-head" role="tablist">

            <li class="active" id="tab-title-general" role="tab" aria-controls="tab-general">
                <span data-target="tab-general"><?php esc_html_e( 'General', TTDD ); ?></span>
            </li>


            <li class="additional_information_tab " id="tab-title-additional_information" role="tab"
                aria-controls="tab-additional_information">
                <span data-target="tab-additional_information"><?php esc_html_e( 'Additional Information', TTDD ); ?></span>
            </li>

        </ul>

        <div class="wpdl-tabs-panel wpdl-tabs-panel-general active" id="tab-general" role="tabpanel"
             aria-labelledby="tab-title-general">

			<?php $wp_settings->generate_fields( $wpdl->get_directory_meta_fields() ); ?>

        </div>

        <div class="wpdl-tabs-panel wpdl-tabs-panel-additional_information " id="tab-additional_information"
             role="tabpanel" aria-labelledby="tab-title-additional_information">

			<?php
			foreach ( $wpdl->get_meta_fields_data() as $meta_field ) {

				$group_title = isset( $meta_field['group_name'] ) ? $meta_field['group_name'] : '';
				$options     = isset( $meta_field['options'] ) ? $meta_field['options'] : '';

				printf( '<h3>%s %s</h3>', esc_html__( 'Meta Fields for', TTDD ), $group_title );

				$wp_settings->generate_fields( array( array( 'options' => $options ) ) );
			}
			?>

        </div>

    </div>

	<?php wp_nonce_field( 'wpdl_new_directory_nonce_action', 'wpdl_new_directory_nonce' ); ?>

    <button type="submit" class="wpdl-btn"><?php esc_html_e( 'Submit Directory', TTDD ); ?></button>

</form>