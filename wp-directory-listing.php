<?php
/*
	Plugin Name: WordPress Directory Listing
	Plugin URI: https://pluginrox.com/plugin/wp-directory-listing/
	Description: Directory Listing Plugin for WordPress
	Version: 1.0.6
	Author: PluginRox
	Author URI: https://pluginrox.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



class WP_Directory_Listing {

	private static $_instance;

    function __construct() {

        $this->load_defines();
        $this->load_scripts();
        $this->load_functions();
        $this->load_classes();
    }

	public static function getInstance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

    function load_functions() {

        require WPDL_PLUGIN_DIR . 'includes/functions.php';
	    require WPDL_PLUGIN_DIR . 'includes/functions-ajax.php';
	    require WPDL_PLUGIN_DIR . 'includes/functions-settings.php';

	    require WPDL_PLUGIN_DIR . 'includes/wpdl-template-hooks.php';
	    require WPDL_PLUGIN_DIR . 'includes/wpdl-template-functions.php';
    }

    function load_classes() {

	    require WPDL_PLUGIN_DIR . 'includes/classes/class-functions.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-hook.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-post-types.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-post-meta.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-wp-settings.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-template-loader.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-directory.php';

	    require WPDL_PLUGIN_DIR . 'includes/classes/class-column-directory.php';
	    require WPDL_PLUGIN_DIR . 'includes/classes/class-column-location.php';
    }



    function admin_scripts() {

	    wp_enqueue_style('wpdl_admin_css', WPDL_PLUGIN_URL.'assets/admin/css/style.css', array(), time());
        wp_enqueue_style('font-awesome', WPDL_PLUGIN_URL.'assets/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('tool-tip', WPDL_PLUGIN_URL.'assets/tool-tip.min.css');

        wp_enqueue_script('jquery');
	    wp_enqueue_script('jquery-ui-sortable' );
        wp_enqueue_script('wpdl_admin_js', plugins_url( 'assets/admin/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
        wp_localize_script('wpdl_admin_js', 'wpdl', $this->get_localize_script() );
    }

    function front_scripts() {

	    wp_enqueue_style('wpdl_styles', WPDL_PLUGIN_URL.'assets/front/css/style.css', array(), time());
	    wp_enqueue_style('slick', WPDL_PLUGIN_URL.'assets/front/css/slick.min.css');
	    wp_enqueue_style('font-awesome', WPDL_PLUGIN_URL.'assets/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('tool-tip', WPDL_PLUGIN_URL.'assets/tool-tip.min.css');

        wp_enqueue_script('jquery');
        wp_enqueue_script('slick', plugins_url( 'assets/front/js/slick.min.js' , __FILE__ ) , array( 'jquery' ));
        wp_enqueue_script('wpdl_js', plugins_url( 'assets/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
        wp_localize_script('wpdl_js', 'wpdl', $this->get_localize_script() );
    }

    function load_scripts() {

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
    }

    function load_defines(){

        $this->define('WPDL_PLUGIN_URL',WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
        $this->define('WPDL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        $this->define('WPDL_PLUGIN_FILE', __FILE__ );
        $this->define('WPDL_PLUGIN_VERSION', '1.0.0' );
        $this->define('WPDL_PLUGIN_FILE', plugin_basename( __FILE__ ) );
        $this->define('TTDD', 'wp-directory-listing' );
    }

    private function define( $name, $value ){
        if( ! defined( $name ) ) define( $name, $value );
    }

    private function get_localize_script(){

    	return apply_filters( 'wpdl_filters_', array(
    		'ajaxurl' => admin_url( 'admin-ajax.php'),
		    'text' => array(
		    	'working' => __('Working...', TTDD),
		    ),
	    ) );
    }
}

global $wp_directory_listing;
if ( ! $wp_directory_listing ) {
	$wp_directory_listing = WP_Directory_Listing::getInstance();
}