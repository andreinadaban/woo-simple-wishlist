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
class WCSW_Functions {

	/**
	 * Creates the new endpoint.
	 *
	 * @since    1.0.0
	 */
	public function endpoint() {

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

		if ( ! WCSW\is_get( 'wcsw-add' ) || WCSW\is_in_wishlist( get_the_ID() ) ) {

			return;

		}

		if ( $data = $this->add_product( WCSW\get_data_array() ) ) {

			$result = update_user_meta( get_current_user_id(), 'wcsw_data', $data );

			$success_message = __( 'The product was successfully added to your wishlist.', 'wcsw' ) . '<a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="button wc-forward">' . __( 'View wishlist', 'wcsw' ) . '</a>';
			$error_message = __( 'The product was not added to your wishlist. Please try again.', 'wcsw' );

			// Adds success notice only if the request was NOT made with AJAX.
			if ( ! WCSW\is_get( 'wcsw-ajax' ) ) {

				// Success.
				if ( $result ) {

					wc_add_notice( $success_message, 'success' );

				// Failure.
				} else {

					wc_add_notice( $error_message, 'error' );

				}

			} else {

				// Success.
				if ( $result ) {

					echo '<div class="woocommerce-message">' . $success_message . '</div>';

				// Failure.
				} else {

					echo '<div class="woocommerce-error">' . $error_message . '</div>';

				}

				// Prevents other output.
				exit;

			}

		}

	}

	/**
	 * Validates the $_GET variable and adds the ID to the data array.
	 *
	 * @since    1.0.0
	 */
	public function add_product( $data = array() ) {

		$id = $_GET['wcsw-add'];

		if ( ! WCSW\is_valid( $id ) ) {

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
		if ( ! WCSW\is_get( 'wcsw-remove' ) ) {

			return;

		}

		// The "$data" variable contains the new products array after the product removal.
		if ( $data = $this->remove_product( WCSW\get_data_array() ) ) {

			$result = update_user_meta( get_current_user_id(), 'wcsw_data', $data );

			$success_message = __( 'The product was successfully removed from your wishlist.', 'wcsw' );
			$error_message = __( 'The product was not removed from your wishlist. Please try again.', 'wcsw' );

			// Adds success notice only if the request was NOT made with AJAX.
			if ( ! WCSW\is_get( 'wcsw-ajax' ) ) {

				// Success.
				if ( $result ) {

					wc_add_notice( $success_message, 'success' );

				// Failure.
				} else {

					wc_add_notice( $error_message, 'error' );

				}

			} else {

				// Success.
				if ( $result ) {

					echo '<div class="woocommerce-message">' . $success_message . '</div>';

				// Failure.
				} else {

					echo '<div class="woocommerce-error">' . $error_message . '</div>';

				}

				// Prevents other output.
				exit;

			}

		}

	}

	/**
	 * Validates the $_GET variable and removes the ID from the data array.
	 *
	 * @since    1.0.0
	 */
	public function remove_product( $data = array() ) {

		// The ID of the product meant to be removed.
		$id = $_GET['wcsw-remove'];

		// The new products array that will exclude the removed product.
		$new = array();

		// If the GET variable is not a valid ID then do nothing.
		if ( ! WCSW\is_valid( $id ) ) {

			return false;

		}

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
	 * AJAX processing function.
	 *
	 * @since    1.0.0
	 */
	public function ajax_processing() {

		// No need for any further processing, however this is necessary for the "wp_ajax_" hook.

	}

}
