<?php

/*
 * Plugin Name: WooCommerce Simple Wishlist
 * Plugin URI:
 * Description: A simple WooCommerce wishlist plugin.
 * Version: 1.0.0
 * Author: Andrei Nadaban
 * Author URI: http://andreinadaban.ro
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcsw
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WCSW_DIR', plugin_dir_path( __FILE__ ) );

/*
 * Plugin version.
 */
define( 'WCSW_VER', '1.0.0' );

/*
 * Sets the plugin version in the database if it doesn't match with the defined version.
 */
if ( WCSW_VER !== get_option( 'wcsw_version' ) ) {

	update_option( 'wcsw_version', WCSW_VER, 'yes' );

}

/*
 * Loads all classes from the includes folder.
 */
foreach ( glob( WCSW_DIR . '/includes/*.php' ) as $file ) {

	require_once $file;

}

/*
 * Initialization.
 */
if ( class_exists( 'WCSW\Wishlist' ) ) {

	$wcsw = new WCSW\Wishlist();

	$wcsw->init();

}

register_activation_hook( __FILE__, array( 'WCSW\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WCSW\Plugin', 'deactivate' ) );
