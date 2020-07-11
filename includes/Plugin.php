<?php
/**
 * The plugin bootstrap file.
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

namespace WPS\WP\Plugins\MyPlugin;

use WPS\WP\Plugin\PluginBase;

if ( ! class_exists( __NAMESPACE__ . '\Plugin' ) ) {
	/**
	 * Class Plugin
	 *
	 * @package \WPS\WP\Plugins\MyPlugin
	 */
	class Plugin extends PluginBase {

		/**
		 * Plugin Version Number
		 */
		const VERSION = WPS_PLUGIN_VERSION;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected static $plugin_name = 'wps-plugin';

		/**
		 * Holds plugin's post types.
		 *
		 * @var PostTypes
		 */
		public $post_types;

		/**
		 * Plugin constructor.
		 *
		 * @param array $args Optional args.
		 */
		protected function __construct( $args = array() ) {
			// Register Post Types.
			$this->post_types = PostTypes::get_instance();

			// Construct the parent.
			parent::__construct( wp_parse_args( $args, array(
				'directory' => trailingslashit( WPS_PLUGIN_DIRNAME . '/templates' ),
				'name'      => self::$plugin_name,
				'version'   => self::VERSION,
			) ) );
		}

		/**
		 * Activation function.
		 */
		public static function on_deactivation() {
			if ( isset( $_GET['delete_action'] ) && ( 'plugin-only' === $_GET['delete_action'] || 'everything' === $_GET['delete_action'] ) ) {
				update_option( 'wps_plugin_delete_action', $_GET['delete_action'], false );
			}

			try {
			} catch ( \Exception $e ) {
			}

			flush_rewrite_rules();
		}

		/**
		 * Activation function.
		 */
		public static function on_activation() {
			add_option( 'wps_plugin_delete_action', 'plugin-only', false );

			$instance = self::get_instance();
			$instance->post_types->register();
			flush_rewrite_rules();

		}
	}
}

