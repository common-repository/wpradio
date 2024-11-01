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

import {accountStore} from "./stores";


const channelSelector = (scope, multipleAllowed = false) => {
  return {
    scope: scope,
    opened: false,
    selectedKey: null,
    selectedValue: null,
    highlighted: null,
    multipleAllowed,

    getSelectedChannelName: function () {
      if (accountStore().data !== null && this.selectedKey !== null) {
        if (this.selectedKey !== '') {
          return accountStore().data.channels[this.selectedKey].name;
        } else {
          return 'Multiple Channels'
        }

      } else {
        return '';
      }
    },
    getSelectedChannelColor: function () {

    },
    onSelect(selectedKey) {
      this.selectedKey = selectedKey;
      this.selectedValue = (selectedKey !== '') ? accountStore().data.channels[selectedKey].name : 'Multiple Channels';
      this.opened = false;
    },
    onEscape() {
      //maybe reset the highlited to the currently selected
      this.opened = false;
    },
    init() {
      var properties = this;

      this.$watch('opened', (val) =>
        properties.highlighted = properties.selectedKey
      );

      if (!this.multipleAllowed) {
        document.addEventListener('wpradio.accountLoaded', function () {
          properties.selectedKey = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].streaming_server_mountpoint;
          properties.selectedValue = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].name;
          properties.highlighted = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].streaming_server_mountpoint;
        });
        document.addEventListener('wpradio.initialServerStats', function () {
          properties.selectedKey = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].streaming_server_mountpoint;
          properties.selectedValue = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].name;
          properties.highlighted = accountStore().data.channels[Object.keys(accountStore().data.channels)[0]].streaming_server_mountpoint;
        });
      } else {
        document.addEventListener('wpradio.accountLoaded', function () {
          properties.selectedKey = '';
          properties.selectedValue = 'Multiple Channels';
          properties.highlighted = '';
        });
      }

    },
    onButtonClick: function () {
      if (!this.opened) {
        this.opened = true;
      }
    },
    onOptionSelect: function () {
      if (null !== this.highlighted) {
        this.select(this.highlighted);
      }
    },


  }
}

export default channelSelector;
