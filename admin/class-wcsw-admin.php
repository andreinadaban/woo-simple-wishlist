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

	    $settings_tabs['wcsw_tab'] = __( 'WooCommerce Simple Wishlist', 'wcsw' );

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
			    'name'    => __( 'General settings', 'wcsw' ),
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
                    'icon_text' => __( 'Icon & text', 'wcsw' ),
                ),
			    'default' => 'icon',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_style'
		    ),
		    'wcsw_settings_button_text_color' => array(
			    'name'    => __( 'Button text color', 'wcsw' ),
			    'type'    => 'color',
			    'default' => '#3c3c3c',
			    'css'     => 'width: 6em;',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_text_color'
		    ),
		    'wcsw_settings_button_icon_color' => array(
			    'name'    => __( 'Button icon color', 'wcsw' ),
			    'type'    => 'color',
			    'default' => '#96588a',
			    'css'     => 'width: 6em;',
			    'desc'    => '',
			    'id'      => 'wcsw_settings_button_icon_color'
		    ),
		    'wcsw_settings_button_archive' => array(
			    'name'    => __( 'Add to wishlist button', 'wcsw' ),
			    'type'    => 'checkbox',
			    'default' => 'yes',
			    'desc'    => __( 'Show the Add to wishlist button in archive pages.', 'wcsw' ),
			    'id'      => 'wcsw_settings_button_archive'
		    ),
		    'wcsw_settings_button_clear' => array(
			    'name'    => __( 'Clear wishlist button', 'wcsw' ),
			    'type'    => 'checkbox',
			    'default' => 'yes',
			    'desc'    => __( 'Show the Clear wishlist button in the My account page.', 'wcsw' ),
			    'id'      => 'wcsw_settings_button_clear'
		    ),
		    'wcsw_settings_load_css' => array(
			    'name'    => __( 'CSS', 'wcsw' ),
			    'type'    => 'checkbox',
			    'default' => 'yes',
			    'desc'    => __( 'Load basic styles.', 'wcsw' ),
			    'id'      => 'wcsw_settings_load_css'
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

	/**
	 * Adds a link to the settings page in the plugins page.
	 */
	function add_settings_link( $links ) {

		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wcsw_tab' ) . '">' . __( 'Settings', 'wcsw' ) . '</a>'
		);

		return array_merge( $action_links, $links );

	}

}
