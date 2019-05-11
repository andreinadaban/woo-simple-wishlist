<?php

/**
 * Fired during plugin activation.
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
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since     1.0.0
 */
class WCSW_Activator {

	/**
	 * Fired during plugin activation. Saves default settings in the database.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! is_plugin_active( WCSW_WOO ) ) {
			return;
		}

		if ( ! get_transient( 'wcsw_flush' ) ) {

			set_transient( 'wcsw_flush', '1', 0 );

		}

		if ( ! get_option( 'wcsw_settings_button_style' ) ) {

			add_option( 'wcsw_settings_button_style', 'icon' );

		}

		if ( ! get_option( 'wcsw_settings_button_archive' ) ) {

			add_option( 'wcsw_settings_button_archive', 'yes' );

		}

		if ( ! get_option( 'wcsw_settings_button_clear' ) ) {

			add_option( 'wcsw_settings_button_clear', 'yes' );

		}

		if ( ! get_option( 'wcsw_settings_button_icon' ) ) {

			add_option( 'wcsw_settings_button_icon', 'heart' );

		}

		if ( ! get_option( 'wcsw_settings_button_icon_color' ) ) {

			add_option( 'wcsw_settings_button_icon_color', '#96588a' );

		}

		if ( ! get_option( 'wcsw_settings_button_text_color' ) ) {

			add_option( 'wcsw_settings_button_text_color', '#3c3c3c' );

		}

	}

}
