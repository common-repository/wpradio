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
<div x-data="channelSelector('PodcastsPanel')" >

	<div x-show="$store.app.state==='ready' && $store.account.data.subscription.podcasts_recorder===null"
		 class=" rounded-lg bg-white shadow sm:rounded-lg">
		<div class="px-4 py-5 sm:p-6">
			<h3 class="text-lg leading-6 font-medium text-gray-900">
				<?= __( 'Podcasts Recorder Disabled', 'wpradio' ); ?>
			</h3>
			<div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
				<p>
					<?= __( 'Your current plan does not support the podcasts recorder functionality.', 'wpradio' ); ?>
					<br/>
				</p>
			</div>
			<div class="mt-3 text-sm leading-5">
				<span class="inline-flex rounded-md shadow-sm">
				  <a href="<?= WPRadio\Admin\Helpers::getRegistrationLink( $this->settings->getAffiliateID() ) ?>" target="_blank" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-100 active:bg-indigo-700 transition ease-in-out duration-150">
					<?= __( 'Upgrade your plan', 'wpradio' ); ?>
				  </a>
				</span>
			</div>
		</div>
	</div>

	<div x-data="podcastsTable" x-show="$store.app.state==='ready' && $store.account.data.subscription.podcasts_recorder!==null"
		 class="bg-white overflow-hidden shadow rounded-lg">

		<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
			<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
				<div class="ml-4 mt-4 flex items-center">

					<div>
						<h3 class="text-lg leading-6 font-medium text-gray-900">
							<?= __( 'Recorded Podcasts', 'wpradio' ); ?>
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
		<div x-show="playing!==null" class="podcasts-player flex items-center"
			 x-transition:enter="transition-transform transition-opacity ease-out duration-300"
			 x-transition:enter-start="opacity-0 transform -translate-y-2"
			 x-transition:enter-end="opacity-100 transform translate-y-0"
			 x-transition:leave="transition ease-in duration-300"
			 x-transition:leave-end="opacity-0 transform -translate-y-3">
			<audio id="player" preload="none" controls>
			</audio>
			<span @click="closePlayer()" class="cursor-pointer mr-3">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
						 stroke="currentColor">
					  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
					</svg>
				</span>
		</div>
		<div class="overflow-scroll sm:overflow-auto">

			<table class="min-w-full divide-y divide-gray-200 truncate">
				<thead>
				<tr class="">
					<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
						<?= __( 'Name', 'wpradio' ); ?>
					</th>
					<th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
						<?= __( 'Recorded At', 'wpradio' ); ?>
					</th>
					<th class="px-6 py-3 bg-gray-50"></th>
				</tr>
				</thead>
				<tbody x-show="tableState==='loading'" class="bg-white divide-y divide-gray-200">
				<tr class="">
					<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-gray-900">
						<div class="cp-line w-20">
						</div>
					</td>
					<td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-500">
						<div class="cp-line w-32">
						</div>
					</td>
					<td class="px-6 py-4 whitespace-nowrap text-right text-sm leading-5 font-medium">
						<div class="cp-line w-10">
						</div>
					</td>
				</tr>
				</tbody>
				<tbody id="wpradio_podcastsTableBody" x-show="tableState==='ready' && podcasts.length>0"
					   class="bg-white divide-y divide-gray-200">
				<template x-for="podcast in podcasts" :key="podcast.id">
					<tr class="" :class="{'bg-indigo-100' : playing===podcast.id}">
						<td class="podcasts-table-name-field px-6 py-1 sm:py-4 whitespace-normal text-sm leading-5 font-medium text-gray-900 break-all">
							<div x-show="editing!==podcast.id" x-text="podcast.name" :title="podcast.name"
								 class="truncate"></div>
							<div x-show="editing===podcast.id" class="mt-1 flex rounded-md shadow-sm items-center ">
								<div class="relative flex-grow focus-within:z-10">
									<input minlength="5" maxlength="150" :id="'podcastTitleInput-' + podcast.id"
										   :placeholder="podcast.name"
										   class="block w-full rounded-none px-4 py-2 border border-gray-300 text-sm leading-5 rounded-l-md pl-2 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
								</div>
								<button
									@click="if (currentAction===null && document.getElementById('podcastTitleInput-' + podcast.id).value.length!==0) { updateTitle(selectedKey, podcast.id, document.getElementById('podcastTitleInput-' + podcast.id).value); }"
									class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
									<span x-show="currentAction===null"><?= __( 'Update', 'wpradio' ); ?></span>
									<svg x-show="currentAction!==null" class="animate-spin h-5 w-5 text-gray "
										 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
										<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
												stroke-width="4"></circle>
										<path class="opacity-75" fill="currentColor"
											  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
									</svg>
								</button>
								<div @click="if(currentAction===null) { editing=null; }" class="cursor-pointer ml-2">
									<svg class="h-5 w-5 text-red-500 " xmlns="http://www.w3.org/2000/svg" fill="none"
										 viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
									</svg>
								</div>
							</div>
						</td>
						<td x-text="podcast.recorded_at"
							class="px-6  py-1 sm:py-4 whitespace-nowrap text-sm leading-5 text-gray-500">
						</td>
						<td class="px-6  py-1 sm:py-4 whitespace-nowrap text-right text-sm leading-5 font-medium">
							<div class="flex justify-end">

								<span @click="play(podcast.id)" class="ml-3 cursor-pointer">
									<svg class="h-6 w-6 text-gray-400 hover:text-gray-500 focus:text-gray-500"
										 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
										 stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											  d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
											  d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
									</svg>
								</span>

								<span
									@click="if (currentAction===null) { document.getElementById('podcastTitleInput-' + podcast.id).value = ''; editing = podcast.id; }"
									class="ml-3 cursor-pointer">
									<svg class="ml-4 h-6 w-6 text-gray-400 hover:text-gray-500 focus:text-gray-500"
										 viewBox="0 0 20 20" fill="currentColor">
										<path
											d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
										<path fill-rule="evenodd"
											  d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
											  clip-rule="evenodd"></path>
									</svg>
								</span>

								<span @click="if (currentAction===null) { deletePodcast(selectedKey, podcast.id) }"
									  class="ml-3 cursor-pointer">
									<svg x-show="deleting !== podcast.id"
										 class="ml-4 h-6 w-6 text-gray-400 hover:text-gray-500 focus:text-gray-500"
										 viewBox="0 0 20 20" fill="currentColor">
										<path fill-rule="evenodd"
											  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
											  clip-rule="evenodd"></path>
									</svg>
									<svg x-show="deleting === podcast.id" class="animate-spin h-6 w-6 text-gray "
										 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
										<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
												stroke-width="4"></circle>
										<path class="opacity-75" fill="currentColor"
											  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
									</svg>
								</span>

							</div>
						</td>
					</tr>
				</template>
				</tbody>
				<tbody
					x-show="tableState==='empty' || (podcasts.length === 0 && tableState!=='loading' && tableState!=='error')"
					class="bg-white divide-y divide-gray-200">
				<tr>
					<td colspan="5"
						class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium text-center text-gray-800">
						<?= __( 'No Podcasts', 'wpradio' ); ?>
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
</div>
