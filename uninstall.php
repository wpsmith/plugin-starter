<?php
/**
 * The uninstall stuffs.
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

// if uninstall.php is not called by WordPress, die!
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

require_once( 'plugin.php' );

//// Get what we are supposed to do on deletion/uninstall.
//$uninstall_mgr = Plugin::get_uninstall_manager();
//$wps_plugin_delete_action = $uninstall_mgr->get_uninstall_action();
//
//// Bail if not deleting everything.
//if ( 'everything' !== $wps_plugin_delete_action ) {
//	return;
//}
//
//// Delete Options.
//$uninstall_mgr->uninstall();
//delete_option( Plugin::get_plugin_name() );
//
//// Optionally could remove all feedback.
//if ( apply_filters( 'wps_plugin_delete_items', true ) ) {
//	$WPS_PLUGIN_feedback = new \WP_Query(
//		array(
//			'post_type'              => 'type',
//			'post_status'            => 'any',
//			'posts_per_page'         => - 1,
//			'cache_results'          => false,
//			'no_found_rows'          => true,
//			'update_post_meta_cache' => false,
//			'update_post_term_cache' => false,
//			'lazy_load_term_meta'    => false,
//		)
//	);
//	foreach ( $WPS_PLUGIN_feedback->get_posts() as $WPS_PLUGIN_feedback_post ) {
//		wp_delete_post( $WPS_PLUGIN_feedback_post->ID );
//	}
//}

// That's all folks!
