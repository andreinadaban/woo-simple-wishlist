<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/*
 * Deletes the plugin version record in the database.
 */
if ( get_option( 'wcsw_version' ) ) {

	delete_option( 'wcsw_version' );

}
