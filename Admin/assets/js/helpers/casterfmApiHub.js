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
import Alert from "../helpers/alert";

const casterfmApiHubUrl = __('casterfmApiHub');
const privateToken = __('privateToken');

const casterfmApiHub = axios.create({
  baseURL: casterfmApiHubUrl + 'private/',
  params: {
    token: privateToken
  }
});

casterfmApiHub.interceptors.response.use(function (response) {
  return response.data;
}, function (error) {

  if (error.response !== undefined && error.response.status !== undefined && error.response.status === 401) {
    switch (error.response.status) {
      case 401:
        Alert('error', __('Private Token Invalid'), __('The supplied private token is not valid.'), [], null, true);
        return Promise.reject('alreadyStopped');
        break;

      case 429:
        Alert('error', __('Too Many Requests'), __('Request failed due to throttling. please try again in a minute.'), [], null, false);
        return Promise.reject('alreadyStopped');
        break;

      case 403:
        Alert('error', __('Caster.fm API Account Suspended'), error.response.data.error.message, [], null, true);
        return Promise.reject('alreadyStopped');
        break;

      default:
        return Promise.reject(error);
        break;
    }
  } else {
    return Promise.reject(error);
  }
});

export default casterfmApiHub;
