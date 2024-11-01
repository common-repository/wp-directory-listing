<?php
/**
 * Single Directory Object
 *
 * @Author        Pluginrox
 * @Copyright:    2018 Pluginrox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


class WPDL_Directory {

	public $ID = null;


	/**
	 * WPDL_Directory constructor.
	 *
	 * @param string $directory_id
	 */
	function __construct( $directory_id = '' ) {

		$this->init( $directory_id );
	}


	/**
     * init() of a Directory
     *
	 * @param $directory_id
	 */
	function init( $directory_id ) {

		$this->ID = empty( $directory_id ) ? get_the_ID() : $directory_id;
	}


	/**
	 * Return Favourite Status of Directory based on User
	 *
	 * @param bool $user_id
	 *
	 * @return mixed
	 */
	function get_favourite_status( $user_id = false ) {

		$user_id          = ! $user_id ? get_current_user_id() : $user_id;
		$wishlisted_users = $this->get_meta( 'wpdl_favourite_users', array(), false );
		$favourite_status = in_array( $user_id, $wishlisted_users ) ? 'fav' : 'unfav';

		return apply_filters( 'wpdl_filters_get_favourite_status', $favourite_status, $user_id, $this );
	}


	/**
	 * Return Directory Longitude
	 *
	 * @return mixed|void
	 */
	function get_longitude() {

		return apply_filters( 'wpdl_filters_latitude', $this->get_meta( '_dir_longitude' ) );
	}


	/**
	 * Return Directory Item Latitude
	 *
	 * @return mixed|void
	 */
	function get_latitude() {

		return apply_filters( 'wpdl_filters_latitude', $this->get_meta( '_dir_latitude' ) );
	}

	/**
	 * Return Directory Sharer html
	 *
	 * @filter wpdl_filters_directory_share_html
	 * @return mixed|void
	 */
	function get_share_html() {

		global $wpdl;

		$all_profiles = $wpdl->get_social_profiles();
		$share_html   = "";

		foreach ( $this->get_share() as $platform ) :

			if ( ! isset( $all_profiles[ $platform ] ) ) {
				continue;
			}

			switch ( $platform ) {

				case 'facebook' :
					$share_html .= sprintf( '<a href="//www.facebook.com/sharer/sharer.php?u=%s"><span class="meta-item"><i class="fa fa-facebook"></i></span></a>', $this->get_permalink() );
					break;

				case 'twitter' :
					$share_html .= sprintf( '<a href="//twitter.com/home?status=%s"><span class="meta-item"><i class="fa fa-twitter"></i></span></a>', $this->get_permalink() );
					break;

				case 'pinterest' :
					$share_html .= sprintf( '<a href="//pinterest.com/pin/create/button/?url=%s&media=%s&description=%s"><span class="meta-item"><i class="fa fa-pinterest"></i></span></a>', $this->get_permalink(), $this->get_thumbnail_url(), $this->get_short_description() );
					break;

				default:
					$share_html .= '';
					break;
			}

		endforeach;


		return apply_filters( 'wpdl_filters_directory_share_html', $share_html );
	}


	/**
	 * Get Sharer of Single Directory Item
	 *
	 * @return mixed|void
	 */
	function get_share() {

		$_dir_share = $this->get_meta( '_dir_share' );
		$_dir_share = empty( $_dir_share ) ? array() : $_dir_share;


		return apply_filters( 'wpdl_filters_directory_share', $_dir_share, $this->ID, $this );
	}


	/**
	 * Return All reviews of Single Directory Item
	 *
	 * @param array $args
	 *
	 * @filter wpdl_filters_directory_reviews
	 * @return mixed|void
	 */
	function get_reviews( $args = array() ) {

		$args = array_merge( $args, array(
			'post_id' => $this->ID
		) );

		$comments = get_comments( $args );

		return apply_filters( 'wpdl_filters_directory_reviews', $comments, $this->ID, $this );
	}


	/**
	 * Get Review Rating value
	 *
	 * @filter wpdl_filters_review_rating
	 * @return float|int
	 */
	function get_review_rating() {

		$rating_count = 0;
		$rating_total = 0;

		foreach ( $this->get_reviews( array( 'fields' => 'ids' ) ) as $comment_id ) {

			$wpdl_review_rating = get_comment_meta( $comment_id, 'wpdl_review_rating', true );
			$wpdl_review_rating = empty( $wpdl_review_rating ) ? 0 : (int) $wpdl_review_rating;

			$rating_count ++;
			$rating_total += $wpdl_review_rating;
		}

		$rating_value = (int) $rating_count > 0 ? ceil( $rating_total / $rating_count ) : 0;

		return apply_filters( 'wpdl_filters_review_rating', $rating_value, $this->ID, $this );
	}


	/**
	 * Return HTML of Rating of Single Directory Itme
	 *
	 * @return false|string
	 */
	function get_rating_html() {

		ob_start();

		$review_rating = $this->get_review_rating();

		echo '<span class="wpdl-directory-reviews">';

		for ( $i = 0; $i < 5; ++ $i ) {
			printf( '<i class="rating-icon %s fa fa-star"></i>', $i < $review_rating ? 'rating-fill' : '' );
		}

		echo '</span>';

		return ob_get_clean();
	}


	/**
	 * Return Rating Count HTML
	 *
	 * @filter wpdl_filters_rating_count_html
	 * @return mixed|void
	 */
	function get_rating_count_html() {

		$review_count = count( $this->get_reviews() );

		ob_start();

		printf( __( '<span class="total-rating">%s Rating%s</span>', TTDD ), $review_count, $review_count > 1 ? 's' : '' );

		return apply_filters( 'wpdl_filters_rating_count_html', ob_get_clean(), $this->ID, $this );
	}


	/**
	 * Return if a Directory item has rating or Not
	 */
	function has_rating() {

		if ( $this->get_review_rating() > 0 ) {
			return true;
		}

		return false;
	}


	/**
	 * Get Meta Data of Directory what created dynamically
	 *
	 * @return mixed
	 */
	function get_meta_data() {

		global $wpdl;

		$meta_data = array();

		foreach ( $wpdl->get_meta_data() as $meta__group ) {

			$fields     = isset( $meta__group['fields'] ) ? $meta__group['fields'] : array();
			$group_name = isset( $meta__group['group_name'] ) ? $meta__group['group_name'] : '';

			foreach ( $fields as $field_id => $field ) {

				$meta_key     = isset( $field['meta_key'] ) ? $field['meta_key'] : '';
				$meta_icon    = isset( $field['meta_icon'] ) ? $field['meta_icon'] : '';
				$meta_value   = $this->get_meta( $meta_key );
				$meta_label   = isset( $field['meta_key'] ) ? explode( '_', $field['meta_key'] ) : array();
				$meta_label   = ucwords( implode( ' ', $meta_label ) );
				$meta_display = isset( $field['show_frontend'] ) && $field['show_frontend'] == $field_id ? 'yes' : '';

				$meta_data[ $group_name ][] = array(
					'key'     => $meta_key,
					'label'   => $meta_label,
					'icon'    => $meta_icon,
					'value'   => $meta_value,
					'display' => $meta_display,
				);
			}
		}

		return apply_filters( 'wpdl_filters_directory_meta_data', $meta_data );
	}


	/**
	 * Return Formatted Meta Data for given meta_key
	 *
	 * @param bool $is_echo
	 * @param string $before
	 * @param string $after
	 * @param int $limit
	 *
	 * @return mixed
	 */
	function print_formatted_meta( $is_echo = true, $before = '<span>', $after = '</span>', $limit = 999 ) {

		$content = '';
		$count   = 0;

		foreach ( $this->get_meta_data() as $meta_data ) :
			foreach ( $meta_data as $meta ) :

				if ( ! isset( $meta['display'] ) || $meta['display'] != 'yes' ) {
					continue;
				}

				$meta_icon  = isset( $meta['icon'] ) ? $meta['icon'] : '';
				$meta_label = isset( $meta['label'] ) ? $meta['label'] : '';
				$meta_value = isset( $meta['value'] ) ? $meta['value'] : '';

				if ( is_array( $meta_value ) && reset( $meta_value ) == 'no' ) {
					$formatted_meta = sprintf( '<i class="fa %s" aria-hidden="true"></i> <del>%s</del>', $meta_icon, $meta_label );
				} elseif ( is_array( $meta_value ) ) {
					$formatted_meta = sprintf( '<i class="fa %s" aria-hidden="true"></i> %s', $meta_icon, $meta_label );
				} else {
					$formatted_meta = sprintf( '<i class="fa %s" aria-hidden="true"></i> %s %s', $meta_icon, $meta_value, $meta_label );
				}

				if ( $count < $limit ) {
					$content .= ! empty( $formatted_meta ) ? sprintf( '%s%s%s', $before, $formatted_meta, $after ) : '';
					$count ++;
				}

			endforeach;
		endforeach;

		if ( $is_echo ) {
			echo apply_filters( 'wpdl_filters_formatted_meta', $content, $this );
		} else {
			return apply_filters( 'wpdl_filters_formatted_meta', $content, $this );
		}
	}


	/**
	 * Get Keywords as Array of Single Directory
	 *
	 * @param array $args
	 *
	 * @return mixed|void
	 */
	function get_keywords( $args = array() ) {

		$keywords = wp_get_post_terms( $this->ID, 'directory_tags', $args );

		return apply_filters( 'wpdl_filters_directory_keywords', $keywords, $this->ID, $this );
	}


	/**
     * Return formatted Categories for this Directory
     *
	 * @param array $args
	 * @param string $delimeter
	 *
	 * @return mixed|void
	 */
	function get_formatted_categories( $args = array(), $delimeter = ', ' ) {

		$categories = array_map( function ( $cat ) {
			return $cat->name;
		}, $this->get_categories( $args ) );

		return apply_filters( 'wpdl_filters_directory_formatted_categories', implode( $delimeter, $categories ) );
	}


	/**
	 * Get Categories as Array of Single Directory
	 *
	 * @param array $args
	 *
	 * @return mixed|void
	 */
	function get_categories( $args = array() ) {

		$categories = wp_get_post_terms( $this->ID, 'directory_cat', $args );

		return apply_filters( 'wpdl_filters_directory_categories', $categories, $this->ID, $this );
	}


    /**
     * Get Directory Full Price with Interval
     *
     * @filter wpdl_filters_directory_full_price
     * @return string
     */
    function get_full_price() {
        global $wpdl;
        $price = wpdl_price( $this->get_price(), array(
            'currency'      => $wpdl->get_currency(),
            'billing_cycle' => $this->get_meta( '_dir_interval' )
        ) );
        return apply_filters( 'wpdl_filters_directory_full_price', $price, $this->ID, $this );
    }


    /**
     * Get Directory item flat Price
     *
     * @filter wpdl_filters_directory_price
     * @return float
     */
    function get_price() {

        $_dir_price = $this->get_meta( '_dir_price' );
        $_dir_price = empty( $_dir_price ) ? 0 : (float) $_dir_price;

        return apply_filters( 'wpdl_filters_directory_price', $_dir_price, $this->ID, $this );
    }


    /**
     * Return boolean whether a Directory Item has Price or Not
     *
     * @return bool
     */
    function has_price() {
        return $this->get_price() != 0;
    }


	/**
	 * Return the published date in any format
	 *
	 * @filter wpdl_filters_published_date
	 *
	 * @param string $format http://php.net/manual/en/function.date.php#example-2810
	 *
	 * @return string
	 */
	function get_published_date( $format = '' ) {

		$format = empty( $format ) ? 'F j, Y, g:i a' : $format;

		return apply_filters( 'wpdl_filters_published_date', get_the_date( $format, $this->ID ), $this->ID, $this );
	}


	/**
	 * Get Author Object
	 *
	 * @filter wpdl_filters_directory_author
	 * @todo Make a Global user object extending WP_User object
	 *
	 * @return mixed
	 */
	function get_author( $user_attribute = false ) {

		$_dir_post   = get_post( $this->get_id() );
		$_dir_author = $this->get_meta( '_dir_author' );
		$_dir_author = empty( $_dir_author ) || $_dir_author == 0 ? $_dir_post->post_author : $_dir_author;
		$_dir_author = new WP_User( $_dir_author );

		if ( ! $user_attribute ) {
			return apply_filters( 'wpdl_filters_directory_author', $_dir_author, $this->ID, $user_attribute, $this );
		}

		$user_attribute_val = isset( $_dir_author->{$user_attribute} ) ? $_dir_author->{$user_attribute} : '';

		return apply_filters( 'wpdl_filters_directory_author', $user_attribute_val, $this->ID, $user_attribute, $this );
	}


	/**
	 * Get Listing for
	 *
	 * @filter: wpdl_filters_directory_listing_for
	 * @return mixed|void
	 */
	function get_acquisition_type() {

		global $wpdl;

		$_dir_acquisition_type = $this->get_meta( '_dir_acquisition_type' );
		$all_options           = $wpdl->get_directory_acquisition_types();
		$selcted_options       = isset( $all_options[ $_dir_acquisition_type ] ) ? $all_options[ $_dir_acquisition_type ] : '';

		return apply_filters( 'wpdl_filters_directory_listing_for', $selcted_options, $this->ID, $this );
	}


	/**
	 * Return Location for this Directory
	 *
	 * @param bool $return_object
	 *
	 * @return mixed
	 */
	function get_location( $return_object = true ) {

		$location = $this->get_meta( '_dir_location' );

		if ( $return_object ) {
			$location = get_post( $location );
		}

		return apply_filters( 'wpdl_filters_directory_location', $location, $this->get_id(), $this );
	}


	/**
     * Return State Name for this Directory
     *
	 * @return mixed
	 */
	function get_state_name() {

	    global $wpdl;

	    return apply_filters( 'wpdl_filters_get_state_name', $wpdl->get_state( $this->get_state() ) );
	}


	/**
     * Return State Key of this Directory
     *
	 * @return mixed
	 */
	function get_state() {

		$location_id = $this->get_location( false );
		$state_key = get_post_meta( $location_id, '_loc_states', true );

		return apply_filters( 'wpdl_filters_get_state', $state_key );
    }


	/**
     * Return Country Name for this Directory
     *
	 * @return mixed
	 */
    function get_country_name() {

	    global $wpdl;

	    return apply_filters( 'wpdl_filters_get_country_name', $wpdl->get_country( $this->get_country() ) );
	}


	/**
     * Return Country for this Directory
     *
	 * @return mixed
	 */
	function get_country() {

		$location_id = $this->get_location( false );
		$country_key = get_post_meta( $location_id, '_loc_country', true );

		return apply_filters( 'wpdl_filters_get_country', $country_key );
    }


	/**
	 * Return whether a Directory Item has Gallery or not
	 * @return bool
	 */
	function has_gallery() {

		if ( count( $this->get_gallery_images( false, false ) ) == 0 ) {
			return false;
		}

		return true;
	}


	/**
	 * Return Gallery Image URLs
	 *
	 * @param bool $size
	 * @param bool $include_featured
	 * @param bool $with_alt
	 *
	 * @return mixed|void
	 */
	function get_gallery_images( $size = false, $include_featured = true, $with_alt = false ) {

		$size      = ! $size ? 'wpdl_gallery_image' : $size;
		$size      = apply_filters( 'wpdl_filters_gallery_image_size', $size, $this );
		$image_ids = $this->get_meta( '_dir_gallery' );
		$image_ids = empty( $image_ids ) ? array() : $image_ids;
		$image_arr = array();

		foreach ( $image_ids as $image_id ) {

			$image     = wp_get_attachment_image_src( $image_id, $size );
			$image_src = isset( $image[0] ) ? $image[0] : '';
			$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

			if ( empty( $image_src ) ) {
				continue;
			}

			if ( $with_alt ) {
				$image_arr[] = array(
					'src' => $image_src,
					'alt' => $image_alt,
				);
				continue;
			}

			$image_arr[] = $image_src;
		}

		if ( $include_featured ) {
			$image_arr = array_merge( array( get_the_post_thumbnail_url( $this->ID, $size ) ), $image_arr );
		}

		return apply_filters( 'wpdl_filters_directory_gallery_images', $image_arr );
	}


	/**
	 * Return Short Description for Single Directory
	 *
	 * @param bool $limit
	 * @param string $more
	 *
	 * @return mixed|void
	 */
	function get_short_description( $limit = false, $more = '...' ) {

		$short_description = $this->get_meta( '_dir_short_description' );
		$short_description = empty( $short_description ) ? '' : strip_shortcodes( $short_description );

		if ( $limit && ! empty( $limit ) ) {

			$limit             = apply_filters( 'wpdl_filters_short_description_length', $limit );
			$more              = apply_filters( 'wpdl_filters_short_description_more', $more );
			$short_description = wp_trim_words( $short_description, $limit, $more );
		}

		return apply_filters( 'wpdl_filters_directory_short_description', $short_description, $this->ID, $this );
	}


	/**
	 * Return Directory Thumbnail URl
	 *
	 * @return mixed|void
	 */
	function get_thumbnail_url( $size = 'post-thumbnail' ) {

		$thumbnail_url = get_the_post_thumbnail_url( $this->ID, $size );

		if ( empty( $thumbnail_url ) ) {

			$gallery_images = $this->get_gallery_images( $size );
			$thumbnail_url  = empty( $gallery_images ) ? '' : reset( $gallery_images );
		}

		return apply_filters( 'wpdl_filters_directory_thumbnail_url', $thumbnail_url, $this->ID, $this );
	}


	/**
     * Return Marker Infobox HTML
     *
	 * @return mixed|void
	 */
	function get_marker_infobox_html() {

		ob_start();

		?>

        <div class="row map-info-box">
            <div class="col-md-6 directory-thumbnail">
                <img src="<?php echo esc_url( $this->get_thumbnail_url() ); ?>"
                     alt="<?php echo esc_attr( $this->get_name() ); ?>">
            </div>

            <div class="col-md-6 directory-details">
                <h5>
                    <a href="<?php echo esc_url( $this->get_permalink() ); ?>">
						<?php echo esc_html( $this->get_name() ); ?>
                    </a>
                </h5>
                <div class="directory-item-rating">
		            <span class="wpdl-directory-reviews">
		                <i class="rating-icon rating-fill fa fa-star"></i>
		                <i class="rating-icon rating-fill fa fa-star"></i>
		                <i class="rating-icon rating-fill fa fa-star"></i>
		                <i class="rating-icon  fa fa-star"></i>
		                <i class="rating-icon  fa fa-star"></i>
		            </span>
                </div>
                <div class="directory-item-price">
                    <span class="directory-item-price-value"><?php echo wp_kses_post( $this->get_full_price() ); ?></span>
                </div>
            </div>
        </div>

		<?php

		return apply_filters( 'wpdl_filters_marker_infobox_html', ob_get_clean(), $this->get_id(), $this );
	}


	/**
	 * Check and return Boolean Featured status of Single Directory
	 *
	 * @return bool
	 */
	function is_featured() {

		$_dir_featured = $this->get_meta( '_dir_featured', array() );
		$_dir_featured = reset( $_dir_featured );

		if ( $_dir_featured == 'yes' ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Return the Directory Permalink
	 *
	 * @return mixed|void
	 */
	function get_permalink() {

		return apply_filters( 'wpdl_filters_directory_permalink', get_the_permalink( $this->ID ) );
	}


	/**
	 * Return Directory Item Name
	 *
	 * @return mixed|void
	 */
	function get_name() {

		return apply_filters( 'wpdl_filters_directory_slug', get_the_title( $this->ID ) );
	}


	/**
     * Return Directory ID
     *
	 * @return null
	 */
	function get_id() {
		return $this->ID;
	}


	/**
	 * Return Post Meta Value
	 *
	 * @param string $meta_key
	 * @param string $default
	 * @param boolean $single
	 *
	 * @return mixed
	 */
	function get_meta( $meta_key = '', $default = '', $single = true ) {

		$meta_value = get_post_meta( $this->ID, $meta_key, $single );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'wpdl_filters_get_post_meta', $meta_value, $meta_key, $this );
	}
}