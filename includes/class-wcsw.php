<?php

/**
 * The file that defines the core plugin class.
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
 * The core plugin class.
 *
 * This class is used to define internationalization and admin and public hooks.
 * It also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @since    1.0.0
 */
class WCSW {

	/**
	 * The loader that's responsible for maintaining and registering all the hooks that power the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       WCSW_Loader    $loader    Maintains and registers all the hooks for the plugin.
	 */
	private $loader;

	/**
	 * The current version of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $version    The current version of the plugin.
	 */
	private $version;

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * Sets the plugin name and version that can be used throughout the plugin.
	 * Loads the dependencies, defines the locale, and sets the admin and public hooks.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WCSW_VERSION' ) ) {
			$this->version = WCSW_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->set_version();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();
		$this->define_admin_hooks();

	}

	/**
	 * Sets the plugin version in the database.
	 */
	private function set_version() {

		if ( ! get_option( 'wcsw_version' ) ) {

			add_option( 'wcsw_version', $this->version );

		}

	}

	/**
	 * Loads the required dependencies for the plugin.
	 *
	 * Creates an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the plugin.
		require_once WCSW_DIR . '/includes/class-wcsw-loader.php';

		// The class responsible for defining internationalization functionality.
		require_once WCSW_DIR . '/includes/class-wcsw-i18n.php';

		// The classes responsible for defining all actions that occur in the public side of the site.
		require_once WCSW_DIR . '/public/class-wcsw-public-wishlist.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-assets.php';

		// The classes responsible for defining all actions that occur in the admin area.
		require_once WCSW_DIR . '/admin/class-wcsw-admin.php';

		$this->loader = new WCSW_Loader();

	}

	/**
	 * Defines the locale for this plugin for internationalization.
	 *
	 * Uses the WCSW_i18n class in order to set the domain and to register the hook with WordPress.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function set_locale() {

		$i18n = new WCSW_i18n();

		$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Creates the new endpoint.
	 *
	 * @since    1.0.0
	 */
	public function add_endpoint() {

		add_rewrite_endpoint( 'wishlist', EP_PAGES );

	}

	/**
	 * Flushes the rewrite rules based on a transient.
	 *
	 * This function usually runs on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function flush() {

		if ( get_transient( 'wcsw_flush' ) ) {

			flush_rewrite_rules();

			delete_transient( 'wcsw_flush' );

		}

	}

	/**
	 * Registers all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_public_hooks() {

		$public_wishlist = new WCSW_Public_Wishlist();
		$public_assets   = new WCSW_Public_Assets();

		$this->loader->add_action( 'wp_loaded', $this, 'add_endpoint', 10 );
		$this->loader->add_action( 'wp_loaded', $this, 'flush', 20 );
		$this->loader->add_action( 'wp_loaded', $public_wishlist, 'add', 10 );
		$this->loader->add_action( 'wp_loaded', $public_wishlist, 'remove', 10 );
		$this->loader->add_action( 'wp_loaded', $public_wishlist, 'clear', 10 );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_assets, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $public_wishlist, 'add_button' );

		// Show the "Add to wishlist" button on product archive pages.
		if ( get_option( 'wcsw_settings_button_archive' ) && get_option( 'wcsw_settings_button_archive' ) === 'yes' ) {

			$this->loader->add_action( 'woocommerce_after_shop_loop_item', $public_wishlist, 'add_button', 12 );

		}

		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $public_wishlist, 'load_template' );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $public_wishlist, 'add_menu', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $public_wishlist, 'process_ajax_request' );
		$this->loader->add_action( 'wp_footer', $public_wishlist, 'add_js_variables' );

	}

	/**
	 * Registers all of the hooks related to the admin functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {

		$admin = new WCSW_Admin();

		$this->loader->add_action( 'admin_init', $admin, 'check_for_dependencies' );
		$this->loader->add_action( 'admin_notices', $admin, 'add_notices' );
		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $admin, 'add_settings_tab', 50 );
		$this->loader->add_action( 'woocommerce_settings_tabs_wcsw_tab', $admin, 'settings_tab' );
		$this->loader->add_action( 'woocommerce_update_options_wcsw_tab', $admin, 'update_settings' );

	}

	/**
	 * Runs the loader to execute all of the hooks.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

}
