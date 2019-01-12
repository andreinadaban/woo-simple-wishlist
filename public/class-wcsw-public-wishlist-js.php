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

		if ( is_shop() || is_product_category() || is_product_tag() || is_account_page() || is_singular( 'product' ) ) {

			$ajax_url = get_admin_url() . 'admin-ajax.php';
			$empty_wishlist_notice = $this->ui->get_empty_wishlist_notice();

			$is_account_page = is_account_page() ? 'true' : 'false';
			$is_product_page = ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ? 'true' : 'false';
			$is_single_page  = is_singular( 'product' ) ? 'true' : 'false';

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

}
