<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes properties and methods used across both
 * the public-facing side of the site and the admin area.
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin specific hooks,
 * and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since     1.0.0
 * @author    Andrei Nadaban <contact@andreinadaban.ro>
 */
class WCSW {

	/**
	 * The loader that's responsible for maintaining and registering
	 * all hooks that power the plugin.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       WCSW_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area
	 * and the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WCSW_VERSION' ) ) {
			$this->version = WCSW_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'woocommerce-simple-wishlist';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following classes that make up the plugin:
	 *
	 * - WCSW_Loader. Orchestrates the hooks of the plugin.
	 * - WCSW_i18n. Defines internationalization functionality.
	 * - WCSW_Admin. Defines all hooks for the admin area.
	 * - WCSW_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		 */
		require_once WCSW_DIR . '/includes/class-wcsw-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		 */
		require_once WCSW_DIR . '/includes/class-wcsw-i18n.php';

		/**
		 * Helper class.
		 */
		require_once WCSW_DIR . '/includes/class-wcsw-helpers.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WCSW_DIR . '/admin/class-wcsw-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 */
		require_once WCSW_DIR . '/public/class-wcsw-public.php';

		$this->loader = new WCSW_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WCSW_i18n class in order to set the domain
	 * and to register the hook with WordPress.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function set_locale() {

		$plugin_i18n = new WCSW_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WCSW_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_public_hooks() {

		$plugin_public = new WCSW_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_public, 'button' );
		$this->loader->add_action( 'init', $plugin_public, 'endpoint', 10 );
		$this->loader->add_action( 'init', $plugin_public, 'flush', 20 );
		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $plugin_public, 'template' );
		$this->loader->add_action( 'init', $plugin_public, 'add', 10 );
		$this->loader->add_action( 'init', $plugin_public, 'remove', 10 );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'js_variables' );
		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $plugin_public, 'ajax_processing' );

		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public, 'menu', 10, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WCSW_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
