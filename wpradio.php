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
 * The plugin bootstrap file
 *
 *
 * @link              https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @since             1.0.0
 * @package           WPRadio
 *
 * @wordpress-plugin
 * Plugin Name:       WPRadio
 * Plugin URI:        https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * Description:       Wordpress Radio is an entire radio streaming platform embedded in your WordPress site
 * Version:           1.0.4
 * Author:            Caster.fm
 * Author URI:        https://www.caster.fm/
 * License:           GPL-2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpradio
 * Domain Path:       /Languages
 */

namespace WPRadio;

use WPRadio\Includes\Activator;
use WPRadio\Includes\Deactivator;
use WPRadio\Includes\Main;
use WPRadio\Includes\Updater;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autoloader
require_once plugin_dir_path( __FILE__ ) . 'Autoloader.php';

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPRADIO_VERSION', '1.0.4' );

/**
 * The string used to uniquely identify this plugin.
 */
define( 'WPRADIO_SLUG', 'wpradio' );

/**
 * Whether the plugin is running in debug mode
 */
define( 'WPRADIO_DEBUG', FALSE );

/**
 * Some Caster.fm API URLS
 */
define( 'WPRADIO_CASTERFMAPI_HUB', 'https://hub.cloud.caster.fm/' );
define( 'WPRADIO_CASTERFMAPI_CONSOLE', 'https://cloud.caster.fm/' );
define( 'WPRADIO_CASTERFMAPI_CDN', 'https://cdn.cloud.caster.fm/' );

define( 'WPRADIO_CASTERFMAPI_TUTORIALS', 'https://www.caster.fm/help/cloud/broadcasting-tutorials/' );
define( 'WPRADIO_CASTERFMAPI_SUPPORT', 'https://clients.caster.fm/submitticket.php?step=2&deptid=6' );

/**
 * Configuration data
 *  - db-version:   Start with 0 and increment by 1. It should not be updated with every plugin update,
 *                  only when database update is required.
 */
$configuration = [
	'version'    => WPRADIO_VERSION,
	'db-version' => 0
];

/**
 * The ID for the configuration options in the database.
 */
$configurationOptionName = WPRADIO_SLUG . '-configuration';

/**
 * The code that runs during plugin activation.
 * This action is documented in Includes/Activator.php
 */
register_activation_hook( __FILE__, function ( $networkWide ) use ( $configuration, $configurationOptionName ) {
	Activator::activate( $networkWide, $configuration, $configurationOptionName );
} );

/**
 * Run the activation code when a new site is created.
 * We'll disable the multiSite/Network mechanism for now
 */
add_action( 'wpmu_new_blog', function ( $blogId ) {
	Activator::activateNewSite( $blogId );
} );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in Includes/Deactivator.php
 */
register_deactivation_hook( __FILE__, function ( $networkWide ) {
	Deactivator::deactivate( $networkWide );
} );

/**
 * Update the plugin.
 * It runs every time, when the plugin is started.
 */
add_action( 'plugins_loaded', function () use ( $configuration, $configurationOptionName ) {
	Updater::update( $configuration['db-version'], $configurationOptionName );
}, 1 );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function runPlugin() {
	$plugin = new Main();
	$plugin->run();
}

runPlugin();
