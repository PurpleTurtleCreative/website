<?php
/*This file is part of HelloPTCPlugin, hello-elementor child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function HelloPTCPlugin_enqueue_child_styles() {
$parent_style = 'parent-style';
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version') );
	}
add_action( 'wp_enqueue_scripts', 'HelloPTCPlugin_enqueue_child_styles' );

/*Write here your own functions */

/* Show menu ordering meta field for blog Posts */
add_action( 'admin_init', function() {
  add_post_type_support( 'post', 'page-attributes' );
});

/* Hide admin menu bar for all users except editors */
if ( ! current_user_can( 'edit_pages' ) ) {
  add_filter( 'show_admin_bar', '__return_false' );
}
