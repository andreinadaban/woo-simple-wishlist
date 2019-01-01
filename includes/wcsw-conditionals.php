<?php

/**
 * This file contains conditional functions.
 *
 * @since    1.0.0
 */

/**
 * Checks if the product is already in the wishlist.
 *
 * @since    1.0.0
 */
function wcsw_is_in_wishlist( $product_id, $data ) {

	$user_data   = $data->wcsw_get_raw_data();
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

/**
 * Checks if there is a GET request.
 *
 * @since    1.0.0
 */
function wcsw_is_get_request( $param ) {

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
function wcsw_is_valid_id( $id ) {

	if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {

		return false;

	}

	return true;

}
