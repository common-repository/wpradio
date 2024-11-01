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

use WPRadio\Admin\Admin;
use WPRadio\Admin\NetworkSettings;
use WPRadio\Admin\Settings;
use WPRadio\Frontend\Frontend;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Includes
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Main {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 */
	protected $pluginSlug;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version    = WPRADIO_VERSION;
		$this->pluginSlug = WPRADIO_SLUG;
	}

	/**
	 * Create the objects and register all the hooks of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineHooks() {
		$isAdmin        = is_admin();
		$isMultisite    = is_multisite();
		$isNetworkAdmin = is_network_admin();

		/**
		 * Includes objects - Register all of the hooks related both to the admin area and to the public-facing functionality of the plugin.
		 */
		$i18n = new I18n( $this->pluginSlug );
		$i18n->initializeHooks();

		/**
		 * Network Admin objects - Register all of the hooks related to the network admin area functionality of the plugin.
		 */
		$networkSettings = false;
		if ( $isMultisite ) {
			$networkSettings = new NetworkSettings( $this->pluginSlug );
			if ( $isNetworkAdmin ) {
				$networkSettings->initializeHooks( $isNetworkAdmin );
			}
		}

		// The Settings' hook initialization runs on Admin area only.
		$settings = new Settings( $this->pluginSlug, $networkSettings );


		/**
		 * Admin objects - Register all of the hooks related to the admin area functionality of the plugin.
		 */
		if ( $isAdmin ) {
			$admin = new Admin( $this->pluginSlug, $this->version, $settings );
			$admin->initializeHooks( $isAdmin );
			$settings->initializeHooks( $isAdmin );
		} /**
		 * Frontend objects - Register all of the hooks related to the public-facing functionality of the plugin.
		 */
		else {
			$frontend = new Frontend( $this->pluginSlug, $this->version, $settings );
			$frontend->initializeHooks( $isAdmin );
		}
	}

	/**
	 * Run the plugin.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->defineHooks();
	}
}
