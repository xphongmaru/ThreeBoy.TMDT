<?php
/**
 * Plugin Name:       Themebeez Toolkit
 * Plugin URI:        https://wordpress.org/plugins/themebeez-toolkit/
 * Description:       A essential toolkit for themes made by www.themebeez.com. This plugin extends themes made by themebeez & adds functionality to import demo data in just a click.
 * Version:           1.3.2
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Tested up to:      6.7.1
 * Author:            themebeez
 * Author URI:        https://themebeez.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       themebeez-toolkit
 * Domain Path:       /languages
 *
 * @package Themebeez_Toolkit
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'THEMEBEEZTOOLKIT_VERSION', '1.3.2' );

// Define THEMEBEEZTOOLKIT_PLUGIN_FILE.
if ( ! defined( 'THEMEBEEZTOOLKIT_PLUGIN_FILE' ) ) {
	define( 'THEMEBEEZTOOLKIT_PLUGIN_FILE', __FILE__ );
}


if ( ! defined( 'THEMEBEEZ_TOOLKIT_THEME_PATH' ) ) {
	define( 'THEMEBEEZ_TOOLKIT_THEME_PATH', get_parent_theme_file_path() );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-themebeez-toolkit-activator.php
 */
function activate_themebeez_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-toolkit-activator.php';
	Themebeez_Toolkit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-themebeez-toolkit-deactivator.php
 */
function deactivate_themebeez_toolkit() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-toolkit-deactivator.php';
	Themebeez_Toolkit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_themebeez_toolkit' );
register_deactivation_hook( __FILE__, 'deactivate_themebeez_toolkit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-toolkit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_themebeez_toolkit() {

	$plugin = new Themebeez_Toolkit();
	$plugin->run();
}
run_themebeez_toolkit();


// Include the main Demo Importer class.
if ( ! class_exists( 'Themebeez_Demo_Importer' ) ) {
	include_once __DIR__ . '/includes/demo-importer/class-themebeez-demo-importer.php';
}

/**
 * Main instance of Themebeez_Demo_Importer.
 *
 * Returns the main instance of TET to prevent the need to use globals.
 *
 * @since 1.0.0
 * @return Themebeez_Demo_Importer
 */
function themebeez_demo_importer_init() {

	return Themebeez_Demo_Importer::instance();
}

$theme = themebeez_toolkit_theme();

$themes = themebeez_toolkit_theme_array();

if ( $theme->get( 'Author' ) === 'themebeez' && in_array( $theme->get( 'TextDomain' ), $themes, true ) ) {

	// Global for backwards compatibility.
	$GLOBALS['themebeez-demo-importer'] = themebeez_demo_importer_init();
}
