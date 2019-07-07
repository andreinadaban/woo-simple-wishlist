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
	 * The core class instance.
	 *
	 * @since     1.0.0
	 */
	private static $instance;

	/**
	 * The current version of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $version    The current version of the plugin.
	 */
	private $version;

	/**
	 * The configuration array.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       array    $config    The configuration array.
	 */
	private $config;

	/**
	 * The loader that's responsible for maintaining and registering all the hooks that power the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       WCSW_Loader    $loader    Maintains and registers all the hooks for the plugin.
	 */
	private $loader;

	/**
	 * Internationalization.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private $i18n;

	/**
	 * Public.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private $public;

	/**
	 * Public assets.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private $public_assets;

	/**
	 * Admin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private $admin;

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * Sets the plugin name and version that can be used throughout the plugin.
	 * Loads the dependencies, defines the locale, and sets the admin and public hooks.
	 *
	 * @since    1.0.0
	 */
	private function __construct( $config ) {

		if ( defined( 'WCSW_VERSION' ) ) {
			$this->version = WCSW_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->set_version();

		$this->config = $config;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Instantiates the class and allows only one instance.
	 */
	public static function instantiate() {

		// Checks is $instance has been set.
		if ( ! isset( self::$instance ) ) {

			// Creates the instance with the default options.
			self::$instance = new WCSW( apply_filters( 'wcsw_config', array(
				'button_add_icon'        => WCSW_DIR . '/public/assets/dist/svg/heart-add.svg',
				'button_add_label'       => 'Add to wishlist',
				'button_clear'           => true,
				'button_clear_icon'      => WCSW_DIR . '/public/assets/dist/svg/clear.svg',
				'button_clear_label'     => 'Clear wishlist',
				'button_in_archive'      => true,
				'button_remove_icon'     => WCSW_DIR . '/public/assets/dist/svg/heart-remove.svg',
				'button_remove_label'    => 'Remove from wishlist',
				'button_style'           => 'icon_text',
				'menu_name'              => 'Wishlist',
				'menu_position'          => 2,
				'message_add_error'      => 'The product was not added to your wishlist. Please try again.',
				'message_add_success'    => 'The product was successfully added to your wishlist.',
				'message_add_view'       => 'View wishlist',
				'message_empty'          => 'There are no products in the wishlist yet.',
				'message_empty_label'    => 'Go shop',
				'message_clear_error'    => 'The wishlist was not cleared. Please try again.',
				'message_clear_success'  => 'The wishlist was successfully cleared.',
				'message_remove_error'   => 'The product was not removed from your wishlist. Please try again.',
				'message_remove_success' => 'The product was successfully removed from your wishlist.',
			) ) );

		}

		// Returns the instance.
		return self::$instance;

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

		$this->loader        = new WCSW_Loader();
		$this->i18n          = new WCSW_i18n();
		$this->public        = new WCSW_Public_Wishlist( $this->config );
		$this->public_assets = new WCSW_Public_Assets();
		$this->admin         = new WCSW_Admin();

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

		$this->loader->add_action( 'plugins_loaded', $this->i18n, 'load_plugin_textdomain' );

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

		$this->loader->add_action( 'wp_loaded', $this, 'add_endpoint', 10 );
		$this->loader->add_action( 'wp_loaded', $this, 'flush', 20 );
		$this->loader->add_action( 'wp_loaded', $this->public, 'add_product', 10 );
		$this->loader->add_action( 'wp_loaded', $this->public, 'remove_product', 10 );
		$this->loader->add_action( 'wp_loaded', $this->public, 'clear', 10 );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public_assets, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->public_assets, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $this->public, 'add_buttons' );

		// Shows the Add to wishlist button on product archive pages.
		if ( $this->config['button_in_archive'] ) {

			$this->loader->add_action( 'woocommerce_after_shop_loop_item', $this->public, 'add_buttons', 12 );

		}

		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $this->public, 'load_template' );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $this->public, 'add_menu', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wcsw_ajax', $this->public, 'process_ajax_request' );
		$this->loader->add_action( 'wp_footer', $this->public, 'add_js_variables' );

	}

	/**
	 * Registers all of the hooks related to the admin functionality of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {

		$this->loader->add_action( 'admin_init', $this->admin, 'check_for_dependencies' );
		$this->loader->add_action( 'admin_notices', $this->admin, 'add_notices' );

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
	 * Gets the public instance.
	 *
	 * @since    1.0.0
	 */
	public function get_public() {
		return $this->public;
	}

}
