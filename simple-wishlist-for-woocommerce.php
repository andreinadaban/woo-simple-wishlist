<?php

/**
 * Plugin Name:    Simple Wishlist for WooCommerce
 * Plugin URI:     https://github.com/andreinadaban/simple-wishlist-for-woocommerce
 * Description:    A simple extension for WooCommerce that provides the basic functionality of a wishlist and a set of functions and filters for easy customization.
 * Version:        1.0.4
 * Author:         Andrei Nadaban
 * Author URI:     https://andreinadaban.com
 * License:        GPL-3.0+
 * License URI:    https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:    sw
 * Domain Path:    /languages
 */

namespace SW;

defined( 'WPINC' ) || exit;

/**
 * Current plugin version.
 */
define( __NAMESPACE__ . '\PLUGIN_VERSION', '1.0.4' );

/**
 * Plugin directory.
 */
define( __NAMESPACE__ . '\PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin main file.
 */
define( __NAMESPACE__ . '\PLUGIN_MAIN_FILE', plugin_basename( __FILE__ ) );

/**
 * Loads the Activator and Deactivator classes.
 */
require_once PLUGIN_DIR_PATH . 'includes/Activator.php';
require_once PLUGIN_DIR_PATH . 'includes/Deactivator.php';

/**
 * Hooks the Activator and Deactivator methods.
 */
register_activation_hook( __FILE__, array( __NAMESPACE__ . '\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\Deactivator', 'deactivate' ) );

/**
 * Loads the translations.
 */
load_plugin_textdomain( 'sw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * The core plugin class.
 */
require_once PLUGIN_DIR_PATH . 'includes/Core.php';

/**
 * The functions.
 */
require_once PLUGIN_DIR_PATH . 'includes/functions.php';

/**
 * Runs the plugin after theme setup to allow developers to change the config array in the theme's functions.php file.
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\run' );
