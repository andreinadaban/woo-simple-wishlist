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

		// The wishlist base class.
		require_once WCSW_DIR . '/includes/class-wcsw-wishlist.php';

		// The classes responsible for defining all actions that occur in the public side of the site.
		require_once WCSW_DIR . '/public/class-wcsw-public-wishlist-assets.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-wishlist-ui.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-wishlist-controller.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-wishlist-js.php';

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

		$public_wishlist_assets     = new WCSW_Public_Wishlist_Assets();
		$public_wishlist_ui         = new WCSW_Public_Wishlist_UI();
		$public_wishlist_controller = new WCSW_Public_Wishlist_Controller( $public_wishlist_ui );
		$public_wishlist_js         = new WCSW_Public_Wishlist_JS( $public_wishlist_ui );

		$this->loader->add_action( 'init', $this, 'add_endpoint', 10 );
		$this->loader->add_action( 'init', $this, 'flush', 20 );

		$this->loader->add_action( 'init', $public_wishlist_controller, 'add', 10 );
		$this->loader->add_action( 'init', $public_wishlist_controller, 'remove', 10 );

		$this->loader->add_action( 'wp_enqueue_scripts', $public_wishlist_assets, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $public_wishlist_ui, 'add_button' );
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $public_wishlist_ui, 'add_button', 12 );
		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $public_wishlist_ui, 'load_template' );

		$this->loader->add_filter( 'woocommerce_account_menu_items', $public_wishlist_ui, 'add_menu', 10, 1 );

		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $public_wishlist_controller, 'process_ajax_request' );

		$this->loader->add_action( 'wp_footer', $public_wishlist_js, 'add_js_variables' );

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
