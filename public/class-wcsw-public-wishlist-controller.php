<?php

/**
 * The public wishlist class.
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
 * The public wishlist class.
 *
 * @since    1.0.0
 */
class WCSW_Public_Wishlist_Controller extends WCSW_Wishlist {

	/**
	 * The WCSW_Public_Wishlist_UI object.
	 *
	 * @var    WCSW_Public_Wishlist_UI    $ui
	 */
	private $ui;

	/**
	 * Loads the WCSW_Public_Wishlist_UI object.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $ui ) {

		$this->ui = $ui;

	}

	/**
	 * Adds the product to the wishlist when the "Add to wishlist" button is clicked.
	 *
	 * @since    1.0.0
	 */
	public function add() {

		if ( ! $this->is_get_request( 'wcsw-add' ) || $this->is_in_wishlist( get_the_ID() ) ) {

			return;

		}

		if ( $new_wishlist_data = $this->add_product( $this->get_data_array() ) ) {

			$result = update_user_meta( get_current_user_id(), 'wcsw_data', $new_wishlist_data );

			$this->ui->display_notice( 'add', $result );

		}

	}

	/**
	 * Validates the $_GET variable and adds the ID to the data array.
	 *
	 * @since    1.0.0
	 */
	private function add_product( $wishlist_content = array() ) {

		$id = $_GET['wcsw-add'];

		if ( ! $this->is_valid_id( $id ) || ! is_user_logged_in() ) {

			return false;

		}

		$wishlist_content['products'][$id] = array(
			'title' => get_the_title( $id ),
		);

		return json_encode( $wishlist_content );

	}

	/**
	 * Removes the product from the wishlist.
	 *
	 * @since    1.0.0
	 */
	public function remove() {

		// If there is no request to remove a product do nothing.
		if ( ! $this->is_get_request( 'wcsw-remove' ) ) {

			return;

		}

		// The "$data" variable contains the new products array after the product removal.
		if ( $new_wishlist_data = $this->remove_product( $this->get_data_array() ) ) {

			$user_id = get_current_user_id();

			// Deletes the database record if the last product was removed.
			if ( empty( json_decode( $new_wishlist_data ) ) ) {
				$result = delete_user_meta( $user_id, 'wcsw_data' );
			} else {
				$result = update_user_meta( $user_id, 'wcsw_data', $new_wishlist_data );
			}

			$this->ui->display_notice( 'remove', $result );

		}

	}

	/**
	 * Validates the $_GET variable and removes the ID from the data array.
	 *
	 * @since    1.0.0
	 */
	private function remove_product( $wishlist_content = array() ) {

		// The ID of the product meant to be removed.
		$id = $_GET['wcsw-remove'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! $this->is_valid_id( $id ) || ! is_user_logged_in() ) {

			return false;

		}

		// The new products array that will exclude the removed product.
		$new_wishlist_data = array();

		// Loop through the added products.
		foreach ( $wishlist_content as $data_key => $data_value ) {

			foreach ( $data_value as $key => $value ) {

				// If the ID from the GET variable is equal to the current ID then skip the following code.
				if ( $id == $key ) {

					continue;

				}

				// Add each product to the new products array.
				// The "$value" variable contains the product data(eg. product title).
				$new_wishlist_data['products'][$key] = $value;

			}

		}

		// Returns the new data after product removal.
		return json_encode( $new_wishlist_data );

	}

	/**
	 * Clears the wishlist.
	 *
	 * @since    1.0.0
	 */
	public function clear() {

		// If there is no request to clear the wishlist do nothing.
		if ( ! $this->is_get_request( 'wcsw-clear' ) ) {
			return;
		}

		$user_id = get_current_user_id();

		// Deletes the database record.
		$result = delete_user_meta( $user_id, 'wcsw_data' );

		$this->ui->display_notice( 'clear', $result );

	}

	/**
	 * AJAX processing function.
	 *
	 * @since    1.0.0
	 */
	public function process_ajax_request() {

		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

	}

}
