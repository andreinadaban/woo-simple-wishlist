<?php

namespace WCSW;

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
class Assets {

	/**
	 * Registers the JavaScript for the public area.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wcsw-public-js', plugin_dir_url( __FILE__ ) . 'assets/dist/js/wcsw-public.js', array( 'jquery' ), filemtime( DIR . 'public/assets/dist/js/wcsw-public.js' ), true );

	}

}
