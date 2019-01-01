<?php

/**
 * The functions class.
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
 * The functions class.
 *
 * @since    1.0.0
 */
class WCSW_Public_Functions {

	/**
	 * The WCSW_Data class instance.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       WCSW_Data    $data
	 */
	private $data;

	/**
	 * WCSW_Public_UI constructor.
	 *
	 * @param    WCSW_Data    $data    The WCSW_Data class instance.
	 */
	public function __construct( WCSW_Data $data ) {

		$this->data = $data;

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
	 * Adds the product to the wishlist when the "Add to wishlist" button is clicked.
	 *
	 * @since    1.0.0
	 */
	public function add() {

		if ( ! wcsw_is_get_request( 'wcsw-add' ) || wcsw_is_in_wishlist( get_the_ID(), $this->data ) ) {

			return;

		}

		if ( $data = $this->add_product( $this->data->get_data_array() ) ) {

			$result = update_user_meta( get_current_user_id(), 'wcsw_data', $data );

			$this->display_notice( 'add', $result );

		}

	}

	/**
	 * Validates the $_GET variable and adds the ID to the data array.
	 *
	 * @since    1.0.0
	 */
	private function add_product( $data = array() ) {

		$id = $_GET['wcsw-add'];

		if ( ! wcsw_is_valid_id( $id ) ) {

			return false;

		}

		$data['products'][$id] = array(
			'title' => get_the_title( $id ),
			'date_added' => current_time( 'timestamp' ),
		);

		return json_encode( $data );

	}

	/**
	 * Removes the product from the wishlist.
	 *
	 * @since    1.0.0
	 */
	public function remove() {

		// If there is no request to remove a product do nothing.
		if ( ! wcsw_is_get_request( 'wcsw-remove' ) ) {

			return;

		}

		// The "$data" variable contains the new products array after the product removal.
		if ( $data = $this->remove_product( $this->data->get_data_array() ) ) {

			$current_user_id = get_current_user_id();

			// Deletes the database record if the last product was removed.
			if ( empty( json_decode($data) ) ) {
				$result = delete_user_meta( $current_user_id, 'wcsw_data' );
			} else {
				$result = update_user_meta( $current_user_id, 'wcsw_data', $data );
			}

			$this->display_notice( 'remove', $result );

		}

	}

	/**
	 * Validates the $_GET variable and removes the ID from the data array.
	 *
	 * @since    1.0.0
	 */
	private function remove_product( $data = array() ) {

		// The ID of the product meant to be removed.
		$id = $_GET['wcsw-remove'];

		// If the GET variable is not a valid ID then do nothing.
		if ( ! wcsw_is_valid_id( $id ) ) {

			return false;

		}

		// The new products array that will exclude the removed product.
		$new = array();

		// Loop through the added products.
		foreach ( $data as $data_key => $data_value ) {

			foreach ( $data_value as $key => $value ) {

				// If the ID from the GET variable is equal to the current ID then skip the following code.
				if ( $id == $key ) {

					continue;

				}

				// Add each product to the new products array.
				// The "$value" variable contains the product data(eg. product title).
				$new['products'][$key] = $value;

			}

		}

		// Returns the new data after product removal.
		return json_encode( $new );

	}

	/**
	 * Displays the appropriate notice.
	 *
	 * @since    1.0.0
	 */
	private function display_notice( $type, $result ) {

		$add_success_message    = sprintf( '<a href="%s" class="button wc-forward">%s</a>%s', wc_get_account_endpoint_url( 'wishlist' ), __( 'View wishlist', 'wcsw' ), __( 'The product was successfully added to your wishlist.', 'wcsw' ) );
		$add_error_message      = __( 'The product was not added to your wishlist. Please try again.', 'wcsw' );

		$remove_success_message = __( 'The product was successfully removed from your wishlist.', 'wcsw' );
		$remove_error_message   = __( 'The product was not removed from your wishlist. Please try again.', 'wcsw' );

		// Adds success notice only if the request was NOT made with AJAX.
		if ( ! wcsw_is_get_request( 'wcsw-ajax' ) ) {

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
	 * AJAX processing function.
	 *
	 * @since    1.0.0
	 */
	public function process_ajax_request() {

		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

	}

}
