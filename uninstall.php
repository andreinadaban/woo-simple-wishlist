<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since    1.0.0
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Deletes the plugin version record from the options table.
 *
 * @since  1.0.0
 */
if ( get_option( 'wcsw_version' ) ) {

	delete_option( 'wcsw_version' );

}
