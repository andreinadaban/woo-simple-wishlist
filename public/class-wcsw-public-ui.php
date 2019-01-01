<?php

/**
 * The UI class.
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
 * The UI class.
 *
 * @since    1.0.0
 */
class WCSW_Public_UI {

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
	 * Creates the add to wishlist button.
	 *
	 * @since    1.0.0
	 */
	public function button() {

		if ( ! is_user_logged_in() ) {

			return;

		}

		$product_id = get_the_ID();

		// If the current product is already in the wishlist, adds the "View wishlist" button.
		if ( wcsw_is_in_wishlist( $product_id, $this->data ) ) {

			echo $this->get_view_wishlist_button();

		}

		// If the current product is not in the wishlist, adds the "Add to wishlist" button.
		if ( ! wcsw_is_in_wishlist( $product_id, $this->data ) ) {

			printf( '<a href="?wcsw-add=' . $product_id . '" class="%s">%s</a>', 'wcsw-button wcsw-button-ajax wcsw-button-add button', __( 'Add to wishlist', 'wcsw' ) );

		}

	}

	/**
	 * Returns the view wishlist button HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_view_wishlist_button() {

		return sprintf( ' <a href="' . wc_get_account_endpoint_url( 'wishlist' ) . '" class="%s">%s</a>', 'wcsw-button wcsw-button-wishlist button', __( 'View wishlist', 'wcsw' ) );

	}

	/**
	 * Creates the new menu item.
	 *
	 * @since    1.0.0
	 */
	public function menu( $items ) {

		// The menu item position.
		// Default: between Orders and Downloads(2).
		$position = apply_filters( 'wcsw_my_account_menu_position', 2 );

		$items_1 = array_slice( $items, 0, $position, true );
		$items_2 = array_slice( $items, $position, null, true );

		$items_1['wishlist'] = __( 'Wishlist', 'wcsw' );

		$items = array_merge( $items_1, $items_2 );

		return $items;

	}

	/**
	 * Loads the wishlist template.
	 *
	 * @since    1.0.0
	 */
	public function template() {

		$custom_template = get_template_directory() . '/woocommerce-simple-wishlist/wishlist.php';

		$data = $this->data->get_data_array();

		// Shows the notice if there are no products in the wishlist...
		if ( ! $data || empty( $data ) ) {

			$this->the_empty_wishlist_notice();

			// ...and stops here.
			return;

		}

		$products = $data['products'];

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
	 * Returns the empty wishlist notice HTML.
	 *
	 * @since    1.0.0
	 */
	public function get_empty_wishlist_notice() {

		$url     = esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) );
		$label   = __( 'Go shop', 'wcsw' );
		$message = __( 'There are no products in the wishlist yet.', 'wcsw' );

		return sprintf( '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info"><a class="woocommerce-Button button" href="%s">%s</a>%s</div>', $url, $label, $message );

	}

	/**
	 * Echoes the empty wishlist notice HTML.
	 *
	 * @since    1.0.0
	 */
	public function the_empty_wishlist_notice() {

		echo $this->get_empty_wishlist_notice();

	}

}
