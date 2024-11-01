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
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * It is used to prepare custom files, tables, or any other things that the plugin may need
 * before it actually executes, and that it needs to remove upon uninstallation.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Includes
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Activator {

	/**
	 * Define the plugins that our plugin requires to function.
	 * The key is the plugin name, the value is the plugin file path.
	 *
	 * @since 1.0.0
	 * @var string[]
	 */

	//	* Not required at the moment since no dependencies are required
	//	----------------------
	//	const REQUIRED_PLUGINS = [
	//		//'Hello Dolly' => 'hello-dolly/hello.php',
	//		//'WooCommerce' => 'woocommerce/woocommerce.php'
	//	];

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @param bool $networkWide bool Plugin is network-wide activated or not.
	 * @param array $configuration The plugin's configuration data.
	 * @param string $configurationOptionName string The ID for the configuration options in the database.
	 *
	 * @since    1.0.0
	 */
	public static function activate( $networkWide, $configuration, $configurationOptionName ) {
		// Initialize default configuration values. If exist doesn't do anyting.
		// If single site, it is saved in the option table. If multisite, it is saved in the sitemeta table.
		add_network_option( get_current_network_id(), $configurationOptionName, $configuration );

		// If Multisite is enabled, the configuration data is saved as network option.
		if ( is_multisite() ) {
			// Network-wide activation
			if ( $networkWide ) {
				// Permission check
				if ( ! current_user_can( 'manage_network_plugins' ) ) {
					deactivate_plugins( plugin_basename( __FILE__ ) );
					// Localization class hasn't been loaded yet.
					wp_die( 'You don\'t have proper authorization to activate a plugin!' );
				}

				/**
				 * Network setup
				 */
				// Your Network setup code comes here...

				/**
				 * Site specific setup
				 */
				// Loop through the sites
				foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blogId ) {
					switch_to_blog( $blogId );
					self::checkDependencies( true, $blogId );
					self::onActivation();
					restore_current_blog();
				}
			}
		} else // Single site activation
		{
			// Permission check
			if ( ! current_user_can( 'activate_plugins' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				// Localization class hasn't been loaded yet.
				wp_die( 'You don\'t have proper authorization to activate a plugin!' );
			}

			self::checkDependencies();
			self::onActivation();
		}
	}

	/**
	 * Activate the newly creatied site if the plugin was network-wide activated.
	 * Hook: wpmu_new_blog
	 *
	 * @param int $blogId ID of the newly creatied site.
	 *
	 * @since    1.0.0
	 */
	public static function activateNewSite( $blogId ) {
		if ( is_plugin_active_for_network( 'wpradio/wpradio.php' ) ) {
			switch_to_blog( $blogId );
			self::checkDependencies( true, $blogId );
			self::onActivation();
			restore_current_blog();
		}
	}

	/**
	 * Check whether the required plugins are active.
	 *
	 * @param bool $networkWideActivation Network wide activation.
	 * @param int $blogId On Multisite context: ID of the currently checking site.
	 *
	 * @since      1.0.0
	 */
	private static function checkDependencies( $networkWideActivation = false, $blogId = 0 ) {
//		* Not required at the moment since no dependencies are required
//		----------------------
//		foreach ( self::REQUIRED_PLUGINS as $WPRadio => $pluginFilePath ) {
//			if ( ! is_plugin_active( $pluginFilePath ) ) {
//				// Deactivate the plugin.
//				deactivate_plugins( plugin_basename( __FILE__ ) );
//
//				if ( $networkWideActivation ) {
//					wp_die( "This plugin requires {$WPRadio} plugin to be active on site: " . $blogId );
//				} else {
//					wp_die( "This plugin requires {$WPRadio} plugin to be active!" );
//				}
//			}
//		}
	}

	/**
	 * The actual tasks performed during activation of a plugin.
	 * Should handle only stuff that happens during a single site activation,
	 * as the process will repeated for each site on a Multisite/Network installation
	 * if the plugin is activated network wide.
	 */
	public static function onActivation() {

	}
}
