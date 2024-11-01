=== WPRadio - Wordpress Radio Streaming Plugin ===
Contributors: casterfm
Tags: shoutcast, icecast, streaming, radio streaming, radio, radio station, radio hosting, radio server, wpradio, wordpressradio, stream hosting, free radio server
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 5.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An entire radio streaming platform within your WordPress site.

== Description ==

**Wordpress Radio** is the only WordPress plugin that creates an entire radio streaming platform within your WordPress site.
It utilizes [Caster.fm Cloud API](https://www.caster.fm/free-cloud-stream-hosting) to allow you to create an internet radio station and provide a management interface for the streaming server, listeners, widgets and podcasts management.
And the best part is, it is FREE!

== Features ==

  **Server & Broadcast Management:**
Start and the stop the streaming server, view multi-channel real time broadcast status and meta-data, change meta-data and drop broadcast source and view real-time listeners stats…
All within the app dashboard.

  **Live Listeners Information:**
View your listeners information in real time, including connection duration, the device they are using to listen and the ability to drop specific listener.

  **Recorded Podcasts Management:**
Listen to, rename and delete your automatically recorded podcasts.

  **Live Stream and Recorded Podcasts Players Shortcodes Generator:**
Customize the provided widgets / players and generate shortcodes for usage within your WordPress site.

== Affiliates ==
If you're a WordPress hosting company you can register as an affiliate to offer Caster.fm API service as your own and get a share of the revenue from your subscribers.
[Learn More About Caster.fm API Affiliates Program](https://www.caster.fm/free-cloud-stream-hosting/affiliates)
After obtaining an affiliate id you can insert it at the Wordpress Radio page within the Network Admin section.
This will make sure all your hosted WordPress sites will be redirected to your registration gateway URL when prompted for an API private token.

== Development ==
  The javascript and css source files are located within the `Admin/assets` directory and uses Laravel Mix for assets management/compilation.
  To build those you'll need to install NodeJS and NPM.
  After which cd to the assets directory and execute the following command:
`npm install`
`npm run dev`
This will compile the assets and publish them at the parent directory.

== Installation ==

1. Upload `wpradio` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. [Register](https://cloud.caster.fm/register) for FREE at [Caster.fm Cloud](https://www.caster.fm/free-cloud-stream-hosting) to get an api token
4. Update your API private token at the plugin settings page

> **Affiliates/WordPress Hosting Companies:** Check the Affiliates section below

== Frequently Asked Questions ==

= Is this really free? =

Yes, Caster.fm API has a free plan with all the main features required for an internet radio station, including players to embed into your site.
*There are premium paid plans that adds more features.*

= is this plugin compatible with Multi-Site/Network? =

Yes it is.



== Screenshots ==

1. Panel Dashboard
2. Listeners Tracking
3. Podcasts Management
4. Widgets Configuration

== Changelog ==

= 1.0.4 =
* Updated the plugin to work with the latest Caster.fm Cloud API

= 1.0.3 =
* Updated dependencies and libraries
* Minor bug fixes

= 1.0.2 =
* Some minor fixes

= 1.0.1 =
* Fix news feed url

= 1.0.0 =
* Initial release of the plugin

== Upgrade Notice ==

= 1.0.2 =
Some minor fixes

= 1.0.1 =
Fix news feed url

= 1.0.0 =
Initial release of the plugin
