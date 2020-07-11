<?php
/**
 * Charity single post template.
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
 * @since  0.1.0
 * @access public
 */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'type';

	return $classes;
} );

/**
 * Remove entry info.
 *
 * Callback defined in Genesis.
 *
 * @see genesis_post_info
 */
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

/**
 * Remove entry footer.
 *
 * Callback defined in Genesis.
 *
 * @see genesis_entry_footer_markup_open
 * @see genesis_entry_footer_markup_close
 * @see genesis_post_meta
 */
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

/**
 * Hide entry header.
 *
 * Callback defined in Genesis.
 *
 * @see genesis_title_hidden
 */
//add_filter( 'genesis_title_hidden', '__return_true');

// Let 'em rip!
genesis();
