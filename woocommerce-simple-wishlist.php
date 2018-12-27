<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://andreinadaban.ro
 * @since             1.0.0
 * @package           WooCommerce_Simple_Wishlist
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Simple Wishlist
 * Plugin URI:
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Andrei Nadaban
 * Author URI:        http://andreinadaban.ro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-simple-wishlist
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-simple-wishlist-activator.php
 */
function activate_woocommerce_simple_wishlist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-simple-wishlist-activator.php';
	WooCommerce_Simple_Wishlist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-simple-wishlist-deactivator.php
 */
function deactivate_woocommerce_simple_wishlist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-simple-wishlist-deactivator.php';
	WooCommerce_Simple_Wishlist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_simple_wishlist' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_simple_wishlist' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-simple-wishlist.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_simple_wishlist() {

	$plugin = new WooCommerce_Simple_Wishlist();
	$plugin->run();

}
run_woocommerce_simple_wishlist();
