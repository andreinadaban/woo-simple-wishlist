<?php

/**
 * The wishlist class.
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
 * The wishlist class.
 *
 * @since    1.0.0
 */
class WCSW_Wishlist {

	/**
	 * Gets existing user data from the database as JSON.
	 *
	 * @since    1.0.0
	 */
	public function get_raw_data() {

		return get_user_meta( get_current_user_id(), 'wcsw_data', true );

	}

	/**
	 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
	 *
	 * @since    1.0.0
	 */
	public function get_data_array() {

		return json_decode( $this->get_raw_data(), true );

	}

	/**
	 * Checks if the product is already in the wishlist.
	 *
	 * @since    1.0.0
	 */
	public function is_in_wishlist( $product_id ) {

		$wishlist_content = $this->get_data_array();
		$product_ids = [];

		if ( $wishlist_content ) {

			foreach ( $wishlist_content as $products => $product ) {

				foreach ( $product as $id => $details ) {

					$product_ids[] = $id;

					if ( in_array( $product_id, $product_ids ) ) {

						return true;

					}

				}

			}

		}

		return false;

	}

	/**
	 * Checks if there is a GET request.
	 *
	 * @since    1.0.0
	 */
	public function is_get_request( $param ) {

		if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {

			return true;

		}

		return false;

	}

	/**
	 * Checks if the $_GET variable is a valid product ID.
	 *
	 * @since    1.0.0
	 */
	public function is_valid_id( $id ) {

		if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {

			return false;

		}

		return true;

	}

}
