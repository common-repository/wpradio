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

Alpine.effect(() => {
  const value = Alpine.store('alert').type;

  if (value === null) {
    document.getElementsByTagName('BODY')[0].classList.remove('overflow-hidden');
  } else {
    document.getElementsByTagName('BODY')[0].classList.add('overflow-hidden');
  }
});

const Alert = function(type = null, title = null, message = null, buttons = [], code = null, fatal = false) {
  Alpine.store('alert').type = type;
  setTimeout(() => {
    Alpine.store('alert').title = title;
    Alpine.store('alert').message = message;
    Alpine.store('alert').buttons = buttons;
    Alpine.store('alert').code = code;
    Alpine.store('alert').fatal = fatal;
  }, 60);
};

export default Alert;

/*
const Alert = function(alertStore){

  return {
    overlay: {
      ['x-show']() {
        return alertStore.type!==null;
      },
      ['x-transition:enter']() {
        return "ease-out duration-300";
      },
      ['x-transition:enter-start']() {
        return "opacity-0";
      },
      ['x-transition:enter-end']() {
        return "opacity-100";
      },
      ['x-transition:leave']() {
        return "ease-in duration-200";
      },
      ['x-transition:leave-start']() {
        return "opacity-100";
      },
      ['x-transition:leave-end']() {
        return "opacity-0";
      },
    },

    modal: {
      ['x-show']() {
        return alertStore.type!==null;
      },
      ['x-transition:enter']() {
        return "ease-out duration-300";
      },
      ['x-transition:enter-start']() {
        return "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95";
      },
      ['x-transition:enter-end']() {
        return "opacity-100 translate-y-0 sm:scale-100";
      },
      ['x-transition:leave']() {
        return "ease-in duration-200";
      },
      ['x-transition:leave-start']() {
        return "opacity-100 translate-y-0 sm:scale-100";
      },
      ['x-transition:leave-end']() {
        return "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95";
      },
    },

    iconContainer: {
      [':class']() {
        return { 'bg-green-100': alertStore.type==='ok', 'bg-red-100': alertStore.type==='error', 'bg-orange-100': alertStore.type==='warning' };
      },
    },

    iconSvg: {
      ['x-show']() {
        return alertStore.type!==null;
      },
      [':class']() {
        return { 'text-red-600': alertStore.type==='error', 'text-orange-600': alertStore.type==='warning' , 'text-green-600': alertStore.type==='ok' };
      },

    }

  }

};

module.exports = Alert;
*/
