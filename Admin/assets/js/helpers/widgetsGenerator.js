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
import Pickr from '@simonwep/pickr';
import __ from "./translation";


const WidgetsGenerator = (widgetType) => {
  return {

    widgetType: widgetType,
    channel: null,
    theme: 'light',
    themeChange(theme) {
      if (theme !== this.theme) {
        this.theme = theme;
        this.updateEmbedPlayer();
      }
    },
    accentColor: '#7F3EE8',
    accentColorPicker: null,

    init() {
      var properties = this;

      Alpine.effect(() => {
        this.updateChannel(properties.selectedKey);
      })

      properties.accentColorPicker = Pickr.create({
        el: '.' + properties.widgetType + '-color-picker',
        useAsButton: true,
        theme: 'monolith',

        lockOpacity: false,
        default: properties.accentColor,

        components: {

          // Main components
          preview: true,
          opacity: false,
          hue: true,

          // Input / output Options
          interaction: {
            hex: false,
            rgba: false,
            hsla: false,
            hsva: false,
            cmyk: false,
            input: true,
            cancel: true,
            clear: false,
            save: true
          }
        }
      });

      properties.accentColorPicker.on('cancel', instance => {

        instance.hide();

      }).on('save', (color, instance) => {

        properties.accentColor = color.toHEXA().toString();
        document.getElementsByClassName(properties.widgetType + '-color-picker-indicator')[0].style.backgroundColor = color.toHEXA().toString();
        instance.hide();
        properties.updateEmbedPlayer();

      });

    },
    updateChannel(key) {
      this.channel = key ? accountStore().data.channels[key].id : '';
      this.updateEmbedPlayer();
    },

    updateEmbedPlayer() {
      document.getElementById(this.widgetType + '-embedded-player').innerHTML = `<div data-type="${this.widgetType}Player" data-publicToken="${__('publicToken')}" data-theme="${this.theme}" data-color="${this.accentColor.replace('#', '')}" data-channelId="${this.channel}" data-rendered="false" class="cstrEmbed"><a href="https://www.caster.fm">Shoutcast Hosting</a> <a href="https://www.caster.fm">Stream Hosting</a> <a href="https://www.caster.fm">Radio Server Hosting</a></div>`;
      window.casterfmWidgetsRescan();
    },

    getShortcode() {
      return `[wpradio_player type="${this.widgetType}" channel="${this.channel}" color="${this.accentColor.replace('#', '')}" theme="${this.theme}"]`;
    }

  }
}

export default WidgetsGenerator;
