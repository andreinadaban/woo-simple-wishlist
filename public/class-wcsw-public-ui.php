<?php

/**
 * The UI class.
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
 * The UI class.
 *
 * @since    1.0.0
 */
class WCSW_UI {

	/**
	 * Creates the add to wishlist button.
	 *
	 * @since    1.0.0
	 */
	public function button() {

		if ( ! is_user_logged_in() ) {

			return;

		}

		$product_id = get_the_ID();

		// If the current product is already in the wishlist, adds the "View wishlist" button.
		if ( WCSW\is_in_wishlist( $product_id ) ) {

			echo $this->view_wishlist_button();

		}

		// If the current product is not in the wishlist, adds the "Add to wishlist" button.
		if ( ! WCSW\is_in_wishlist( $product_id ) ) {

			printf( '<a href="?wcsw-add=' . $product_id . '" class="%s">%s</a>', 'wcsw-button wcsw-button-ajax wcsw-button-add button', __( 'Add to wishlist', 'wcsw' ) );

		}

	}

	/**
	 * View wishlist button.
	 *
	 * @since    1.0.0
	 */
	public function view_wishlist_button() {

		return sprintf( ' <a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="%s">%s</a>', 'wcsw-button wcsw-button-wishlist button', __( 'View wishlist', 'wcsw' ) );

	}

	/**
	 * Creates the new menu item.
	 *
	 * @since    1.0.0
	 */
	public function menu( $items ) {

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
	public function template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		// Loads the custom template.
		if ( file_exists( $custom_template ) ) {

			require_once $custom_template;

		}

		// Loads the default template.
		if ( ! file_exists( $custom_template ) ) {

			require_once WCSW_DIR . '/templates/wishlist.php';

		}

	}

}
