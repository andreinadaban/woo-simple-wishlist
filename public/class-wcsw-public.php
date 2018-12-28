<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since    1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two example hooks
 * for how to enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Andrei Nadaban <contact@andreinadaban.ro>
 */
class WCSW_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WCSW_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WCSW_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-simple-wishlist-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WCSW_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WCSW_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-simple-wishlist-public.js', array( 'jquery' ), $this->version, true );

	}

	/*
	 * Creates the add to wishlist button.
	 */
	public function button() {

		if ( ! is_user_logged_in() ) {

			return;

		}

		$product_id = get_the_ID();

		/*
		 * If the current product is already in the wishlist.
		 */
		if ( WCSW_Helpers::is_in_wishlist( $product_id ) ) {

			echo $this->go_to_wishlist_button();

		}

		/*
		 * If the current product is not in the wishlist.
		 */
		if ( ! WCSW_Helpers::is_in_wishlist( $product_id ) ) {

			printf( '<a href="?wcsw-add=' . $product_id . '" class="%s">%s</a>', 'wcsw-button wcsw-button-ajax wcsw-button-add button', __( 'Add to wishlist', 'wcsw' ) );

		}

	}

	/*
	 * Go to wishlist button.
	 */
	public function go_to_wishlist_button() {

		return sprintf( ' <a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="%s">%s</a>', 'wcsw-button wcsw-button-wishlist button', __( 'Go to wishlist', 'wcsw' ) );

	}

	/*
	 * Creates the new menu item.
	 */
	public function menu( $items ) {

		/*
		 * The menu item position.
		 * Default: between Orders and Downloads(2).
		 */
		$position = apply_filters( 'wcsw_my_account_menu_position', 2 );

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1['wishlist'] = __( 'Wishlist', 'wcsw' );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/*
	 * Creates the new endpoint.
	 */
	public function endpoint() {

		add_rewrite_endpoint( 'wishlist', EP_PAGES );

	}

	/*
	 * Flushes the rewrite rules based on a transient.
	 */
	public function flush() {

		if ( get_transient( 'wcsw_flush' ) ) {

			flush_rewrite_rules();

			delete_transient( 'wcsw_flush' );

		}

	}

	/*
	 * Loads the wishlist template.
	 */
	public function template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		/*
		 * Custom template.
		 */
		if ( file_exists( $custom_template ) ) {

			require_once $custom_template;

		}

		/*
		 * Default template.
		 */
		if ( ! file_exists( $custom_template ) ) {

			require_once WCSW_DIR . '/templates/wishlist.php';

		}

	}

	/*
	 * Adds the product to the wishlist.
	 */
	public function add() {

		if ( ! WCSW_Helpers::is_get( 'wcsw-add' ) || WCSW_Helpers::is_in_wishlist( get_the_ID() ) ) {

			return;

		}

		if ( $data = $this->add_product( WCSW_Helpers::get_data_array() ) ) {

			update_user_meta( get_current_user_id(), 'wcsw_data', $data );

			/*
			 * Add success notice only if the request was NOT made with AJAX.
			 */
			if ( ! WCSW_Helpers::is_get( 'wcsw-ajax' ) ) {

				wc_add_notice( __( 'The product was successfully added to your wishlist.', 'wcsw' ), 'success' );

			} else {

				echo __( 'The product was successfully added to your wishlist.', 'wcsw' );

			}

		}

	}

	/*
	 * Validates the $_GET variable and adds the id to the data array.
	 */
	public function add_product( $data = array() ) {

		$id = $_GET['wcsw-add'];

		if ( ! WCSW_Helpers::is_valid( $id ) ) {

			return false;

		}

		$data['products'][$id] = array(
			'title' => get_the_title( $id ),
			'date_added' => current_time( 'timestamp' ),
		);

		return json_encode( $data );

	}

	/*
	 * Removes the product from the wishlist.
	 */
	public function remove() {

		/*
		 * If there is no request to remove a product do nothing.
		 */
		if ( ! WCSW_Helpers::is_get( 'wcsw-remove' ) ) {

			return;

		}

		/*
		 * The "$data" variable contains the new products array after the product removal.
		 */
		if ( $data = $this->remove_product( WCSW_Helpers::get_data_array() ) ) {

			update_user_meta( get_current_user_id(), 'wcsw_data', $data );

			/*
			 * Add success notice only if the request was NOT made with AJAX.
			 */
			if ( ! WCSW_Helpers::is_get( 'wcsw-ajax' ) ) {

				wc_add_notice( __( 'The product was successfully removed from your wishlist.', 'wcsw' ), 'success' );

			} else {

				echo __( 'The product was successfully removed from your wishlist.', 'wcsw' );

			}

		}

	}

	/*
	 * Validates the $_GET variable and removes the ID from the data array.
	 */
	public function remove_product( $data = array() ) {

		/*
		 * The ID of the product meant to be removed.
		 */
		$id  = $_GET['wcsw-remove'];

		/*
		 * The new products array that will exclude the removed product.
		 */
		$new = array();

		/*
		 * If the GET variable is not a valid ID then do nothing.
		 */
		if ( ! WCSW_Helpers::is_valid( $id ) ) {

			return false;

		}

		/*
		 * Loop through the added products.
		 */
		foreach ( $data as $data_key => $data_value ) {

			foreach ( $data_value as $key => $value ) {

				/*
				 * If the ID from the GET variable is equal to the current ID then skip the following code.
				 */
				if ( $id == $key ) {

					continue;

				}

				/*
				 * Add each product to the new products array.
				 * The "$value" variable contains the product data(eg. product title).
				 */
				$new['products'][$key] = $value;

			}

		}

		/*
		 * Returns the new data after product removal.
		 */
		return json_encode( $new );

	}

	/*
	 * JS variables.
	 */
	public function js_variables() {

		if ( is_account_page() || is_singular( 'product' ) ) {

			$go_to_wishlist_button = $this->go_to_wishlist_button();
			$ajax_url = get_admin_url() . '/admin-ajax.php';

			echo <<<EOT
			
				<script>
				
					var goToWishlistButton = '{$go_to_wishlist_button}';
					var ajaxURL = '{$ajax_url}';
				
				</script>

EOT;

		}

	}

	/*
	 * AJAX processing function.
	 */
	public function ajax_processing() {

		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

	}

}
