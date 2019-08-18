<?php

/**
 * The admin class.
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
 * The admin class.
 *
 * @since    1.0.0
 */
class WCSW_Admin {

	/**
	 * Checks if the required plugins are active.
     *
     * @since    1.0.0
	 */
	public function check_dependencies() {

		if ( ! class_exists( 'WooCommerce' ) ) {

			// If the required plugins are not active, the plugin is deactivated.
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			deactivate_plugins( WCSW_PLUGIN );

		}

	}

	/**
	 * Adds admin notices.
	 *
	 * @since    1.0.0
	 */
	public function add_notices() {

		// If the required plugins are not active.
		if ( ! class_exists( 'WooCommerce' ) ) {

			$message = __( 'The WooCommerce Simple Wishlist plugin requires WooCommerce to be installed and active.', 'wcsw' );

			printf( '<div class="error"><p>%s</p></div>', $message );

		}

	}

}
