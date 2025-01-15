<?php
/**
 * Plugin Name: PTC Dev Environment
 */

if ( 'production' === wp_get_environment_type() ) {
	return; // This plugin's functionality does not apply to production.
}

// //////////////////////////////////////////////////// //

/**
 * Dequeue WordFence's authentication JavaScript preventing empty password.
 *
 * @see wp-content/plugins/wordfence/modules/login-security/classes/controller/wordfencels.php
 */
add_action( 'login_enqueue_scripts', function() {
	wp_dequeue_script( 'wordfence-ls-login' );
	wp_dequeue_style( 'wordfence-ls-login' );
}, 999 );

/**
 * Authenticate all login requests if username is valid. Password can be blank.
 *
 * @see https://usersinsights.com/wordpress-user-login-hooks/
 */
add_filter( 'authenticate', function( $user, $username, $password ) {
	if ( ! is_a( $user, '\WP_User' ) && $username ) {
		$user = get_user_by( 'login', $username );
		if ( ! is_a( $user, '\WP_User' ) ) {
			$user = new \WP_Error( 404, "Could not find user with username <strong>{$username}</strong>. Autologin failed." );
		}
	}
	return $user;
}, 999, 3 );

/**
 * Prevent cURL error 28: Resolving timed out errors in local env.
 *
 * @link https://developer.wordpress.org/reference/hooks/http_request_timeout/
 */
add_filter( 'http_request_timeout', function ( $timeout_seconds ) {
	$timeout_seconds = 10;
	return $timeout_seconds;
}, 999, 1 );


/**
 * Use local development domain.
 */
add_filter( 'ptc_completionist_pro_licensing_provider_api_url', function ( $url ) {
	$url = str_replace(
		'https://purpleturtlecreative.com/',
		'http://localhost/',
		$url
	);
	return $url;
}, 999, 1 );
