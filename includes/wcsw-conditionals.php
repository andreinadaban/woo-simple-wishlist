<?php

namespace WCSW;

/*
 * Checks if the product is already in the wishlist.
 */
function is_in_wishlist( $product_id ) {

	$user_data   = get_raw_data();
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
function is_get( $param ) {

	if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {

		return true;

	}

	return false;

}

/*
 * Checks if the $_GET variable is a valid product ID.
 */
function is_valid( $id ) {

	if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {

		return false;

	}

	return true;

}
