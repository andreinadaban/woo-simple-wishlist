<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since    1.0.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Deletes the plugin data from the options table.
 *
 * @since  1.0.0
 */
if ( get_option( 'sw_version' ) ) {
	delete_option( 'sw_version' );
}
