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
 * Base Settings class.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Admin
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class SettingsBase {
	const TEXT_SUFFIX = '-tx';
	const TEXTAREA_SUFFIX = '-ta';
	const CHECKBOX_SUFFIX = '-cb';
	const RADIO_SUFFIX = '-rb';
	const SELECT_SUFFIX = '-sl';

	/**
	 * Initialize the class.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Sanitizes the option's value.
	 *
	 * Based on:
	 * @link https://divpusher.com/blog/wordpress-customizer-sanitization-examples/
	 *
	 * @since             1.0.0
	 * @package           WPRadio
	 *
	 * @param array $input The unsanitized collection of options.
	 *
	 * @return array $output     The collection of sanitized values.
	 */
	public function sanitizeOptionsCallback( array $input = null ) {
		if ( $input === null ) {
			return [];
		}

		// Define the array for the sanitized options
		$output = [];

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {
			// Sanitize Checkbox. Input must be boolean.
			if ( $this->endsWith( $key, self::CHECKBOX_SUFFIX ) ) {
				$output[ $key ] = isset( $input[ $key ] );
			} // Sanitize Radio button. Input must be a slug: [a-z,0-9,-,_].
			elseif ( $this->endsWith( $key, self::RADIO_SUFFIX ) ) {
				$output[ $key ] = isset( $input[ $key ] ) ? sanitize_key( $input[ $key ] ) : '';
			} // Sanitize Select aka Dropdown. Input must be a slug: [a-z,0-9,-,_].
			elseif ( $this->endsWith( $key, self::SELECT_SUFFIX ) ) {
				$output[ $key ] = isset( $input[ $key ] ) ? sanitize_key( $input[ $key ] ) : '';
			} // Sanitize Text
			elseif ( $this->endsWith( $key, self::TEXT_SUFFIX ) ) {
				$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
			} // Sanitize Textarea
			elseif ( $this->endsWith( $key, self::TEXTAREA_SUFFIX ) ) {
				$output[ $key ] = isset( $input[ $key ] ) ? sanitize_textarea_field( $input[ $key ] ) : '';
			} // Edge cases, fallback to default. Input must be Text.
			else {
				$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
			}
		}

		// Return the array processing any additional functions filtered by this action
		return $output;
	}

	/**
	 * Determine if a string ends with another string.
	 *
	 * @param   $haystack       string Base string.
	 * @param   $needle         string The searched value.
	 *
	 * @return bool If the string ends with the another string reruen true, otherwise false
	 * @package           WPRadio
	 *
	 * @since             1.0.0
	 */
	private function endsWith( $haystack, $needle ) {
		$haystackLenght = strlen( $haystack );
		$needleLenght   = strlen( $needle );

		if ( $needleLenght > $haystackLenght ) {
			return false;
		}

		return substr_compare( $haystack, $needle, - $needleLenght, $needleLenght ) === 0;
	}
}
