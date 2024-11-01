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

namespace WPRadio\Admin;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Network-wide Settings of the admin area.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Admin
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class NetworkSettings extends SettingsBase {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 */
	protected $pluginSlug;

	/**
	 * The slug name for the menu.
	 * Should be unique for this menu page and only include
	 * lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().
	 *
	 * @since    1.0.0
	 */
	private $menuSlug;

	/**
	 * General settings' group name.
	 *
	 * @since    1.0.0
	 */
	private $generalOptionGroup;

	/**
	 * General settings' section.
	 * The slug-name of the section of the settings page in which to show the box.
	 *
	 * @since    1.0.0
	 */
	private $generalSettingsSectionId;

	/**
	 * General settings page.
	 * The slug-name of the settings page on which to show the section.
	 *
	 * @since    1.0.0
	 */
	private $generalPage;

	/**
	 * Name of general options. Expected to not be SQL-escaped.
	 *
	 * @since    1.0.0
	 */
	private $networkGeneralOptionName;

	/**
	 * Collection of network options.
	 *
	 * @since    1.0.0
	 */
	private $networkGeneralOptions;

	/**
	 * Ids of setting fields.
	 */
	private $affiliateId_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginSlug The name of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $pluginSlug ) {
		$this->pluginSlug = $pluginSlug;
		$this->menuSlug   = $this->pluginSlug;

		/**
		 * General
		 */
		$this->generalOptionGroup       = $pluginSlug . '-network-general-option-group';
		$this->generalSettingsSectionId = $pluginSlug . '-network-general-section';
		$this->generalPage              = $pluginSlug . '-network-general';
		$this->networkGeneralOptionName = $pluginSlug . '-network-settings';

		$this->affiliateId_id = 'affiliate_id' . self::TEXT_SUFFIX;
	}

	/**
	 * Register all the hooks of this class.
	 *
	 * @param bool $isNetworkAdmin Whether the current request is for a network administrative interface page.
	 *
	 * @since    1.0.0
	 */
	public function initializeHooks( $isNetworkAdmin ) {
		// Network Admin
		if ( $isNetworkAdmin ) {
			add_action( 'network_admin_menu', [ $this, 'setupNetworkSettingsMenu' ] );
			add_action( 'network_admin_edit_wpradio_update_network_options', [
				$this,
				'wpradio_update_network_options'
			] );
		}
	}

	/**
	 * This function introduces the plugin options into the Network Main menu.
	 */
	public function setupNetworkSettingsMenu() {
		//Add the menu item to the Main menu
		add_menu_page(
			'Wordpress Radio Network Options',                      // Page title: The title to be displayed in the browser window for this page.
			'Wordpress Radio',                                      // Menu title: The text to be used for the menu.
			'manage_network_options',                           // Capability: The capability required for this menu to be displayed to the user.
			$this->menuSlug,                                    // Menu slug: The slug name to refer to this menu by. Should be unique for this menu page.
			[
				$this,
				'renderNetworkSettingsPageContent'
			],   // Callback: The name of the function to call when rendering this menu's page
			'dashicons-microphone',                                 // Icon
			81                                                  // Position: The position in the menu order this item should appear.
		);

		// Get the values of the setting we've registered with register_setting(). It used in the callback functions.
		$this->networkGeneralOptions = $this->getNetworkGeneralOptions();

		// Add a new section to a settings page.
		add_settings_section(
			$this->generalSettingsSectionId,                // ID used to identify this section and with which to register options
			__( 'Caster.fm API Affiliate Setup', 'wpradio' ),           // Title to be displayed on the administration page
			[ $this, 'networkGeneralOptionsCallback' ],  // Callback used to render the description of the section
			$this->generalPage                              // Page on which to add this section of options
		);

		add_settings_field(
			$this->affiliateId_id,                        // ID used to identify the field throughout the theme.
			__( 'Affiliate ID', 'wpradio' ),            // The label to the left of the option interface element.
			[
				$this,
				'affiliateIdCallback'
			],         // The name of the function responsible for rendering the option interface.
			$this->generalPage,                    // The page on which this option will be displayed.
			$this->generalSettingsSectionId,       // The name of the section to which this field belongs.
			[ 'label_for' => $this->affiliateId_id ]   // Extra arguments used when outputting the field. CSS Class can also be added to the <tr> element with the 'class' key.
		);

		// 'register_setting()' is useless in the Network Admin area.
	}

	/**
	 * Renders the Settings page to display for the Settings menu defined above.
	 *
	 * @since   1.0.0
	 */
	public function renderNetworkSettingsPageContent() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_network_options' ) ) {
			return;
		}

		// Add error/update messages.
		// Check if the user have submitted the settings. Wordpress will add the "updated" $_GET parameter to the url
		if ( isset( $_GET['updated'] ) ) {
			// Add settings saved message with the class of "updated"
			add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'Settings saved.' ), 'success' );
		}

		// Show error/update messages
		settings_errors( $this->pluginSlug );

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php esc_html_e( 'Wordpress Radio Network Settings', 'wpradio' ); ?></h2>

			<form method="post" action="edit.php?action=wpradio_update_network_options">
				<?php
				settings_fields( $this->generalOptionGroup );
				do_settings_sections( $this->generalPage );

				submit_button();
				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * This function here is hooked up to a special action and necessary to process
	 * the saving of the options. This is the big difference with a normal options page.
	 */
	public function wpradio_update_network_options() {
		// Security check.
		// On the settings page we used the '$this->generalOptionGroup' slug when calling 'settings_fields'
		// but we must add the '-options' postfix when we check the nonce.
		if ( wp_verify_nonce( $_POST['_wpnonce'], $this->generalOptionGroup . '-options' ) === false ) {
			wp_die( __( 'Failed security check.', 'wpradio' ) );
		}

		// Sanitize the option values
		$sanitizedOptions = $this->sanitizeOptionsCallback( $_POST[ $this->networkGeneralOptionName ] );

		// Update the options
		update_network_option( get_current_network_id(), $this->networkGeneralOptionName, $sanitizedOptions );

		// At last we redirect back to our options page.
		wp_redirect( add_query_arg( [
			'page'    => $this->menuSlug,
			'updated' => 'true'
		], network_admin_url( 'admin.php' ) ) );
		exit;
	}

#region GENERAL OPTIONS

	/**
	 * Return the General options.
	 */
	public function getNetworkGeneralOptions() {
		if ( isset( $this->networkGeneralOptions ) ) {
			return $this->networkGeneralOptions;
		}

		$currentNetworkId            = get_current_network_id();
		$this->networkGeneralOptions = get_network_option( $currentNetworkId, $this->networkGeneralOptionName, [] );

		// If options don't exist, create them.
		if ( $this->networkGeneralOptions === [] ) {
			$this->networkGeneralOptions = $this->defaultNetworkGeneralOptions();
			update_network_option( $currentNetworkId, $this->networkGeneralOptionName, $this->networkGeneralOptions );
		}

		return $this->networkGeneralOptions;
	}

	/**
	 * Provide default values for the Network General Options.
	 *
	 * @return array
	 */
	private function defaultNetworkGeneralOptions() {
		return [
			$this->affiliateId_id => ''
		];
	}

	/**
	 * This function provides the description for the General Options page.
	 *
	 * It's called from the initializeNetworkGeneralOptions function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function networkGeneralOptionsCallback() {
		echo '<p>' . esc_html__( 'If you are a Caster.fm API Affiliate you can set your affiliate id for the entire network.', 'wpradio' ) . '<br />' . esc_html__( 'This will change the links to the Caster.fm API registration page displayed at the Wordpress Radio interface for site managers.', 'wpradio' ) . '</p>';
	}

	public function affiliateIdCallback() {
		printf( '<input type="text" id="%s" name="%s[%s]" value="%s" class="regular-text" />', $this->affiliateId_id, $this->networkGeneralOptionName, $this->affiliateId_id, $this->networkGeneralOptions[ $this->affiliateId_id ] );
	}

	/**
	 * Get Affiliate ID option.
	 */
	public function getAffiliateId() {
		$this->networkGeneralOptions = $this->getNetworkGeneralOptions();

		return $this->networkGeneralOptions[ $this->affiliateId_id ];
	}

#endregion

}
