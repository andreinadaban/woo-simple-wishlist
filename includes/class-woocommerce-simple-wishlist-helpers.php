<?php

/**
 * Helper methods.
 *
 * @since    1.0.0
 */

/**
 * Helper methods.
 *
 * @author     Andrei Nadaban <contact@andreinadaban.ro>
 */
class WooCommerce_Simple_Wishlist_Helpers {

	/*
	 * Checks if the product is already in the wishlist.
	 */
	static function is_in_wishlist( $product_id ) {

		$user_data   = self::get_data();
		$product_ids = [];

		if ( $user_data ) {

			$wcsw_data = json_decode( $user_data, true );

			foreach ( $wcsw_data as $wcsw_key => $wcsw_value ) {

				foreach ( $wcsw_value as $key => $value ) {

					$product_ids[] = $key;

					if ( in_array( $product_id, $product_ids ) ) {

						return true;

					}

				}

			}

		}

		return false;

	}

	/*
	 * Checks if there is a GET request.
	 */
	static function is_get( $param ) {

		if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {

			return true;

		}

		return false;

	}

	/*
	 * Checks if the $_GET variable is a valid product ID.
	 */
	static function is_valid( $id ) {

		if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {

			return false;

		}

		return true;

	}

	/*
	 * Gets existing user data from the database as JSON.
	 */
	static function get_data() {

		return get_user_meta( get_current_user_id(), 'wcsw_data', true );

	}

	/*
	 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
	 */
	static function get_data_array() {

		return json_decode( self::get_data(), true );

	}

}
