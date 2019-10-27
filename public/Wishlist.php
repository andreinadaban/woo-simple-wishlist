<?php

/**
 * Simple Wishlist for WooCommerce
 * Copyright (C) 2018-2019 Andrei Nadaban
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace SW;

/**
 * The public wishlist class.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The public wishlist class.
 *
 * @since    1.0.0
 */
class Wishlist {

	/**
	 * The configuration array from the core class.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       array    $core_config
	 */
	private $core_config;

	/**
	 * Sets the config variable.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function __construct( $config ) {
		$this->core_config = $config;
	}

	/**
	 * Creates the add to wishlist and remove from wishlist buttons on the product page and on the product archive pages.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function the_buttons( $product_id = false ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		// This works inside loops and on single product pages.
		if ( ! $product_id ) {
			$product_id = get_the_ID();
		}

		// Checks if the product is in the wishlist only once to minimize database calls.
		$is_in_wishlist = $this->is_in_wishlist( $product_id );

		printf(
			'<div class="sw-button-container">%s%s</div>',
			$this->get_add_button( $product_id, $is_in_wishlist ),
			$this->get_remove_button( $product_id, $is_in_wishlist )
		);

	}

	/**
	 * Returns the Add to wishlist button HTML.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    string
	 */
	private function get_add_button( $product_id, $is_in_wishlist ) {

		$nonce_token = wp_create_nonce( 'sw_add_to_wishlist_' . $product_id );
		$style       = $is_in_wishlist ? 'display: none; ' : '';
		$text        = esc_html( $this->core_config['button_add_label'] );
		$icon        = file_get_contents( $this->core_config['button_add_icon'] );
		$label       = $this->get_label( $icon, $text );

		return sprintf(
			' <a href="?sw-add=%s&nonce-token=%s" id="sw-button-add-%s" class="%s" style="%s" title="%s">%s</a>',
			$product_id,
			$nonce_token,
			$product_id,
			'sw-button sw-button-ajax sw-button-add',
			$style,
			$text,
			$label
		);

	}

	/**
	 * Returns the Remove from wishlist button HTML.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    string
	 */
	private function get_remove_button( $product_id, $is_in_wishlist ) {

		$nonce_token = wp_create_nonce( 'sw_remove_from_wishlist_' . $product_id );
		$style       = $is_in_wishlist ? '' : 'display: none; ';
		$text        = esc_html( $this->core_config['button_remove_label'] );
		$icon        = file_get_contents( $this->core_config['button_remove_icon'] );
		$label       = $this->get_label( $icon, $text );

		return sprintf(
			' <a href="?sw-remove=%s&nonce-token=%s" id="sw-button-remove-%s" class="%s" style="%s" title="%s">%s</a>',
			$product_id,
			$nonce_token,
			$product_id,
			'sw-button sw-button-ajax sw-button-remove',
			$style,
			$text,
			$label
		);

	}

	/**
	 * Shows the Clear wishlist button HTML.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function the_clear_button() {

		if ( ! $this->core_config['button_clear'] ) {
			return;
		}

		$nonce_token = wp_create_nonce( 'sw_clear_wishlist' );
		$text        = esc_html( $this->core_config['button_clear_label'] );
		$icon        = file_get_contents( $this->core_config['button_clear_icon'] );
		$label       = $this->get_label( $icon, $text );

		printf(
			' <a href="?sw-clear=1&nonce-token=%s" class="%s" title="%s">%s</a>',
			$nonce_token,
			'sw-button sw-button-ajax sw-button-clear',
			$text,
			$label
		);

	}

	/**
	 * Creates button label.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    string
	 */
	private function get_label( $icon, $text ) {

		switch ( $this->core_config['button_style'] ) {
			case 'icon':
				$label = $icon;
				break;
			case 'text':
				$label = $text;
				break;
			case 'icon_text':
				$label = $icon . '<span class="sw-button-text">' . $text . '</span>';
				break;
			default:
				$label = $icon . '<span class="sw-button-text">' . $text . '</span>';
		}

		return $label;

	}

	/**
	 * Creates the new menu item.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    array
	 */
	public function menu( $items ) {

		$position = $this->core_config['menu_position'];

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1[ esc_html( $this->core_config['endpoint'] ) ] = esc_html( $this->core_config['menu_name'] );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/**
	 * Loads the wishlist template.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function the_template() {

		// If requested via AJAX.
		if ( $this->is_get_request( 'sw-ajax' ) ) {
			ob_start();
		}

		// This variable is used inside the template.
		$products = $this->get_data_array();

		// Loads the selected template.
		require $this->select_template();

		// Returns the template content if requested via AJAX.
		if ( $this->is_get_request( 'sw-ajax' ) ) {
			return ob_get_clean();
		}

	}

	/**
	 * Selects the template to be used.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function select_template() {

		$custom_template = get_template_directory() . '/simple-wishlist-for-woocommerce/wishlist.php';

		return file_exists( $custom_template ) ? $custom_template : DIR . 'public/templates/wishlist.php';

	}

	/**
	 * Adds the product to the current user's wishlist.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function add_product() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		// If the product is already in the wishlist.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'sw-add' ) ||
		     ! $this->is_valid_nonce( 'sw_add_to_wishlist_' . $_GET['sw-add'] ) ||
		       $this->is_in_wishlist( $_GET['sw-add'] ) ) {

			return;

		}

		// The ID of the product meant to be added.
		$id = $_GET['sw-add'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! $this->is_valid_id( $id ) ) {
			return;
		}

		// Gets the current wishlist data.
		$wishlist_content = $this->get_data_array();

		// Adds the new product to the array.
		$wishlist_content[$id] = apply_filters( 'sw_save_data', array(
			't' => get_the_title( $id ),
		) );

		// Tries to save to the database and shows a notice based on the result.
		$this->notice( 'add', update_user_meta( get_current_user_id(), 'sw_data', json_encode( $wishlist_content ) ) );

	}

	/**
	 * Removes the product from the current user's wishlist.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function remove_product() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		// If the product is not in the wishlist.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'sw-remove' ) ||
		     ! $this->is_valid_nonce( 'sw_remove_from_wishlist_' . $_GET['sw-remove'] ) ||
		     ! $this->is_in_wishlist( $_GET['sw-remove'] ) ) {

			return;

		}

		// The ID of the product meant to be removed.
		$id = $_GET['sw-remove'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! $this->is_valid_id( $id ) ) {
			return;
		}

		// Gets the current wishlist data.
		$wishlist_content = $this->get_data_array();

		// The new products array that will exclude the removed product.
		$new_wishlist_data = array();

		// Loop through the added products.
		foreach ( $wishlist_content as $key => $value ) {

			// If the ID from the GET variable is equal to the current ID then skip the following code.
			if ( $id == $key ) {
				continue;
			}

			// Add each product to the new products array.
			// The $value variable contains the product data (e.g. product title).
			$new_wishlist_data[$key] = $value;

		}

		// Current user ID.
		$user_id = get_current_user_id();

		// Deletes the database record if the last product was removed.
		if ( empty( $new_wishlist_data ) ) {
			$result = delete_user_meta( $user_id, 'sw_data' );
		} else {
			$result = update_user_meta( $user_id, 'sw_data', json_encode( $new_wishlist_data ) );
		}

		// Tries to save to the database and shows a notice based on the result.
		$this->notice( 'remove', $result );

	}

	/**
	 * Clears the current user's wishlist.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function clear() {

		// If the user is not logged in.
		// If there is no get request.
		// If the nonce is not valid.
		if ( ! is_user_logged_in() ||
		     ! $this->is_get_request( 'sw-clear' ) ||
		     ! $this->is_valid_nonce( 'sw_clear_wishlist' ) ) {

			return;

		}

		// Tries to save to the database and shows a notice based on the result.
		$this->notice( 'clear', delete_user_meta( get_current_user_id(), 'sw_data' ) );

	}

	/**
	 * Returns the empty wishlist notice HTML.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    string
	 */
	public function get_empty_notice() {

		$url     = wc_get_page_permalink( 'shop' );
		$label   = esc_html( $this->core_config['message_empty_label'] );
		$message = esc_html( $this->core_config['message_empty'] );

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
	 * Displays the empty wishlist notice HTML.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function the_empty_notice() {
		echo $this->get_empty_notice();
	}

	/**
	 * Displays the appropriate notice.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function notice( $type, $result ) {

		$add_success_message    = sprintf(
			'<a href="%s" class="%s">%s</a>%s',
			wc_get_account_endpoint_url( $this->core_config['endpoint'] ),
			'button wc-forward',
			esc_html( $this->core_config['message_add_view'] ),
			esc_html( $this->core_config['message_add_success'] )
		);
		$add_error_message      = esc_html( $this->core_config['message_add_error'] );
		$remove_success_message = esc_html( $this->core_config['message_remove_success'] );
		$remove_error_message   = esc_html( $this->core_config['message_remove_error'] );
		$clear_success_message  = esc_html( $this->core_config['message_clear_success'] );
		$clear_error_message    = esc_html( $this->core_config['message_clear_error'] );

		// Adds a WC notice only if the request was NOT made with AJAX.
		if ( ! $this->is_get_request( 'sw-ajax' ) ) {

			// Success.
			if ( $result ) {
				wc_add_notice( ${$type . '_success_message'}, 'success' );
			// Failure.
			} else {
				wc_add_notice( ${$type . '_error_message'}, 'error' );
			}

		} else {

			$output = array();

			// Success.
			if ( $result ) {

				$output['status'] = (BOOL) $result;
				$output['notice'] = sprintf( '<div class="woocommerce-message">%s</div>', ${$type . '_success_message'} );

			// Failure.
			} else {

				$output['status'] = (BOOL) $result;
				$output['notice'] = sprintf( '<div class="woocommerce-error">%s</div>', ${$type . '_error_message'} );

			}

			$output['template'] = $this->the_template();

			echo json_encode( $output );

			// Prevents other output.
			exit;

		}

	}

	/**
	 * Adds some JavaScript variables.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function js() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		$ajax_url = get_admin_url() . 'admin-ajax.php';

		echo "<script>var ajaxURL = '{$ajax_url}';</script>";

	}

	/**
	 * Gets existing user data from the database as JSON.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    string
	 */
	private function get_raw_data() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		return get_user_meta( get_current_user_id(), 'sw_data', true );

	}

	/**
	 * Gets existing user data from the database as JSON and converts it to a PHP array before returning it.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    array|bool
	 */
	private function get_data_array() {

		if ( ! $this->get_raw_data() ) {
			return false;
		}

		return json_decode( $this->get_raw_data(), true );

	}

	/**
	 * Gets existing data, for any user, from the database as JSON and converts it to an array before returning it.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    array
	 */
	public function get_user_data( $user_id ) {
		return json_decode( get_user_meta( $user_id, 'sw_data', true ), true );
	}

	/**
	 * Checks if the product is already in the wishlist.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @return    bool
	 */
	public function is_in_wishlist( $product_id ) {

		$wishlist_content = $this->get_data_array();
		$product_ids = [];

		if ( $wishlist_content ) {

			foreach ( $wishlist_content as $id => $details ) {

				$product_ids[] = $id;

				if ( in_array( $product_id, $product_ids ) ) {
					return true;
				}

			}

		}

		return false;

	}

	/**
	 * Checks if there is a GET request.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @return    bool
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
	 * @since     1.0.0
	 * @access    private
	 * @return    bool
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
	 * @since     1.0.0
	 * @access    private
	 * @return    bool
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
	 * @since     1.0.0
	 * @access    public
	 */
	public function ajax() {
		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.
	}

}
