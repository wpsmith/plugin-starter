<?php
/**
 * The plugin post types file.
 *
 * This file creates all the post types related to this plugin.
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

use WPS\Core\Singleton;
use WPS\WP\Customizer\Customizer;
use WPS\WP\Debug;
use WPS\WP\Fields;
use WPS\WP\Genesis\ArchiveSettings;
use WPS\WP\PostTypes\PostType;

if ( ! class_exists( __NAMESPACE__ . '\PostTypes' ) ) {
	/**
	 * Class Plugin
	 *
	 * @package \WPS\WP\Plugins\MyPlugin
	 */
	class PostTypes extends Singleton {

		/**
		 * Holds plugin post types.
		 *
		 * @var [sting]PostType
		 */
		public $post_types = array();

		/**
		 * Google Maps API Key.
		 *
		 * @var string
		 */
		private static $google_api_key = '';

		/**
		 * ACF Fields.
		 *
		 * @var Fields
		 */
		public static Fields $fields;

		/**
		 * Plugin constructor.
		 *
		 * @param array $args Optional args.
		 */
		protected function __construct( $args = array() ) {
			// Register post types and taxonomies, etc.
			add_action( 'init', array( $this, 'register' ), 0 );

			// Templating.
			add_action( 'template_include', array( $this, 'template_include' ), PHP_INT_MAX );

			// Add ACF.
			add_action( 'acf/init', array( $this, 'acf' ) );
			add_action( 'acf/fields/google_map/api', function ( $api ) {
				$api['key'] = self::$google_api_key;

				return $api;
			} );
			add_action( 'acf/init', function () {
				if ( function_exists( 'acf_update_setting' ) ) {
					acf_update_setting( 'google_api_key', self::$google_api_key );
				}
			} );

			// Configuration.
			add_action( 'wps_customizer', array( $this, 'customize_register' ) );

			// Parent constructor.
			parent::__construct( $args );
		}

		/**
		 * Conditionally includes the template.
		 *
		 * @param string $template Template path.
		 *
		 * @return mixed|null|string
		 */
		public function template_include( $template ) {

			$post_type = $this->post_types['type'];

			if ( ! $post_type->is_post_type() ) {
				return $template;
			}

			$loader = Plugin::get_instance()->get_template_loader();

			// @TODO Make own is_post_type_archive that checks entire array, not just first one.
			if ( is_archive() ) {
				return $loader->get_template_part( 'genesis/types' );
			} elseif ( is_singular( 'type' ) ) {
				return $loader->get_template_part( 'genesis/type' );
			}

			return $template;

		}

		/**
		 * Registers post types.
		 */
		public function register() {
			// Register Post Types.
			$this->register_type();
		}

		/** TYPE */

		/**
		 * Registers type post type.
		 */
		public function register_type() {
			$post_type_name = 'type';

			try {

				$post_type = new PostType( $post_type_name, array(
					'menu_icon'    => 'dashicons-buddicons-pm',
					'public'       => true,
					'map_meta_cap' => true,
					'hierarchical' => true,
					'supports'     => [
						'title',
						'thumbnail',
						'editor',
						'custom-fields',
						'revisions',
						'author',
						'post-formats',
						'comments',
						'page-attributes',
						'genesis-cpt-archives-seo-settings',
						'genesis-title-toggle',
						'genesis-cpt-archives-settings',
						'genesis-scripts',
					],
					'rewrite'      => array(
						'slug'       => 'type',
						'with_front' => true,
					),
					'show_in_rest' => true,
					'has_archive'  => 'types',
				) );

				$post_type->create();

				$this->post_types[ $post_type_name ] = $post_type;

				// All done if doing activation hook.
				if ( false !== strpos( current_filter(), 'activate' ) ) {
					return;
				}

				// Register Archive settings
				if ( is_admin() ) {
					$archive_settings = ArchiveSettings::get_instance();
					$archive_settings->register_post_type( $post_type_name );
				}

				$this->acf();

			} catch ( \Exception $e ) {
				// do nothing
			}

		}

		/**
		 * Define the meta box and field configurations.
		 */
		public function acf() {

			$this->acf_single_type();
//			$this->register_acf_block_type();

		}

		/**
		 * Define the meta box and field configurations.
		 */
		public function register_acf_block_type() {
			// Get the Loader.
			$loader = Plugin::get_instance()->get_template_loader();

			// Register the block.
			if ( function_exists( 'acf_register_block_type' ) ) {
				acf_register_block_type(
					[
						'name'            => 'types-grid',
						'title'           => __( 'Types Grid', 'my-plugin' ),
						'description'     => __( 'Display a grid of type.', 'my-plugin' ),
						'render_template' => $loader->locate_template( 'blocks/types-grid.php', false, false ),
						'category'        => 'layout',
						'icon'            => 'art',
						'mode'            => 'preview',
						'keywords'        => [ 'types', 'types grid' ],
						'supports'        => [
							'align' => [ 'wide', 'full' ]
						]
					]
				);
			}

			// Block settings
			$types = Fields::get_instance()->new_fields_builder( 'types_grid_block' );
			$types
				->addText( 'name' )
				->setLocation( 'block', '==', 'acf/types-grid' );

			if ( function_exists( 'acf_add_local_field_group' ) ) {
				acf_add_local_field_group( $types->build() );
			}
		}

		/**
		 * Single type ACF fields.
		 *
		 * @throws \StoutLogic\AcfBuilder\FieldNameCollisionException
		 */
		public function acf_single_type() {
			$types = Fields::get_instance()->new_fields_builder( 'types' );

			$types
				->addText( 'name' );

			// LOCATION
			$types
				->setLocation( 'post_type', '==', 'type' );

			self::$fields = $types->build();
			if ( function_exists( 'acf_add_local_field_group' ) ) {
				acf_add_local_field_group( self::$fields );
			}
		}

		/**
		 * @param Customizer $wps_customizer WPS Customizer object.
		 */
		public function customize_register( Customizer $wps_customizer ) {

			$config = Customizer::get_config( 'wps-types-blocks', WPS_PLUGIN_DIRNAME );

			/**
			 * Filter the `$config` array, built from /config/customizer-theme-settings.php.
			 *
			 * @param array $config The config array for theme settings in the Customizer.
			 */
			$config = apply_filters( 'wps_customizer_config', $config, 'wps-types-blocks' );

			$wps_customizer->register( $config );

		}
	}
}

