<?php

namespace WCSW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * The plugin class.
 */
if ( ! class_exists( __NAMESPACE__ . '\Plugin' ) ) {

	class Plugin {

		/*
		 * Runs on plugin activation.
		 */
		static function activate() {

			if ( ! get_transient( 'wcsw_flush' ) ) {

				set_transient( 'wcsw_flush', '1', 0 );

			}

		}

		/*
		 * Runs on plugin deactivation.
		 */
		static function deactivate() {

			if ( get_transient( 'wcsw_flush' ) ) {

				delete_transient( 'wcsw_flush' );

			}

			flush_rewrite_rules();

		}

	}

}
