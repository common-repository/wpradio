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
<div class="bg-white shadow rounded-lg">
	<div x-data="{}" class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6 rounded-t-lg">
		<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
			<div class="ml-4 mt-4 flex items-center">

				<div class="mr-3">
					<div :class="{ 'bg-green-500': $store.server.data!==null, 'bg-red-500': $store.server.data===null }"
						 class="flex-shrink-0 rounded-md p-3">
						<template x-if="$store.server.state!=='loading'">
							<svg class="h-5 w-5 text-white"
								 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									  d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
							</svg>
						</template>
						<template x-if="$store.server.state==='loading'">
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
						<?= __( 'Streaming Server', 'wpradio' ); ?> <span
							x-text="($store.server.data===null)?'<?= __( 'Offline', 'wpradio' ); ?>':'<?= __( 'Online', 'wpradio' ); ?>'"
							:class="{ 'bg-green-100 text-green-800': $store.server.data!==null, 'bg-red-100 text-red-800': $store.server.data===null }"
							class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 "> </span>
					</h3>
				</div>

			</div>
			<div class="ml-4 mt-4 flex-shrink-0">
						<span class="inline-flex rounded-md shadow-sm">
						  <button x-bind:disabled="$store.server.actionState==='inprogress'"
								  x-on:click="($store.server.data===null && $store.server.actionState==='ready') ? $store.server.actions.startServer() : $store.server.actions.stopServer() "
								  type="button" class="btn gray">
							  <span x-show="$store.server.actionState==='ready'"
									x-text="($store.server.data===null) ? '<?= __( 'Start Server', 'wpradio' ); ?>' : '<?= __( 'Stop Server', 'wpradio' ); ?>'"></span>
							  <svg x-show="$store.server.actionState!=='ready'" class="animate-spin h-5 w-5 text-white "
								   xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
										stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor"
									  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							  </svg>
						  </button>
						</span>
			</div>
		</div>
	</div>
	<div class="px-4 py-5 sm:p-6">

		<dl class="mb-5 grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-4">
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500 flex">
					<?= __( 'Server Hostname', 'wpradio' ); ?>
				</dt>
				<dd
					class="mt-1 text-sm leading-5 text-gray-900">
					<div class="mt-1 flex rounded-md shadow-sm ">
						<div class="relative flex items-stretch flex-grow focus-within:z-10">
							<input readonly id="wpradio-stream-hostname" x-bind:value="($store.account.state==='loaded') ? $store.account.data.streaming_server.domain : ''" type="text"
								   class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
						</div>
						<button data-clipboard-target="#wpradio-stream-hostname" class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
								 viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
							</svg>
						</button>
					</div>
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500 flex">
					<?= __( 'Server Port', 'wpradio' ); ?>
				</dt>
				<dd
					class="mt-1 text-sm leading-5 text-gray-900">
					<div class="mt-1 flex rounded-md shadow-sm ">
						<div class="relative flex items-stretch flex-grow focus-within:z-10">
							<input readonly id="wpradio-stream-port" x-bind:value="($store.account.state==='loaded') ? $store.account.data.streaming_server_port : ''" type="text" class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
						</div>
						<button data-clipboard-target="#wpradio-stream-port"
								class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
							</svg>
						</button>
					</div>
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500">
					<?= __( 'Server Start Time', 'wpradio' ); ?>
				</dt>
				<dd
					x-text="($store.server.data!==null) ? $store.server.formatIcecastDate($store.server.data.server_start_iso8601) : '--'"
					class="mt-1 text-sm leading-5 text-gray-900">
				</dd>
			</div>
			<div class="sm:col-span-1">
				<dt class="text-sm leading-5 font-medium text-gray-500 flex">
					<?= __( 'Broadcast Username', 'wpradio' ); ?>
				</dt>
				<dd class="mt-1 text-sm leading-5 text-gray-900">
					<div class="mt-1 flex rounded-md shadow-sm ">
						<div class="relative flex items-stretch flex-grow focus-within:z-10">
							<input readonly id="wpradio-stream-username" value="source" type="text" class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
						</div>
						<button data-clipboard-target="#wpradio-stream-username"
								class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
							</svg>
						</button>
					</div>
				</dd>
			</div>
		</dl>

		<div class="space-y-1" x-data="channelSelector('ServerPanel')">
			<dl class="p-3 border border-gray-200 rounded-md grid grid-cols-1 grid-rows-1 gap-4 sm:grid-cols-4">
				<div
					class="bg-gray-50 sm:col-span-2 sm:row-span-2 items-center rounded-md -m-3 p-3 sm:m-0 sm:p-0 sm:pt-4 sm:pl-4 sm:pr-4 sm:rounded-none">
					<dt class="text-sm leading-5 font-medium text-gray-500">
						<?= __( 'Channel', 'wpradio' ); ?>
					</dt>
					<dd class="mt-1 text-sm leading-5 text-gray-900">
						<?php require( 'wpradio-all-channels-select-template.php' ) ?>
					</dd>
				</div>
				<div class="sm:col-span-1">
					<dt class="text-sm leading-5 font-medium text-gray-500 flex">
						<?= __( 'Mount-Point', 'wpradio' ); ?>
					</dt>
					<dd
						class="mt-1 text-sm leading-5 text-gray-900">
						<div class="mt-1 flex rounded-md shadow-sm ">
							<div class="relative flex items-stretch flex-grow focus-within:z-10">
								<input readonly id="wpradio-stream-mount" x-bind:value="($store.account.state==='loaded' && selectedKey!==null) ? '/' + $store.account.data.channels[selectedKey].streaming_server_mountpoint : '--'" type="text" class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							</div>
							<button data-clipboard-target="#wpradio-stream-mount"
									class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
								<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
								</svg>
							</button>
						</div>
					</dd>
				</div>
				<div class="sm:col-span-1" x-data="broadcastPassword">
					<dt class="text-sm leading-5 font-medium text-gray-500 flex">
						<?= __( 'Broadcast Password', 'wpradio' ); ?>
						<div @keydown.escape="dropDownOpen = false" @click.outside="dropDownOpen = false"
							 class="ml-2 relative inline-block text-left">
							<div>
								<button @click="dropDownOpen = !dropDownOpen"
										class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
										aria-label="Options" id="options-menu" aria-haspopup="false"
										aria-expanded="true" x-bind:aria-expanded="dropDownOpen">
									<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
										<path
											d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
									</svg>
								</button>
							</div>

							<div x-show="dropDownOpen"
								 x-description="Dropdown panel, show/hide based on dropdown state."
								 x-transition:enter="transition ease-out duration-100"
								 x-transition:enter-start="transform opacity-0 scale-95"
								 x-transition:enter-end="transform opacity-100 scale-100"
								 x-transition:leave="transition ease-in duration-75"
								 x-transition:leave-start="transform opacity-100 scale-100"
								 x-transition:leave-end="transform opacity-0 scale-95"
								 class="z-50 origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg">
								<div class="rounded-md bg-white ring-1 ring-black ring-opacity-5">
									<div class="py-1" role="menu" aria-orientation="vertical"
										 aria-labelledby="options-menu">
										<a @click="dropDownOpen = false; modalOpen = true;"
										   class="block cursor-pointer px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
										   role="menuitem">
											<span><?= __( 'Regenerate Password', 'wpradio' ); ?></span>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div x-show="modalOpen" class="fixed z-10 inset-0 overflow-y-auto">
							<div
								class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
								<div x-show="modalOpen"
									 x-description="Background overlay, show/hide based on modal state."
									 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
									 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
									 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
									 class="fixed inset-0 transition-opacity">
									<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
								</div>

								<span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
								<div x-show="modalOpen" x-description="Modal panel, show/hide based on modal state."
									 x-transition:enter="ease-out duration-300"
									 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
									 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
									 x-transition:leave="ease-in duration-200"
									 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
									 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
									 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
									 role="dialog" aria-modal="true" aria-labelledby="modal-headline">
									<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
										<div class="sm:flex sm:items-start">
											<div
												class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
												<svg class="h-6 w-6 text-red-600"
													 x-description="Heroicon name: exclamation" fill="none"
													 viewBox="0 0 24 24" stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round"
														  stroke-width="2"
														  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
												</svg>
											</div>
											<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
												<h3 class="text-lg leading-6 font-medium text-gray-900">
													<?= __( 'Regenerate Broadcast Password', 'wpradio' ); ?>
												</h3>
												<div class="mt-2">
													<p class="text-sm leading-5 text-gray-500">
														<?= __( 'You are about to generate a new broadcast password for this channel, once finished the server will be shutdown.<br />Please note that you will need to reconfigure your broadcast software with the new password.', 'wpradio' ); ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
									  <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
										<button @click="regeneratePassword(selectedKey)" x-bind:disabled="loading"
												type="button"
												class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring ring-red-100 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
											  <svg x-show="loading" class="animate-spin h-5 w-5 text-white "
												   xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
												<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
														stroke-width="4"></circle>
												<path class="opacity-75" fill="currentColor"
													  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
											  </svg>
											<div x-show="!loading">
											  <?= __( 'Proceed', 'wpradio' ); ?>
										  </div>
										</button>
									  </span>
										<span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
										<button @click="modalOpen = loading" type="button" x-bind:disabled="loading"
												class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-100 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
										  <?= __( 'Cancel', 'wpradio' ); ?>
										</button>
									  </span>
									</div>
								</div>
							</div>
						</div>

					</dt>
					<dd class="mt-1 text-sm leading-5 text-gray-900">
						<div class="mt-1 flex rounded-md shadow-sm ">
							<div class="relative flex items-stretch flex-grow focus-within:z-10">
								<input readonly id="wpradio-stream-password" x-bind:value="($store.account.state==='loaded' && selectedKey!==null) ? $store.account.data.channels[selectedKey].streaming_server_password : '--'" type="text" class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							</div>
							<button data-clipboard-target="#wpradio-stream-password"
									class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
								<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
								</svg>
							</button>
						</div>
					</dd>
				</div>
				<div class="sm:col-span-2">
					<dt class="text-sm leading-5 font-medium text-gray-500 flex">
						<?= __( 'Direct Stream Link', 'wpradio' ); ?>
					</dt>
					<dd
						class="mt-1 text-sm leading-5 text-gray-900 truncate sm:break-normal pb-2 pl-1">
						<div class="mt-1 flex rounded-md shadow-sm">
							<div class="relative flex items-stretch flex-grow focus-within:z-10">
								<input :class="{'text-yellow-700 font-medium':!($store.account.state==='loaded' && selectedKey!==null && $store.account.data.subscription.protected_stream===false)}" readonly id="wpradio-stream-link" x-bind:value="($store.account.state==='loaded' && selectedKey!==null && $store.account.data.subscription.protected_stream===false) ? 'https://' + $store.account.data.streaming_server.domain  +  ':' + $store.account.data.streaming_server_port + '/' + $store.account.data.channels[selectedKey].streaming_server_mountpoint : '<?= __( 'Upgrade your plan to unlock', 'wpradio' ); ?>'" type="text" class="block w-full rounded-none rounded-l-md pl-2 text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							</div>
							<button data-clipboard-target="#wpradio-stream-link"
									class="copybutton -ml-px relative inline-flex items-center space-x-2 px-2 py-1 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
								<svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
								</svg>
							</button>
						</div>
					</dd>
				</div>
			</dl>
		</div>
	</div>
</div>
