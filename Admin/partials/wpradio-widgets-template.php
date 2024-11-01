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

<div class="rounded-md bg-white border-l-4 border-r-4 border-blue-400 p-4 mb-5">
	<div class="flex">
		<div class="flex-shrink-0">
			<!-- Heroicon name: information-circle -->
			<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
				<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
			</svg>
		</div>
		<div class="ml-3">
			<p class="text-xs font-bold text-blue-500">
				<?= __( 'When using those widgets, 3 invisible links will be embedded into the page, those WILL NOT be displayed to your visitors.', 'wpradio' ); ?>
			</p>
		</div>
	</div>
</div>


<div x-data="channelSelector('newStream', true)" class="bg-white overflow-hidden shadow rounded-lg">
	<div x-data="widgetsGenerator('newStream')">
		<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
			<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
				<div class="ml-4 mt-4 flex items-center">
					<div class="mr-3">
						<div class="bg-gray-400 flex-shrink-0 rounded-md p-3">
							<svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
							</svg>
						</div>
					</div>
					<div>
						<h3 class="text-lg leading-6 font-medium text-gray-900">
							<?= __( 'Stream Player', 'wpradio' ); ?>
						</h3>
					</div>
				</div>
				<div class=" flex justify-between items-center flex-wrap sm:flex-nowrap">
					<div class="ml-4 mt-4 items-center">
						<?php
						$wpradio_channelSelectorAllowedEmpty = true;
						require( 'wpradio-all-channels-select-template.php' );
						?>
					</div>
					<div class="ml-4 mt-4 items-center">
						<span class="relative z-0 inline-flex shadow-sm rounded-md">
						<button @click="themeChange('light')" type="button"
								:class="{'text-white bg-gray-500':(theme==='light'), 'bg-white text-gray-700 hover:bg-gray-300':(theme!=='light')}"
								class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium focuxs:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<?= __( 'Light', 'wpradio' ); ?>
						</button>
						<button @click="themeChange('dark')" type="button"
								:class="{'text-white bg-gray-500':(theme==='dark'), 'bg-white text-gray-700 hover:bg-gray-300':(theme!=='dark')}"
								class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<?= __( 'Dark', 'wpradio' ); ?>
						</button>
						</span>
					</div>
					<div class="ml-4 mt-4 items-center">
						<button type="button"
								class="newStream-color-picker relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm">
							<div class="flex items-center">
								<span class="newStream-color-picker-indicator flex-shrink-0 inline-block h-4 w-4 rounded"
									style="background-color: #7F3EE8;"></span>
								<span class="ml-3 block truncate" x-text="accentColor"></span>
							</div>
							<span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
								<svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd"
										d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
										clip-rule="evenodd"/>
								</svg>
							</span>
						</button>
					</div>
				</div>

			</div>

		</div>

		<div class="border-b border-gray-200 px-3 py-4 sm:px-4">
			<div class=" flex justify-between items-center flex-wrap sm:flex-nowrap">
				<h4 class="text-md leading-6 font-medium text-gray-900">
					<?= __( 'Shortcode', 'wpradio' ); ?>
				</h4>
				<div class="w-full ml-5">
					<div class="mt-1 flex rounded-md shadow-sm ">
						<div class="relative flex items-stretch flex-grow focus-within:z-10">
							<input id="newStream-shortcode" x-bind:value="getShortcode()" type="text"
								class="block w-full rounded-none rounded-l-md pl-2 sm:text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150"
								placeholder="John Doe">
						</div>
						<button data-clipboard-target="#newStream-shortcode"
								class="copybutton -ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
							</svg>
							<span><?= __( 'Copy', 'wpradio' ); ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<div id="newStream-embedded-player" class="px-4 py-5 sm:p-6">
		</div>
	</div>
</div>

<div x-data="channelSelector('podcasts')" class="bg-white overflow-hidden shadow rounded-lg mt-5">
	<div x-data="widgetsGenerator('podcasts')">
		<div class="bg-gray-50 border-b border-gray-200 px-4 py-5 sm:px-6">
			<div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
				<div class="ml-4 mt-4 flex items-center">
					<div class="mr-3">
						<div class="bg-gray-400 flex-shrink-0 rounded-md p-3">
							<svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
							</svg>
						</div>
					</div>
					<div>
						<h3 class="text-lg leading-6 font-medium text-gray-900">
							<?= __( 'Podcasts Player', 'wpradio' ); ?>
						</h3>
					</div>
				</div>
				<div class=" flex justify-between items-center flex-wrap sm:flex-nowrap">
					<div class="ml-4 mt-4 items-center">
						<?php
						$wpradio_channelSelectorAllowedEmpty = false;
						require( 'wpradio-all-channels-select-template.php' );
						?>
					</div>
					<div class="ml-4 mt-4 items-center">
						<span class="relative z-0 inline-flex shadow-sm rounded-md">
						<button @click="themeChange('light')" type="button"
								:class="{'text-white bg-gray-500':(theme==='light'), 'bg-white text-gray-700 hover:bg-gray-300':(theme!=='light')}"
								class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<?= __( 'Light', 'wpradio' ); ?>
						</button>
						<button @click="themeChange('dark')" type="button"
								:class="{'text-white bg-gray-500':(theme==='dark'), 'bg-white text-gray-700 hover:bg-gray-300':(theme!=='dark')}"
								class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<?= __( 'Dark', 'wpradio' ); ?>
						</button>
						</span>
					</div>
					<div class="ml-4 mt-4 items-center">
						<button type="button"
								class="podcasts-color-picker relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm">
							<div class="flex items-center">
								<span class="podcasts-color-picker-indicator flex-shrink-0 inline-block h-4 w-4 rounded"
									style="background-color: #7F3EE8;"></span>
								<span class="ml-3 block truncate" x-text="accentColor"></span>
							</div>
							<span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
								<svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd"
										d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
										clip-rule="evenodd"/>
								</svg>
							</span>
						</button>
					</div>
				</div>

			</div>

		</div>

		<div class="border-b border-gray-200 px-3 py-4 sm:px-4">
			<div class=" flex justify-between items-center flex-wrap sm:flex-nowrap">
				<h4 class="text-md leading-6 font-medium text-gray-900">
					<?= __( 'Shortcode', 'wpradio' ); ?>
				</h4>
				<div class="w-full ml-5">
					<div class="mt-1 flex rounded-md shadow-sm ">
						<div class="relative flex items-stretch flex-grow focus-within:z-10">
							<input id="podcasts-shortcode" x-bind:value="getShortcode()" type="text"
								class="block w-full rounded-none rounded-l-md pl-2 sm:text-sm border-gray-300 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150"
								placeholder="John Doe">
						</div>
						<button data-clipboard-target="#podcasts-shortcode"
								class="copybutton -ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring ring-blue-100 focus:border-blue-300 transition ease-in-out duration-150">
							<svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
								viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
							</svg>
							<span><?= __( 'Copy', 'wpradio' ); ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<div id="podcasts-embedded-player" class="px-4 py-5 sm:p-6">

		</div>
	</div>
</div>
