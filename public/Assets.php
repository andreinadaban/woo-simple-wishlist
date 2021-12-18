<?php

namespace SW;

/**
 * The assets class.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The assets class.
 *
 * @since    1.0.0
 */
class Assets {

	/**
	 * Registers the JavaScript files for the public area.
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'sw-public', plugin_dir_url( __FILE__ ) . 'assets/dist/js/main.js', array( 'jquery' ), filemtime( PLUGIN_DIR_PATH . 'public/assets/dist/js/main.js' ), true );

	}

}
