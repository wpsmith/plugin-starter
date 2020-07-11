<?php
/**
 * Plugin Name:     WPS Codeable Sample Plugin
 * Plugin URI:      https://wpsmith.net
 * Description:     Sample plugin written for Codeable evaluation.
 * Author:          Travis Smith <t@wpsmith.net>
 * Author URI:      https://wpsmith.net
 * Text Domain:     wps-codeable
 * Domain Path:     /languages
 * Version:         0.0.1
 *
 * The main plugin file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category   WPS\WP\Plugins\MyPlugin
 * @package    WPS\WP\Plugins\MyPlugin
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2020 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://wpsmith.net/
 * @since      0.0.1
 */

namespace WPS\WP\Plugins;

use WPS\WP\Plugin;

// Require autoloader.
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Version number.
 *
 * @const WPS_PLUGIN_VERSION Version number.
 */
define( 'WPS_PLUGIN_VERSION', '0.0.1' );

/**
 * Root plugin file.
 *
 * @const WPS_PLUGIN_FILE __FILE__
 */
define( 'WPS_PLUGIN_FILE', __FILE__ );

/**
 * Root plugin directory.
 *
 * @const WPS_PLUGIN_DIRNAME __FILE__
 */
define( 'WPS_PLUGIN_DIRNAME', dirname( __FILE__ ) );

// Instantiate!
MyPlugin\Plugin::get_instance();

// Plugins stuff.
add_action( 'plugins_loaded', function () {
	// Prevent plugin from being able to be updated.
	new Plugin\PreventUpdate( plugin_basename( __FILE__ ) );
} );

// Register our on activation hook.
register_activation_hook( WPS_PLUGIN_FILE, array( __NAMESPACE__ . '\Plugin', 'on_activation' ) );
register_deactivation_hook( WPS_PLUGIN_FILE, array( __NAMESPACE__ . '\Plugin', 'on_deactivation' ) );

// Just delete the stuff & don't attempt to anonymize.
add_filter( 'wp_anonymize_feedback', '__return_false' );

