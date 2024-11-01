<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

if( ! function_exists( 'wpdl_add_plugin_menu_page' ) ) {
    function wpdl_add_plugin_menu_page(){
        // dynamic meta configuration page
	    $meta_generator = array(
            'page_nav' 	=> __( 'Configure Meta', TTDD ),
            'show_submit' => true,
        );
        // Page Settings
	    $options = array(
            'page_nav' 	=> __( 'Page Options', TTDD ),
		    'page_settings' => array(

		    	'section_pages' => array(
					'title' => __('Pages', TTDD),
				    'options' => array(
					    array(
						    'id'		=> 'wpdl_page_myaccount',
						    'title'		=> __('My account page',TTDD),
						    'details'	=> __('Select my account page',TTDD),
						    'type'		=> 'select2',
						    'args'		=> 'PAGES',
					    ),
					    array(
						    'id'		=> 'wpdl_page_directory_archive',
						    'title'		=> __('Directory archive page',TTDD),
						    'details'	=> __('Select directory archive page',TTDD),
						    'type'		=> 'select2',
						    'args'		=> 'PAGES',
					    ),
				    ),
			    ),

		    ),
        );
        // Core Query & Loop Settings
	    $settings = array(
            'page_nav' 	=> __( 'Settings', TTDD ),
		    'page_settings' => array(

		    	'section_pages' => array(
					'title' => __('Directory Archive', TTDD),
				    'options' => array(
					    array(
						    'id'		=> 'directory_items_per_page',
						    'title'		=> __('Posts per page',TTDD),
						    'details'	=> __('Set how many directory items will show in Archive page. Default: 10',TTDD),
						    'type'		=> 'number',
						    'placeholder'		=> __('10', TTDD),
					    ),
					    array(
						    'id'		=> 'directory_items_per_row',
						    'title'		=> __('Items Per Row',TTDD),
						    'details'	=> __('Set how many directory items will show a single row. Default: 3',TTDD),
						    'type'		=> 'select',
						    'args'   => array(
						    	'2' => __( '2 Items', TTDD ),
						    	'3' => __( '3 Items', TTDD ),
						    	'4' => __( '4 Items', TTDD ),
						    ),
					    ),
				    ),
			    ),

		    ),
        );
        // Currency Settings
        $currency = array(
            'page_nav' 	=> __( 'Currency', TTDD ),
            'page_settings' => array(
                'section_pages' => array(
                    'title' => __('Currency Settings', TTDD),
                    'options' => array(
                        array(
                            'id'		=> 'wpdl_currency',
                            'title'		=> __('Currency', TTDD ),
                            'details'	=> __('This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.', TTDD ),
                            'type'		=> 'select2',
                            'args'      => wpdl_currencies(),
                            'value'     => empty( get_option( 'wpdl_currency' ) ) ? apply_filters( 'wpdl_filters_default_currency', 'USD' ) : get_option( 'wpdl_currency' ),
                        ),
                        array(
                            'id'		=> 'wpdl_currency_pos',
                            'title'		=> __('Currency Position', TTDD ),
                            'details'	=> __('This controls the position of the currency symbol.', TTDD ),
                            'type'		=> 'select',
                            'args'      => array(
                                'left'        => __( 'Left', TTDD ),
                                'right'       => __( 'Right', TTDD ),
                                'left_space'  => __( 'Left with space', TTDD ),
                                'right_space' => __( 'Right with space', TTDD ),
                            ),
                            'value'     => empty( get_option( 'wpdl_currency_pos' ) ) ? 'left' : get_option( 'wpdl_currency_pos' ),
                        ),
                        array(
                            'id'		=> 'wpdl_price_thousand_sep',
                            'title'		=> __('Thousand separator', TTDD ),
                            'details'	=> __('This sets the thousand separator of displayed prices.', TTDD ),
                            'type'		=> 'text',
                            'value'     => empty( get_option( 'wpdl_price_thousand_sep' ) ) ? '' : get_option( 'wpdl_price_thousand_sep' ),
                        ),
                        array(
                            'id'		=> 'wpdl_price_decimal_sep',
                            'title'		=> __('Decimal separator', TTDD ),
                            'details'	=> __('This sets the decimal separator of displayed prices.', TTDD ),
                            'type'		=> 'text',
                            'value'     => empty( get_option( 'wpdl_price_decimal_sep' ) ) ? '.' : get_option( 'wpdl_price_decimal_sep' ),
                        ),
                        array(
                            'id'		=> 'wpdl_price_num_decimals',
                            'title'		=> __('Number of decimals', TTDD ),
                            'details'	=> __('This sets the number of decimal points shown in displayed prices.', TTDD ),
                            'type'		=> 'number',
                            'value'     => false === get_option( 'wpdl_price_num_decimals' ) || get_option( 'wpdl_price_num_decimals' ) === '' ? 2 : abs( (int) get_option( 'wpdl_price_num_decimals' ) ),
                        ),
                    ),
                ),

            ),
        );

        $pages = array(
            'wpdl_meta'      => apply_filters( 'wpdl_filters_settings_wpdl_meta', $meta_generator ),
            'wpdl_options'   => apply_filters( 'wpdl_filters_settings_wpdl_options', $options ),
            'wpdl_settings'   => apply_filters( 'wpdl_filters_settings_wpdl_settings', $settings ),
            'wpdl_currency'   => apply_filters( 'wpdl_settings_currency', $currency ),
        );

        new WP_Settings( array(
	        'add_in_menu'       => true,
	        'menu_type'         => 'submenu',
	        'menu_title'        => __( 'Settings', TTDD ),
	        'page_title'        => __( 'Directory Listing Settings', TTDD ),
	        'menu_page_title'   => __( 'Directory Listing Settings', TTDD ),
	        'capability'        => "manage_options",
	        'parent_slug'       => "edit.php?post_type=directory",
	        'menu_slug'         => "wpdl",
	        'pages'             => apply_filters( 'wpdl_filters_settings_pages', $pages ),
        ) );
    }
}
add_action( 'init', 'wpdl_add_plugin_menu_page' );

if( ! function_exists('wpdl_wp_settings_page_meta') ) {
	function wpdl_wp_settings_page_meta() {

		global $wpdl;

		include $wpdl->admin_template_path() . 'settings-box-metabox.php';
	}
}
add_action('wp_settings_page_wpdl_meta', 'wpdl_wp_settings_page_meta');



if( ! function_exists( 'wpdl_add_whitelist_options' ) ){
	function wpdl_add_whitelist_options( $options ) {

		$options['wpdl_meta'][] = 'wpdl_meta_fields';
		return $options;
	}
}
add_filter('whitelist_options', 'wpdl_add_whitelist_options', 10, 1);