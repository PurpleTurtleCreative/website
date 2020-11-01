<?php
/*
Plugin Name:   Tidy Nags
Description:   Removes all nags and displays them only in Dashboard > Nags
Version:       1.0
Author:        Purple Turtle Creative
Author URI:    https://purpleturtlecreative.com/
License:       Copyright (c) 2020 Michelle Blanchette
*/

/* Add Dashboard > Nags page */
add_action( 'admin_menu', function() {
	add_submenu_page(
		'index.php',
		'Plugin Nags',
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
});

/* Remove nags if not on Dashboard > Nags page */
add_action( 'admin_head', function() {
	if (
		isset( $_SERVER['REQUEST_URI'] )
		&& strpos( $_SERVER['REQUEST_URI'], 'index.php?page=ptc-tidy-nags' ) !== FALSE
	) {
		return;
	}
	remove_all_actions( 'admin_notices' );
	remove_all_actions( 'all_admin_notices' );
}, 1);
