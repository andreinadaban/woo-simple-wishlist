<?php

/**
 * The assets class.
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
 * The assets class.
 *
 * @since    1.0.0
 */
class WCSW_Assets extends WCSW_Public {

	/**
	 * Registers the JavaScript files for the public side of the website.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-simple-wishlist-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Adds some JavaScript variables.
	 *
	 * @since    1.0.0
	 */
	public function js_variables() {

		if ( is_account_page() || is_singular( 'product' ) ) {

			$ui = new WCSW_UI();

			// The "Go to wishlist" button HTML.
			$go_to_wishlist_button = $ui->go_to_wishlist_button();
			$ajax_url = get_admin_url() . '/admin-ajax.php';

			echo <<<EOT
			
				<script>
				
					var goToWishlistButton = '{$go_to_wishlist_button}';
					var ajaxURL = '{$ajax_url}';
				
				</script>

EOT;

		}

	}

}
