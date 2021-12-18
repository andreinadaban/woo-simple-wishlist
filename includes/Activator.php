<?php

namespace SW;

/**
 * The activator class.
 *
 * @since     1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The activator class.
 *
 * @since    1.0.0
 */
class Activator {

	/**
	 * Runs on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// If the required plugins are not active, the execution stops here.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Sets a transient that is used to flush the rewrite rules only once.
		if ( ! get_transient( 'sw_flush' ) ) {
			set_transient( 'sw_flush', '1', 0 );
		}

	}

}
