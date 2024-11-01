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

namespace WPRadio;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The PSR-4 autoloader project-specific implementation.
 *
 *
 * @link https://www.php-fig.org/psr/psr-4/examples/ The code this autoloader is based upon.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 *
 * @param string $className The fully-qualified class name.
 *
 * @return void
 */
spl_autoload_register( function ( $className ) {
	// Project-specific namespace prefix
	$prefix = 'WPRadio\\';

	// Base directory for the namespace prefix
	$baseDir = __DIR__ . '/';

	// Does the class use the namespace prefix?
	$prefixLength = strlen( $prefix );
	if ( strncmp( $prefix, $className, $prefixLength ) !== 0 ) {
		// No, move to the next registered autoloader
		return;
	}

	// Get the relative class name
	$relativeClassName = substr( $className, $prefixLength );

	// Replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$filePath = $baseDir . str_replace( '\\', '/', $relativeClassName ) . '.php';

	// If the file exists, require it
	if ( file_exists( $filePath ) ) {
		require_once $filePath;
	} else {
		exit( esc_html( "The file $className.php could not be found!" ) );
	}
} );
