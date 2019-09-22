<?php

namespace SW;

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

		if ( get_transient( 'sw_flush' ) ) {

			delete_transient( 'sw_flush' );

		}

		flush_rewrite_rules();

	}

}
