<?php

/**
 * The data class.
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
 * The data class.
 *
 * @since    1.0.0
 */
class WCSW_Data {

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

}
