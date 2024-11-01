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
<div id="dashboardSidebar" class="w-full xl:w-1/3 mb-4 px-3">

	<div class="bg-white border-l-4 border-r-4 border-blue-400 rounded-lg p-4 mb-4">
		<div class="flex">
			<div class="flex-shrink-0">
				<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
				</svg>
			</div>
			<div class="ml-3">
				<p class="text-sm leading-5 text-blue-700">
					<span class="font-medium text-blue-700 hover:text-blue-600 transition ease-in-out duration-150">
						<?= __( 'Getting Started:', 'wpradio' ); ?>
					</span>
					<br />
					<?= __( 'Learn how to broadcast at the ', 'wpradio' ); ?>
					<a href="<?=WPRADIO_CASTERFMAPI_TUTORIALS;?>" target="_blank" class="font-medium underline text-blue-700 hover:text-blue-600 transition ease-in-out duration-150">
						<?= __( 'Tutorials Section', 'wpradio' ); ?>
					</a>
				</p>
			</div>
		</div>
	</div>

	<div class="bg-white overflow-hidden shadow rounded-lg  mb-3">
		<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
			<h4 class="text-lg leading-6 font-medium text-gray-900">
				<?= __( 'News', 'wpradio' ); ?>
			</h4>
		</div>
		<div class="p-2">
			<ul id="sidebarNewsList" class="divide-y divide-gray-200">
			</ul>
		</div>
	</div>

	<div class="bg-white overflow-hidden shadow rounded-lg">
		<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6 flex justify-between items-center flex-wrap sm:flex-nowrap">
			<div>
				<h4 class="text-lg leading-6 font-medium text-gray-900">
					<?= __( 'Subscription', 'wpradio' ); ?>
				</h4>
			</div>
			<div>
				<a href="<?=WPRADIO_CASTERFMAPI_CONSOLE;?>" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-md leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-100 active:bg-indigo-700 transition ease-in-out duration-150">
					<?= __( 'Manage', 'wpradio' ); ?>
				</a>
			</div>
		</div>
		<div class="px-4 py-5 sm:p-4">
			<dl>
				<div class="sm:grid sm:grid-cols-4 sm:gap-4">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-2">
						<?= __( 'Plan', 'wpradio' ); ?>
					</dt>
					<dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
						<div
							x-text="($store.account.state==='loaded') ? $store.account.data.subscription.plan_name : ''"
							x-show="$store.account.state==='loaded'">
						</div>
					</dd>
				</div>
				<div class="mt-5 sm:grid sm:mt-3 sm:grid-cols-4 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-3">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-1">
						<?= __( 'Addons', 'wpradio' ); ?>
					</dt>
					<dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-3">
						<div
							x-show="$store.account.state==='loaded' && $store.account.data.subscription.addons_count > 0">
							<ul class="border border-gray-200 rounded-md">
								<template
									x-for="(addon, index) in ($store.account.state==='loaded') ? $store.account.data.subscription.addons : [] "
									:key="addon.addon_id + index">
									<li x-text="addon.quantity + ' - ' + addon.addon_name"
										:class="{'border-t border-gray-200 ': index > 0}"
										class="pl-3 pr-4 py-3 mb-0 items-center text-sm leading-5">
									</li>
								</template>
							</ul>
						</div>
					</dd>
				</div>
				<div class="mt-5 sm:grid sm:mt-3 sm:grid-cols-4 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-3">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-2">
						<?= __( 'Broadcast Channels', 'wpradio' ); ?>
					</dt>
					<dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
						<div
							x-text="($store.account.state==='loaded') ? $store.account.data.channels_count + ' / ' +  $store.account.data.subscription.max_channels : ''"
							x-show="$store.account.state==='loaded'">
						</div>
					</dd>
				</div>
				<div class="mt-5 sm:grid sm:mt-3 sm:grid-cols-4 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-3">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-2">
						<?= __( 'Listener Slots', 'wpradio' ); ?>
					</dt>
					<dd x-text="($store.account.state==='loaded') ? $store.account.data.subscription.listeners_slots : '' "
						class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
					</dd>
				</div>
				<div class="mt-8 sm:grid sm:mt-5 sm:grid-cols-4 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-2">
						<?= __( 'Max Quality/Bitrate', 'wpradio' ); ?>
					</dt>
					<dd x-text="($store.account.state==='loaded') ? $store.account.data.subscription.bitrate + ' Kbps' : '' "
						class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
					</dd>
				</div>
				<div class="mt-8 sm:grid sm:mt-5 sm:grid-cols-4 sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
					<dt class="text-sm leading-5 font-medium text-gray-500 sm:col-span-1">
						<?= __( 'Podcasts Recorder', 'wpradio' ); ?>
					</dt>

					<dd x-show="$store.account.state==='loaded' && $store.account.data.subscription.podcasts_recorder === null"
						class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-3">
						<div class="bg-yellow-50 border-l-4 border-r-4 border-yellow-400 p-3 rounded-lg">
							<div class="flex">
								<div class="flex-shrink-0">
									<!-- Heroicon name: exclamation -->
									<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
									</svg>
								</div>
								<div class="ml-3">
									<p class="text-sm leading-5 text-yellow-700">
										<span class="font-medium text-yellow-700 hover:text-yellow-600 transition ease-in-out duration-150">
											<?= __( 'Upgrade your subscription plan to unlock this feature', 'wpradio' ); ?>
										</span>
									</p>
								</div>
							</div>
						</div>
					</dd>
					<dd x-show="$store.account.state==='loaded' && $store.account.data.subscription.podcasts_recorder !== null"
						class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-3">
						<div class="border border-gray-200 rounded-md">
							<dl class="grid grid-cols-1 gap-x-4 gap-y-2">
								<div class="sm:col-span-1 grid grid-cols-4  p-2 pb-0">
									<dt class="text-sm leading-5 font-medium text-gray-500  col-span-3">
										<?= __( 'Maximum Recordings', 'wpradio' ); ?>
									</dt>
									<dd x-text="($store.account.state==='loaded' && $store.account.data.subscription.podcasts_recorder !== null) ? $store.account.data.subscription.podcasts_recorder.amount : '' "
										class="text-sm leading-5 text-gray-900  col-span-1">
									</dd>
								</div>
								<div class="sm:col-span-1 grid grid-cols-4  border-t border-gray-200 p-2">
									<dt class="text-sm leading-5 font-medium text-gray-500  col-span-3">
										<?= __( 'Maximum Duration', 'wpradio' ); ?>
									</dt>
									<dd x-text="($store.account.state==='loaded' && $store.account.data.subscription.podcasts_recorder !== null) ? $store.account.data.subscription.podcasts_recorder.duration + ' <?= __( 'Hour/s', 'wpradio' ); ?>': '' "
										class="text-sm leading-5 text-gray-900  col-span-1">
									</dd>
								</div>
							</dl>
						</div>
					</dd>
				</div>
			</dl>
		</div>
	</div>

</div>
