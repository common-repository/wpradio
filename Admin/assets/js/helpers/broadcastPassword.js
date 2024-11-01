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
import __ from "./translation";
import casterfmApiHub from "./casterfmApiHub";
import {updateServerStatus} from "./streamingServer";
import Alert from "./alert";

const broadcastPassword = () => {
  return {
    dropDownOpen: false,
    modalOpen: false,
    loading: false,
    // selectedKey: null,

    regeneratePassword(mountpoint) {
      var properties = this;
      this.loading = true;

      casterfmApiHub.post('channel/' + accountStore().data.channels[mountpoint].id + '/change_password')
        .then(function (message) {

          casterfmApiHub.get('accountInfo')
            .then(function (response) {

              let accountObject = _.omit(response, 'channels');
              accountObject.channels = _.indexBy(response.channels, 'streaming_server_mountpoint');
              accountStore().data = accountObject;

              updateServerStatus().finally(function () {
                properties.loading = false;
                properties.modalOpen = false;
                properties.dropDownOpen = false;
                Alert('ok', __('Success!'), __('Broadcast password regenerated successfully. Please make sure to update your broadcast software settings with the new password.'), [{
                  class: 'ok',
                  title: __('Close'),
                  action: 'close'
                }], null, false);
              });

            })
            .catch(function (error) {
              if (error !== 'alreadyStopped') {
                let errorCode = null;
                if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.uuid !== undefined && error.response.data.error.uuid !== '') {
                  errorCode = error.response.data.error.uuid;
                }
                Alert('error', __('Service Unavailable'), __('Cannot access account information, please try again later or contact support'), [], errorCode, true);
                //throw new Error("Cannot load account details");
              }
            })

        }).catch(function (error) {

        let errorMessage = '';
        if (error.response) {
          errorMessage = error.response.data.message;
        } else {
          errorMessage = __('Could not perform the selected action at the moment. please try again later or contact support.');
        }

        properties.loading = false;
        properties.modalOpen = false;
        properties.dropDownOpen = false;

        Alert('error', __('Error!'), errorMessage, [{
          class: 'danger',
          title: __('Close'),
          action: 'close'
        }], null, false);

      }).finally(function () {
      });

    },

    init() {
      this.$watch('modalOpen', value => {
        if (value === true) {
          document.body.classList.add('overflow-hidden')
        } else {
          document.body.classList.remove('overflow-hidden')
        }
      });
    },

  }
}

export default broadcastPassword;
