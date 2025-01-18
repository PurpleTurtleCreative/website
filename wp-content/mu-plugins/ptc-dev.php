<?php
/**
 * Plugin Name: PTC Dev Environment
 */

namespace Purple_Turtle_Creative\Dev_Env;

if ( 'production' === wp_get_environment_type() ) {
	return; // This plugin's functionality does not apply to production.
}

add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\handle_login_enqueue_scripts', 999 );
add_filter( 'authenticate', __NAMESPACE__ . '\handle_authenticate', 999, 3 );
add_filter( 'http_request_timeout', __NAMESPACE__ . '\handle_http_request_timeout', 999, 1 );
// add_filter( 'ptc_completionist_pro_licensing_provider_api_url', __NAMESPACE__ . '\handle_ptc_completionist_pro_licensing_provider_api_url', 999, 1 );

// //////////////////////////////////////////////////// //

/**
 * Dequeue WordFence's authentication JavaScript preventing empty password.
 *
 * @see wp-content/plugins/wordfence/modules/login-security/classes/controller/wordfencels.php
 */
function handle_login_enqueue_scripts() {
	wp_dequeue_script( 'wordfence-ls-login' );
	wp_dequeue_style( 'wordfence-ls-login' );
}

/**
 * Authenticate all login requests if username is valid. Password can be blank.
 *
 * @see https://usersinsights.com/wordpress-user-login-hooks/
 *
 * @param null|\WP_User|\WP_Error $user \WP_User if the user is authenticated. \WP_Error or null otherwise.
 * @param string                  $username Username or email address.
 * @param string                  $password User password.
 *
 * @return null|\WP_User|\WP_Error
 */
function handle_authenticate( $user, $username, $password ) {
	if ( ! is_a( $user, '\WP_User' ) && $username ) {
		$user = get_user_by( 'login', $username );
		if ( ! is_a( $user, '\WP_User' ) ) {
			$user = new \WP_Error( 404, "Could not find user with username <strong>{$username}</strong>. Autologin failed." );
		}
	}
	return $user;
}

/**
 * Prevent cURL error 28: Resolving timed out errors in local env.
 *
 * @link https://developer.wordpress.org/reference/hooks/http_request_timeout/
 *
 * @param int $timeout_seconds The timeout duration.
 *
 * @return int
 */
function handle_http_request_timeout( $timeout_seconds ) {
	$timeout_seconds = 10;
	return $timeout_seconds;
};

/**
 * Use local development domain for testing ptc-resources-server changes.
 *
 * @param string $url The licensing provider endpoint URL.
 *
 * @return string
 */
function handle_ptc_completionist_pro_licensing_provider_api_url( $url ) {
	$url = str_replace(
		'https://purpleturtlecreative.com/',
		'http://localhost/',
		$url
	);
	return $url;
}
