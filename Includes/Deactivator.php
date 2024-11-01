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
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Includes
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @param bool $networkWide Plugin is network-wide activated or not.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate( $networkWide ) {
		if ( is_multisite() && $networkWide ) {
			// Permission check
			if ( ! current_user_can( 'manage_network_plugins' ) ) {
				// Localization class hasn't been loaded yet.
				wp_die( 'You don\'t have proper authorization to deactivate a plugin!' );
			}

			// Loop through the sites
			foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blogId ) {
				switch_to_blog( $blogId );
				self::onDeactivation();
				restore_current_blog();
			}
		} else {
			// Permission check
			if ( ! current_user_can( 'activate_plugins' ) ) {
				// Localization class hasn't been loaded yet.
				wp_die( 'You don\'t have proper authorization to deactivate a plugin!' );
			}

			self::onDeactivation();
		}
	}

	/**
	 * The actual tasks performed during deactivation of a plugin.
	 * Should handle only stuff that happens during a single site deactivation,
	 * as the process will repeated for each site on a Multisite/Network installation
	 * if the plugin is deactivated network wide.
	 */
	public static function onDeactivation() {

	}
}
