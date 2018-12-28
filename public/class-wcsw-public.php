<?php

/**
 * The public-facing functionality of the plugin.
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
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two example hooks
 * for how to enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Andrei Nadaban <contact@andreinadaban.ro>
 */
class WCSW_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

}
