<?php
/**
 * The plugin taxonomies file.
 *
 * This file is creates the necessary taxonomies related to the plugin.
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category   WPS\WP\Plugins\Services
 * @package    WPS\WP\Plugins\Services
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://wpsmith.net/
 * @since      0.0.1
 */

namespace WPS\WP\Plugins\MyPlugin;

use WPS\Core\Singleton;
use WPS\WP\Taxonomies\Taxonomy;

if ( ! class_exists( __NAMESPACE__ . '\Taxonomies' ) ) {
	/**
	 * Class Taxonomies
	 *
	 * @package \WPS\WP\Plugins\MyPlugin
	 */
	class Taxonomies extends Singleton {

		/**
		 * The suffix.
		 *
		 * @var string
		 */
		public $suffix = '';

		/**
		 * Holds plugin taxonomies.
		 *
		 * @var [sting]Taxonomy
		 */
		public $taxonomies = array();

		/**
		 * Plugin constructor.
		 *
		 * @param array $args Optional args.
		 */
		protected function __construct( $args = [] ) {

			$this->suffix = wp_scripts_get_suffix();

			// Register taxonomies, etc.
			$this->register();

		}

		/**
		 * Registers all taxonomies.
		 */
		public function register() {

			$this->register_tax();

		}

		/**
		 * Registers custom category.
		 */
		public function register_tax() {

			$tax = new Taxonomy( 'tax', [
				'type',
			], [
				'public'  => true,
				'rewrite' => [
					'slug' => 'tax',
				],
			] );

			$this->taxonomies['tax'] = $tax;

		}

	}
}

