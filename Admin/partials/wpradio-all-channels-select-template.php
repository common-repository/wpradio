<?php
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

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<span class="inline-block w-full rounded-md shadow-sm">
	<button x-ref="button" @keydown.arrow-up.stop.prevent="onButtonClick()"
			@keydown.arrow-down.stop.prevent="onButtonClick()" @click="onButtonClick()" type="button"
			aria-haspopup="listbox" :aria-expanded="opened" aria-labelledby="listbox-label"
			class="cursor-default relative w-full rounded-md border border-gray-300 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5 flex items-center space-x-3">

		<span :aria-label="$store.server.channelIsOnline(selectedKey) ? 'Online' : 'Offline'"
			  :class="{ 'bg-blue-400': selectedKey === '', 'bg-green-400': (selectedKey !== '' && $store.server.channelIsOnline(selectedKey)), 'bg-red-400': (selectedKey !== '' && !$store.server.channelIsOnline(selectedKey))}"
			  class="bg-green-400 flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>

		<span x-text="getSelectedChannelName()" class="block truncate"></span>
		<span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
			<svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
			  <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round"
					stroke-linejoin="round"></path>
			</svg>
		</span>
	</button>
</span>

<div x-show="opened" @click.outside="opened = false" x-description="Select popover, show/hide based on select state."
	 x-transition:enter="transition-transform transition-opacity ease-out duration-300"
	 x-transition:enter-start="opacity-0 transform -translate-y-2"
	 x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300"
	 x-transition:leave-end="opacity-0 transform -translate-y-3"
	 class="absolute mt-1 rounded-md bg-white shadow-lg z-50" style="display: none;">

	<ul @keydown.enter.stop.prevent="onOptionSelect()" @keydown.space.stop.prevent="onOptionSelect()"
		@keydown.escape="onEscape()" <?php //@keydown.arrow-up.prevent="onArrowUp()" @keydown.arrow-down.prevent="onArrowDown()" ?>
		x-ref="listbox" tabindex="-1" role="listbox" aria-labelledby="listbox-label"
		class="max-h-60 rounded-md py-1 text-base leading-6 ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm sm:leading-5"
		aria-activedescendant="listbox-item-4">

		<?php
		if ( isset( $wpradio_channelSelectorAllowedEmpty ) && $wpradio_channelSelectorAllowedEmpty !== false ) {
			?>

			<li :id="'listbox-item-empty-' + scope" role="option" @click="onSelect('')" @mouseenter="highlighted = ''"
				@mouseleave="highlighted = null"
				:class="{ 'text-white bg-indigo-600': highlighted === '', 'text-gray-900': !(highlighted === '') }"
				class="text-gray-900 cursor-default select-none relative py-2 pl-4 pr-4 flex items-center space-x-3">
				<span class="bg-blue-400 flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>
				<span x-state:on="Selected" x-state:off="Not Selected"
					  :class="{ 'font-semibold': selectedKey === '', 'font-normal': !(selectedKey === '') }"
					  class="font-normal block truncate">
						<?= __( 'Multiple Channels', 'wpradio' ); ?>
					</span>
			</li>

			<?php
		}
		?>
		<template
			x-for="mountpoint in ($store.account.state==='loaded') ? Object.keys($store.account.data.channels) : [] "
			:key="mountpoint">

			<li :id="'listbox-item-' + mountpoint" role="option" @click="onSelect(mountpoint)"
				@mouseenter="highlighted = mountpoint" @mouseleave="highlighted = null"
				:class="{ 'text-white bg-indigo-600': highlighted === mountpoint, 'text-gray-900': !(highlighted === mountpoint) }"
				class="text-gray-900 cursor-default select-none relative py-2 pl-4 pr-4 flex items-center space-x-3">

				<span :aria-label="$store.server.channelIsOnline(mountpoint) ? 'Online' : 'Offline'"
					  :class="{ 'bg-green-400': $store.server.channelIsOnline(mountpoint), 'bg-red-400': !$store.server.channelIsOnline(mountpoint)}"
					  class="bg-green-400 flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>

				<span x-state:on="Selected" x-state:off="Not Selected"
					  :class="{ 'font-semibold': selectedKey === mountpoint, 'font-normal': !(selectedKey === mountpoint) }"
					  class="font-normal block truncate" x-text="$store.account.data.channels[mountpoint].name">
				</span>
			</li>

		</template>

	</ul>

</div>
