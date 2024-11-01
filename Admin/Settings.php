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
 * Settings of the admin area.
 * Add the appropriate suffix constant for every field ID to take advantake the standardized sanitizer.
 *
 * @since      1.0.0
 * @package    WPRadio
 * @subpackage WPRadio/Admin
 * @link       https://www.caster.fm/free-cloud-stream-hosting/wordpress-plugin
 * @author     Caster.fm <webmaster@caster.fm>
 */
class Settings extends SettingsBase {
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
	protected $menuSlug;

	/**
	 * The network settings object or false when not a multisite setup.
	 *
	 * @since    1.0.0
	 */
	private $networkSettings;

	/**
	 * The Caster.fm API affiliate ID from the network settings
	 *
	 * @since    1.0.0
	 */
	protected $affiliateId;

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
	private $generalOptionName;

	/**
	 * Collection of options.
	 *
	 * @since    1.0.0
	 */
	private $generalOptions;

	/**
	 * Ids of setting fields.
	 */
	protected $privateToken_id;
	protected $publicToken_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginSlug The name of this plugin.
	 * @param NetworkSettings $networkSettings The network settings object.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $pluginSlug, $networkSettings ) {
		$this->pluginSlug      = $pluginSlug;
		$this->menuSlug        = $this->pluginSlug;
		$this->networkSettings = $networkSettings;

		if ( $networkSettings !== false ) {
			$this->affiliateId = $networkSettings->getAffiliateId();
		}

		/**
		 * General
		 */
		$this->generalOptionGroup       = $pluginSlug . '-general-option-group';
		$this->generalSettingsSectionId = $pluginSlug . '-general-section';
		$this->generalPage              = $pluginSlug . '-general';
		$this->generalOptionName        = $pluginSlug . '-general';


		$this->privateToken_id = 'private_token' . self::TEXT_SUFFIX;
		$this->publicToken_id  = 'public_token' . self::TEXT_SUFFIX;

	}

	/**
	 * Register all the hooks of this class.
	 *
	 * @param bool $isAdmin Whether the current request is for an administrative interface page.
	 *
	 * @since    1.0.0
	 */
	public function initializeHooks( $isAdmin ) {
		// Admin
		if ( $isAdmin ) {
			add_action( 'admin_menu', [ $this, 'setupMenu' ], 10 );
			add_action( 'admin_init', [ $this, 'initializeGeneralOptions' ], 10 );
		}
	}

	/**
	 * This function introduces the plugin options into the Main menu.
	 */
	public function setupMenu() {
		//Add the settings sub-menu item to the plugin main menu item
		add_submenu_page(
			$this->menuSlug . '_dashboard',
			'Wordpress Radio Settings',
			'Settings',
			'manage_options',
			$this->menuSlug . '_settings',
			[ $this, 'renderSettingsPageContent' ]
		);
	}

	/**
	 * Renders the Settings page to display for the Settings menu defined above.
	 *
	 * @since   1.0.0
	 */
	public function renderSettingsPageContent() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show error/update messages
		settings_errors( $this->pluginSlug );

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php esc_html_e( 'Wordpress Radio Settings', 'wpradio' ); ?></h2>


			<form method="post" action="options.php">
				<?php
				settings_fields( $this->generalOptionGroup );
				do_settings_sections( $this->generalPage );

				submit_button();
				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}


#region GENERAL OPTIONS

	/**
	 * Initializes the General Options by registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initializeGeneralOptions() {
		// Get the values of the setting we've registered with register_setting(). It used in the callback functions.
		$this->generalOptions = $this->getGeneralOptions();

		// Add a new section to a settings page.
		add_settings_section(
			$this->generalSettingsSectionId,            // ID used to identify this section and with which to register options
			'',               // Title to be displayed on the administration page
			[ $this, 'generalOptionsCallback' ],     // Callback used to render the description of the section
			$this->generalPage                          // Page on which to add this section of options
		);

		// Next, we'll introduce the fields for toggling the visibility of content elements.
		add_settings_field(
			$this->privateToken_id,                        // ID used to identify the field throughout the theme.
			__( 'Private Token', 'wpradio' ),            // The label to the left of the option interface element.
			[
				$this,
				'privateTokenCallback'
			],         // The name of the function responsible for rendering the option interface.
			$this->generalPage,                    // The page on which this option will be displayed.
			$this->generalSettingsSectionId,       // The name of the section to which this field belongs.
			[ 'label_for' => $this->privateToken_id ]   // Extra arguments used when outputting the field. CSS Class can also be added to the <tr> element with the 'class' key.
		);

		// Finally, we register the fields with WordPress.
		/**
		 * If you want to use the setting in the REST API (wp-json/wp/v2/settings),
		 * you’ll need to call register_setting() on the rest_api_init action, in addition to the normal admin_init action.
		 */
		$registerSettingArguments = [
			'type'              => 'array',
			'description'       => '',
			'sanitize_callback' => [ $this, 'validateSettingsCallback' ],
			'show_in_rest'      => false
		];

		register_setting( $this->generalOptionGroup, $this->generalOptionName, $registerSettingArguments );
	}

	/**
	 * @param array|null $input
	 *
	 * @return array
	 */
	public function validateSettingsCallback( array $input = null ) {
		if ( $input === null ) {
			return [];
		}

		// Define the array for the sanitized options
		$output = $this->sanitizeOptionsCallback( $input );

		if ( array_key_exists( $this->privateToken_id, $output ) && ! empty( $output[ $this->privateToken_id ] ) ) {

			$tokenValidationRequestUrl = WPRADIO_CASTERFMAPI_HUB . 'private/checkToken?token=' . $output[ $this->privateToken_id ];
			$tokenValidationResponse   = wp_remote_get( $tokenValidationRequestUrl );

			$output[ $this->privateToken_id ] = '';
			$output[ $this->publicToken_id ]  = '';

			if ( is_wp_error( $tokenValidationResponse ) ) {
                var_dump($tokenValidationResponse);
				add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'WPError: Cannot make the validation request.' ), 'error' );
			} else {
				$tokenValidationResponseCode = wp_remote_retrieve_response_code( $tokenValidationResponse );

				if ( $tokenValidationResponseCode === 401 ) {
					add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'Invalid Private Token Provided.' ), 'error' );
				} elseif ( $tokenValidationResponseCode !== 200 ) {
					add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'Cannot validate token, server returned error code: ' ) . $tokenValidationResponseCode, 'error' );
				} else {
					$tokenValidationResponseBody = wp_remote_retrieve_body( $tokenValidationResponse );
					$tokenValidationResponseJson = json_decode( $tokenValidationResponseBody, true );
					if ( ! $tokenValidationResponseJson || $tokenValidationResponseJson === null ) {
						add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'Cannot validate token, server returned non json body.' ), 'error' );
					} else {
						$output[ $this->privateToken_id ] = $tokenValidationResponseJson['private_token'];
						$output[ $this->publicToken_id ]  = $tokenValidationResponseJson['public_token'];
						add_settings_error( $this->pluginSlug, $this->pluginSlug . '-message', __( 'Tokens saved successfully!' ), 'success' );
					}
				}
			}
		}

		/**
		 * Settings errors should be added inside the $sanitize_callback function.
		 * Example: add_settings_error($this->pluginSlug, $this->pluginSlug . '-message', __('Error.'), 'error');
		 */

		return $output;
	}

	/**
	 * Return the General options.
	 */
	public function getGeneralOptions() {
		if ( isset( $this->generalOptions ) ) {
			return $this->generalOptions;
		}

		$this->generalOptions = get_option( $this->generalOptionName, [] );

		// If options don't exist, create them.
		if ( $this->generalOptions === [] ) {
			$this->generalOptions = $this->defaultGeneralOptions();
			update_option( $this->generalOptionName, $this->generalOptions );
		}

		return $this->generalOptions;
	}

	/**
	 * Provide default values for the General Options.
	 *
	 * @return array
	 */
	private function defaultGeneralOptions() {
		return [
			$this->privateToken_id => '',
			$this->publicToken_id  => '',
		];
	}

	/**
	 * This function provides a simple description for the General Options page.
	 *
	 * It's called from the initializeGeneralOptions function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function generalOptionsCallback() {
		echo '<p style="margin-top: 10px;">' . esc_html__( 'This plugin requires you to have a Caster.fm Cloud account and set your API Private Token.', 'wpradio' ) . "<br /><a style='margin-top:10px' class='button button-secondary' href='" . Helpers::getRegistrationLink( $this->affiliateId ) . "'>" . __( 'Caster.fm API Registration', 'wpradio' ) . "</a>" . '</p>';
	}

	public function privateTokenCallback() {
		printf( '<input type="text" id="%s" name="%s[%s]" value="%s" class="regular-text"  />', $this->privateToken_id, $this->generalOptionName, $this->privateToken_id, $this->generalOptions[ $this->privateToken_id ] );
	}

	/**
	 * Get the Private Token.
	 */
	public function getPrivateToken() {
		$this->generalOptions = $this->getGeneralOptions();

		return (string) $this->generalOptions[ $this->privateToken_id ];
	}

	/**
	 * Get the Public Token.
	 */
	public function getPublicToken() {
		$this->generalOptions = $this->getGeneralOptions();

		return (string) $this->generalOptions[ $this->publicToken_id ];
	}

#endregion

	/**
	 * Get the Affiliate ID.
	 */
	public function getAffiliateID() {
		return $this->affiliateId;
	}
}
