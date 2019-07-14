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
	 * Checks if WooCommerce is active.
     *
     * @since    1.0.0
	 */
	public function check_for_dependencies() {

		if ( is_admin() &&
             current_user_can( 'activate_plugins' ) &&
             ! is_plugin_active( WCSW_WOO ) ) {

			deactivate_plugins( WCSW_PLUGIN );

			if ( isset( $_GET['activate'] ) ) {

				unset( $_GET['activate'] );

			}
		}

	}

	/**
	 * Displays admin notices.
	 *
	 * @since    1.0.0
	 */
	public function add_notices() {

		if ( is_admin() &&
		     current_user_can( 'activate_plugins' ) &&
		     ! is_plugin_active( WCSW_WOO ) ) {

			$notice = __( 'Please install and activate the WooCommerce plugin.', 'wcsw' );

			printf( '<div class="error"><p><strong>WooCommerce Simple Wishlist: </strong>%s</p></div>', $notice );

		}

	}

}
