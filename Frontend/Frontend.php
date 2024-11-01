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

namespace WPRadio\Frontend;

use WPRadio\Admin\Settings;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The frontend functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Frontend
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Frontend {
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
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginSlug The name of the plugin.
	 * @param string $version The version of this plugin.
	 * @param Settings $settings The Settings object.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $pluginSlug, $version, $settings ) {
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
		// Frontend
		if ( ! $isAdmin ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ], 10 );
			add_action( 'init', [ $this, 'initShortcodes' ] );
		}
	}

	/**
	 * Register the JavaScript for the frontend side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts() {

		$scriptId  = $this->pluginSlug . '-widgetsEmbed';
		$scriptUrl = WPRADIO_CASTERFMAPI_CDN . 'widgets/embed.js';
		if ( wp_register_script( $scriptId, $scriptUrl, [], $this->version, true ) === false ) {
			exit( esc_html__( 'Script could not be registered: ', 'wpradio' ) . $scriptUrl );
		}
	}


	public function initShortcodes() {
		add_shortcode( 'wpradio_player', [ $this, 'wpradioPlayerShortcode' ] );
	}

	/**
	 * The [wpradioPlayer] shortcode.
	 * Handles widgets display on frontend.
	 *
	 * @param array $userAttributes
	 * @param string $content Shortcode content. Default null.
	 * @param string $tag Shortcode tag (name). Default empty.
	 *
	 * @return string
	 */
	public function wpradioPlayerShortcode( $userAttributes = [], $content = null, $tag = '' ) {

		wp_enqueue_script( $this->pluginSlug . '-widgetsEmbed' );

		// normalize attribute keys, lowercase
		$userAttributes = array_change_key_case( (array) $userAttributes, CASE_LOWER );

		// override default attributes with user attributes
		$attributes = shortcode_atts(
			[
				'type'    => 'newStreamPlayer',
				'channel' => '',
				'color'   => '7F3EE8',
				'theme'   => 'light',
			], $userAttributes, $tag
		);

		return '<div data-type="' . $attributes['type'] . 'Player" data-publicToken="' . $this->settings->getPublicToken() . '" data-theme="' . $attributes['theme'] . '" data-color="' . $attributes['color'] . '" data-channelId="' . $attributes['channel'] . '" data-rendered="false" class="cstrEmbed"><a href="https://www.caster.fm">Shoutcast Hosting</a> <a href="https://www.caster.fm">Stream Hosting</a> <a href="https://www.caster.fm">Radio Server Hosting</a></div>';
	}
}
