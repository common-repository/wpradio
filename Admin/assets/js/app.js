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

import './stores/index';
import Alpine from 'alpinejs';
import casterfmApiHub from './helpers/casterfmApiHub';
import {accountStore, alertStore, appStore, serverStore} from './helpers/stores';
import Alert from "./helpers/alert";
import __ from "./helpers/translation";
import {Dashboard} from "./pages/dashboard";
import {Listeners} from "./pages/listeners";
import {Podcasts} from "./pages/podcasts";
import {Widgets} from "./pages/widgets";
import ClipboardJS from "clipboard/dist/clipboard";
import channelSelector from "./helpers/channelSelector";
import broadcastPassword from "./helpers/broadcastPassword";
import listenersTable from "./helpers/listenersTable";
import WidgetsGenerator from "./helpers/widgetsGenerator";
import podcastsTable from "./helpers/podcastsTable";
import fetchFeeds from "./helpers/feeds";

window.Alpine = Alpine

const currentPage = pagenow;
const privateToken = __('privateToken');
const publicToken = __('publicToken');

window.appStore = appStore();
window.alertStore = alertStore();
window.accountStore = accountStore();
window.serverStore = serverStore();

window.wpradioComponenets = {};

Alpine.data('channelSelector', channelSelector);
Alpine.data('broadcastPassword', broadcastPassword);
Alpine.data('listenersTable', listenersTable);
Alpine.data('widgetsGenerator', WidgetsGenerator);
Alpine.data('podcastsTable', podcastsTable);

Alpine.start()

document.addEventListener('DOMContentLoaded', function () {

  let clipboardCoppier = new ClipboardJS('.copybutton');

  fetchFeeds();

  document.getElementById('footer-upgrade').prepend('Wordpress Radio v' + __('wpradioVersion') + '. Wordpress ');

  if (!privateToken.match('^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}$')) {
    let alertButtons = [];
    if (__('isAdmin') === '1') {
      alertButtons = [
        {
          class: 'gray',
          title: __('FREE Caster.fm API Registration'),
          action: function () {
            let Url = __('casterfmApiRegistrationUrl');
            window.open(Url);
          }
        },
        {
          class: 'error',
          title: __('Settings Page'),
          action: function () {
            let Url = __('settingsPageUrl');
            window.location.href = Url;
          }
        }
      ];
    }
    Alert('error', __('Caster.fm API Tokens are required'), __('Wordpress Radio plugin requires you to have a FREE Caster.fm Cloud account and set your API Private Token at the plugin settings page'), alertButtons, null, true);
    throw new Error("Private token doesn't exists");
  }

  appStore().loaderMessage = __("Loading Page");

  switch (currentPage) {
    case 'toplevel_page_wpradio_dashboard':
      Dashboard();
      break;

    case 'wordpress-radio_page_wpradio_listeners':
      Listeners();
      break;

    case 'wordpress-radio_page_wpradio_podcasts':
      Podcasts();
      break;

    case 'wordpress-radio_page_wpradio_widgets':
      Widgets();
      break;

    default:
      Alert('error', 'Invalid Page', 'You really should not be getting this error', [], null, true);
      throw new Error("Invalid Page Name:" + currentPage);
      break;
  }

  appStore().loaderMessage = __("Loading Account");

  casterfmApiHub.get('accountInfo')
    .then(function (response) {
      let accountObject = _.omit(response, 'channels');
      accountObject.channels = _.indexBy(response.channels, 'streaming_server_mountpoint');
      accountStore().data = accountObject;
      accountStore().state = 'loaded';

      const AccountLoadedEvent = new Event('wpradio.accountLoaded');
      document.dispatchEvent(AccountLoadedEvent)
    })
    .catch(function (error) {
      if (error !== 'alreadyStopped') {
        let errorCode = null;
        if (error.response !== undefined && error.response.data !== undefined && error.response.data.error !== undefined && error.response.data.error !== null && error.response.data.error.uuid !== undefined && error.response.data.error.uuid !== '') {
          errorCode = error.response.data.error.uuid;
        }
        Alert('error', __('Service Unavailable'), __('Cannot access account information, please try again later or contact support'), [], errorCode, true);
        throw new Error("Cannot load account details");
      }
    })


});
