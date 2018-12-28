<?php

namespace WCSW;

/*
 * Gets existing user data from the database as JSON.
 */
function get_data() {

	return get_user_meta( get_current_user_id(), 'wcsw_data', true );

}

/*
 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
 */
function get_data_array() {

	return json_decode( get_data(), true );

}
