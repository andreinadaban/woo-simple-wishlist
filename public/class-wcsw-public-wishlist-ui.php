<?php

/**
 * The public wishlist ui class.
 *
 * @since    1.0.0
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public wishlist ui class.
 *
 * @since    1.0.0
 */
class WCSW_Public_Wishlist_UI extends WCSW_Wishlist {

	/**
	 * Creates the add to wishlist or remove from wishlist buttons on the product page and on the product archive pages.
	 *
	 * @since    1.0.0
	 */
	public function add_button() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		$product_id = get_the_ID();
		$is_in_wishlist = $this->is_in_wishlist( $product_id );

		echo $this->get_add_to_wishlist_button( $product_id, $is_in_wishlist );
		echo $this->get_remove_from_wishlist_button( $product_id, $is_in_wishlist, 'star-fill' );

	}

	/**
	 * Returns the add to wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_add_to_wishlist_button( $product_id, $is_in_wishlist = false ) {

		$style = $is_in_wishlist ? 'display: none;' : '';

		return sprintf( ' <a href="?wcsw-add=%s" class="%s" style="%s" title="%s">%s</a>', $product_id, 'wcsw-button wcsw-button-ajax wcsw-button-add button', $style, __( 'Add to wishlist', 'wcsw' ), file_get_contents( WCSW_DIR . '/public/svg/star-stroke.svg' ) );

	}

	/**
	 * Returns the remove from wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_remove_from_wishlist_button( $product_id, $is_in_wishlist = true, $icon = 'x' ) {

		$style = $is_in_wishlist ? '' : 'display: none;';

		return sprintf( ' <a href="?wcsw-remove=%s" class="%s" style="%s" title="%s">%s</a>', $product_id, 'wcsw-button wcsw-button-ajax wcsw-button-remove button', $style, __( 'Remove from wishlist', 'wcsw' ), file_get_contents( WCSW_DIR . '/public/svg/' . $icon . '.svg' ) );

	}

	/**
	 * Creates the new menu item.
	 *
	 * @since    1.0.0
	 */
	public function add_menu( $items ) {

		// The menu item position.
		// Default: between Orders and Downloads(2).
		$position = apply_filters( 'wcsw_my_account_menu_position', 2 );

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1['wishlist'] = __( 'Wishlist', 'wcsw' );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/**
	 * Loads the wishlist template.
	 *
	 * @since    1.0.0
	 */
	public function load_template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		$wishlist_data = $this->get_data_array();

		// Shows the notice if there are no products in the wishlist...
		if ( ! $wishlist_data || empty( $wishlist_data ) ) {

			echo $this->get_empty_wishlist_notice();

			// ...and stops here.
			return;

		}

		// This variable is used inside the template.
		$products = $wishlist_data['products'];

		// Loads the custom template if one exists.
		if ( file_exists( $custom_template ) ) {

			require_once $custom_template;

		}

		// Loads the default template.
		if ( ! file_exists( $custom_template ) ) {

			require_once WCSW_DIR . '/templates/wishlist.php';

		}

	}

	/**
	 * Returns the empty wishlist notice HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_empty_wishlist_notice() {

		$url     = esc_url( wc_get_page_permalink( 'shop' ) );
		$label   = __( 'Go shop', 'wcsw' );
		$message = __( 'There are no products in the wishlist yet.', 'wcsw' );

		return sprintf( '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info"><a class="woocommerce-Button button" href="%s">%s</a>%s</div>', $url, $label, $message );

	}

	/**
	 * Displays the appropriate notice.
	 *
	 * @since    1.0.0
	 */
	public function display_notice( $type, $result ) {

		$add_success_message    = sprintf( '<a href="%s" class="button wc-forward">%s</a>%s', wc_get_account_endpoint_url( 'wishlist' ), __( 'View wishlist', 'wcsw' ), __( 'The product was successfully added to your wishlist.', 'wcsw' ) );
		$add_error_message      = __( 'The product was not added to your wishlist. Please try again.', 'wcsw' );

		$remove_success_message = __( 'The product was successfully removed from your wishlist.', 'wcsw' );
		$remove_error_message   = __( 'The product was not removed from your wishlist. Please try again.', 'wcsw' );

		// Adds success notice only if the request was NOT made with AJAX.
		if ( ! $this->is_get_request( 'wcsw-ajax' ) ) {

			// Success.
			if ( $result ) {

				wc_add_notice( ${$type . '_success_message'}, 'success' );

			// Failure.
			} else {

				wc_add_notice( ${$type . '_error_message'}, 'error' );

			}

		} else {

			// Success.
			if ( $result ) {

				printf( '<div class="woocommerce-message">%s</div>', ${$type . '_success_message'} );

			// Failure.
			} else {

				printf( '<div class="woocommerce-error">%s</div>', ${$type . '_error_message'} );

			}

			// Prevents other output.
			exit;

		}

	}

}
