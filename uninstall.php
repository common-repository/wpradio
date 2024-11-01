<?php
/**
Wordpress Radio (wpradio) - a radio streaming platform for wordpress
Copyright (C) 2020 Caster.fm (www.caster.fm)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */


// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete the plugin's configuration data.
$currentNetworkId = get_current_network_id();
deleteConfigOptions( $currentNetworkId );

// If Multisite is enabled, then uninstall the plugin on every site.
if ( is_multisite() ) {
	// Permission check
	if ( ! current_user_can( 'manage_network_plugins' ) ) {
		wp_die( 'You don\'t have proper authorization to delete a plugin!' );
	}

	/**
	 * Delete the Network options
	 */
	deleteNetworkOptions( $currentNetworkId );

	/**
	 * Delete the site specific options
	 */
	foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blogId ) {
		switch_to_blog( $blogId );
		// Site specific uninstall code starts here...
		deleteOptions();
		restore_current_blog();
	}
} else {
	// Permission check
	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_die( 'You don\'t have proper authorization to delete a plugin!' );
	}

	deleteOptions();
}

/**
 * Delete the plugin's configuration data.
 *
 * @param int $currentNetworkId
 *
 * @since    1.0.0
 */
function deleteConfigOptions( $currentNetworkId ) {
	delete_network_option( $currentNetworkId, 'wpradio-configuration' );
}

/**
 * Delete the plugin's network options.
 *
 * @param int $currentNetworkId
 *
 * @since    1.0.0
 */
function deleteNetworkOptions( $currentNetworkId ) {
	delete_network_option( $currentNetworkId, 'wpradio-network-settings' );
}

/**
 * Delete the plugin's options.
 *
 * @since    1.0.0
 */
function deleteOptions() {
	delete_option( 'wpradio-general' );
}
