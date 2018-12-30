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
	 * @access    protected
	 * @var       WCSW_Loader    $loader    Maintains and registers all the hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string    $version    The current version of the plugin.
	 */
	protected $version;

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

		$this->plugin_name = 'wcsw';

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

			add_option( 'wcsw_version', $this->get_version() );

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
		require_once WCSW_DIR . '/public/class-wcsw-public.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-assets.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-ui.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-functions.php';

		// Other functions.
		require_once WCSW_DIR . '/includes/wcsw-conditionals.php';
		require_once WCSW_DIR . '/includes/wcsw-data.php';

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
	 * Registers all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_public_hooks() {

		$assets    = new WCSW_Assets( $this->get_plugin_name(), $this->get_version() );
		$ui        = new WCSW_UI();
		$functions = new WCSW_Functions();

		$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $assets, 'js_variables' );

		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $ui, 'button' );
		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $ui, 'template' );

		$this->loader->add_action( 'init', $functions, 'endpoint', 10 );
		$this->loader->add_action( 'init', $functions, 'flush', 20 );
		$this->loader->add_action( 'init', $functions, 'add', 10 );
		$this->loader->add_action( 'init', $functions, 'remove', 10 );
		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $functions, 'ajax_processing' );

		$this->loader->add_filter( 'woocommerce_account_menu_items', $ui, 'menu', 10, 1 );

	}

	/**
	 * Runs the loader to execute all of the hooks.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress
	 * and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks.
	 *
	 * @since     1.0.0
	 * @return    WCSW_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieves the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
