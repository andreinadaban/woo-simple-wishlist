<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://andreinadaban.ro
 * @since      1.0.0
 *
 * @package    WooCommerce_Simple_Wishlist
 * @subpackage WooCommerce_Simple_Wishlist/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WooCommerce_Simple_Wishlist
 * @subpackage WooCommerce_Simple_Wishlist/includes
 * @author     Andrei Nadaban <contact@http://andreinadaban.ro>
 */
class WooCommerce_Simple_Wishlist_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-simple-wishlist',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
