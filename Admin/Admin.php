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

namespace WPRadio\Admin;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Admin
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 */
	private $pluginSlug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	private $version;

	/**
	 * The settings of this plugin.
	 *
	 * @since    1.0.0
	 */
	private $settings;

	/**
	 * The page details array
	 *
	 * @since    1.0.0
	 */
	private $page;

	private $pageIdsLoadJS = [
		'toplevel_page_wpradio_dashboard',
		'wordpress-radio_page_wpradio_listeners',
		'wordpress-radio_page_wpradio_podcasts',
		'wordpress-radio_page_wpradio_widgets',
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginSlug The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @param Settings $settings The Settings object.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $pluginSlug, $version, Settings $settings ) {
		$this->pluginSlug = $pluginSlug;
		$this->version    = $version;
		$this->settings   = $settings;
	}

	/**
	 * Register all the hooks of this class.
	 *
	 * @param bool $isAdmin Whether the current request is for an administrative interface page.
	 *
	 * @since    1.0.0
	 */
	public function initializeHooks( $isAdmin ) {
		if ( $isAdmin ) {
			add_action( 'admin_menu', [ $this, 'setupMenu' ], 10 );

			add_action( 'admin_post_wpradio_player', [ $this, 'displayStreamPlayer' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueueStyles' ], 10 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ], 10 );
		}
	}

	/**
	 * This function introduces the plugin options into the Main menu.
	 */
	public function setupMenu() {
		//Add the menu item to the Main menu
		add_menu_page(
			'Wordpress Radio',
			'Wordpress Radio',
			'read',
			$this->pluginSlug . '_dashboard',
			[ $this, 'renderDashboardPageContent' ],
			'dashicons-microphone',
			81
		);

		add_submenu_page(
			$this->pluginSlug . '_dashboard',
			'Wordpress Radio Dashboard',
			'Dashboard',
			'read',
			$this->pluginSlug . '_dashboard',
			[ $this, 'renderDashboardPageContent' ]
		);

		add_submenu_page(
			$this->pluginSlug . '_dashboard',
			'Wordpress Radio Listeners',
			'Listeners',
			'read',
			$this->pluginSlug . '_listeners',
			[ $this, 'renderListenersPageContent' ]
		);

		add_submenu_page(
			$this->pluginSlug . '_dashboard',
			'Wordpress Radio Podcasts',
			'Podcasts',
			'read',
			$this->pluginSlug . '_podcasts',
			[ $this, 'renderPodcastsPageContent' ]
		);

		add_submenu_page(
			$this->pluginSlug . '_dashboard',
			'Wordpress Radio Widgets',
			'Widgets',
			'read',
			$this->pluginSlug . '_widgets',
			[ $this, 'renderWidgetsPageContent' ]
		);

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $pageId A screen id to filter the current admin page
	 *
	 * @since   1.0.0
	 */
	public function enqueueStyles( $pageId ) {
		/**
		 * Register the styles globally
		 */
		$styleId       = $this->pluginSlug . '-admin';
		$styleFileName = 'wpradio-admin.css';
		$styleUrl      = plugin_dir_url( __FILE__ ) . 'css/' . $styleFileName;
		if ( wp_register_style( $styleId, $styleUrl, [], $this->version, 'all' ) === false ) {
			exit( esc_html__( 'Style could not be registered: ', 'wpradio' ) . $styleUrl );
		}

		/**
		 * enque the style only for plugin specific pages
		 */
		if ( in_array( $pageId, $this->pageIdsLoadJS ) ) {
			wp_enqueue_style( $styleId );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $pageId A screen id to filter the current admin page
	 *
	 * @since   1.0.0
	 */
	public function enqueueScripts( $pageId ) {
		/**
		 * Register the scripts globally
		 */
		$scriptId      = $this->pluginSlug . '-admin';
		$scripFileName = 'wpradio-admin.js';
		$scriptUrl     = plugin_dir_url( __FILE__ ) . 'js/' . $scripFileName;
		if ( wp_register_script( $scriptId, $scriptUrl, [
				'jquery',
				'underscore',
				'wp-i18n'
			], $this->version, true ) === false ) {
			exit( esc_html__( 'Script could not be registered: ', 'wpradio' ) . $scriptUrl );
		}

		/**
		 * Add javascript localization
		 */
		$translation_array = [
			'Loading Page'                                                                                                                                                   => __( 'Loading Page' ),
			'Loading Account'                                                                                                                                                => __( 'Loading Account' ),
			'Checking Server Status'                                                                                                                                         => __( 'Checking Server Status' ),
			'Caster.fm API Tokens are required'                                                                                                                              => __( 'Caster.fm API Tokens are required' ),
			'Wordpress Radio plugin requires you to have a FREE account at Caster.fm API and set your API Private Token at the plugin settings page'                         => __( 'Wordpress Radio plugin requires you to have a FREE account at Caster.fm API and set your API Private Token at the plugin settings page' ),
			'FREE Caster.fm API Registration'                                                                                                                                => __( 'FREE Caster.fm API Registration' ),
			'Settings Page'                                                                                                                                                  => __( 'Settings Page' ),
			'Online'                                                                                                                                                         => __( 'Online' ),
			'Offline'                                                                                                                                                        => __( 'Offline' ),
			'Start Server'                                                                                                                                                   => __( 'Start Server' ),
			'Stop Server'                                                                                                                                                    => __( 'Stop Server' ),
			'Action Failed'                                                                                                                                                  => __( 'Action Failed' ),
			'Service Unavailable'                                                                                                                                            => __( 'Service Unavailable' ),
			'Cannot access account information, please try again later or contact support'                                                                                   => __( 'Cannot access account information, please try again later or contact support' ),
			'Private Token Invalid'                                                                                                                                          => __( 'Private Token Invalid' ),
			'The supplied private token is not valid.'                                                                                                                       => __( 'The supplied private token is not valid.' ),
			'Could not perform the selected action at the moment. please try again later or contact support.'                                                                => __( 'Could not perform the selected action at the moment. please try again later or contact support.' ),
			'Error!'                                                                                                                                                         => __( 'Error!' ),
			'Success!'                                                                                                                                                       => __( 'Success!' ),
			'Close'                                                                                                                                                          => __( 'Close' ),
			'Server has been started successfully'                                                                                                                           => __( 'Server has been started successfully' ),
			'Server has been stopped successfully'                                                                                                                           => __( 'Server has been stopped successfully' ),
			'Upgrade your plan to unlock'                                                                                                                                    => __( 'Upgrade your plan to unlock' ),
			'Recording'                                                                                                                                                      => __( 'Recording' ),
			'Finished'                                                                                                                                                       => __( 'Finished' ),
			'Too Many Requests'                                                                                                                                              => __( 'Too Many Requests' ),
			'Request failed due to throttling. please try again in a minute.'                                                                                                => __( 'Request failed due to throttling. please try again in a minute.' ),
			'Source Dropped. please note that most broadcast software has an auto-reconnect feature which will continue to reconnect to the server even after being dropped' => __( 'Source Dropped. please note that most broadcast software has an auto-reconnect feature which will continue to reconnect to the server even after being dropped' ),
			'Metadata updated successfully.'                                                                                                                                 => __( 'Metadata updated successfully.' ),
			'Minutes'                                                                                                                                                        => __( 'Minutes' ),
			'Bytes'                                                                                                                                                          => __( 'Bytes' ),
			'An error occurred.'                                                                                                                                             => __( 'An error occurred.' ),

		];

		$configuration_array = [
			'privateToken'               => $this->settings->getPrivateToken(),
			'publicToken'                => $this->settings->getPublicToken(),
			'casterfmApiHub'             => WPRADIO_CASTERFMAPI_HUB,
			'casterfmCdn'                => WPRADIO_CASTERFMAPI_CDN,
			'isAdmin'                    => current_user_can( 'manage_options' ),
			'casterfmApiRegistrationUrl' => Helpers::getRegistrationLink( $this->settings->getAffiliateID() ),
			'settingsPageUrl'            => admin_url( 'admin.php?page=wpradio_settings' ),
			'listenersPageUrl'           => admin_url( 'admin.php?page=wpradio_listeners' ),
			'podcastsPageUrl'            => admin_url( 'admin.php?page=wpradio_podcasts' ),
			'widgetsPageUrl'             => admin_url( 'admin.php?page=wpradio_widgets' ),
			'wpradioVersion'             => $this->version,
		];

		wp_localize_script( $scriptId, 'wpradiolang', array_merge( $translation_array, $configuration_array ) );

		/**
		 * enque the scripts only for plugin specific pages
		 */
		if ( in_array( $pageId, $this->pageIdsLoadJS ) ) {
			wp_enqueue_script( $scriptId );
		}

		/**
		 * register Widgets script
		 */
		$scriptId      = $this->pluginSlug . '-admin-widgets';
		$scriptUrl = WPRADIO_CASTERFMAPI_CDN . 'widgets/embed.js';
		if ( wp_register_script( $scriptId, $scriptUrl,array(), $this->version, false ) === false ) {
			exit( esc_html__( 'Script could not be registered: ', 'wpradio' ) . $scriptUrl );
		}

		/**
		 * enque the widgets script only for the widget page
		 */
		if ( $pageId === 'wordpress-radio_page_wpradio_widgets' ) {
			wp_enqueue_script( $scriptId );
		}
	}

	/**
	 * Renders the Dashboard page
	 *
	 * @since   1.0.0
	 */
	public function renderDashboardPageContent() {
		$this->loadTemplate( __( 'Dashboard', 'wpradio' ), 'dashboard' );
	}

	/**
	 * Renders the Listeners page
	 *
	 * @since   1.0.0
	 */
	public function renderListenersPageContent() {
		$this->loadTemplate( __( 'Listeners', 'wpradio' ), 'listeners' );
	}

	/**
	 * Renders the Podcasts page
	 *
	 * @since   1.0.0
	 */
	public function renderPodcastsPageContent() {
		$this->loadTemplate( __( 'Podcasts', 'wpradio' ), 'podcasts' );
	}

	/**
	 * Renders the Widgets page
	 *
	 * @since   1.0.0
	 */
	public function renderWidgetsPageContent() {
		$this->loadTemplate( __( 'Widgets', 'wpradio' ), 'widgets' );
	}

	/**
	 * Displays the embedded stream player for the admin interface
	 * This used for the admin area when the direct stream link is not available.
	 *
	 * @since   1.0.0
	 */
	public function displayStreamPlayer() {

		$cdnPath = WPRADIO_CASTERFMAPI_CDN;

		//Only parameter that needs to be sanitized and then escaped since we're echoing it.
		$channel = esc_html(sanitize_text_field($_REQUEST['channelId']));

		echo <<<EOD
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Stream Player</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="referrer" content="no-referrer-when-downgrade">
	<style>
		body, html {
			padding: 0;
			margin: 0;
		}
	</style>
</head>
<body>
<div data-type="newStreamPlayer" data-publicToken="{$this->settings->getPublicToken()}" data-theme="light" data-color="" data-channelId="{$channel}" data-rendered="false" class="cstrEmbed">
	<a href="https://www.caster.fm">Shoutcast Hosting</a> <a href="https://www.caster.fm">Stream Hosting</a> <a href="https://www.caster.fm">Radio Server Hosting</a>
</div>
<script src="{$cdnPath}widgets/embed.js"></script>
</body>
</html>
EOD;

	}


	/**
	 * Checks for tokens existence
	 *
	 * @return bool
	 * @since   1.0.0
	 */
	protected function tokensExists() {
		$privateToken = $this->settings->getPrivateToken();
		$publicToken = $this->settings->getPublicToken();
		return ( ! empty( $privateToken ) && ! empty( $publicToken ) );
	}

	/**
	 * Loads Template file
	 *
	 * @param string $pageName
	 * @param string $template
	 *
	 * @return void
	 * @since   1.0.0
	 */
	protected function loadTemplate( $pageName, $template ) {
		define( 'WPRADIO_PAGE_NAME', __( $pageName, 'wpradio' ) );
		define( 'WPRADIO_PAGE_TEMPLATE', $template );
		$currentPage = get_current_screen();
		define( 'WPRADIO_CURRENT_PAGE', $currentPage->id );

		require_once 'partials/wpradio-layout-template.php';

	}
}
