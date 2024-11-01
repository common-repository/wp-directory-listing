<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPDL_Responses_list_table extends WP_List_Table {

    public function __construct() {
        parent::__construct( array(
            'singular' => 'post',
            'plural' => 'posts',
            'ajax' => false,
        ) );
    }

    public function prepare_items() {

        $per_page = $this->get_items_per_page('wpdl_response_list_items_per_page');

        $args = array(
            'post_type' => 'wpcf7_responses',
            'posts_per_page' => $per_page,
            'orderby' => 'title',
            'order' => 'ASC',
            'offset' => ( $this->get_pagenum() - 1 ) * $per_page,
        );

        if ( ! empty( $_REQUEST['s'] ) ) {

            $args['s'] = sanitize_text_field( $_REQUEST['s'] );
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            if ( 'title' == sanitize_text_field( $_REQUEST['orderby'] ) ) {
                $args['orderby'] = 'title';
            } elseif ( 'author' == sanitize_text_field( $_REQUEST['orderby'] ) ) {
                $args['orderby'] = 'author';
            } elseif ( 'date' == sanitize_text_field( $_REQUEST['orderby'] ) ) {
                $args['orderby'] = 'date';
            }
        }

        if ( ! empty( $_REQUEST['order'] ) ) {
            if ( 'asc' == strtolower( $_REQUEST['order'] ) ) {
                $args['order'] = 'ASC';
            } elseif ( 'desc' == strtolower( $_REQUEST['order'] ) ) {
                $args['order'] = 'DESC';
            }
        } else $args['order'] = 'DESC';

        if( isset( $_REQUEST['cf_f'] ) && ! empty( sanitize_text_field( $_REQUEST['cf_f'] ) ) ) {

            $args['meta_query'][] = array(
                'key' => 'wpdl_form_id',
                'value' => sanitize_text_field($_GET['cf_f']),
                'compare' => '=',
            );
        }

        $q = new WP_Query();
        $this->items = $q->query( $args );

        $total_items = $q->found_posts;
        $total_pages = ceil( $total_items / $per_page );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'per_page' => $per_page,
        ) );
    }

    public function extra_tablenav( $which ) {

        if( $which != 'top' ) return;

        $forms      = get_posts( 'post_type=wpcf7_contact_form&post_per_page=-1' );
        $form_id	= isset( $_GET['cf_f'] ) ? filter_input( INPUT_GET, 'cf_f', FILTER_SANITIZE_STRING ) : '';

        echo '<div class="alignleft actions filter-by-form">';
        echo "<select name='cf_f' id='cf_f'>";
        printf( "<option value=''>%s</option>", __('Select Contact form', TTDD) );

        foreach( $forms as $form ) {

            printf( "<option %s value='%s'>%s</option>", $form_id == $form->ID ? 'selected' : '', $form->ID, $form->post_title );
        }

        echo "</select>";

        submit_button( __('Filter responses', TTDD), '', '', false, array( 'id' => 'filter-submit' ) );

        echo "</div>";

        echo '<div class="alignleft actions filter-by-form">';
        $this->search_box( __( 'Search Responses', 'contact-form-7' ), 'wpcf7-contact' );
        echo "</div>";
    }

    public function column_submitted_on( $item ) {

        $human_time_diff = human_time_diff( get_the_time('U', $item), current_time('timestamp') ) . __( ' ago', TTDD );

        ob_start();

        printf( '<span>%s</span>', get_the_time( 'F j, Y g:i A', $item ) );
        printf( '<div class="row-actions"><span class="timeago-view">%s</span></div>', $human_time_diff );

        return ob_get_clean();
    }

    public function column_cf7_title( $item ) {

        $wpdl_form_id   = get_post_meta( $item->ID, 'wpdl_form_id', true );
        $contact_form   = wpcf7_contact_form( $wpdl_form_id );

        ob_start();

        printf( '<a href="admin.php?page=wpcf7&post=%s&action=edit">%s</a>', $wpdl_form_id, get_the_title( $wpdl_form_id ) );
        printf( '<div class="row-actions"><span class="shortcode-view">%s</span></div>', $contact_form->shortcode() );

        return ob_get_clean();
    }

    public function column_title( $item ) {

        $edit_link  = add_query_arg( array( 'post' => $item->ID, 'action' => 'edit' ), admin_url( 'post.php', false ) );

        ob_start();

        printf('<strong><a class="row-title" href="%1$s">%2$s</a></strong>', esc_url( $edit_link ), esc_html( get_the_title( $item->ID ) ) );

        printf('<div class="row-actions">' );
        printf('<span class="view"><a href="%1$s">%2$s</a> | </span>', $edit_link, __('View', TTDD));
        printf('<span class="trash"><a href="%1$s">%2$s</a></span>', get_delete_post_link( $item->ID ), __('Trash', TTDD));
        printf('</div>');

        return ob_get_clean();
    }

    public function column_cb( $item ) {

        return sprintf('<input type="checkbox" id="cb-select-%1$s" name="post[]" value="%1$s" />', $item->ID );
    }

    protected function column_default( $item, $column_name ) {
        return '';
    }



    protected function get_bulk_actions() {
        $actions = array(
            'delete' => __( 'Delete', 'contact-form-7' ),
        );

        return $actions;
    }

    protected function get_sortable_columns() {
        return array();
    }

    public function get_columns() {
        return get_column_headers( get_current_screen() );
    }

    public static function define_columns() {

        return array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Response ID', 'contact-form-7' ),
            'cf7_title'     => __( 'Form title', 'contact-form-7' ),
            'submitted_on'  => __( 'Submitted on', 'contact-form-7' ),
        );
    }
}

