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
<div x-data="channelSelector('ListenersPanel')" class="bg-white overflow-hidden shadow rounded-lg">

	<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
		<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
			<div class="ml-4 mt-4 flex items-center">

				<div>
					<h3 class="text-lg leading-6 font-medium text-gray-900">
						<?= __( 'Listeners', 'wpradio' ); ?>
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
	<div x-show="$store.server.channelIsOnline(selectedKey)" class="overflow-scroll sm:overflow-auto"
		 x-transition:enter="transition-transform transition-opacity ease-out duration-300"
		 x-transition:enter-start="opacity-0 transform -translate-y-2"
		 x-transition:enter-end="opacity-100 transform translate-y-0"
		 x-transition:leave="transition ease-in duration-300"
		 x-transition:leave-end="opacity-0 transform -translate-y-3">

		<table x-data="listenersTable" class="min-w-full divide-y divide-gray-200 truncate">
			<thead>
			<tr>
				<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
					<?= __( 'IP', 'wpradio' ); ?>
				</th>
				<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
					<?= __( 'Conneceted', 'wpradio' ); ?>
				</th>
				<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
					<?= __( 'Lag', 'wpradio' ); ?>
				</th>
				<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
					<?= __( 'User Agent', 'wpradio' ); ?>
				</th>
				<th class="px-6 py-3 bg-gray-50"></th>
			</tr>
			</thead>
			<tbody x-show="tableState==='loading'" class="bg-white divide-y divide-gray-200">
			<tr>
				<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-gray-900">
					<div class="cp-line w-32">
					</div>
				</td>
				<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">
					<div class="cp-line w-20">
					</div>
				</td>
				<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">
					<div class="cp-line w-10">
					</div>
				</td>
				<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">
					<div class="cp-line w-56">
					</div>
				</td>
				<td class="px-6 py-4 whitespace-nowrap text-right text-sm leading-5 font-medium">
					<div class="cp-line w-10">
					</div>
				</td>
			</tr>
			</tbody>
			<tbody id="wpradio_listenersTableBody" x-show="tableState==='ready' && listeners.length>0"
				   class="bg-white divide-y divide-gray-200">
			<template x-for="(listener, index) in listeners" :key="listener.id">
				<tr>
					<td x-text="listener.ip"
						class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-gray-900">

					</td>
					<td x-text="listener.duration"
						class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">

					</td>
					<td x-text="listener.lag" class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">

					</td>
					<td x-text="(typeof listener.agent.os.name!=='undefined' && typeof listener.agent.browser.name!=='undefined') ? listener.agent.os.name + ',' + listener.agent.browser.name : listener.agent.ua"
						class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">

					</td>
					<td class="px-6 py-4 whitespace-nowrap text-right text-sm leading-5 font-medium">
						<a @click="kickListener(selectedKey, listener.id)" href="#">Kick</a>
					</td>
				</tr>
			</template>
			</tbody>
			<tbody
				x-show="tableState==='empty' || (listeners.length === 0 && tableState!=='loading' && tableState!=='error')"
				class="bg-white divide-y divide-gray-200">
			<tr>
				<td colspan="5"
					class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-center text-gray-800">
					<?= __( 'No Listeners', 'wpradio' ); ?>
				</td>
			</tr>
			</tbody>
			<tbody x-show="tableState==='error'" class="bg-white divide-y divide-gray-200">
			<tr>
				<td colspan="5"
					class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-center text-gray-800">
					<?= __( 'An error occurred, please try again later.', 'wpradio' ); ?>
				</td>
			</tr>
			</tbody>
		</table>

	</div>

</div>
