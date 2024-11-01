<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
 * Return query var value inside custom endpoints
 *
 * @param $var
 * @param $endpoint
 *
 * @return string|mixed
 */

if ( ! function_exists( 'wpdl_get_query_var_inside_endpoints' ) ) {
	function wpdl_get_query_var_inside_endpoints( $var = false, $endpoint = 'dashboard' ) {

		if ( empty( $var ) || ! $var ) {
			return false;
		}

		$query_vars = get_query_var( wpdl_get_current_endpoint() );
		$var_value  = pathinfo( $query_vars, PATHINFO_BASENAME );

		return apply_filters( 'wpdl_filters_query_var_inside_endpoints', $var_value, $var, $endpoint );
	}
}


/**
 * Print Notice into dom directory
 *
 * @param string    | $notice | Notice content
 * @param string    | $type | info / warning / error
 * @param bool      | $is_dismissable | Not effective
 * @param bool      | $is_echo | Whether to Print the notice or Return
 *
 * @return string
 */

function wpdl_print_notice( $notice = '', $type = 'info', $is_dismissable = true, $is_echo = true ) {

	$notice_html = sprintf( '<div class="wpdl-notice wpdl-notice-%s %s">%s</div>', $type, $is_dismissable ? 'wpdl-notice-dismissable' : '', $notice );
	$notice_html = apply_filters( 'wpdl_filters_notice', $notice_html, $notice, $type, $is_dismissable );

	if ( $is_echo ) {
		echo $notice_html;
	} else {
		return $notice_html;
	}
}


/**
 * Check is page
 *
 * @param $page_name | String | Which page you want to check
 *
 * @return boolean
 */

if ( ! function_exists( 'wpdl_is_page' ) ) {
	function wpdl_is_page( $page_name = '' ) {

		global $wp_query, $wpdl;

		$current_endpoint = wpdl_get_current_endpoint();
		$query_vars       = $wp_query ? $wp_query->query_vars : array();
		$bool_result      = false;

		switch ( $page_name ) {

			case 'new_directory' :
				if ( 'directories' == $current_endpoint && isset( $query_vars[ $current_endpoint ] ) && $query_vars[ $current_endpoint ] == 'new' ) {
					$bool_result = true;
				}
				break;

			case 'myaccount' :
				if ( get_the_ID() == $wpdl->page_myaccount ) {
					$bool_result = true;
				}
				break;

			case 'directory_archive' :
				if ( get_the_ID() == $wpdl->page_directory_archive ) {
					$bool_result = true;
				}
				break;
		}

		return apply_filters( 'wpdl_filters_is_page', $bool_result );
	}
}


/**
 * Return New Directory Submission URL
 *
 * @filter wpdl_filters_directory_submission_url
 * @return Url
 */

if ( ! function_exists( 'wpdl_get_directory_submission_url' ) ) {
	function wpdl_get_directory_submission_url() {

		return apply_filters( 'wpdl_filters_directory_submission_url', sprintf( '%sdirectories/new/', wpdl_get_myaccount_endpoint_url() ) );
	}
}


/**
 * Return Pagination
 *
 * @param $query_object WP_Query
 * @param $args
 *
 * @return string
 */

if ( ! function_exists( 'wpdl_pagination' ) ) {
	function wpdl_pagination( $query_object = false, $args = array() ) {

		global $wp_query;

		$previous_query = $wp_query;

		if ( $query_object ) {
			$wp_query = $query_object;
		}

		$paged = max( 1, ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1 );

		$defaults = array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $paged,
			'total'     => $wp_query->max_num_pages,
			'prev_text' => __( 'Previous', TTDD ),
			'next_text' => __( 'Next', TTDD ),
		);

		$args           = apply_filters( 'wpdl_filters_wpdl_pagination', array_merge( $defaults, $args ) );
		$paginate_links = paginate_links( $args );

		$wp_query = $previous_query;

		return $paginate_links;
	}
}


/**
 * Check is My Account Page
 *
 * @return boolean
 */

if ( ! function_exists( 'wpdl_is_myaccount_page' ) ) {
	function wpdl_is_myaccount_page() {

		global $wpdl;

		if ( get_the_ID() == $wpdl->page_myaccount ) {
			return true;
		} else {
			return false;
		}
	}
}


/**
 * Return My Account Nav Icons
 *
 * @filter wpdl_filters_myaccount_nav_icons
 *
 * @param $endpoint string
 *
 * @return html
 */

if ( ! function_exists( 'wpdl_myaccount_nav_icons' ) ) {
	function wpdl_myaccount_nav_icons( $endpoint = 'dashboard' ) {

		switch ( $endpoint ) {
			case 'dashboard' :
				$icon = '<i class="fa fa-tachometer"></i>';
				break;

			case 'directories' :
				$icon = '<i class="fa fa-folder-open-o"></i>';
				break;

			case 'favourites' :
				$icon = '<i class="fa fa-heart"></i>';
				break;

			case 'logout' :
				$icon = '<i class="fa fa-sign-out"></i>';
				break;

			default:
				$icon = '';
		}

		return apply_filters( 'wpdl_filters_myaccount_nav_icons', $icon, $endpoint );
	}
}


/**
 * Return Current Endpoint for My Account Page
 *
 */

if ( ! function_exists( 'wpdl_get_current_endpoint' ) ) {
	function wpdl_get_current_endpoint() {

		global $wp, $wpdl;

		foreach ( $wpdl->get_query_vars() as $key => $value ) {
			if ( isset( $wp->query_vars[ $key ] ) ) {
				return $key;
			}
		}

		return 'dashboard';
	}
}


/**
 * Return My Account Navigation Item URL
 */

if ( ! function_exists( 'wpdl_get_myaccount_endpoint_url' ) ) {
	function wpdl_get_myaccount_endpoint_url( $endpoint = 'dashboard' ) {

		$permalink = wpdl_get_page_permalink( 'myaccount' );

		if ( 'dashboard' === $endpoint ) {
			return $permalink;
		}

		global $wpdl;

		$query_vars = $wpdl->get_query_vars();
		$endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;
		$url        = trailingslashit( $permalink ) . trailingslashit( $endpoint );

//		$url = add_query_arg( $endpoint, $value, $permalink );

		return apply_filters( 'wpdl_filters_myaccount_endpoint_url', $url, $endpoint );
	}
}


/**
 * Return My Account Navigation Item
 */

if ( ! function_exists( 'wpdl_get_myaccount_navigation' ) ) {
	function wpdl_get_myaccount_navigation() {
		global $wpdl;

		return $wpdl->get_myaccount_navigation();
	}
}


/**
 * Return My Account Nav Item Classes
 *
 * @filter wpdl_filters_myaccount_nav_item_classes
 * @return mixed|string
 */

if ( ! function_exists( 'wpdl_get_myaccount_nav_item_classes' ) ) {
	function wpdl_get_myaccount_nav_item_classes( $endpoint = 'dashboard' ) {

		global $wp;

		$classes = array(
			'wpdl-myaccount-na-link',
			'wpdl-myaccount-na-link--' . $endpoint,
		);

		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
			$current = true;
		}

		if ( $current ) {
			$classes[] = 'is-active';
		}

		$classes = apply_filters( 'wpdl_filters_myaccount_nav_item_classes', $classes, $endpoint );

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}
}


/**
 * Return Page Permalink
 *
 * @return mixed | String
 */

if ( ! function_exists( 'wpdl_get_page_permalink' ) ) {
	function wpdl_get_page_permalink( $permalink_for = false ) {

		global $wpdl;

		switch ( $permalink_for ) {

			case 'myaccount' :
				$permalink = get_permalink( $wpdl->page_myaccount );
				break;

			case 'archive' :
				$permalink = get_permalink( $wpdl->page_directory_archive );
				break;

			default:
				$permalink = get_the_permalink( $permalink_for );
				break;
		}

		return apply_filters( 'wpdl_filters_page_permalink', $permalink, $permalink_for );
	}
}


if ( ! function_exists( 'wpdl_get_template_part' ) ) {
	function wpdl_get_template_part( $slug, $name = '' ) {

		global $wpdl;

		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php.
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				$wpdl->template_path() . "{$slug}-{$name}.php"
			) );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( $wpdl->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
			$template = $wpdl->plugin_path() . "/templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", $wpdl->template_path() . "{$slug}.php" ) );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'wpdl_filters_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'wpdl_get_template' ) ) {
	function wpdl_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = wpdl_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', TTDD ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'wpdl_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'wpdl_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'wpdl_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'wpdl_locate_template' ) ) {
	function wpdl_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		global $wpdl;

		if ( ! $template_path ) {
			$template_path = $wpdl->template_path();
		}

		if ( ! $default_path ) {
			$default_path = $wpdl->plugin_path() . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'wpdl_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'wpdl_add_meta_field' ) ) {
	function wpdl_add_meta_field( $group_id = '', $meta_field_id = '', $args = array() ) {

		$meta_field_id   = empty( $meta_field_id ) ? time() : $meta_field_id;
		$meta_key        = isset( $args['meta_key'] ) ? $args['meta_key'] : '';
		$meta_icon       = isset( $args['meta_icon'] ) ? $args['meta_icon'] : '';
		$meta_field_type = isset( $args['meta_field_type'] ) ? $args['meta_field_type'] : '';
		$meta_type_data  = isset( $args['meta_type_data'] ) ? $args['meta_type_data'] : '';
		$show_frontend   = isset( $args['show_frontend'] ) && $args['show_frontend'] == $meta_field_id ? 'checked' : '';

		if ( empty( $group_id ) ) {
			return '';
		}

		global $wpdl;

		ob_start();

		?>
        <div class="meta-field meta-field-<?php echo $meta_field_id; ?>">

            <input class="meta-field-inline" type="text"
                   name="wpdl_meta_fields[<?php echo $group_id; ?>][fields][<?php echo $meta_field_id; ?>][meta_key]"
                   value="<?php echo $meta_key; ?>" placeholder="meta-key">
            <input class="meta-field-inline" type="text"
                   name="wpdl_meta_fields[<?php echo $group_id; ?>][fields][<?php echo $meta_field_id; ?>][meta_icon]"
                   value="<?php echo $meta_icon; ?>" placeholder="fa fa-amazon">

            <select class="meta-field-type-selector"
                    name="wpdl_meta_fields[<?php echo $group_id; ?>][fields][<?php echo $meta_field_id; ?>][meta_field_type]">

                <option value=""><?php _e( 'Select Field Type', TTDD ); ?></option>

				<?php foreach ( $wpdl->get_meta_field_types() as $field_key => $field_name ) : ?>

					<?php $selected = $field_key == $meta_field_type ? 'selected' : ''; ?>
					<?php printf( '<option %s value="%s">%s</option>', $selected, $field_key, $field_name ); ?>

				<?php endforeach; ?>
            </select>

			<?php $display = in_array( $meta_field_type, array(
				'select',
				'radio',
				'checkbox'
			) ) ? 'display:inline-block;' : 'display:none;'; ?>

            <input style="<?php echo $display; ?>" class="meta-type-data" type="text"
                   name="wpdl_meta_fields[<?php echo $group_id; ?>][fields][<?php echo $meta_field_id; ?>][meta_type_data]"
                   value="<?php echo $meta_type_data; ?>" value="<?php echo $meta_type_data; ?>"
                   placeholder="option-1|option-2|option-3" size="60">

            <div class="meta-field-head-inline meta-field-controller">

                <span class="tt--top tt--info" aria-label="Show in Frontend">
                    <input type="checkbox" value="<?php echo $meta_field_id; ?>" <?php echo $show_frontend; ?>
                           name="wpdl_meta_fields[<?php echo $group_id; ?>][fields][<?php echo $meta_field_id; ?>][show_frontend]">
				</span>

                <i class="drag-meta-field fa fa-arrows"></i>
                <i class="remove-meta-field fa fa-close"></i>
            </div>

        </div>
        <script>jQuery(document).ready(function ($) {
                $(function () {
                    $('.meta-fields').sortable({handle: ".drag-meta-field", revert: true});
                });
            })</script>
		<?php

		return ob_get_clean();
	}
}


if ( ! function_exists( 'wpdl_add_meta_group' ) ) {
	function wpdl_add_meta_group( $group_id = '', $args = array() ) {

		$group_id   = empty( $group_id ) ? time() : $group_id;
		$group_name = isset( $args['group_name'] ) ? $args['group_name'] : '';
		$fields     = isset( $args['fields'] ) ? $args['fields'] : array();

		ob_start();

		?>
        <div class="meta-field-group meta-field-group-<?php echo $group_id; ?>">

            <div class="meta-field-group-head">
                <input type="text" name="wpdl_meta_fields[<?php echo $group_id; ?>][group_name]"
                       placeholder="<?php _e( 'Group name', TTDD ); ?>" value="<?php echo $group_name; ?>" size="40">
                <div class="group-controller">
                    <i class="expand-meta-group fa fa-chevron-down"></i>
                    <i class="remove-meta-group fa fa-close"></i>
                </div>
            </div>

            <div class="meta-fields">
                <div class="button add-new-meta-field" group-id="<?php echo $group_id; ?>">Add New Field</div>

				<?php foreach ( $fields as $meta_field_id => $meta_field_args ) : ?>
					<?php echo wpdl_add_meta_field( $group_id, $meta_field_id, $meta_field_args ); ?>
				<?php endforeach; ?>
            </div>

        </div>
		<?php

		return ob_get_clean();
	}
}

if ( ! function_exists( 'wpdl_get_directory' ) ) {
    /**
     * Get Single Directory Object
     * @global WPDL_Directory $directory
     *
     * @param int|WPDL_Directory|null $directory_id
     *
     * @return WPDL_Directory
     */
	function wpdl_get_directory( $directory_id = '' ) {

		$directory_id = empty( $directory_id ) ? get_the_ID() : $directory_id;
		$_directory   = new WPDL_Directory( $directory_id );

		if ( ! $_directory ) {
			return null;
		}

		return $_directory;
	}
}

/**
 * Get full list of currency codes.
 * @since  1.0.5
 * @return array
 */
function wpdl_currencies() {
    static $currencies;
    if ( ! isset( $currencies ) ) {
        $currencies = array_unique( apply_filters( 'wpdl_currencies', array(
            'AED' => __( 'United Arab Emirates dirham', TTDD ),
            'AFN' => __( 'Afghan afghani', TTDD ),
            'ALL' => __( 'Albanian lek', TTDD ),
            'AMD' => __( 'Armenian dram', TTDD ),
            'ANG' => __( 'Netherlands Antillean guilder', TTDD ),
            'AOA' => __( 'Angolan kwanza', TTDD ),
            'ARS' => __( 'Argentine peso', TTDD ),
            'AUD' => __( 'Australian dollar', TTDD ),
            'AWG' => __( 'Aruban florin', TTDD ),
            'AZN' => __( 'Azerbaijani manat', TTDD ),
            'BAM' => __( 'Bosnia and Herzegovina convertible mark', TTDD ),
            'BBD' => __( 'Barbadian dollar', TTDD ),
            'BDT' => __( 'Bangladeshi taka', TTDD ),
            'BGN' => __( 'Bulgarian lev', TTDD ),
            'BHD' => __( 'Bahraini dinar', TTDD ),
            'BIF' => __( 'Burundian franc', TTDD ),
            'BMD' => __( 'Bermudian dollar', TTDD ),
            'BND' => __( 'Brunei dollar', TTDD ),
            'BOB' => __( 'Bolivian boliviano', TTDD ),
            'BRL' => __( 'Brazilian real', TTDD ),
            'BSD' => __( 'Bahamian dollar', TTDD ),
            'BTC' => __( 'Bitcoin', TTDD ),
            'BTN' => __( 'Bhutanese ngultrum', TTDD ),
            'BWP' => __( 'Botswana pula', TTDD ),
            'BYR' => __( 'Belarusian ruble (old)', TTDD ),
            'BYN' => __( 'Belarusian ruble', TTDD ),
            'BZD' => __( 'Belize dollar', TTDD ),
            'CAD' => __( 'Canadian dollar', TTDD ),
            'CDF' => __( 'Congolese franc', TTDD ),
            'CHF' => __( 'Swiss franc', TTDD ),
            'CLP' => __( 'Chilean peso', TTDD ),
            'CNY' => __( 'Chinese yuan', TTDD ),
            'COP' => __( 'Colombian peso', TTDD ),
            'CRC' => __( 'Costa Rican col&oacute;n', TTDD ),
            'CUC' => __( 'Cuban convertible peso', TTDD ),
            'CUP' => __( 'Cuban peso', TTDD ),
            'CVE' => __( 'Cape Verdean escudo', TTDD ),
            'CZK' => __( 'Czech koruna', TTDD ),
            'DJF' => __( 'Djiboutian franc', TTDD ),
            'DKK' => __( 'Danish krone', TTDD ),
            'DOP' => __( 'Dominican peso', TTDD ),
            'DZD' => __( 'Algerian dinar', TTDD ),
            'EGP' => __( 'Egyptian pound', TTDD ),
            'ERN' => __( 'Eritrean nakfa', TTDD ),
            'ETB' => __( 'Ethiopian birr', TTDD ),
            'EUR' => __( 'Euro', TTDD ),
            'FJD' => __( 'Fijian dollar', TTDD ),
            'FKP' => __( 'Falkland Islands pound', TTDD ),
            'GBP' => __( 'Pound sterling', TTDD ),
            'GEL' => __( 'Georgian lari', TTDD ),
            'GGP' => __( 'Guernsey pound', TTDD ),
            'GHS' => __( 'Ghana cedi', TTDD ),
            'GIP' => __( 'Gibraltar pound', TTDD ),
            'GMD' => __( 'Gambian dalasi', TTDD ),
            'GNF' => __( 'Guinean franc', TTDD ),
            'GTQ' => __( 'Guatemalan quetzal', TTDD ),
            'GYD' => __( 'Guyanese dollar', TTDD ),
            'HKD' => __( 'Hong Kong dollar', TTDD ),
            'HNL' => __( 'Honduran lempira', TTDD ),
            'HRK' => __( 'Croatian kuna', TTDD ),
            'HTG' => __( 'Haitian gourde', TTDD ),
            'HUF' => __( 'Hungarian forint', TTDD ),
            'IDR' => __( 'Indonesian rupiah', TTDD ),
            'ILS' => __( 'Israeli new shekel', TTDD ),
            'IMP' => __( 'Manx pound', TTDD ),
            'INR' => __( 'Indian rupee', TTDD ),
            'IQD' => __( 'Iraqi dinar', TTDD ),
            'IRR' => __( 'Iranian rial', TTDD ),
            'IRT' => __( 'Iranian toman', TTDD ),
            'ISK' => __( 'Icelandic kr&oacute;na', TTDD ),
            'JEP' => __( 'Jersey pound', TTDD ),
            'JMD' => __( 'Jamaican dollar', TTDD ),
            'JOD' => __( 'Jordanian dinar', TTDD ),
            'JPY' => __( 'Japanese yen', TTDD ),
            'KES' => __( 'Kenyan shilling', TTDD ),
            'KGS' => __( 'Kyrgyzstani som', TTDD ),
            'KHR' => __( 'Cambodian riel', TTDD ),
            'KMF' => __( 'Comorian franc', TTDD ),
            'KPW' => __( 'North Korean won', TTDD ),
            'KRW' => __( 'South Korean won', TTDD ),
            'KWD' => __( 'Kuwaiti dinar', TTDD ),
            'KYD' => __( 'Cayman Islands dollar', TTDD ),
            'KZT' => __( 'Kazakhstani tenge', TTDD ),
            'LAK' => __( 'Lao kip', TTDD ),
            'LBP' => __( 'Lebanese pound', TTDD ),
            'LKR' => __( 'Sri Lankan rupee', TTDD ),
            'LRD' => __( 'Liberian dollar', TTDD ),
            'LSL' => __( 'Lesotho loti', TTDD ),
            'LYD' => __( 'Libyan dinar', TTDD ),
            'MAD' => __( 'Moroccan dirham', TTDD ),
            'MDL' => __( 'Moldovan leu', TTDD ),
            'MGA' => __( 'Malagasy ariary', TTDD ),
            'MKD' => __( 'Macedonian denar', TTDD ),
            'MMK' => __( 'Burmese kyat', TTDD ),
            'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', TTDD ),
            'MOP' => __( 'Macanese pataca', TTDD ),
            'MRO' => __( 'Mauritanian ouguiya', TTDD ),
            'MUR' => __( 'Mauritian rupee', TTDD ),
            'MVR' => __( 'Maldivian rufiyaa', TTDD ),
            'MWK' => __( 'Malawian kwacha', TTDD ),
            'MXN' => __( 'Mexican peso', TTDD ),
            'MYR' => __( 'Malaysian ringgit', TTDD ),
            'MZN' => __( 'Mozambican metical', TTDD ),
            'NAD' => __( 'Namibian dollar', TTDD ),
            'NGN' => __( 'Nigerian naira', TTDD ),
            'NIO' => __( 'Nicaraguan c&oacute;rdoba', TTDD ),
            'NOK' => __( 'Norwegian krone', TTDD ),
            'NPR' => __( 'Nepalese rupee', TTDD ),
            'NZD' => __( 'New Zealand dollar', TTDD ),
            'OMR' => __( 'Omani rial', TTDD ),
            'PAB' => __( 'Panamanian balboa', TTDD ),
            'PEN' => __( 'Peruvian nuevo sol', TTDD ),
            'PGK' => __( 'Papua New Guinean kina', TTDD ),
            'PHP' => __( 'Philippine peso', TTDD ),
            'PKR' => __( 'Pakistani rupee', TTDD ),
            'PLN' => __( 'Polish z&#x142;oty', TTDD ),
            'PRB' => __( 'Transnistrian ruble', TTDD ),
            'PYG' => __( 'Paraguayan guaran&iacute;', TTDD ),
            'QAR' => __( 'Qatari riyal', TTDD ),
            'RON' => __( 'Romanian leu', TTDD ),
            'RSD' => __( 'Serbian dinar', TTDD ),
            'RUB' => __( 'Russian ruble', TTDD ),
            'RWF' => __( 'Rwandan franc', TTDD ),
            'SAR' => __( 'Saudi riyal', TTDD ),
            'SBD' => __( 'Solomon Islands dollar', TTDD ),
            'SCR' => __( 'Seychellois rupee', TTDD ),
            'SDG' => __( 'Sudanese pound', TTDD ),
            'SEK' => __( 'Swedish krona', TTDD ),
            'SGD' => __( 'Singapore dollar', TTDD ),
            'SHP' => __( 'Saint Helena pound', TTDD ),
            'SLL' => __( 'Sierra Leonean leone', TTDD ),
            'SOS' => __( 'Somali shilling', TTDD ),
            'SRD' => __( 'Surinamese dollar', TTDD ),
            'SSP' => __( 'South Sudanese pound', TTDD ),
            'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', TTDD ),
            'SYP' => __( 'Syrian pound', TTDD ),
            'SZL' => __( 'Swazi lilangeni', TTDD ),
            'THB' => __( 'Thai baht', TTDD ),
            'TJS' => __( 'Tajikistani somoni', TTDD ),
            'TMT' => __( 'Turkmenistan manat', TTDD ),
            'TND' => __( 'Tunisian dinar', TTDD ),
            'TOP' => __( 'Tongan pa&#x2bb;anga', TTDD ),
            'TRY' => __( 'Turkish lira', TTDD ),
            'TTD' => __( 'Trinidad and Tobago dollar', TTDD ),
            'TWD' => __( 'New Taiwan dollar', TTDD ),
            'TZS' => __( 'Tanzanian shilling', TTDD ),
            'UAH' => __( 'Ukrainian hryvnia', TTDD ),
            'UGX' => __( 'Ugandan shilling', TTDD ),
            'USD' => __( 'United States (US) dollar', TTDD ),
            'UYU' => __( 'Uruguayan peso', TTDD ),
            'UZS' => __( 'Uzbekistani som', TTDD ),
            'VEF' => __( 'Venezuelan bol&iacute;var', TTDD ),
            'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', TTDD ),
            'VUV' => __( 'Vanuatu vatu', TTDD ),
            'WST' => __( 'Samoan t&#x101;l&#x101;', TTDD ),
            'XAF' => __( 'Central African CFA franc', TTDD ),
            'XCD' => __( 'East Caribbean dollar', TTDD ),
            'XOF' => __( 'West African CFA franc', TTDD ),
            'XPF' => __( 'CFP franc', TTDD ),
            'YER' => __( 'Yemeni rial', TTDD ),
            'ZAR' => __( 'South African rand', TTDD ),
            'ZMW' => __( 'Zambian kwacha', TTDD ),
        ) ) );
    }
    return $currencies;
}

/**
 * Get Base Currency Code.
 * @since  1.0.5
 * @return string
 */
function wpdl_get_currency() {
    return apply_filters( 'wpdl_currency', get_option( 'wpdl_currency' ) );
}
/**
 * Get Currency symbol.
 * @since  1.0.5
 * @param string $currency Currency. (default: '').
 * @return string
 */
function wpdl_get_currency_symbols( $currency = '' ) {
    if ( ! $currency ) $currency = wpdl_get_currency();
    $symbols = apply_filters( 'wpdl_currency_symbols', array(
        'AED' => '&#x62f;.&#x625;',
        'AFN' => '&#x60b;',
        'ALL' => 'L',
        'AMD' => 'AMD',
        'ANG' => '&fnof;',
        'AOA' => 'Kz',
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => 'Afl.',
        'AZN' => 'AZN',
        'BAM' => 'KM',
        'BBD' => '&#36;',
        'BDT' => '&#2547;&nbsp;',
        'BGN' => '&#1083;&#1074;.',
        'BHD' => '.&#x62f;.&#x628;',
        'BIF' => 'Fr',
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => 'Bs.',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTC' => '&#3647;',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYR' => 'Br',
        'BYN' => 'Br',
        'BZD' => '&#36;',
        'CAD' => '&#36;',
        'CDF' => 'Fr',
        'CHF' => '&#67;&#72;&#70;',
        'CLP' => '&#36;',
        'CNY' => '&yen;',
        'COP' => '&#36;',
        'CRC' => '&#x20a1;',
        'CUC' => '&#36;',
        'CUP' => '&#36;',
        'CVE' => '&#36;',
        'CZK' => '&#75;&#269;',
        'DJF' => 'Fr',
        'DKK' => 'DKK',
        'DOP' => 'RD&#36;',
        'DZD' => '&#x62f;.&#x62c;',
        'EGP' => 'EGP',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '&euro;',
        'FJD' => '&#36;',
        'FKP' => '&pound;',
        'GBP' => '&pound;',
        'GEL' => '&#x20be;',
        'GGP' => '&pound;',
        'GHS' => '&#x20b5;',
        'GIP' => '&pound;',
        'GMD' => 'D',
        'GNF' => 'Fr',
        'GTQ' => 'Q',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => '&#70;&#116;',
        'IDR' => 'Rp',
        'ILS' => '&#8362;',
        'IMP' => '&pound;',
        'INR' => '&#8377;',
        'IQD' => '&#x639;.&#x62f;',
        'IRR' => '&#xfdfc;',
        'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
        'ISK' => 'kr.',
        'JEP' => '&pound;',
        'JMD' => '&#36;',
        'JOD' => '&#x62f;.&#x627;',
        'JPY' => '&yen;',
        'KES' => 'KSh',
        'KGS' => '&#x441;&#x43e;&#x43c;',
        'KHR' => '&#x17db;',
        'KMF' => 'Fr',
        'KPW' => '&#x20a9;',
        'KRW' => '&#8361;',
        'KWD' => '&#x62f;.&#x643;',
        'KYD' => '&#36;',
        'KZT' => 'KZT',
        'LAK' => '&#8365;',
        'LBP' => '&#x644;.&#x644;',
        'LKR' => '&#xdbb;&#xdd4;',
        'LRD' => '&#36;',
        'LSL' => 'L',
        'LYD' => '&#x644;.&#x62f;',
        'MAD' => '&#x62f;.&#x645;.',
        'MDL' => 'MDL',
        'MGA' => 'Ar',
        'MKD' => '&#x434;&#x435;&#x43d;',
        'MMK' => 'Ks',
        'MNT' => '&#x20ae;',
        'MOP' => 'P',
        'MRO' => 'UM',
        'MUR' => '&#x20a8;',
        'MVR' => '.&#x783;',
        'MWK' => 'MK',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => 'MT',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => 'C&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#x631;.&#x639;.',
        'PAB' => 'B/.',
        'PEN' => 'S/.',
        'PGK' => 'K',
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PRB' => '&#x440;.',
        'PYG' => '&#8370;',
        'QAR' => '&#x631;.&#x642;',
        'RMB' => '&yen;',
        'RON' => 'lei',
        'RSD' => '&#x434;&#x438;&#x43d;.',
        'RUB' => '&#8381;',
        'RWF' => 'Fr',
        'SAR' => '&#x631;.&#x633;',
        'SBD' => '&#36;',
        'SCR' => '&#x20a8;',
        'SDG' => '&#x62c;.&#x633;.',
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&pound;',
        'SLL' => 'Le',
        'SOS' => 'Sh',
        'SRD' => '&#36;',
        'SSP' => '&pound;',
        'STD' => 'Db',
        'SYP' => '&#x644;.&#x633;',
        'SZL' => 'L',
        'THB' => '&#3647;',
        'TJS' => '&#x405;&#x41c;',
        'TMT' => 'm',
        'TND' => '&#x62f;.&#x62a;',
        'TOP' => 'T&#36;',
        'TRY' => '&#8378;',
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => 'Sh',
        'UAH' => '&#8372;',
        'UGX' => 'UGX',
        'USD' => '&#36;',
        'UYU' => '&#36;',
        'UZS' => 'UZS',
        'VEF' => 'Bs F',
        'VND' => '&#8363;',
        'VUV' => 'Vt',
        'WST' => 'T',
        'XAF' => 'CFA',
        'XCD' => '&#36;',
        'XOF' => 'CFA',
        'XPF' => 'Fr',
        'YER' => '&#xfdfc;',
        'ZAR' => '&#82;',
        'ZMW' => 'ZK',
    ) );
    $currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
    return apply_filters( 'wpdl_currency_symbol', $currency_symbol, $currency );
}

/**
 * Get the price format depending on the currency position.
 * @since  1.0.5
 * @return string
 */
function wpdl_get_price_format() {
    $currency_pos = get_option( 'wpdl_currency_pos' );
    // default is left
    $format       = '%1$s%2$s';
    switch ( $currency_pos ) {
        case 'left':
            $format = '%1$s%2$s';
            break;
        case 'right':
            $format = '%2$s%1$s';
            break;
        case 'left_space':
            $format = '%1$s&nbsp;%2$s';
            break;
        case 'right_space':
            $format = '%2$s&nbsp;%1$s';
            break;
    }
    return apply_filters( 'wpdl_price_format', $format, $currency_pos );
}

/**
 * Return the thousand separator for prices.
 *
 * @since  1.0.5
 * @return string
 */
function wpdl_get_price_thousand_separator() {
    return stripslashes( apply_filters( 'wpdl_get_price_thousand_separator', get_option( 'wpdl_price_thousand_sep' ) ) );
}

/**
 * Return the decimal separator for prices.
 *
 * @since  1.0.5
 * @return string
 */
function wpdl_get_price_decimal_separator() {
    $separator = apply_filters( 'wpdl_get_price_decimal_separator', get_option( 'wpdl_price_decimal_sep' ) );
    return $separator ? stripslashes( $separator ) : '.';
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @since  1.0.5
 * @return int
 */
function wpdl_get_price_decimals() {
    $num_decim = apply_filters( 'wpdl_get_price_decimals', get_option( 'wpdl_price_num_decimals' ) );
    return $num_decim === '' ? 2 : absint( $num_decim );
}

/**
 * Trim trailing zeros off prices.
 * @since  1.0.5
 * @param string|float|int $price Price.
 * @return string
 */
function wpdl_trim_zeros( $price ) {
    return preg_replace( '/' . preg_quote( wpdl_get_price_decimal_separator(), '/' ) . '0++$/', '', $price );
}

/**
 * Format the price with a currency symbol.
 * @since  1.0.5
 * @param  float $price Raw price.
 * @param  array $args  Arguments to format a price {
 *     Array of arguments.
 *     Defaults to empty array.
 *
 *     @type string $currency           Currency code.
 *                                      Defaults to empty string (Use the result from get_wpdl_currency()).
 *     @type string $decimal_separator  Decimal separator.
 *                                      Defaults the result of wpdl_get_price_decimal_separator().
 *     @type string $thousand_separator Thousand separator.
 *                                      Defaults the result of wpdl_get_price_thousand_separator().
 *     @type string $decimals           Number of decimals.
 *                                      Defaults the result of wpdl_get_price_decimals().
 *     @type string $price_format       Price format depending on the currency position.
 *                                      Defaults the result of get_wpdl_price_format().
 *     @type string $plan_seperator     Pricing plan separator.
 *                                      Defaults / (slash).
 *     @type string $plan               Pricing plan for current price.
 *                                      Defaults empty.
 * }
 * @return string
 */
function wpdl_price( $price, $args = array() ) {
    $args = apply_filters( 'wpdl_price_args', wp_parse_args( $args, array(
        'currency'                  => '',
        'decimal_separator'         => wpdl_get_price_decimal_separator(),
        'thousand_separator'        => wpdl_get_price_thousand_separator(),
        'decimals'                  => wpdl_get_price_decimals(),
        'price_format'              => wpdl_get_price_format(),
        'billing_cycle_separator'   => '/',
        'billing_cycle'             => '',
    ) ) );

    $unformatted_price = $price;
    $negative          = $price < 0;
    $price             = apply_filters( 'wpdl_raw_price', floatval( $negative ? $price * -1 : $price ) );
    $price             = apply_filters( 'wpdl_formatted_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

    if ( apply_filters( 'wpdl_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
        $price = wpdl_trim_zeros( $price );
    }

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="wpdl-currency-symbol">' . wpdl_get_currency_symbols( $args['currency'] ) . '</span>', $price );
    $output          = '<span class="wpdl-price">' . $formatted_price . '</span>';
    $billing_cycle = wpdl_billing_cycle( $args['billing_cycle'] );
    if( ! empty( $billing_cycle ) ) {
        if( ! empty( $args['billing_cycle_separator'] ) ) $output .= sprintf( '<span class="wpdl-billing-cycle-separator">%s</span>', esc_html( $args['billing_cycle_separator'] ) );
        $output .= sprintf( '<span class="wpdl-billing-cycle">%s</span>', $billing_cycle );
    }

    /**
     * Filters the string of price markup.
     *
     * @param string $output            Price HTML markup.
     * @param string $price             Formatted price.
     * @param array  $args              Pass on the args.
     * @param float  $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
     */
    return apply_filters( 'wpdl_price', $output, $price, $args, $unformatted_price );
}

/**
 * Get Billing Cycle/s
 * @since 1.0.5
 * @param string $cycle
 * @return string[]|string
 */
function wpdl_billing_cycle( $cycle = 'all' ) {
    $cycles = apply_filters( 'wpdl_billing_cycles', array(
        'daily'       => __( 'Daily', TTDD ),
        'weekly'      => __( 'Weekly', TTDD ),
        'bi_weekly'   => __( 'Bi-Weekly', TTDD ),
        'monthly'     => __( 'Monthly', TTDD ),
        'quarter'     => __( 'Per 3 Month', TTDD ),
        'half_yearly' => __( 'Half Yearly', TTDD ),
        'yearly'      => __( 'Yearly', TTDD ),
    ) );
    if( $cycle === 'all' ) return $cycles;
    $cycle = ( isset( $cycles[$cycle] ) ) ? $cycles[$cycle] : '';
    return apply_filters( 'wpdl_billing_cycle', $cycle );
}