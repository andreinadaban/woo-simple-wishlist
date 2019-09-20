<?php

namespace WCSW;

/**
 * The core plugin class.
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
 * @since    1.0.0
 */
class Core {

	/**
	 * The core class instance.
	 *
	 * @since    1.0.0
	 * @var      object    $instance
	 */
	private static $instance;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version
	 */
	private $version;

	/**
	 * The configuration array.
	 *
	 * @since    1.0.0
	 * @var      array    $config
	 */
	private $config;

	/**
	 * The loader that's responsible for registering all the hooks.
	 *
	 * @since    1.0.0
	 * @var      object    $loader
	 */
	private $loader;

	/**
	 * Internationalization.
	 *
	 * @since    1.0.0
	 * @var      object    $i18n
	 */
	private $i18n;

	/**
	 * Public.
	 *
	 * @since    1.0.0
	 * @var      object    $public
	 */
	private $public;

	/**
	 * Public assets.
	 *
	 * @since    1.0.0
	 * @var      object    $public_assets
	 */
	private $public_assets;

	/**
	 * Admin.
	 *
	 * @since    1.0.0
	 * @var      object    $admin
	 */
	private $admin;

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * Sets the plugin version, loads the dependencies, defines the locale, and sets the admin and public hooks.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function __construct( $config ) {

		if ( defined( 'VERSION' ) ) {
			$this->version = VERSION;
		} else {
			$this->version = '1.0.0';
		}

		// Sets the plugin version in the database.
		$this->set_version();

		$this->config = $config;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Instantiates the core class.
	 *
	 * Sets the default options and allows only one instance of the core class.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    object
	 */
	public static function instantiate() {

		// Checks if $instance has been set.
		if ( ! isset( self::$instance ) ) {

			// Creates the instance with the default options.
			self::$instance = new Core( apply_filters( 'wcsw_config', array(
				'ajax'                    => true,
				'button_add_icon'         => DIR . 'public/assets/dist/svg/heart-add.svg',
				'button_add_label'        => 'Add to wishlist',
				'button_clear'            => true,
				'button_clear_icon'       => DIR . 'public/assets/dist/svg/clear.svg',
				'button_clear_label'      => 'Clear wishlist',
				'button_default'          => true,
				'button_in_archive'       => true,
				'button_remove_icon'      => DIR . 'public/assets/dist/svg/heart-remove.svg',
				'button_remove_label'     => 'Remove from wishlist',
				'button_style'            => 'icon_text',
				'endpoint'                => 'wishlist',
				'menu_name'               => 'Wishlist',
				'menu_position'           => 2,
				'message_add_error'       => 'The product was not added to your wishlist. Please try again.',
				'message_add_success'     => 'The product was successfully added to your wishlist.',
				'message_add_view'        => 'View wishlist',
				'message_empty'           => 'There are no products in the wishlist yet.',
				'message_empty_label'     => 'Go shop',
				'message_clear_error'     => 'The wishlist was not cleared. Please try again.',
				'message_clear_success'   => 'The wishlist was successfully cleared.',
				'message_remove_error'    => 'The product was not removed from your wishlist. Please try again.',
				'message_remove_success'  => 'The product was successfully removed from your wishlist.',
			) ) );

		}

		// Returns the instance.
		return self::$instance;

	}

	/**
	 * Loads the required dependencies.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the plugin.
		require_once DIR . 'includes/Loader.php';

		// The class responsible for defining internationalization functionality.
		require_once DIR . 'includes/I18n.php';

		// The classes responsible for defining all actions that occur in the public side of the site.
		require_once DIR . 'public/Wishlist.php';
		require_once DIR . 'public/Assets.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once DIR . 'admin/Admin.php';

		$this->loader        = new Loader();
		$this->i18n          = new I18n();
		$this->public        = new Wishlist( $this->config );
		$this->public_assets = new Assets();
		$this->admin         = new Admin();

	}

	/**
	 * Registers all of the hooks related to the public functionality of the plugin.
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

		if ( $this->config['ajax'] ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $this->public_assets, 'enqueue_scripts' );
		}

		if ( $this->config['button_default'] ) {
			$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $this->public, 'button_add_remove' );
		}

		if ( $this->config['button_default'] && $this->config['button_in_archive'] ) {
			$this->loader->add_action( 'woocommerce_after_shop_loop_item', $this->public, 'button_add_remove', 12 );
		}

		$this->loader->add_action( 'woocommerce_account_wishlist_endpoint', $this->public, 'load_template' );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $this->public, 'add_menu', 10, 1 );

		if ( $this->config['button_default'] ) {
			$this->loader->add_action( 'wcsw_after_table', $this->public, 'button_clear' );
		}

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

		$this->loader->add_action( 'admin_init', $this->admin, 'check_dependencies' );
		$this->loader->add_action( 'admin_notices', $this->admin, 'add_notices' );

	}

	/**
	 * Sets the plugin version in the database.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function set_version() {

		if ( ! get_option( 'wcsw_version' ) ) {
			add_option( 'wcsw_version', $this->version );
		}

	}

	/**
	 * Defines the locale for internationalization.
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
	 * @since     1.0.0
	 * @access    public
	 */
	public function add_endpoint() {
		add_rewrite_endpoint( $this->config['endpoint'], EP_PAGES );
	}

	/**
	 * Flushes the rewrite rules.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function flush() {

		// Flushes the rewrite rules only once, if the transient exists.
		// The transient is created on plugin activation.
		if ( get_transient( 'wcsw_flush' ) ) {

			flush_rewrite_rules();

			// Deletes the transient to prevent flushing more than once.
			delete_transient( 'wcsw_flush' );

		}

	}

	/**
	 * Gets the public instance.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    object
	 */
	public function get_public() {
		return $this->public;
	}

	/**
	 * Runs the loader to execute all of the hooks.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

}
