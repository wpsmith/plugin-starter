<?php
/**
 * Cards archive post template.
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

/**
 * Add body class.
 *
 * @param array $classes The existing body classes.
 *
 * @return array $classes The modified body classes.
 */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'type';

	return $classes;
} );

/**
 * Archive Post Class.
 *
 * Breaks the posts into two columns.
 *
 * @param array $classes
 *
 * @return array
 */
add_filter( 'post_class', function ( $classes ) {
	global $wp_query;
	if ( ! $wp_query->is_main_query() ) {
		return $classes;
	}

	$classes[] = 'one-half';
	if ( 0 == $wp_query->current_post % 2 ) {
		$classes[] = 'first';
	}

	$taxonomies = get_object_taxonomies( array( 'post_type' => 'type' ) );
	foreach ( $taxonomies as $taxonomy ) {
		if ( has_term( '', $taxonomy ) ) {
			$classes[] = "has-$taxonomy";
		}
	}

	return $classes;
} );

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width' );

add_filter( 'genesis_post_info', '__return_false' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

genesis();
