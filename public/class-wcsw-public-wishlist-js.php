<?php

/**
 * The JS class.
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
 * The JS class.
 *
 * @since    1.0.0
 */
class WCSW_Public_Wishlist_JS {

	/**
	 * The WCSW_Public_UI class instance.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       WCSW_Public_Wishlist_UI    $ui
	 */
	private $ui;

	/**
	 * WCSW_Public_JS_Variables constructor.
	 *
	 * @param    WCSW_Public_Wishlist_UI    $ui    The WCSW_Public_UI class instance.
	 */
	public function __construct( WCSW_Public_Wishlist_UI $ui ) {

		$this->ui = $ui;

	}

	/**
	 * Adds some JavaScript variables.
	 *
	 * @since    1.0.0
	 */
	public function add_js_variables() {

		if ( is_account_page() || is_singular( 'product' ) ) {

			$ajax_url = get_admin_url() . 'admin-ajax.php';
			$view_wishlist_button = $this->ui->get_view_wishlist_button();
			$empty_wishlist_notice = $this->ui->get_empty_wishlist_notice();

			echo <<<EOT
			
				<script>
				
					var ajaxURL = '{$ajax_url}';
					var viewWishlistButton = '{$view_wishlist_button}';
					var emptyWishlistNotice = '{$empty_wishlist_notice}';
				
				</script>

EOT;

		}

	}

}
