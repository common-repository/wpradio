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
<div class="bg-white overflow-hidden shadow rounded-lg mt-5">
	<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
		<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
			<div class="ml-4 mt-4 flex items-center">
				<div class="mr-3">
					<div class="bg-gray-400 flex-shrink-0 rounded-md p-3">
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
						<?= __( 'Listeners', 'wpradio' ); ?>
					</h3>
				</div>
			</div>

			<div class="ml-4 mt-4 flex items-center">
						<span class="inline-flex rounded-md shadow-sm">
						  <a href="<?= admin_url( 'admin.php?page=wpradio_listeners' ) ?>" type="button"
							 class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring ring-blue-100 active:bg-indigo-700 transition ease-in-out duration-150">
							<svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
								 viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									  d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
							</svg>
							<?= __( 'Detailed View', 'wpradio' ); ?>
						  </a>
						</span>
			</div>
		</div>

	</div>
	<div
		x-show="$store.server.data!==null && $store.server.data.source!==null && Object.keys($store.server.data.source).length>0"
		class="px-4 py-5 sm:p-6" x-transition:enter="transition-transform transition-opacity ease-out duration-300"
		x-transition:enter-start="opacity-0 transform -translate-y-2"
		x-transition:enter-end="opacity-100 transform translate-y-0"
		x-transition:leave="transition ease-in duration-300"
		x-transition:leave-end="opacity-0 transform -translate-y-3">
		<div class="listeners-chart-container" style="position: relative;height:350px; width:100%;">
			<canvas id="listeners-chart"></canvas>
		</div>
	</div>
</div>
