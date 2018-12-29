<?php

/**
 * Fired during plugin deactivation.
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
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since     1.0.0
 */
class WCSW_Deactivator {

	/**
	 * Fired during plugin deactivation.
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
