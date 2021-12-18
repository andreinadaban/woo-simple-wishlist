<?php

namespace SW;

/**
 * The admin class.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The admin class.
 *
 * @since    1.0.0
 */
class Admin {

	/**
	 * Checks if the required plugins are active. If the required plugins are not active, the plugin is deactivated.
     *
     * @since    1.0.0
	 */
	public function dependencies() {

		if ( ! class_exists( 'WooCommerce' ) ) {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			deactivate_plugins( PLUGIN_MAIN_FILE );

		}

	}

	/**
	 * Adds admin notices.
	 *
	 * @since    1.0.0
	 */
	public function notices() {

		if ( ! class_exists( 'WooCommerce' ) ) {

			$message = __( 'The Simple Wishlist for WooCommerce plugin requires WooCommerce to be installed and active.', 'sw' );

			printf( '<div class="error"><p>%s</p></div>', $message );

		}

	}

}
