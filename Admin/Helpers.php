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

/**
 * Class Helpers
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Admin
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Helpers {

	/**
	 * @param null $affiliateId
	 *
	 * @return string
	 */
	public static function getRegistrationLink( $affiliateId = null ) {
		return ( $affiliateId === null || empty( $affiliateId ) ) ? WPRADIO_CASTERFMAPI_CONSOLE : WPRADIO_CASTERFMAPI_CONSOLE . "affiliateGateway?affiliate_id=$affiliateId";
	}

}
