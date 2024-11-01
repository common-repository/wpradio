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
import casterfmApiHub from "./casterfmApiHub";
import Alert from "./alert";
import __ from "./translation";
import { parseISO, format } from 'date-fns'


const podcastsTable = () => {
  return {
    tableState: 'loading', //'loading', 'ready', 'error', 'empty'
    currentAction: null,
    podcasts: [],
    playing: null,
    editing: null,
    deleting: null,

    loadPodcasts(mountpoint) {
      var properties = this;
      if (properties.currentAction === null) {
        properties.tableState = 'loading';
        properties.podcasts = [];

        casterfmApiHub.get('podcasts/' + accountStore().data.channels[mountpoint].id)
          .then(function (response) {

            let podcastsArray = response;
            if (podcastsArray.length === 0) {
              properties.tableState = 'empty';
            } else {

              properties.podcasts = _.map(podcastsArray, function (podcast) {
                return {
                  id: podcast.id,
                  name: podcast.name,
                  recorded_at: format(parseISO(podcast.recorded_at), 'MMM do yyyy, HH:mm'),
                  url: podcast.url,
                };
              });
              properties.tableState = 'ready';
            }

          })
          .catch(function (error) {
            properties.podcasts = [];
            properties.tableState = 'error';
          })
          .finally(function () {
          });

      } else {
        properties.podcasts = [];
        properties.tableState = 'error';
      }
    },

    init() {
      var properties = this;
      this.$watch('selectedKey', function (val) {
        properties.tableState = 'loading';
        properties.closePlayer();
        properties.loadPodcasts(val);
      });
    },

    closePlayer() {
      window.wpradioComponenets.podcastsPlayer.stop();
      window.wpradioComponenets.podcastsPlayer.source = {
        type: 'audio',
        sources: [],
      };
      this.playing = null;
    },

    play(podcastId) {
      let podcast = this.podcasts[_.findLastIndex(this.podcasts, {
        id: podcastId
      })];
      window.wpradioComponenets.podcastsPlayer.stop();
      window.wpradioComponenets.podcastsPlayer.source = {
        type: 'audio',
        sources: [{
          src: podcast.url
          //type: 'audio/mp3',
        }],
      };
      window.wpradioComponenets.podcastsPlayer.play();
      this.playing = podcastId;
    },

    updateTitle(mountpoint, podcastId, title) {
      var properties = this;
      if (properties.currentAction === null) {
        properties.currentAction = 'updatingTitle';

        casterfmApiHub.post('podcasts/' + accountStore().data.channels[mountpoint].id + '/' + podcastId + '/' + 'update', {
          name: title
        })
          .then(function (response) {
            properties.podcasts[_.findLastIndex(properties.podcasts, {
              id: podcastId
            })].name = title;
            properties.editing = null;
          })
          .catch(function (error) {
            if (error !== 'alreadyStopped') {
              let errorCode = null;
              let message = __('Cannot update recording name, please try again later or contact support');
              if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.uuid !== undefined && error.response.data.error.uuid !== '') {
                errorCode = error.response.data.error.uuid;
              }
              if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.message !== undefined && error.response.data.error.message !== '') {
                message = error.response.data.error.message;
              }
              Alert('error', __('An error occurred.'), message, [{
                class: 'error',
                title: __('Close'),
                action: 'close'
              }], errorCode, true);

            }
          })
          .finally(function () {
            properties.currentAction = null;
          });

      }
    },

    deletePodcast(mountpoint, podcastId) {
      var properties = this;
      if (properties.currentAction === null) {
        properties.currentAction = 'deletingPodcast';
        properties.deleting = podcastId;
        casterfmApiHub.post('podcasts/' + accountStore().data.channels[mountpoint].id + '/' + podcastId + '/' + 'delete')
          .then(function (response) {
            properties.podcasts.splice(_.findLastIndex(properties.podcasts, {
              id: podcastId
            }), 1);
          })
          .catch(function (error) {
            if (error !== 'alreadyStopped') {
              let errorCode = null;
              let message = __('Cannot delete recording, please try again later or contact support');
              if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.uuid !== undefined && error.response.data.error.uuid !== '') {
                errorCode = error.response.data.error.uuid;
              }
              if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.message !== undefined && error.response.data.error.message !== '') {
                message = error.response.data.error.message;
              }
              Alert('error', __('An error occurred.'), message, [{
                class: 'error',
                title: __('Close'),
                action: 'close'
              }], errorCode, true);

            }
          })
          .finally(function () {
            properties.currentAction = null;
            properties.deleting = null;
          });

      }
    },

  }
}

export default podcastsTable;
