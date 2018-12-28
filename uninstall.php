<?php

/**
 * Fired when the plugin is uninstalled.
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/*
 * Deletes the plugin version record from the options table.
 *
 * @since  1.0.0
 */
if ( get_option( 'woocommerce_simple_wishlist_version' ) ) {

	delete_option( 'woocommerce_simple_wishlist_version' );

}
