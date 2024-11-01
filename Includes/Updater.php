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

namespace WPRadio\Includes;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Update the plugin's database.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Includes
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Updater {
	/**
	 * Update the plugin, by running the incremental updates one by one.
	 *
	 * For example, if the current DB version is 0, and the target DB version is 2,
	 * this function will execute update routines:
	 *  - updateRoutine1()
	 *  - updateRoutine2()
	 *
	 * @param int $currentDatabaseVersion The currennt database version expected by the plugin.
	 * @param string $configurationOptionName The ID for the configuration options in the database.
	 *
	 * @since    1.0.0
	 */
	public static function update( $currentDatabaseVersion, $configurationOptionName ) {
		$multisite = is_multisite();

		// Get the configuration data
		$currentNetworkId = get_current_network_id();
		$configuration    = get_network_option( $currentNetworkId, $configurationOptionName );

		$installedDatabaseVersion = $configuration['db-version'];
		if ( $installedDatabaseVersion < $currentDatabaseVersion ) {
			// No PHP timeout for running updates
			set_time_limit( 0 );

			// Run update routines one by one until the current version number reaches the target version number
			while ( $installedDatabaseVersion < $currentDatabaseVersion ) {
				$installedDatabaseVersion ++;

				// Each db version will require a separate update function for example,
				// for db-version 1, the function name should be updateRoutine1
				$updateRoutineFunctionName = 'updateRoutine' . $installedDatabaseVersion;

				if ( is_callable( [ __CLASS__, $updateRoutineFunctionName ] ) ) {
					if ( $multisite ) {
						// Loop through the sites
						foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blogId ) {
							switch_to_blog( $blogId );
							call_user_func( [ __CLASS__, $updateRoutineFunctionName ] );
							restore_current_blog();
						}
					} else {
						call_user_func( [ __CLASS__, $updateRoutineFunctionName ] );
					}

					// Update the configuration option in the database, so that this process can always pick up where it left off
					$configuration['db-version'] = $installedDatabaseVersion;
					update_network_option( $currentNetworkId, $configurationOptionName, $configuration );
				} else {
					wp_die( __( 'Update routine not callable: ', 'wpradio' ) . __CLASS__ . '\\' . $updateRoutineFunctionName . '()' );
				}
			}

			// Set back the PHP timeout to default
			set_time_limit( 30 );
		}
	}

	/**
	 * Update routine for the upcomming database version called by 'update' function
	 *
	 * @since    1.0.0
	 */
	private static function updateRoutine1() {
		/**
		 * Usefull tools to consider:
		 *  - array_merge()
		 *  - dbDelta()
		 *  - wpdb Class
		 */
	}
}
