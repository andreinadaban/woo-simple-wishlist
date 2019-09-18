<?php

namespace WCSW;

/**
 * Plugin Name:    WooCommerce Simple Wishlist
 * Plugin URI:     http://andreinadaban.ro
 * Description:    A simple extension for WooCommerce that provides the basic functionality of a wishlist and a set of functions and filters for easy customization.
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
define( __NAMESPACE__ . '\VERSION', '1.0.0' );

/**
 * Plugin directory.
 */
define( __NAMESPACE__ . '\DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin main file.
 */
define( __NAMESPACE__ . '\PLUGIN', plugin_basename( __FILE__ ) );

/**
 * Loads the Activator and Deactivator classes.
 */
require_once DIR . 'includes/Activator.php';
require_once DIR . 'includes/Deactivator.php';

/**
 * Hooks the Activator and Deactivator methods.
 */
register_activation_hook( __FILE__, array( __NAMESPACE__ . '\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\Deactivator', 'deactivate' ) );

/**
 * The core plugin class.
 */
require DIR . 'includes/Core.php';

/**
 * The functions.
 */
require DIR . 'includes/functions.php';

/**
 * Runs the plugin after theme setup to allow developers to change the config array in the theme's functions.php file.
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\run' );
