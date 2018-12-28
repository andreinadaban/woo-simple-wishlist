<?php

/**
 * Fired during plugin deactivation.
 *
 * @since    1.0.0
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since     1.0.0
 * @author    Andrei Nadaban <contact@andreinadaban.ro>
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
