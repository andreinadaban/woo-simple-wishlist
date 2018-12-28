<?php

/**
 * Plugin Name:    WooCommerce Simple Wishlist
 * Plugin URI:     http://andreinadaban.ro
 * Description:    A simple WooCommerce extension that allows you to add products to a wishlist.
 * Version:        1.0.0
 * Author:         Andrei Nadaban
 * Author URI:     http://andreinadaban.ro
 * License:        GPL-2.0+
 * License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:    wcsw
 * Domain Path:    /languages
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Current plugin version.
 */
define( 'WCSW_VERSION', '1.0.0' );

/**
 * Plugin directory.
 */
define( 'WCSW_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wcsw-activator.php.
 */
function activate_wcsw() {
	require_once WCSW_DIR . '/includes/class-wcsw-activator.php';
	WCSW_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wcsw-deactivator.php.
 */
function deactivate_wcsw() {
	require_once WCSW_DIR . '/includes/class-wcsw-deactivator.php';
	WCSW_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wcsw' );
register_deactivation_hook( __FILE__, 'deactivate_wcsw' );

/**
 * The core plugin class that is used to define internationalization, admin and public hooks.
 */
require WCSW_DIR . '/includes/class-wcsw.php';

/**
 * Begins execution of the plugin.
 *
 * @since  1.0.0
 */
function run_wcsw() {

	$plugin = new WCSW();
	$plugin->run();

}

run_wcsw();
