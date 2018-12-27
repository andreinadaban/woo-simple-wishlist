<?php

namespace WCSW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * The wishlist class.
 */
if ( ! class_exists( __NAMESPACE__ . '\Wishlist' ) ) {

	class Wishlist {

		/*
		 * Initialization function.
		 */
		public function init() {

			$this->button_add();
			$this->menu_register();
			$this->endpoint_register();
			$this->flush_run();
			$this->template_add();
			$this->add_to();
			$this->remove_from();
			$this->enqueue_scripts();
			$this->ajax_actions();

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
			if ( self::is_in_wishlist( $product_id ) ) {

				echo $this->go_to_wishlist_button();

			}

			/*
			 * If the current product is not in the wishlist.
			 */
			if ( ! self::is_in_wishlist( $product_id ) ) {

				printf( '<a href="?wcsw-add=' . $product_id . '" class="%s">%s</a>',
					'wcsw-button wcsw-button-ajax wcsw-button-add button', __( 'Add to wishlist', 'wcsw' )
				);

			}

		}

		/*
		 * Go to wishlist button.
		 */
		public function go_to_wishlist_button() {

			return sprintf(
				' <a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="%s">%s</a>',
				'wcsw-button wcsw-button-wishlist button', __( 'Go to wishlist', 'wcsw' )
			);

		}

		/*
		 * Adds the add to wishlist button.
		 */
		public function button_add() {

			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'button' ) );

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
		 * Adds the new menu item.
		 */
		public function menu_register() {

			add_filter( 'woocommerce_account_menu_items', array( $this, 'menu' ), 10, 1 );

		}

		/*
		 * Creates the new endpoint.
		 */
		public function endpoint() {

			add_rewrite_endpoint( 'wishlist', EP_PAGES );

		}

		/*
		 * Adds the new endpoint before(10) flushing the rewrite rules.
		 */
		public function endpoint_register() {

			add_action( 'init', array( $this, 'endpoint' ), 10 );

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
		 * Flushes the rewrite rules after(20) the endpoint is added.
		 */
		public function flush_run() {

			if ( get_transient( 'wcsw_flush' ) ) {

				add_action( 'init', array( $this, 'flush' ), 20 );

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
		 * Adds the wishlist template.
		 */
		public function template_add() {

			add_action( 'woocommerce_account_wishlist_endpoint', array( $this, 'template' ) );

		}

		/*
		 * Adds the product to the wishlist.
		 */
		public function add() {

			if ( ! self::is_get( 'wcsw-add' ) || self::is_in_wishlist( get_the_ID() ) ) {

				return;

			}

			if ( $data = $this->add_product( self::get_data_array() ) ) {

				update_user_meta( get_current_user_id(), 'wcsw_data', $data );

				/*
				 * Add success notice only if the request was NOT made with AJAX.
				 */
				if ( ! self::is_get( 'wcsw-ajax' ) ) {

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

			if ( ! self::is_valid( $id ) ) {

				return false;

			}

			$data['products'][$id] = array(
				'title' => get_the_title( $id ),
				'date_added' => current_time( 'timestamp' ),
			);

			return json_encode( $data );

		}

		/*
		 * Adds the product to the wishlist.
		 */
		public function add_to() {

			add_action( 'init', array( $this, 'add' ), 10 );

		}

		/*
		 * Removes the product from the wishlist.
		 */
		public function remove() {

			/*
			 * If there is no request to remove a product do nothing.
			 */
			if ( ! self::is_get( 'wcsw-remove' ) ) {

				return;

			}

			/*
			 * The "$data" variable contains the new products array after the product removal.
			 */
			if ( $data = $this->remove_product( self::get_data_array() ) ) {

				update_user_meta( get_current_user_id(), 'wcsw_data', $data );

				/*
				 * Add success notice only if the request was NOT made with AJAX.
				 */
				if ( ! self::is_get( 'wcsw-ajax' ) ) {

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
			if ( ! self::is_valid( $id ) ) {

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
		 * Removes the product from the wishlist.
		 */
		public function remove_from() {

			add_action( 'init', array( $this, 'remove' ), 10 );

		}

		/*
		 * Gets existing user data from the database as JSON.
		 */
		static function get_data() {

			return get_user_meta( get_current_user_id(), 'wcsw_data', true );

		}

		/*
		 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
		 */
		static function get_data_array() {

			return json_decode( self::get_data(), true );

		}

		/*
		 * Checks if the product is already in wishlist.
		 */
		static function is_in_wishlist( $product_id ) {

			$user_data   = self::get_data();
			$product_ids = [];

			if ( $user_data ) {

				$wcsw_data = json_decode( $user_data, true );

				foreach ( $wcsw_data as $wcsw_key => $wcsw_value ) {

					foreach ( $wcsw_value as $key => $value ) {

						$product_ids[] = $key;

						if ( in_array( $product_id, $product_ids ) ) {

							return true;

						}

					}

				}

			}

			return false;

		}

		/*
		 * Checks if there is a GET request.
		 */
		static function is_get( $param ) {

			if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {

				return true;

			}

			return false;

		}

		/*
		 * Checks if the $_GET variable is a valid product ID.
		 */
		static function is_valid( $id ) {

			if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {

				return false;

			}

			return true;

		}

		/*
		 * Register scripts.
		 */
		public function register_scripts() {

			if ( is_account_page() || is_singular( 'product' ) ) {

				$go_to_wishlist_button = $this->go_to_wishlist_button();
				$ajax_url = get_admin_url() . '/admin-ajax.php';

				echo <<<EOT
			
				<script>
				
					var goToWishlistButton = '{$go_to_wishlist_button}';
					var ajaxURL = '{$ajax_url}';
				
				</script>

EOT;

				require_once WCSW_DIR . '/js/js.php';

			}

		}

		/*
		 * Enqueue scripts.
		 */
		public function enqueue_scripts() {

			add_action( 'wp_footer', array( $this, 'register_scripts' ) );

		}

		/*
		 * AJAX processing function.
		 */
		public function ajax_processing() {

			// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

		}

		/*
		 * AJAX actions.
		 */
		public function ajax_actions() {

			add_action( 'wp_ajax_wcsw_ajax', array( $this, 'ajax_processing' ) );

		}

	}

}
