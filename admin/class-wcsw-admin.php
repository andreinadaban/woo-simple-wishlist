<?php

/**
 * The admin class.
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
 * The admin class.
 *
 * @since    1.0.0
 */
class WCSW_Admin {

	/**
	 * Checks if WooCommerce is active.
     *
     * @since    1.0.0
	 */
	public function check_for_dependencies() {

		if ( is_admin() &&
             current_user_can( 'activate_plugins' ) &&
             ! is_plugin_active( WCSW_WOO ) ) {

			deactivate_plugins( WCSW_PLUGIN );

			if ( isset( $_GET['activate'] ) ) {

				unset( $_GET['activate'] );

			}
		}

	}

	/**
	 * Displays admin notices.
	 *
	 * @since    1.0.0
	 */
	public function add_notices() {

		if ( is_admin() &&
		     current_user_can( 'activate_plugins' ) &&
		     ! is_plugin_active( WCSW_WOO ) ) {

			$notice = __( 'Please activate the WooCommerce plugin.', 'wcsw' );

			echo <<<EOT
		        <div class="error"><p><strong>WooCommerce Simple Wishlist: </strong>{$notice}</p></div>
EOT;

		}

	}

	/**
     * Adds a tab to the WooCommerce settings menu.
	 *
	 * @since    1.0.0
	 */
    public function add_settings_tab( $settings_tabs ) {

	    $settings_tabs['wcsw_tab'] = __( 'Wishlist', 'wcsw' );

	    return $settings_tabs;

    }

	/**
	 * Adds the settings fields to the settings tab.
	 *
	 * @since     1.0.0
	 */
    public function settings_tab() {

	    woocommerce_admin_fields( $this->get_settings() );

    }

	/**
     * Creates the settings fields.
     *
	 * @since     1.0.0
	 * @return    array
	 */
    private function get_settings() {

	    $settings = array(
		    'wcsw_settings_title' => array(
			    'name'    => __( 'WooCommerce Simple Wishlist Settings', 'wcsw' ),
			    'type'    => 'title',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_title'
		    ),
		    'wcsw_settings_button_style' => array(
			    'name'    => __( 'Button style', 'wcsw' ),
			    'type'    => 'select',
			    'options' => array(
                    'icon'      => __( 'Icon', 'wcsw' ),
                    'text'      => __( 'Text', 'wcsw' ),
                    'icon_text' => __( 'Icon & Text', 'wcsw' ),
                ),
			    'default' => 'icon',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_style'
		    ),
		    'wcsw_settings_button_archive' => array(
			    'name'    => __( 'Show button on archive pages', 'wcsw' ),
			    'type'    => 'checkbox',
			    'default' => 'yes',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_archive'
		    ),
		    'wcsw_settings_button_clear' => array(
			    'name'    => __( 'Show the "Clear Wishlist" button in the "My Account" section.', 'wcsw' ),
			    'type'    => 'checkbox',
			    'default' => 'yes',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_clear'
		    ),
		    'wcsw_settings_section_end' => array(
			    'type'    => 'sectionend',
			    'id'      => 'wcsw_settings_section_end'
		    ),
	    );

	    return $settings;

    }

	/**
	 * Saves the settings.
	 */
	function update_settings() {

		woocommerce_update_options( $this->get_settings() );

	}

}
