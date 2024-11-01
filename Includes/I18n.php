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
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Includes
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class I18n {
	/**
	 * Unique identifier for retrieving translated strings.
	 *
	 * @since    1.0.0
	 */
	protected $domain;

	/**
	 * Initialize the text domain for i18n.
	 *
	 * @param string $domain Textdomain ID.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Register all the hooks of this class.
	 *
	 * @since    1.0.0
	 */
	public function initializeHooks() {
		add_action( 'plugins_loaded', [ $this, 'loadPluginTextdomain' ], 10 );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function loadPluginTextdomain() {
		if ( load_plugin_textdomain( $this->domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/Languages/' ) === false ) {
			//exit('Textdomain could not be loaded from: ' . dirname(dirname(plugin_basename(__FILE__ ))) . '/Languages/');
		}
	}
}
