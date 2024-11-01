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

import axios from 'axios';
import __ from "../helpers/translation";
import {accountStore, appStore, serverStore} from "./stores";
import casterfmApiHub from "./casterfmApiHub";
import Alert from "./alert";

const privateToken = __('privateToken');
const publicToken = __('publicToken');

let streamingServerInstance = null;
export const StreamingServer = () => {
  if (streamingServerInstance === null) {
    const ServerUrl = 'https://' + accountStore().data.streaming_server.domain + ':' + accountStore().data.streaming_server_port + '/';
    streamingServerInstance = axios.create({
      baseURL: ServerUrl + 'admin/',
      auth: {
        username: 'admin',
        password: privateToken
      }
    });

      streamingServerInstance.interceptors.response.use(function (response) {
          response.data = response.data[1]
          return response
      }, function (error) {
          return Promise.reject(error)
      })

  }
  return streamingServerInstance;
};

export const hubServerAction = (action) => {
  return new Promise(function (resolve, reject) {
    casterfmApiHub.post('server/' + action)
      .then(function (response) {
        let message = (action === 'start') ? __('Server has been started successfully') : __('Server has been stopped successfully');
        resolve(message);
      })
      .catch(function (error) {
        if (error !== 'alreadyStopped') {
          let errorCode = null;
          if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.uuid !== undefined && error.response.data.error.uuid !== '') {
            errorCode = error.response.data.error.uuid;
          }
          let errorMessage = __('Could not perform the selected action at the moment. please try again later or contact support.');
          if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.message !== undefined && error.response.data.error.message !== '') {
            errorMessage = error.response.data.error.message;
          }
          Alert('error', __('Error!'), errorMessage, [{
            class: 'ok',
            title: __('Close'),
            action: 'close'
          }], errorCode, false);
        }
        reject();
      })
  });
};

export const updateServerStatus = (initialCheck) => {
  serverStore().state = 'loading';

  if (initialCheck !== undefined) {
    appStore().loaderMessage = __("Checking Server Status");
  }

  return StreamingServer().get('/stats.json')
    .then(function (response) {


        const serverObject = _.omit(response.data, 'source')
      //serverObject.mounts = _.indexBy(response.data.mounts, 'streaming_server_mountpoint');

        const renameMountsKeys = obj => Object
            .keys(obj)
            .reduce((acc, key) => {
                const modifiedKey = key.replace('/', '')
                return ({
                    ...acc,
                    ...{ [modifiedKey]: obj[key] }
                })
            }, {})
        console.log(response.data)
        serverObject.source = renameMountsKeys(response.data.source ?? {})
        // _.map(response.data.mounts, function(mount){ console.info(mount);return mount; });
        // console.log(serverObject)
        serverStore().data = serverObject
    })
    .catch(function (error) {
      serverStore().data = null;
      console.error(error);
    })
    .then(function () {
      serverStore().state = 'loaded';

      if (initialCheck !== undefined) {
        appStore().state = 'ready';
        appStore().loaderMessage = null;
        window.wpradio_serverStatusInterval = setInterval(updateServerStatus, 30000);
        const InitialServerStats = new Event('wpradio.initialServerStats');
        document.dispatchEvent(InitialServerStats)
      }
    });

};
