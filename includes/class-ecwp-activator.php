<?php
/**
 * Fired during plugin activation
 *
 * @link       https://haywoodtech.it
 * @since      1.0.0
 *
 * @package    Ecwp
 * @subpackage Ecwp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ecwp
 * @subpackage Ecwp/includes
 * @author     Haywoood Devteam <wordpresshaywoodtech@gmail.com>
 */
class Ecwp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$settings = array(
			'date_format'     => 'F j, Y',
			'time_format'     => 'g:i a',
			'timezone'        => 'Europe/London',
			'default_view'    => 'month',
			'search'          => '1',
			'redirect_single' => '_self',
			'page'            => 0,
		);
		if ( false === get_option( ECWP_SETTINGS ) ) {
			update_option( ECWP_SETTINGS, $settings );
		}
	}
}
