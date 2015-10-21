<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       DX Page View Tracker
 * Plugin URI:
 * Description:       Tracks user activity on wp pages using piwik service
 * Version:           1.0.0
 * Author:
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dx-pageview-tracker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! defined( 'DS' )){
    define( 'DS', DIRECTORY_SEPARATOR );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_dx_pageview_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes' . DS . 'class-dx-pageview-tracker-activator.php';
	DX_Pageview_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_dx_pageview_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes' . DS . 'class-dx-pageview-tracker-deactivator.php';
	DX_Pageview_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dx_pageview_tracker' );
register_deactivation_hook( __FILE__, 'deactivate_dx_pageview_tracker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes' . DS . 'class-dx-pageview-tracker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dx_pageview_tracker() {

	$plugin = new DX_Pageview_Tracker();
	$plugin->run();

}
run_dx_pageview_tracker();