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

import {serverStore} from "./stores";
import {StreamingServer} from "./streamingServer";
import __ from "./translation";
import Alpine from "alpinejs";
import {UAParser} from 'ua-parser-js'

const listenersTable = () => {
  return {
    tableState: 'loading', //'loading', 'ready', 'error', 'empty'
    currentAction: null,
    listeners: [],

    loadListeners(mountpoint) {
      var properties = this;
      if (serverStore().channelIsOnline(mountpoint) && properties.currentAction === null) {
        properties.tableState = 'loading';
        properties.listeners = [];
        StreamingServer().get('listclients.json?mount=/' + mountpoint)
          .then(function (response) {

              const listenersArray = response.data.source['/' + mountpoint].listener

              if (response.data.source['/' + mountpoint].listeners === 0) {
                  properties.tableState = 'empty'
              } else {
                  properties.listeners = _.map(listenersArray, function (listener) {
                      const userAgent = new UAParser(listener.agent)
                      return {
                          ip: listener.ip,
                          id: listener.id,
                          duration: Math.ceil(listener.connected / 60) + ' ' + __('Minutes'),
                          agent: userAgent.getResult()
                      }
                  })

                  properties.tableState = 'ready'
            }

          })
          .catch(function (error) {
            properties.tableState = 'error';
          })
          .finally(function () {
          });
      } else {
        properties.listeners = [];
        properties.tableState = 'error';
      }
    },

    init() {
      var properties = this;
      this.$watch('selectedKey', function (val) {
        properties.tableState = 'loading';
        properties.loadListeners(val);
      });

      Alpine.effect(() => {
        const serverData = Alpine.store('server').data;

        if (serverData !== null) {
          properties.tableState = 'loading';
          properties.loadListeners(properties.selectedKey);
        }
      })
    },

    kickListener(mountpoint, listenerId) {
      var properties = this;
      properties.tableState = 'loading';
      properties.currentAction = 'kickListener';
      if (serverStore().channelIsOnline(mountpoint)) {
        StreamingServer().get('killclient.json?mount=/' + mountpoint + '&id=' + listenerId)
          .then(function (response) {
            setTimeout(function () {
              properties.currentAction = null;
              properties.loadListeners(mountpoint);
            }, 5000)

          })
          .catch(function (error) {
            properties.currentAction = null;
            properties.tableState = 'ready';
          })
          .finally(function () {
          });
      }
    },

  }
}

export default listenersTable;
