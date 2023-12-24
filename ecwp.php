<?php
/** //phpcs:ignore
 *
 * @link              https://haywoodtech.it
 * @since             1.0.0
 * @package           Ecwp
 *
 * @wordpress-plugin
 * Plugin Name:       Monthly Events Calendar
 * Plugin URI:        https://wpmonthlyevents.com/
 * Description:       Monthly Events Calendar is a powerful calendar and user-friendly plugin that allows you to effortlessly manage and showcase events on your WordPress website.
 * Version:           1.0.2
 * Author:            Haywood Devteam
 * Author URI:        https://haywoodtech.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ecwp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 */
define( 'ECWP_VERSION', '1.0.2' );
define( 'ECWP_ABS_PATH', __DIR__ . '/' );
define( 'ECWP_PATH', plugin_dir_url( __FILE__ ) );
define( 'ECWP_ADMIN_VIEW', ECWP_ABS_PATH . 'admin/partials/' );
define( 'ECWP_ADMIN_LOGO', ECWP_PATH . 'admin/img/logo.svg' );
define( 'ECWP_PUBLIC_VIEW', ECWP_ABS_PATH . 'public/partials/' );
define( 'ECWP_BUILD', ECWP_PATH . 'public/src/dist/' );
define( 'ECWP_JS_ADMIN_DATE', 'MM-DD-YYYY hh:mm A' );
define( 'ECWP_SETTINGS', 'ecwp_settings' );
define( 'ECWP_EVENTS_SLUG', 'events' );
define( 'ECWP_CATEGORY_SLUG', 'events-category' );
define( 'ECWP_TAG_SLUG', 'events-tag' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ecwp-activator.php
 */
function ecwp_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ecwp-activator.php';
	Ecwp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ecwp-deactivator.php
 */
function ecwp_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ecwp-deactivator.php';
	Ecwp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ecwp_activate' );
register_deactivation_hook( __FILE__, 'ecwp_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ecwp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function ecwp_run() {
	$plugin = new Ecwp();
	$plugin->run();
}
ecwp_run();
