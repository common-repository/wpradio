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

import Alpine from 'alpinejs';
import {accountStore, serverStore} from '../helpers/stores';
import {hubServerAction, StreamingServer, updateServerStatus} from '../helpers/streamingServer'
import Alert from "../helpers/alert";
import __ from "../helpers/translation";
import {parse, add, isBefore, format, parseISO} from 'date-fns'


Alpine.store('server', {
  state: 'unloaded', // 'unloaded', 'loading', 'loaded', 'error'
  actionState: 'ready', // 'ready', 'inprogress'
  data: null,
  serverUrl: (channel = '') => {
    return 'https://' + accountStore().data.streaming_server.domain + ':' + accountStore().data.streaming_server_port + '/' + channel;
  },
  recorderStatus: (channel) => {
    if (serverStore().data === null || !Object.prototype.hasOwnProperty.call(serverStore().data.source, channel)) {
      return ''
    }
    if (accountStore().data.subscription.podcasts_recorder === null) {
      return __('Upgrade your plan to unlock')
    }

    if (Object.prototype.hasOwnProperty.call(serverStore().data.source[channel], 'dumpfile_start')) {
      return __('Recording')
    } else {
      return __('Idle')
    }
  },
  recorderDatetime: (channel) => {
    if (serverStore().data === null || !Object.prototype.hasOwnProperty.call(serverStore().data.source, channel)) {
      return ''
    }
    if (accountStore().data.subscription.podcasts_recorder === null) {
      return '---'
    }

    if (Object.prototype.hasOwnProperty.call(serverStore().data.source[channel], 'dumpfile_start')) {
      return serverStore().formatIcecastDate(serverStore().data.source[channel].dumpfile_start)
    } else {
      return '---'
    }
  },
  onlineChannelsText: () => {
    if (accountStore().data === null || serverStore().data === null || Object.keys(serverStore().data.source).length === 0) {
      return 'Off Air'
    }
    if (serverStore().data !== null && Object.keys(serverStore().data.source).length === Object.keys(accountStore().data.channels).length && Object.keys(accountStore().data.channels).length === 1) {
      return 'On Air'
    }
    if (serverStore().data !== null) {
      return Object.keys(serverStore().data.source).length + ' / ' + Object.keys(accountStore().data.channels).length
    }
  },
  onlineChannels: () => {
    if (accountStore().data === null || serverStore().data === null || Object.keys(serverStore().data.source).length === 0) {
      return 'none'
    }
    if (serverStore().data !== null && Object.keys(serverStore().data.source).length === Object.keys(accountStore().data.channels).length) {
      return 'all'
    }
    if (serverStore().data !== null && Object.keys(serverStore().data.source).length !== Object.keys(accountStore().data.channels).length) {
      return 'some'
    }
  },
  totalListeners: () => {
    if (accountStore().data === null || serverStore().data === null || Object.keys(serverStore().data.source).length === 0) {
      return '---'
    }

    let listenersCount = 0

    _.forEach(serverStore().data.source, function (mountpointStats) {
      listenersCount = Number(listenersCount) + Number(mountpointStats.listeners)
    })

    return listenersCount
  },
  channelIsOnline: (channelKey) => {
    return (accountStore().data !== null && serverStore().data !== null && Object.prototype.hasOwnProperty.call(serverStore().data.source, channelKey))
  },
  formatIcecastDate: (dateString) => {
    console.log(parseISO(dateString), dateString)
    return format(parseISO(dateString), 'MMM do yyyy, HH:mm')
  },
  actions: {
    startServer: () => {
      serverStore().actionState = 'inprogress';
      hubServerAction('start')
        .then(function (message) {
          updateServerStatus().finally(function () {
            Alert('ok', __('Success!'), message, [{
              class: 'ok',
              title: __('Close'),
              action: 'close'
            }], null, false);
            serverStore().actionState = 'ready';
          });
        }).catch(function () {
        serverStore().actionState = 'ready';
      });
    },
    stopServer: () => {
      serverStore().actionState = 'inprogress';
      hubServerAction('stop')
        .then(function (message) {
          updateServerStatus().finally(function () {
            Alert('ok', __('Success!'), message, [{
              class: 'ok',
              title: __('Close'),
              action: 'close'
            }], null, false);
            serverStore().actionState = 'ready';
          });
        }).catch(function () {
        serverStore().actionState = 'ready';
      });
    },
    dropSource: (mountpoint) => {
      return new Promise(function (resolve, reject) {
        StreamingServer().get('killsource.json?mount=/' + mountpoint)
          .then(function (message) {
            updateServerStatus().finally(function () {
              Alert('ok', __('Success!'), __('Source Dropped. please note that most broadcast software has an auto-reconnect feature which will continue to reconnect to the server even after being dropped'), [{
                class: 'ok',
                title: __('Close'),
                action: 'close'
              }], null, false);
              resolve();
            });
          }).catch(function (error) {
          let errorMessage = '';
          if (error.response) {
            errorMessage = error.response.data.message;
          } else {
            errorMessage = __('Could not perform the selected action at the moment. please try again later or contact support.');
          }
          Alert('error', __('Error!'), errorMessage, [{
            class: 'danger',
            title: __('Close'),
            action: 'close'
          }], null, false);
          resolve();
        }).finally(function () {
        });
      });
    },
    updateMetadata: (mountpoint, title) => {
      return new Promise(function (resolve, reject) {

        StreamingServer().get('metadata.json?mode=updinfo&charset=UTF-8&mount=/' + mountpoint + '&song=' + encodeURIComponent(title))
          .then(function (message) {
            updateServerStatus().finally(function () {
              Alert('ok', __('Success!'), __('Metadata updated successfully.'), [{
                class: 'ok',
                title: __('Close'),
                action: 'close'
              }], null, false);
              resolve();
            });
          }).catch(function (error) {
          let errorMessage = '';
          if (error.response) {
            errorMessage = error.response.data.message;
          } else {
            errorMessage = __('Could not perform the selected action at the moment. please try again later or contact support.');
          }
          Alert('error', __('Error!'), errorMessage, [{
            class: 'danger',
            title: __('Close'),
            action: 'close'
          }], null, false);
          resolve();
        }).finally(function () {
        });
      });
    },
  },
})

Alpine.effect(() => {
  const serverData = Alpine.store('server').data;

  if (serverData !== null && Alpine.store('account').state === 'loaded') {
    let unproxyfiedObject = JSON.parse(JSON.stringify(Alpine.store('account').data.channels));
    if (Alpine.store('account').data !== null && Object.keys(Alpine.store('account').data.channels).length > 1) {
      Alpine.store('account').data.channels = _.indexBy(
        _.sortBy(unproxyfiedObject, function(channel) {
          return Alpine.store('server').data.source.hasOwnProperty(channel.streaming_server_mountpoint) ? 0 : 1;
        }),
        'streaming_server_mountpoint'
      );
    }
  }
});



export default Alpine
