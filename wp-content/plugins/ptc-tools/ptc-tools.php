<?php
/**
 * PTC Tools
 *
 * @package           PTC_Tools
 * @author            Michelle Blanchette
 * @copyright         2022 Purple Turtle Creative, LLC
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       PTC Tools
 * Description:       Quick scripts and tools for Purple Turtle Creative.
 * Version:           1.0.0
 * Author:            Purple Turtle Creative
 * Author URI:        https://purpleturtlecreative.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace PTC_Tools;

defined( 'ABSPATH' ) || die();

/**
 * The full file path to this plugin's directory ending with a slash.
 *
 * @since 1.0.0
 */
define( __NAMESPACE__ . '\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/* <<<<<<< CODE REGISTRATION >>>>>>> */

add_action(
	'admin_menu',
	function() {

		add_submenu_page(
			'tools.php',
			'Tools &mdash; Purple Turtle Creative',
			'PTC Tools',
			'manage_options',
			'ptc-tools',
			function() {
				if ( current_user_can( 'manage_options' ) ) {
					echo '<div style="margin:4rem 2rem;">';
					require_once PLUGIN_PATH . 'src/tools/database-cleanup.php';
					echo '</div>';
				} else {
					echo '<p><strong>You do not have the proper permissions to access this page.</strong></p>';
				}
			}
		);
	}
);
