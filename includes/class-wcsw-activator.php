<?php

/**
 * Fired during plugin activation.
 *
 * @since    1.0.0
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since     1.0.0
 * @author    Andrei Nadaban <contact@andreinadaban.ro>
 */
class WCSW_Activator {

	/**
	 * Fired during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! get_transient( 'woocommerce_simple_wishlist_flush' ) ) {

			set_transient( 'woocommerce_simple_wishlist_flush', '1', 0 );

		}

	}

}
