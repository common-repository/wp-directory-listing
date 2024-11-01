<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPDL_Functions {

	public $page_myaccount = null;
	public $page_directory_archive = null;
	public $query_vars = array();


	/**
	 * WPDL_Functions constructor.
	 */
	function __construct() {

		self::set_pages();
		$this->init_query_vars();
	}


	/**
	 * Return User Query, It's a WP_Query Object, so don't forget to do wp_reset_query()
	 *
	 * @param array $args
	 * @param bool $user_id
	 *
	 * @return mixed
	 */
	function get_user_favourites( $args = array(), $user_id = false ) {

		$user_id = ! $user_id ? get_current_user_id() : $user_id;
		$args    = (array) $args;
		$args    = array_filter( $args );

		$defaults = array(
			'post_type'      => 'directory',
			'posts_per_page' => $this->get_directory_items_per_page(),
			'post_status'    => ( ! empty( $atts['status'] ) ) ? $atts['status'] : 'publish',
			'paged'          => wpdl_get_query_var_inside_endpoints( 'paged' ),
			'meta_query'     => array(
				array(
					'key'     => 'wpdl_favourite_users',
					'value'   => $user_id,
					'compare' => '=',
				)
			),
		);

		$args = apply_filters( 'wpdl_filters_user_favourites_query_args', array_merge( $defaults, $args ) );

		return apply_filters( 'wpdl_filters_user_favourites_query', new WP_Query( $args ) );
	}


	/**
	 * @return array|bool|mixed
	 */
	function get_directory_meta_fields() {

		if ( ! isset( $this->Post_meta ) ) {
			return false;
		}

		$Post_meta       = $this->Post_meta;
		$meta_fields     = isset( $Post_meta->meta_fields ) ? $Post_meta->meta_fields : array();
		$directory_metas = isset( $meta_fields['directory'] ) ? $meta_fields['directory'] : array();

		return $directory_metas;
	}


	/**
	 * Return WP_Query object for directory items for specific user
	 *
	 * @param array $args
	 * @param int $user_id
	 *
	 * @return mixed|void
	 */
	function get_user_directories( $args = array(), $user_id = 0 ) {

		$user_id = $user_id == 0 ? get_current_user_id() : $user_id;

		$args = (array) $args;
		$args = array_filter( $args );

		$defaults = array(
			'post_type'      => 'directory',
			'posts_per_page' => $this->get_directory_items_per_page(),
			'post_status'    => ( ! empty( $atts['status'] ) ) ? $atts['status'] : 'publish',
			'author'         => $user_id,
			'paged'          => wpdl_get_query_var_inside_endpoints( 'paged' ),
		);

		$args = apply_filters( 'wpdl_filters_user_directory_query_args', array_merge( $defaults, $args ) );

		return apply_filters( 'wpdl_filters_user_directory_query', new WP_Query( $args ) );
	}


	/**
	 * Return Query vars with Filtering
	 *
	 * @filter wpdl_filters_query_vars
	 * @return mixed|void
	 */
	function get_query_vars() {

		return apply_filters( 'wpdl_filters_query_vars', $this->query_vars );
	}


	/**
	 * Init All Query vars
	 */
	function init_query_vars() {

		foreach ( $this->get_myaccount_navigation() as $endpoint => $nav_label ) :

			$this->query_vars[ $endpoint ] = get_option( "wpdl_myaccount_endpoint_$endpoint", $endpoint );

		endforeach;
	}


	/**
	 * Return Navigation for My Account
	 *
	 * @filter
	 * @return mixed|void
	 */
	function get_myaccount_navigation() {

		$navigation = array(
			'dashboard'   => __( 'Dashboard', TTDD ),
			'directories' => __( 'My Directory', TTDD ),
			'favourites'  => __( 'My Favourites', TTDD ),
			'logout'      => __( 'Log out', TTDD ),
		);

		return apply_filters( 'wpdl_filters_myaccount_navigation', $navigation );
	}


	/**
	 * Return Sorting parameters for Directory Archive
	 *
	 * @return mixed|void
	 */
	function get_sorting_parameters() {

		$parameters = array(
			'descending'        => __( 'Descending', TTDD ),
			'ascending'         => __( 'Ascending', TTDD ),
			'price_low_to_high' => __( 'Price - Low to High', TTDD ),
			'price_high_to_low' => __( 'Price - High to Low', TTDD ),
			'best_rated'        => __( 'Best Rated', TTDD ),
		);

		return apply_filters( 'wpdl_filters_sorting_parameters', $parameters );
	}


	/**
	 * Return Items per page for Directory Archive
	 *
	 * @return mixed
	 */
	function get_directory_items_per_page() {

		$items_per_page = self::get_option( 'directory_items_per_page', 10 );

		return apply_filters( 'wpdl_filters_directory_items_per_page', $items_per_page );
	}


	/**
	 * Return Items per Row for Directory Archive
	 *
	 * @return mixed
	 */
	function get_directory_items_per_row() {

		$items_per_row = self::get_option( 'directory_items_per_row', 3 );

		return apply_filters( 'wpdl_filters_directory_items_per_row', $items_per_row );
	}


	/**
	 * Return Social profiles
	 *
	 * @return mixed|void
	 */
	function get_social_profiles() {

		$social_profiles = array(
			'facebook'  => sprintf( '<span class="meta-item"><i class="fa fa-facebook"></i> Facebook</span>' ),
			'twitter'   => sprintf( '<span class="meta-item"><i class="fa fa-twitter"></i> Twitter</span>' ),
			'pinterest' => sprintf( '<span class="meta-item"><i class="fa fa-pinterest"> Pinterst</i></span>' ),
		);

		return apply_filters( 'wpdl_filters_social_profiles', $social_profiles );
	}


	/**
	 * Get tabs of Single Directory Item
	 *
	 * @filter wpdl_filters_directory_tabs
	 * @return mixed|void
	 */
	function get_directory_tabs() {

		$tabs = array();

		$tabs['description'] = array(
			'title'    => __( 'Description', TTDD ),
			'priority' => 10,
			'callback' => 'wpdl_directory_tab_description',
		);

		$tabs['additional_information'] = array(
			'title'    => __( 'Additional Information', TTDD ),
			'priority' => 20,
			'callback' => 'wpdl_directory_tab_additional_information',
		);

		$tabs['reviews'] = array(
			'title'    => __( 'Reviews', TTDD ),
			'priority' => 30,
			'callback' => 'wpdl_directory_tab_reviews',
		);

		return apply_filters( 'wpdl_filters_directory_tabs', $tabs );
	}


    /**
     * Return Currency Symbol
     *
     * @return string
     */
    function get_currency_symbol() {
        $currency_sybmol = wpdl_get_currency_symbols( $this->get_currency() );
        return apply_filters( 'wpdl_filters_currency_symbol', $currency_sybmol );
    }


    /**
     * Return Currency default
     *
     * @return string
     */
    function get_currency() {
        $currency = get_option( 'wpdl_currency' );
        if( empty( $currency ) ) $currency = apply_filters( 'wpdl_filters_default_currency', 'USD' );
        return $currency;
    }


	/**
	 * Return Plugin Path
	 *
	 * @return mixed|void
	 */
	function plugin_path() {
		return apply_filters( 'wpdl_filters_plugin_path', untrailingslashit( WPDL_PLUGIN_DIR ) );
	}


	/**
	 * Return Template path
	 *
	 * @return mixed|void
	 */
	function template_path() {

		return apply_filters( 'wpdl_filters_template_path', 'wpdl/' );
	}


	/**
	 * Return Admin Template Path
	 *
	 * @return mixed|void
	 */
	function admin_template_path() {

		return apply_filters( 'wpdl_filters_admin_template_path', WPDL_PLUGIN_DIR . 'includes/admin-templates/' );;
	}


	/**
	 * Return Direcotry Items for options
	 *
	 * @return mixed|void
	 */
	function get_directory_acquisition_types() {

		return apply_filters(
			'wpdl_filters_directory_acquisition_type', array(
				'for_rent'    => __( 'For Rent', TTDD ),
				'for_sale'    => __( 'For Sale', TTDD ),
				'for_listing' => __( 'For Simple Listing', TTDD ),
			)
		);
	}


	/**
	 * Return Meta Data Types
	 *
	 * @return array
	 */
	function get_meta_data_type() {

		return array_map( function ( $meta_data ) {
			return isset( $meta_data['group_name'] ) ? $meta_data['group_name'] : '';
		}, $this->get_meta_data() );
	}


	function get_meta_fields_data( $meta_data = array(), $args = array() ) {

		$_options   = array();
		$id_prefix  = isset( $args['id_prefix'] ) ? $args['id_prefix'] : '';
		$meta_data  = empty( $meta_data ) ? $this->get_meta_data() : $meta_data;
		$fill_value = isset( $args['fill_value'] ) ? $args['fill_value'] : 'no';
		$fill_from  = isset( $args['fill_from'] ) ? $args['fill_from'] : 'post';

		if ( ! empty( $fill_value ) && ! empty( $fill_from ) ) {
			$posted_data = $fill_from == 'get' ? $_GET : $_POST;
		}

		foreach ( $meta_data as $meta__group ) {

			$fields    = isset( $meta__group['fields'] ) ? $meta__group['fields'] : array();
			$__options = array();

			foreach ( $fields as $field ) {

				$field_title         = isset( $field['meta_key'] ) ? explode( '_', $field['meta_key'] ) : array();
				$field_title         = ucwords( implode( ' ', $field_title ) );
				$meta_type_data      = isset( $field['meta_type_data'] ) ? $field['meta_type_data'] : '';
				$meta_type_data_arr  = empty( $meta_type_data ) ? array() : explode( '|', $meta_type_data );
				$meta_type_data_args = array();

				foreach ( $meta_type_data_arr as $type_arg ) {
					$meta_type_data_args[ sanitize_title( $type_arg ) ] = $type_arg;
				}

				$meta_key = isset( $field['meta_key'] ) ? $field['meta_key'] : '';

				if ( empty( $id_prefix ) ) {
					$meta_value = isset( $posted_data[ $meta_key ] ) ? $posted_data[ $meta_key ] : '';
				} else {
					$meta_value = isset( $posted_data[ $id_prefix ][ $meta_key ] ) ? $posted_data[ $id_prefix ][ $meta_key ] : '';
				}

				$meta_key = empty( $id_prefix ) ? $meta_key : sprintf( '%s[%s]', $id_prefix, $meta_key );

				$__options[] = array(
					'id'    => $meta_key,
					'title' => $field_title,
					'type'  => isset( $field['meta_field_type'] ) ? $field['meta_field_type'] : '',
					'args'  => $meta_type_data_args,
					'value' => $meta_value,
				);
			}

			$_options[] = array(
				'title'   => isset( $meta__group['group_name'] ) ? $meta__group['group_name'] : '',
				'options' => $__options,
			);
		}

		return $_options;
	}


	/**
	 * Return Meta Data
	 *
	 */
	function get_meta_data() {

		$meta_data = get_option( 'wpdl_meta_fields' );
		$meta_data = empty( $meta_data ) ? array() : $meta_data;

		return apply_filters( 'wpdl_filters_meta_data', $meta_data );
	}


	/**
	 * Return States List
	 *
	 * @return mixed
	 */
	function get_states() {

		global $states;
		$all_states = array();

		foreach ( glob( WPDL_PLUGIN_DIR . "assets/i18n/states/*.php" ) as $filename ) {
			include $filename;
		}

		foreach ( $states as $c_code => $state ) {
			foreach ( $state as $s_code => $s_name ) {
				$all_states[ $c_code . '_' . $s_code ] = $s_name;
			}
		}

		return apply_filters( 'wpdl_filters_states', $all_states );
	}


	/**
	 * Return State Name upon State Key
	 *
	 * @param $state_key
	 *
	 * @return mixed
	 */
	function get_state( $state_key ) {

		$__state = '';

		if ( ! empty( $__states = $this->get_states() ) && ! empty( $state_key ) ) {
			$__state = isset( $__states[ $state_key ] ) ? $__states[ $state_key ] : '';
		}

		return apply_filters( 'wpdl_filters_get_state', $__state, $state_key );
	}


	/**
	 * Return Meta Field Types
	 *
	 * @return mixed
	 */
	function get_meta_field_types() {

		$types = array(
			'text'       => __( 'Text field', TTDD ),
			'number'     => __( 'Number field', TTDD ),
			'select'     => __( 'Select options', TTDD ),
			'radio'      => __( 'Radio buttons', TTDD ),
			'checkbox'   => __( 'Checkboxes', TTDD ),
			'datepicker' => __( 'Datepicker', TTDD )
		);

		return apply_filters( 'wpdl_filters_meta_field_tyoes', $types );
	}


	/**
	 * Return Country list
	 *
	 * @return array
	 */
	function get_countries() {

		return apply_filters( 'wpdl_filters_countries', include WPDL_PLUGIN_DIR . 'assets/i18n/countries.php' );
	}


	/**
	 * Return Country Name upon Country Key
	 *
	 * @param $country_key
	 *
	 * @return mixed
	 */

	function get_country( $country_key ) {

		$__country = '';

		if ( ! empty( $__states = $this->get_countries() ) && ! empty( $country_key ) ) {
			$__country = isset( $__states[ $country_key ] ) ? $__states[ $country_key ] : '';
		}

		return apply_filters( 'wpdl_filters_get_country', $__country, $country_key );
	}


	/**
	 * @param string $option_key
	 * @param string $default_val
	 *
	 * @return mixed|string|void
	 */
	static function get_option( $option_key = '', $default_val = '' ) {

		if ( empty( $option_key ) ) {
			return '';
		}

		$option_val = get_option( $option_key, $default_val );
		$option_val = empty( $option_val ) ? $default_val : $option_val;

		return $option_val;
	}


	/**
	 * Set Pages
	 *
	 */
	function set_pages() {

		$this->page_myaccount         = $this->get_option( 'wpdl_page_myaccount' );
		$this->page_directory_archive = $this->get_option( 'wpdl_page_directory_archive' );
	}
}

global $wpdl;

$wpdl = new WPDL_Functions();