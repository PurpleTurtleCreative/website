<?php
/*
Plugin Name:   Tidy Nags
Description:   Removes all nags and displays them only in Dashboard > Nags
Version:       1.0
Author:        Purple Turtle Creative
Author URI:    https://purpleturtlecreative.com/
License:       Copyright (c) 2020 Michelle Blanchette
*/

add_action( 'admin_menu', 'ptc_tidy_nags__admin_menu' );
add_action( 'admin_head', 'ptc_tidy_nags__admin_head', 1 );

/**
 * Adds the Nags admin page.
 */
function ptc_tidy_nags__admin_menu() {
	add_submenu_page(
		'index.php',
		'Admin Nags',
		'Nags',
		'manage_options',
		'ptc-tidy-nags',
		function() {
			?>
			<style>
				#wpcontent {
					padding-right: 20px;
					padding-top: 10px;
				}
				#wpcontent .notice {
					margin: 15px 0;
				}
			</style>
			<p><em><strong>Tidy Nags</strong> has removed plugin notices across your admin area. You may review all current system nags here.</em></p>
			<?php
		},
		999
	);
}

/**
 * Removes nags if not on the Nags admin page.
 */
function ptc_tidy_nags__admin_head() {
	$current_screen = get_current_screen();
	if ( $current_screen && 'dashboard_page_ptc-tidy-nags' !== $current_screen->id ) {
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}
}
