<?php

/**
 * Plugin Name:    WooCommerce Simple Wishlist
 * Plugin URI:
 * Description:    This plugin allows you to add products to a wishlist.
 * Version:        1.0.0
 * Author:         Andrei Nadaban
 * Author URI:     http://andreinadaban.ro
 * License:        GPL-2.0+
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:    woocommerce-simple-wishlist
 * Domain Path:    /languages
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WOOCOMMERCE_SIMPLE_WISHLIST_VERSION', '1.0.0' );

/**
 * Plugin directory.
 */
define( 'WOOCOMMERCE_SIMPLE_WISHLIST_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-simple-wishlist-activator.php.
 */
function activate_woocommerce_simple_wishlist() {
	require_once WOOCOMMERCE_SIMPLE_WISHLIST_DIR . '/includes/class-woocommerce-simple-wishlist-activator.php';
	WooCommerce_Simple_Wishlist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-simple-wishlist-deactivator.php.
 */
function deactivate_woocommerce_simple_wishlist() {
	require_once WOOCOMMERCE_SIMPLE_WISHLIST_DIR . '/includes/class-woocommerce-simple-wishlist-deactivator.php';
	WooCommerce_Simple_Wishlist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_simple_wishlist' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_simple_wishlist' );

/**
 * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 */
require WOOCOMMERCE_SIMPLE_WISHLIST_DIR . '/includes/class-woocommerce-simple-wishlist.php';

/**
 * Begins execution of the plugin.
 *
 * @since  1.0.0
 */
function run_woocommerce_simple_wishlist() {

	$plugin = new WooCommerce_Simple_Wishlist();
	$plugin->run();

}

run_woocommerce_simple_wishlist();
