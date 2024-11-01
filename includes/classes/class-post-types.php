<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WPDL_Post_types {

	public function __construct() {

		add_action( 'init', array( $this, 'postype_directory' ) );
	}

	public function postype_directory() {

		// Register Post Type - Directory
		$this->register_post_type( 'directory', array(
			'singular'  => __( 'Directory', TTDD ),
			'plural'    => __( 'Directories', TTDD ),
			'menu_icon' => WPDL_PLUGIN_URL . 'assets/images/directory.png',
		) );

		// Register Post type - Location
		$this->register_post_type( 'location', array(
			'singular'            => __( 'Location', TTDD ),
			'plural'              => __( 'Locations', TTDD ),
			'show_in_menu'        => 'edit.php?post_type=directory',
			'supports'            => array( 'title', 'thumbnail', ),
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
		) );


		$this->register_taxonomy( 'directory_cat', 'directory', array(
			'singular' => __( 'Directory Category', TTDD ),
			'plural'   => __( 'Directory Categories', TTDD ),
		) );

		$this->register_taxonomy( 'directory_tags', 'directory', array(
			'singular'     => __( 'Keyword', TTDD ),
			'plural'       => __( 'Directory Keywords', TTDD ),
			'hierarchical' => false,
		) );
	}


	private function register_post_type( $post_type, $args ) {

		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$args = apply_filters( 'wpdl_filters_register_post_type_' . $post_type, $args );

		$singular = isset( $args['singular'] ) ? $args['singular'] : '';
		$plural   = isset( $args['plural'] ) ? $args['plural'] : '';

		$args = array_merge( array(
			'labels'              => array(
				'name'               => sprintf( __( '%s', TTDD ), $plural ),
				'singular_name'      => $singular,
				'menu_name'          => __( $singular, TTDD ),
				'all_items'          => sprintf( __( '%s', TTDD ), $plural ),
				'add_new'            => sprintf( __( 'Add %s', TTDD ), $singular ),
				'add_new_item'       => sprintf( __( 'Add %s', TTDD ), $singular ),
				'edit'               => __( 'Edit', TTDD ),
				'edit_item'          => sprintf( __( 'Edit %s', TTDD ), $singular ),
				'new_item'           => sprintf( __( 'New %s', TTDD ), $singular ),
				'view'               => sprintf( __( 'View %s', TTDD ), $singular ),
				'view_item'          => sprintf( __( 'View %s', TTDD ), $singular ),
				'search_items'       => sprintf( __( 'Search %s', TTDD ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', TTDD ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in trash', TTDD ), $plural ),
				'parent'             => sprintf( __( 'Parent %s', TTDD ), $singular )
			),
			'description'         => sprintf( __( 'This is where you can create and manage %s.', TTDD ), $plural ),
			'public'              => true,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'rewrite'             => true,
			'query_var'           => true,
			'supports'            => array( 'title', 'thumbnail', 'editor', 'author' ),
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'menu_icon'           => '',
		), $args );

		register_post_type( $post_type, apply_filters( "register_post_type_$post_type", $args ) );
	}


	private function register_taxonomy( $tax_name, $obj_name, $args = array() ) {

		if ( taxonomy_exists( $tax_name ) ) {
			return;
		}

		$singular     = isset( $args['singular'] ) ? $args['singular'] : __( 'Singular', TTDD );
		$plural       = isset( $args['plural'] ) ? $args['plural'] : __( 'Plural', TTDD );
		$hierarchical = isset( $args['hierarchical'] ) ? $args['hierarchical'] : true;

		register_taxonomy( $tax_name, $obj_name,
			apply_filters( "register_taxonomy_" . $tax_name, array(
				'labels'              => array(
					'name'               => sprintf( __( '%s', TTDD ), $plural ),
					'singular_name'      => $singular,
					'menu_name'          => __( $singular, TTDD ),
					'all_items'          => sprintf( __( '%s', TTDD ), $plural ),
					'add_new'            => sprintf( __( 'Add %s', TTDD ), $singular ),
					'add_new_item'       => sprintf( __( 'Add %s', TTDD ), $singular ),
					'edit'               => __( 'Edit', TTDD ),
					'edit_item'          => sprintf( __( '%s Details', TTDD ), $singular ),
					'new_item'           => sprintf( __( 'New %s', TTDD ), $singular ),
					'view'               => sprintf( __( 'View %s', TTDD ), $singular ),
					'view_item'          => sprintf( __( 'View %s', TTDD ), $singular ),
					'search_items'       => sprintf( __( 'Search %s', TTDD ), $plural ),
					'not_found'          => sprintf( __( 'No %s found', TTDD ), $plural ),
					'not_found_in_trash' => sprintf( __( 'No %s found in trash', TTDD ), $plural ),
					'parent'             => sprintf( __( 'Parent %s', TTDD ), $singular )
				),
				'description'         => sprintf( __( 'This is where you can create and manage %s.', TTDD ), $plural ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => $hierarchical,
				'rewrite'             => true,
				'query_var'           => true,
				'show_in_nav_menus'   => true,
				'show_in_menu'        => true,
			) )
		);
	}
}

new WPDL_Post_types();