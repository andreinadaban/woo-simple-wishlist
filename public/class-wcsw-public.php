<?php

/**
 * The public functionality of the plugin.
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
 * The public functionality of the plugin.
 *
 * Defines the plugin name and version.
 *
 * @since    1.0.0
 */
class WCSW_Public {

	/**
	 * The name of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $plugin_name    The name of the plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Initializes the class and set its properties.
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
