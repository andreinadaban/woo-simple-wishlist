<?php

namespace WCSW;

/**
 * The deactivator class.
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
 * The deactivator class.
 *
 * @since     1.0.0
 */
class Deactivator {

	/**
	 * Runs on plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		if ( get_transient( 'wcsw_flush' ) ) {

			delete_transient( 'wcsw_flush' );

		}

		flush_rewrite_rules();

	}

}
