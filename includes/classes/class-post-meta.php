<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WPDL_Post_meta {

	public $meta_fields = array();

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'publish_box_content' ), 1 );

		$this->setup_meta_fields();
	}

	function publish_box_content() {

		if ( get_post_type() != 'directory' ) {
			return;
		}

		$wp_settings = new WP_Settings();

		$options = array(
			array(
				'id'    => '_dir_featured',
				'title' => __( 'Featured Item', TTDD ),
				'type'  => 'checkbox',
				'args'  => array(
					'yes' => __( 'Make this directory Featured', TTDD ),
				),
			),
		);

		$wp_settings->generate_fields( array( array( 'options' => $options ) ), get_the_ID() );
	}


	public function location_meta_box( $post ) {

		global $wpdl;

		wp_nonce_field( 'meta_location_action', 'nonce_location' );

		include $wpdl->admin_template_path() . 'meta-box-location.php';
	}


	public function directory_meta_box( $post ) {

		global $wpdl;

		wp_nonce_field( 'meta_directory_action', 'nonce_directory' );

		include $wpdl->admin_template_path() . 'meta-box-directory.php';
	}


	public function add_meta_boxes( $post_type ) {

		if ( in_array( $post_type, array( 'directory' ) ) ) {
			add_meta_box( 'wpdl_meta_box', __( 'Directory Data', TTDD ), array(
				$this,
				'directory_meta_box'
			), $post_type, 'normal', 'high' );
		}

		if ( in_array( $post_type, array( 'location' ) ) ) {
			add_meta_box( 'wpdl_meta_box_location', __( 'Location Data', TTDD ), array(
				$this,
				'location_meta_box'
			), $post_type, 'normal', 'high' );
		}
	}

	public function save_location( $post_id ) {

		$fields_location = isset( $this->meta_fields['location'] ) ? $this->meta_fields['location'] : array();
		$data_to_update  = array();
		$data_to_update  = array_merge( $this->get_field_ids( $fields_location ), $data_to_update );

		foreach ( $data_to_update as $meta_key ) {
			$meta_value = isset( $_POST[ $meta_key ] ) ? $this->satinize( $_POST[ $meta_key ] ) : '';
			update_post_meta( $post_id, $meta_key, $meta_value );
		}
	}


	public function save_directory( $post_id ) {

		global $wpdl;

		$fields_directory = isset( $this->meta_fields['directory'] ) ? $this->meta_fields['directory'] : array();
		$data_to_update   = array();
		$data_to_update   = array_merge( $this->get_field_ids( $fields_directory ), $data_to_update );
		$data_to_update   = array_merge( $this->get_field_ids( $wpdl->get_meta_fields_data() ), $data_to_update );

		foreach ( $data_to_update as $meta_key ) {

			$meta_value = isset( $_POST[ $meta_key ] ) ? $this->satinize( $_POST[ $meta_key ] ) : '';
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		$_dir_featured = isset( $_POST['_dir_featured'] ) ? $this->satinize( $_POST['_dir_featured'] ) : '';

		update_post_meta( $post_id, '_dir_featured', $_dir_featured );
	}


	public function save_post( $post_id ) {

		if ( isset( $_POST['nonce_directory'] ) && wp_verify_nonce( $_POST['nonce_directory'], 'meta_directory_action' ) ) {
			$this->save_directory( $post_id );
		}

		if ( isset( $_POST['nonce_location'] ) && wp_verify_nonce( $_POST['nonce_location'], 'meta_location_action' ) ) {
			$this->save_location( $post_id );
		}
	}


	public function get_field_ids( $meta_fields = array() ) {

		$fields = array();

		foreach ( $meta_fields as $section ) {
			$options = isset( $section['options'] ) ? $section['options'] : array();
			$fields  = array_merge( $fields, $options );
		}

		return array_map( function ( $option ) {
			return isset( $option['id'] ) ? $option['id'] : '';
		}, $fields );
	}


	public function setup_meta_fields() {

		global $wpdl;

		// Directory Meta Data
		$this->meta_fields['directory'] = array(

			array(
				'options' => array(

					array(
						'id'       => '_dir_acquisition_type',
						'title'    => __( 'Directory for', TTDD ),
						'details'  => __( 'For which purpose this directory listing is', TTDD ),
						'type'     => 'select',
						'args'     => $wpdl->get_directory_acquisition_types(),
						'required' => true,
					),

					array(
						'id'          => '_dir_price',
						'title'       => __( 'Price', TTDD ),
						'details'     => __( 'Set flat price for your listing. Leave empty for no price.', TTDD ),
						'type'        => 'number',
						'placeholder' => '100000',
					),

					array(
						'id'      => '_dir_interval',
                        'title'   => __( 'Billing Cycle', TTDD ),
						'details' => __( 'Set interval period for the above price (for subscription based item). Leave empty to ignore this and keep the flat price as Final price.', TTDD ),
						'type'    => 'select',
						'args'    => wpdl_billing_cycle( 'all' ),
					),

					array(
						'id'      => '_dir_gallery',
						'title'   => __( 'Gallery', TTDD ),
						'details' => __( 'Create gallery for your listing', TTDD ),
						'type'    => 'gallery',
					),

					array(
						'id'      => '_dir_share',
						'title'   => __( 'Social Share', TTDD ),
						'details' => __( 'Select social share medias you would like', TTDD ),
						'type'    => 'checkbox',
						'args'    => $wpdl->get_social_profiles(),
					),

					array(
						'id'      => '_dir_short_description',
						'title'   => __( 'Short Description', TTDD ),
						'details' => __( 'Add short description for this Directory', TTDD ),
						'type'    => 'textarea',
					),


					array(
						'id'      => '_dir_author',
						'title'   => __( 'Author / Owner', TTDD ),
						'details' => __( 'Update listing author or owner', TTDD ),
						'type'    => 'select2',
						'args'    => 'USERS',
					),

					array(
						'id'          => '_dir_address',
						'title'       => __( 'Street Address', TTDD ),
						'details'     => __( 'Street address for this location', TTDD ),
						'type'        => 'textarea',
						'placeholder' => __( '67 South Tranquil Path, The Woodlands', TTDD ),
					),

					array(
						'id'          => '_dir_latitude',
						'title'       => __( 'Latitude', TTDD ),
						'details'     => __( 'Set latitude for this location', TTDD ),
						'type'        => 'text',
						'placeholder' => '23.764046',
					),

					array(
						'id'          => '_dir_longitude',
						'title'       => __( 'Longitude', TTDD ),
						'details'     => __( 'Set latitude for this location', TTDD ),
						'type'        => 'text',
						'placeholder' => '90.394087',
					),

					array(
						'id'          => '_dir_location',
						'title'       => __( 'Location', TTDD ),
						'details'     => __( 'Set location for this directory. You can add new location from Locations menu under Directories', TTDD ),
						'type'        => 'select2',
						'args'        => 'POSTS_%location%',
					),
				)
			),
		);

		// Location Meta Data
		$this->meta_fields['location'] = array(

			array(
				'options' => array(

					array(
						'id'      => '_loc_states',
						'title'   => __( 'State', TTDD ),
						'details' => __( 'Select state for this location', TTDD ),
						'type'    => 'select2',
						'args'    => $wpdl->get_states(),
					),

					array(
						'id'      => '_loc_country',
						'title'   => __( 'Country', TTDD ),
						'details' => __( 'Select Country for this location', TTDD ),
						'type'    => 'select2',
						'args'    => $wpdl->get_countries(),
					),


				)
			),
		);
	}

	private function satinize( $thing ) {

		if ( is_array( $thing ) ) {
			return apply_filters( 'wpdl_filters_sanitize', wp_kses_post( $thing ) );
		} else {
			return apply_filters( 'wpdl_filters_sanitize', sanitize_text_field( $thing ) );
		}
	}
}

global $wpdl;

$Post_meta = new WPDL_Post_meta();

$wpdl->Post_meta = $Post_meta;