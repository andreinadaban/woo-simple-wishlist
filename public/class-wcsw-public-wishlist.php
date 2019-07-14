<?php

/**
 * The public wishlist class.
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
 * The public wishlist class.
 *
 * @since    1.0.0
 */
class WCSW_Public_Wishlist {

	/**
	 * The configuration array.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       array    $config    The configuration array.
	 */
	private $config;

	/**
	 * Sets the config variable.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Creates the add to wishlist and remove from wishlist buttons on the product page and on the product archive pages.
	 *
	 * @since    1.0.0
	 */
	public function add_remove_buttons( $product_id = false ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! $product_id ) {
			$product_id = get_the_ID();
		}

		// Checks if the product is in the wishlist only once to minimize database calls.
		$is_in_wishlist = $this->is_in_wishlist( $product_id );

		printf(
			'<div class="wcsw-button-container">%s%s</div>',
			$this->add_to_wishlist_button( $product_id, $is_in_wishlist ),
			$this->remove_from_wishlist_button( $product_id, $is_in_wishlist )
		);

	}

	/**
	 * Returns the Add to wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	private function add_to_wishlist_button( $product_id, $is_in_wishlist ) {

		$nonce_token = wp_create_nonce( 'wcsw_add_to_wishlist_' . $product_id );
		$style       = $is_in_wishlist ? 'display: none; ' : '';
		$text        = __( $this->config['button_add_label'], 'wcsw' );
		$icon        = file_get_contents( $this->config['button_add_icon'] );
		$label       = $this->create_label( $icon, $text );

		return sprintf(
			' <a href="?wcsw-add=%s&nonce-token=%s" class="%s" style="%s" title="%s">%s</a>',
			$product_id,
			$nonce_token,
			'wcsw-button wcsw-button-ajax wcsw-button-add',
			$style,
			$text,
			$label
		);

	}

	/**
	 * Returns the Remove from wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	private function remove_from_wishlist_button( $product_id, $is_in_wishlist ) {

		$nonce_token = wp_create_nonce( 'wcsw_remove_from_wishlist_' . $product_id );
		$style       = $is_in_wishlist ? '' : 'display: none; ';
		$text        = __( $this->config['button_remove_label'], 'wcsw' );
		$icon        = file_get_contents( $this->config['button_remove_icon'] );
		$label       = $this->create_label( $icon, $text );

		return sprintf(
			' <a href="?wcsw-remove=%s&nonce-token=%s" class="%s" style="%s" title="%s">%s</a>',
			$product_id,
			$nonce_token,
			'wcsw-button wcsw-button-ajax wcsw-button-remove',
			$style,
			$text,
			$label
		);

	}

	/**
	 * Shows the Clear wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	public function clear_button() {

		if ( ! $this->config['button_clear'] ) {
			return;
		}

		$nonce_token = wp_create_nonce( 'wcsw_clear_wishlist' );
		$text        = __( $this->config['button_clear_label'], 'wcsw' );
		$icon        = file_get_contents( $this->config['button_clear_icon'] );
		$label       = $this->create_label( $icon, $text );

		printf(
			' <a href="?wcsw-clear=1&nonce-token=%s" class="%s" title="%s">%s</a>',
			$nonce_token,
			'wcsw-button wcsw-button-ajax wcsw-button-clear',
			$text,
			$label
		);

	}

	// Creates button label.
	private function create_label( $icon, $text ) {

		switch ( $this->config['button_style'] ) {
			case 'icon':
				$label = $icon;
				break;
			case 'text':
				$label = $text;
				break;
			case 'icon_text':
				$label = $icon . '<span class="wcsw-spacer"></span>' . $text;
				break;
			default:
				$label = $icon . '<span class="wcsw-spacer"></span>' . $text;
		}

		return $label;

	}

	/**
	 * Creates the new menu item.
	 *
	 * @since    1.0.0
	 */
	public function add_menu( $items ) {

		// The menu item position.
		$position = $this->config['menu_position'];

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1['wishlist'] = __( $this->config['menu_name'], 'wcsw' );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/**
	 * Loads the wishlist template.
	 *
	 * @since    1.0.0
	 */
	public function load_template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		$wishlist_data = $this->get_data_array();

		// Shows the notice if there are no products in the wishlist...
		if ( ! $wishlist_data || empty( $wishlist_data ) ) {

			echo $this->get_empty_wishlist_notice();

			// ...and stops here.
			return;

		}

		// This variable is used inside the template.
		$products = $wishlist_data['products'];

		// Loads the custom template if one exists.
		if ( file_exists( $custom_template ) ) {

			require_once $custom_template;

		}

		// Loads the default template.
		if ( ! file_exists( $custom_template ) ) {

			require_once WCSW_DIR . '/templates/wishlist.php';

		}

	}

	/**
	 * Adds the product to the current user's wishlist.
	 *
	 * @since    1.0.0
	 */
	public function add_product() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		// If the product is already in the wishlist.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'wcsw-add' ) ||
		     ! $this->is_valid_nonce( 'wcsw_add_to_wishlist_' . $_GET['wcsw-add'] ) ||
		       $this->is_in_wishlist( $_GET['wcsw-add'] ) ) {

			return;

		}

		// The ID of the product meant to be added.
		$id = $_GET['wcsw-add'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! $this->is_valid_id( $id ) ) {
			return;
		}

		// Gets the current wishlist data.
		$wishlist_content = $this->get_data_array();

		// Adds the new product to the array.
		$wishlist_content['products'][$id] = apply_filters( 'wcsw_save_data', array(
			'title' => get_the_title( $id ),
		) );

		// Tries to save to the database and shows a notice based on the result.
		$this->display_notice( 'add', update_user_meta( get_current_user_id(), 'wcsw_data', json_encode( $wishlist_content ) ) );

	}

	/**
	 * Removes the product from the current user's wishlist.
	 *
	 * @since    1.0.0
	 */
	public function remove_product() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		// If the product is not in the wishlist.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'wcsw-remove' ) ||
		     ! $this->is_valid_nonce( 'wcsw_remove_from_wishlist_' . $_GET['wcsw-remove'] ) ||
		     ! $this->is_in_wishlist( $_GET['wcsw-remove'] ) ) {

			return;

		}

		// The ID of the product meant to be removed.
		$id = $_GET['wcsw-remove'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! $this->is_valid_id( $id ) ) {
			return;
		}

		// Gets the current wishlist data.
		$wishlist_content = $this->get_data_array();

		// The new products array that will exclude the removed product.
		$new_wishlist_data = array();

		// Loop through the added products.
		foreach ( $wishlist_content as $data_key => $data_value ) {

			foreach ( $data_value as $key => $value ) {

				// If the ID from the GET variable is equal to the current ID then skip the following code.
				if ( $id == $key ) {
					continue;
				}

				// Add each product to the new products array.
				// The $value variable contains the product data (e.g. product title).
				$new_wishlist_data['products'][$key] = $value;

			}

		}

		// Current user ID.
		$user_id = get_current_user_id();

		// Deletes the database record if the last product was removed.
		if ( empty( $new_wishlist_data ) ) {
			$result = delete_user_meta( $user_id, 'wcsw_data' );
		} else {
			$result = update_user_meta( $user_id, 'wcsw_data', json_encode( $new_wishlist_data ) );
		}

		// Tries to save to the database and shows a notice based on the result.
		$this->display_notice( 'remove', $result );

	}

	/**
	 * Clears the current user's wishlist.
	 *
	 * @since    1.0.0
	 */
	public function clear() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'wcsw-clear' ) ||
		     ! $this->is_valid_nonce( 'wcsw_clear_wishlist' ) ) {

			return;

		}

		// Tries to save to the database and shows a notice based on the result.
		$this->display_notice( 'clear', delete_user_meta( get_current_user_id(), 'wcsw_data' ) );

	}

	/**
	 * Returns the empty wishlist notice HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_empty_wishlist_notice() {

		$url     = wc_get_page_permalink( 'shop' );
		$label   = __( $this->config['message_empty_label'], 'wcsw' );
		$message = __( $this->config['message_empty'], 'wcsw' );

		return sprintf(
			'<div class="%s"><a class="%s" href="%s">%s</a>%s</div>',
			'woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info',
			'woocommerce-Button button',
			$url,
			$label,
			$message
		);

	}

	/**
	 * Displays the appropriate notice.
	 *
	 * @since    1.0.0
	 */
	public function display_notice( $type, $result ) {

		$add_success_message    = sprintf(
			'<a href="%s" class="%s">%s</a>%s',
			wc_get_account_endpoint_url( 'wishlist' ),
			'button wc-forward',
			__( $this->config['message_add_view'], 'wcsw' ),
			__( $this->config['message_add_success'], 'wcsw' )
		);
		$add_error_message      = __( $this->config['message_add_error'], 'wcsw' );
		$remove_success_message = __( $this->config['message_remove_success'], 'wcsw' );
		$remove_error_message   = __( $this->config['message_remove_error'], 'wcsw' );
		$clear_success_message  = __( $this->config['message_clear_success'], 'wcsw' );
		$clear_error_message    = __( $this->config['message_clear_error'], 'wcsw' );

		// Adds a WC notice only if the request was NOT made with AJAX.
		if ( ! $this->is_get_request( 'wcsw-ajax' ) ) {

			// Success.
			if ( $result ) {
				wc_add_notice( ${$type . '_success_message'}, 'success' );
				// Failure.
			} else {
				wc_add_notice( ${$type . '_error_message'}, 'error' );
			}

		} else {

			// Success.
			if ( $result ) {
				printf( '<div class="woocommerce-message">%s</div>', ${$type . '_success_message'} );
				// Failure.
			} else {
				printf( '<div class="woocommerce-error">%s</div>', ${$type . '_error_message'} );
			}

			// Prevents other output.
			exit;

		}

	}

	/**
	 * Adds some JavaScript variables.
	 *
	 * @since    1.0.0
	 */
	public function add_js_variables() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( is_shop() ||
		     is_product_category() ||
		     is_product_tag() ||
		     is_account_page() ||
		     is_singular( 'product' ) ) {

			$ajax_url = get_admin_url() . 'admin-ajax.php';
			$empty_wishlist_notice = $this->get_empty_wishlist_notice();

			$is_account_page = is_account_page() ? 'true' : 'false';
			$is_single_page  = is_singular( 'product' ) ? 'true' : 'false';
			$is_product_page = ( is_shop() ||
			                     is_product_category() ||
			                     is_product_tag() ||
			                     is_singular( 'product' ) ) ? 'true' : 'false';

			echo <<<EOT
			
				<script>
				
					var ajaxURL = '{$ajax_url}';
					var emptyWishlistNotice = '{$empty_wishlist_notice}';
					
					var isAccountPage = {$is_account_page};
					var isProductPage = {$is_product_page};
					var isSinglePage  = {$is_single_page};
				
				</script>

EOT;

		}

	}

	/**
	 * Gets existing user data from the database as JSON.
	 *
	 * @since    1.0.0
	 */
	private function get_raw_data() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		return get_user_meta( get_current_user_id(), 'wcsw_data', true );

	}

	/**
	 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
	 *
	 * @since    1.0.0
	 */
	private function get_data_array() {

		if ( ! $this->get_raw_data() ) {
			return false;
		}

		return json_decode( $this->get_raw_data(), true );

	}

	/**
	 * Gets existing data, for any user, from the database as JSON and converts it to a PHP array before returning it.
	 *
	 * @since    1.0.0
	 */
	public function get_user_data( $user_id ) {

		return json_decode( get_user_meta( $user_id, 'wcsw_data', true ), true );

	}

	/**
	 * Checks if the product is already in the wishlist.
	 *
	 * @since    1.0.0
	 */
	public function is_in_wishlist( $product_id ) {

		$wishlist_content = $this->get_data_array();
		$product_ids = [];

		if ( $wishlist_content ) {

			foreach ( $wishlist_content as $products => $product ) {

				foreach ( $product as $id => $details ) {

					$product_ids[] = $id;

					if ( in_array( $product_id, $product_ids ) ) {

						return true;

					}

				}

			}

		}

		return false;

	}

	/**
	 * Checks if there is a GET request.
	 *
	 * @since    1.0.0
	 */
	private function is_get_request( $param ) {

		if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Validates nonce.
	 *
	 * @since    1.0.0
	 */
	private function is_valid_nonce( $action ) {

		if ( ! isset( $_GET['nonce-token'] ) ) {
			return false;
		}

		$nonce_token = $_GET['nonce-token'];

		return wp_verify_nonce( $nonce_token, $action );

	}

	/**
	 * Checks if the $_GET variable is a valid product ID.
	 *
	 * @since    1.0.0
	 */
	private function is_valid_id( $id ) {

		if ( ! is_numeric( $id ) || $id <= 0 || $id != round( $id, 0 ) ) {
			return false;
		}

		return true;

	}

	/**
	 * AJAX processing function.
	 *
	 * @since    1.0.0
	 */
	public function process_ajax_request() {

		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

	}

}
