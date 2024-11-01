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

import Alpine from 'alpinejs'

Alpine.store('alert', {
  type: null, // null, 'error', 'warning', 'ok'
  title: null,
  message: null,
  code: null,
  fatal: false,
  buttons: [
    /*{
      class: 'ok',
      title: null,
      action: function() {

      },
    }*/
  ],
  clearAlert: () => {
    store.app.alert.type = null;
    store.app.alert.title = null;
    store.app.alert.message = null;
    store.app.alert.code = null;
    store.app.alert.fatal = false;
    store.app.alert.buttons = [];
  },
})


export default Alpine
