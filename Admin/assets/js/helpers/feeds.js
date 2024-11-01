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
import createListenersGraph from "./listenersGraph";

const newsFeed = () => {
  return axios.get('https://www.caster.fm/tag/wpradio/feed/json/');
};

const announcementFeed = () => {
  return axios.get('https://www.caster.fm/tag/wordpress-radio-announcement/feed/json/');
};


const fetchFeeds = () => {
  Promise.all([newsFeed(), announcementFeed()])
    .then(function (results) {
      const news = results[0].data;
      const announcements = results[1].data;
      const BreakException = {};

      try {
        news.items.forEach(function (item, index) {
          let title = item.title;
          let date = new Date(item.date_modified).toLocaleString();
          let link = item.url;
          if (index > 5) throw BreakException;
          document.getElementById('sidebarNewsList').innerHTML+= `<li class="py-2 flex"><div class="ml-3"><a target="_blank" href="${link}" class="text-sm font-medium text-gray-900">${title}</a><p class="text-sm text-gray-500">${date}</p></div></li>`;
        });
      } catch (e) {
        if (e !== BreakException) throw e;
      }

      if (announcements.items.length > 0)
      {
        let announcement = announcements.items[0];

        let title = announcement.title;
        let date = new Date(announcement.date_modified).toLocaleString();
        let link = announcement.url;
        let summary = announcement.content_html;

        document.getElementById("wpradioAnnouncementReadMore").href = link;
        document.getElementById("wpradioAnnouncementDate").innerHTML = date;
        document.getElementById('wpradioAnnouncementContent').innerHTML = summary;
        document.getElementById('wpradioAnnouncementContainer').classList.remove('hidden');
      }

    });
}

export default fetchFeeds;
