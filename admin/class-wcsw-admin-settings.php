<?php

/**
 * The admin settings class.
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
 * The admin settings class.
 *
 * @since    1.0.0
 */
class WCSW_Admin_Settings {

	public function add_admin_menu() {

		add_menu_page( 'WooCommerce Simple Wishlist', 'WooCommerce Simple Wishlist', 'manage_options', 'woocommerce_simple_wishlist', array( $this, 'options_page' ) );

	}

	public function settings_init() {

		register_setting( 'pluginPage', 'wcsw_settings' );

		add_settings_section(
			'wcsw_pluginPage_section',
			__( '', 'wcsw' ),
			array( $this, 'settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'wcsw_select_field_0',
			__( 'Button style', 'wcsw' ),
			array( $this, 'select_field_0_render' ),
			'pluginPage',
			'wcsw_pluginPage_section'
		);

		add_settings_field(
			'wcsw_checkbox_field_1',
			__( 'Show button on archive pages', 'wcsw' ),
			array( $this, 'checkbox_field_1_render' ),
			'pluginPage',
			'wcsw_pluginPage_section'
		);

		add_settings_field(
			'wcsw_checkbox_field_2',
			__( 'Show "Clear wishlist" button', 'wcsw' ),
			array( $this, 'checkbox_field_2_render' ),
			'pluginPage',
			'wcsw_pluginPage_section'
		);

	}

	public function select_field_0_render() {

		$options = get_option( 'wcsw_settings' );

		?>

        <select name='wcsw_settings[wcsw_select_field_0]'>
            <option value='1' <?php selected( $options['wcsw_select_field_0'], 1 ); ?>>
                <?php _e( 'Icon', 'wcsw' ); ?>
            </option>
            <option value='2' <?php selected( $options['wcsw_select_field_0'], 2 ); ?>>
	            <?php _e( 'Text', 'wcsw' ); ?>
            </option>
            <option value='3' <?php selected( $options['wcsw_select_field_0'], 3 ); ?>>
	            <?php _e( 'Icon & text', 'wcsw' ); ?>
            </option>
        </select>

		<?php

	}

	public function checkbox_field_1_render() {

		$options = get_option( 'wcsw_settings' );

		?>

        <input type='checkbox' name='wcsw_settings[wcsw_checkbox_field_1]' <?php checked( isset( $options['wcsw_checkbox_field_1'] ) ? $options['wcsw_checkbox_field_1'] : '', 1 ); ?> value='1'>

		<?php

	}

	public function checkbox_field_2_render() {

		$options = get_option( 'wcsw_settings' );

		?>

        <input type='checkbox' name='wcsw_settings[wcsw_checkbox_field_2]' <?php checked( isset( $options['wcsw_checkbox_field_2'] ) ? $options['wcsw_checkbox_field_2'] : '', 1 ); ?> value='1'>

		<?php

	}

	public function settings_section_callback() {

	    echo __( '', 'wcsw' );

	}

	public function options_page() {

		?>

        <form action='options.php' method='POST'>

            <h2>WooCommerce Simple Wishlist</h2>

			<?php

			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();

			?>

        </form>

		<?php

	}

}
