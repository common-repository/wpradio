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
<div x-data="channelSelector('BroadcastPanel')""
	 class="bg-white overflow-hidden shadow rounded-lg mt-5">
	<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
		<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
			<div class="ml-4 mt-4 flex items-center">

				<div class="mr-3">
					<div

						:class="{ 'bg-green-500': ($store.server.onlineChannels()==='all'), 'bg-red-500': ($store.server.onlineChannels()==='none'), 'bg-orange-400': ($store.server.onlineChannels()==='some') }"
						class="flex-shrink-0 rounded-md p-3">

						<template x-if="$store.server.state!='loading'">
							<svg class="h-5 w-5 text-white"
								 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									  d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
							</svg>
						</template>
						<template x-if="$store.server.state=='loading'">
							<svg class="animate-spin h-5 w-5 text-white "
								 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
										stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor"
									  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
						</template>

					</div>
				</div>
				<div>
					<h3 class="text-lg leading-6 font-medium text-gray-900">
						<?= __( 'Broadcast', 'wpradio' ); ?> <span x-text="$store.server.onlineChannelsText()"
																   :class="{ 'bg-green-100 text-green-800': ($store.server.onlineChannels()==='all'), 'bg-red-100 text-red-800': ($store.server.onlineChannels()==='none'), 'bg-orange-100 text-orange-800': ($store.server.onlineChannels()==='some') }"
																   class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 "> </span>
					</h3>
				</div>


			</div>
			<div class="ml-4 mt-4 flex flex-wrap items-center w-full sm:w-auto truncate">
				<span class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Channel', 'wpradio' ); ?>:
				</span>
				<span class="mt-1 ml-0 sm:ml-5 text-sm leading-5 text-gray-900 truncate">
					<?php require( 'wpradio-all-channels-select-template.php' ) ?>
				</span>
			</div>
		</div>

	</div>
	<div x-show="$store.server.channelIsOnline(selectedKey)" class="px-4 py-5 sm:p-6"
		 x-transition:enter="transition-transform transition-opacity ease-out duration-300"
		 x-transition:enter-start="opacity-0 transform -translate-y-2"
		 x-transition:enter-end="opacity-100 transform translate-y-0"
		 x-transition:leave="transition ease-in duration-300"
		 x-transition:leave-end="opacity-0 transform -translate-y-3">
		<dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-6">
			<div class="sm:col-span-1 flex flex-start">
				<div>
					<dt class="text-sm items-center flex leading-5 font-medium text-gray-500">
						<div>
							<?= __( 'Source IP', 'wpradio' ); ?>
						</div>
						<div x-data="{ open: false, loading:false }"
							 @keydown.escape="open = false" @click.outside="open = false"
							 class="ml-2 relative inline-block text-left">
							<div>
								<button @click="open = !open"
										class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
										aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true"
										x-bind:aria-expanded="open">
									<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
										<path
											d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
									</svg>
								</button>
							</div>

							<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state."
								 x-transition:enter="transition ease-out duration-100"
								 x-transition:enter-start="transform opacity-0 scale-95"
								 x-transition:enter-end="transform opacity-100 scale-100"
								 x-transition:leave="transition ease-in duration-75"
								 x-transition:leave-start="transform opacity-100 scale-100"
								 x-transition:leave-end="transform opacity-0 scale-95"
								 class="z-50 origin-top-left absolute left-0 mt-2 w-32 rounded-md shadow-lg">
								<div class="rounded-md bg-white ring-1 ring-black ring-opacity-5">
									<div class="py-1" role="menu" aria-orientation="vertical"
										 aria-labelledby="options-menu">
										<a @click="if (loading===false) { loading = true; $store.server.actions.dropSource(selectedKey).finally(function () { open = false; loading = false; }) }"
										   class="block cursor-pointer px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
										   role="menuitem">
											<span
												x-show="loading === false"><?= __( 'Drop Source', 'wpradio' ); ?></span>
											<svg x-show="loading === true"
												 class="mx-auto animate-spin h-5 w-5 text-gray "
												 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
												<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
														stroke-width="4"></circle>
												<path class="opacity-75" fill="currentColor"
													  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
											</svg>
										</a>
									</div>
								</div>
							</div>
						</div>
					</dt>
					<dd x-text="$store.server.channelIsOnline(selectedKey) ? $store.server.data.source[selectedKey].source_ip : ''"
						class="mt-1 text-sm leading-5 text-gray-900">
					</dd>
				</div>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Encoding', 'wpradio' ); ?>
				</dt>
				<dd x-text="$store.server.channelIsOnline(selectedKey) ? $store.server.data.source[selectedKey].server_type : ''"
					class="mt-1 text-sm leading-5 text-gray-900">
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Bitrate', 'wpradio' ); ?>
				</dt>
				<dd x-text="$store.server.channelIsOnline(selectedKey) ? Math.ceil($store.server.data.source[selectedKey].total_bitrate / 1000) + 'Kbps': ''"
					class="mt-1 text-sm leading-5 text-gray-900">
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Recorder Status', 'wpradio' ); ?>
				</dt>
				<dd x-text="($store.server.channelIsOnline(selectedKey) && $store.account.data.subscription.podcasts_recorder!==null) ? $store.server.recorderStatus(selectedKey) : '<?= __( 'Upgrade your plan to unlock', 'wpradio' ); ?>'"
					:class="{'text-orange-500':($store.server.channelIsOnline(selectedKey) && $store.account.data.subscription.podcasts_recorder===null)}"
					class="mt-1 text-sm leading-5">
				</dd>
			</div>
			<div class="sm:col-span-2">
				<dt class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Stream Start Time', 'wpradio' ); ?>
				</dt>
				<dd
					x-text="$store.server.channelIsOnline(selectedKey) ? $store.server.formatIcecastDate($store.server.data.source[selectedKey].stream_start_iso8601) : ''"
					class="mt-1 text-sm leading-5 text-gray-900">
				</dd>
			</div>

			<div x-data="{ open: false, inputOpen: false, loading:false }"
				 class="sm:col-span-4">
				<dt class="items-center flex text-sm leading-5 font-medium text-gray-500">
					<div>
						<?= __( 'Now Playing', 'wpradio' ); ?>
					</div>
					<div @keydown.escape="open = false" @click.outside="open = false"
						 class="ml-2 relative inline-block text-left">
						<div>
							<button @click="open = !open"
									class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
									aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true"
									x-bind:aria-expanded="open">
								<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
									<path
										d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
								</svg>
							</button>
						</div>

						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state."
							 x-transition:enter="transition ease-out duration-100"
							 x-transition:enter-start="transform opacity-0 scale-95"
							 x-transition:enter-end="transform opacity-100 scale-100"
							 x-transition:leave="transition ease-in duration-75"
							 x-transition:leave-start="transform opacity-100 scale-100"
							 x-transition:leave-end="transform opacity-0 scale-95"
							 class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg">
							<div class="rounded-md bg-white ring-1 ring-black ring-opacity-5">
								<div class="py-1" role="menu" aria-orientation="vertical"
									 aria-labelledby="options-menu">
									<a @click="open = false; inputOpen = true;"
									   class="block cursor-pointer px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
									   role="menuitem">
										<span><?= __( 'Update Metadata', 'wpradio' ); ?></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</dt>
				<dd class="mt-2 text-sm leading-5 text-gray-900">
					<div x-show="!inputOpen"
						 x-text="($store.server.channelIsOnline(selectedKey) && $store.server.data.source[selectedKey]['display-title'] !== '') ? $store.server.data.source[selectedKey]['display-title'] : '--'"
						 class="text-sm leading-5 text-gray-900"></div>
					<div x-show="inputOpen" class="mt-1 flex rounded-md shadow-sm items-center">
						<div class="relative flex-grow focus-within:z-10">
							<input maxlength="26" x-ref="currentSongInput"
								   :placeholder="$store.server.channelIsOnline(selectedKey) ? $store.server.data.source[selectedKey]['display-title'] : ''"
								   id="email"
								   class="block w-full rounded-none px-4 py-2 border border-gray-300 text-sm leading-5 rounded-l-md pl-2 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
						</div>
						<button
							@click="if (loading===false) { loading = true; $store.server.actions.updateMetadata(selectedKey,$refs.currentSongInput.value).finally(function () { inputOpen = false, open = false; loading = false; }) }"
							class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
							<span x-show="!loading"><?= __( 'Update', 'wpradio' ); ?></span>
							<svg x-show="loading" class="animate-spin h-5 w-5 text-gray "
								 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
										stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor"
									  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
						</button>
						<div @click="open = false; inputOpen = false;" class="cursor-pointer ml-2">
							<svg class="h-5 w-5 text-red-500 " xmlns="http://www.w3.org/2000/svg" fill="none"
								 viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
							</svg>
						</div>
					</div>
				</dd>
			</div>
			<div class="sm:col-span-2 flex">
				<dt class="text-sm leading-5 font-medium text-gray-500 mr-3">
					<?= __( 'Player', 'wpradio' ); ?>:
				</dt>
				<dd class="mt-1 text-sm leading-5 text-gray-900">
				<span
					x-show="$store.server.channelIsOnline(selectedKey) && $store.account.data.subscription.protected_stream">
					<a href="#"
					   x-on:click.prevent="window.open('<?= admin_url( 'admin-post.php?action=wpradio_player&channelId=', 'admin' ) ?>' + $store.account.data.channels[selectedKey].id, '_blank', 'toolbar=false,scrollbars=false,resizable=yes,width=600,height=148');"
					   class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring ring-indigo-100 active:bg-gray-700 transition ease-in-out duration-150">
						<svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
							 viewBox="0 0 24 24" stroke="currentColor">
						  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
						</svg>
						<?= __( 'Open Stream Player', 'wpradio' ); ?>
					</a>
				</span>
					<audio id="wpradio-streamplayer"
						   x-show="$store.server.channelIsOnline(selectedKey) && !$store.account.data.subscription.protected_stream"
						   preload="none" controls
						   x-bind:src="( $store.server.channelIsOnline(selectedKey) && !$store.account.data.subscription.protected_stream ) ? $store.server.serverUrl(selectedKey) : ''"
						   x-bind:type="( $store.server.channelIsOnline(selectedKey) && !$store.account.data.subscription.protected_stream ) ? $store.server.data.source[selectedKey].server_type : ''">
						Your browser does not support the audio element.
					</audio>

				</dd>
			</div>
		</dl>

	</div>
</div>
