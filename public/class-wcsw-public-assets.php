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
class WCSW_Public_Assets {

	/**
	 * Registers the stylesheets for the public area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wcsw-public-css', plugin_dir_url( __FILE__ ) . 'assets/dist/css/wcsw-public.css', array(), filemtime( WCSW_DIR . '/public/assets/dist/css/wcsw-public.css' ), 'all' );

	}

	/**
	 * Registers the JavaScript files for the public side of the website.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wcsw-public-js', plugin_dir_url( __FILE__ ) . 'assets/dist/js/wcsw-public.js', array( 'jquery' ), filemtime( WCSW_DIR . '/public/assets/dist/js/wcsw-public.js' ), true );

	}

}
