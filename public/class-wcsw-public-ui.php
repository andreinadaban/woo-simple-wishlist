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
 * @author     Andrei Nadaban <contact@andreinadaban.ro>
 */
class WCSW_UI {

	/*
	 * Creates the add to wishlist button.
	 */
	public function button() {

		if ( ! is_user_logged_in() ) {

			return;

		}

		$product_id = get_the_ID();

		/*
		 * If the current product is already in the wishlist.
		 */
		if ( WCSW\is_in_wishlist( $product_id ) ) {

			echo $this->go_to_wishlist_button();

		}

		/*
		 * If the current product is not in the wishlist.
		 */
		if ( ! WCSW\is_in_wishlist( $product_id ) ) {

			printf( '<a href="?wcsw-add=' . $product_id . '" class="%s">%s</a>', 'wcsw-button wcsw-button-ajax wcsw-button-add button', __( 'Add to wishlist', 'wcsw' ) );

		}

	}

	/*
	 * Go to wishlist button.
	 */
	public function go_to_wishlist_button() {

		return sprintf( ' <a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="%s">%s</a>', 'wcsw-button wcsw-button-wishlist button', __( 'Go to wishlist', 'wcsw' ) );

	}

	/*
	 * Creates the new menu item.
	 */
	public function menu( $items ) {

		/*
		 * The menu item position.
		 * Default: between Orders and Downloads(2).
		 */
		$position = apply_filters( 'wcsw_my_account_menu_position', 2 );

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1['wishlist'] = __( 'Wishlist', 'wcsw' );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/*
	 * Loads the wishlist template.
	 */
	public function template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		/*
		 * Custom template.
		 */
		if ( file_exists( $custom_template ) ) {

			require_once $custom_template;

		}

		/*
		 * Default template.
		 */
		if ( ! file_exists( $custom_template ) ) {

			require_once WCSW_DIR . '/templates/wishlist.php';

		}

	}

}
