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

		// The class responsible for getting data from the database.
		require_once WCSW_DIR . '/includes/class-wcsw-data.php';

		// The classes responsible for defining all actions that occur in the public side of the site.
		require_once WCSW_DIR . '/public/class-wcsw-public-assets.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-functions.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-ui.php';
		require_once WCSW_DIR . '/public/class-wcsw-public-js-variables.php';

		// Other functions.
		require_once WCSW_DIR . '/includes/wcsw-conditionals.php';

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

		$assets       = new WCSW_Public_Assets();
		$functions    = new WCSW_Public_Functions();
		$ui           = new WCSW_Public_UI();
		$js_variables = new WCSW_Public_JS_Variables( $ui ); // Inject the WCSW_Public_UI instance as a dependency.

		$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $js_variables, 'add_js_variables' );

		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $ui, 'add_button' );
		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $ui, 'load_template' );

		$this->loader->add_filter( 'woocommerce_account_menu_items', $ui, 'add_menu', 10, 1 );

		$this->loader->add_action( 'init', $functions, 'add_endpoint', 10 );
		$this->loader->add_action( 'init', $functions, 'flush', 20 );
		$this->loader->add_action( 'init', $functions, 'add', 10 );
		$this->loader->add_action( 'init', $functions, 'remove', 10 );
		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $functions, 'process_ajax_request' );

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
