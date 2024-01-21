<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class is used as a base for communicating with the Patchstack API.
 */
class P_Api extends P_Core {

	/**
	 * @var integer The current blog id.
	 */
	public $blog_id;

	/**
	 * Add the actions required for the API.
	 *
	 * @param Patchstack $core
	 * @return void
	 */
	public function __construct( $core ) {
		parent::__construct( $core );
		$this->blog_id = get_current_blog_id();
		add_action( 'patchstack_update_license_status', [ $this, 'update_license_status' ] );
		add_action( 'patchstack_send_ping', [ $this, 'ping' ] );
	}

	/**
	 * Get the API token.
	 *
	 * @param string  $clientid The API client ID.
	 * @param string  $secretkey The API secret key.
	 * @param boolean $fresh Whether or not to get a fresh token.
	 * @return null|string
	 */
	public function get_access_token( $clientid = '', $secretkey = '', $fresh = false ) {
		// Get current access token, if it exists.
		$token_data = $this->get_blog_option( $this->blog_id, 'patchstack_api_token', false );

		// If we do not need a fresh token, get the current one if it's not expired.
		if ( ! $fresh && isset( $token_data['token'] ) && ! $this->has_expired( $token_data['expiresin'] ) ) {
			return $token_data['token'];
		}

		// Call API and get the new access token.
		$response = $this->fetch_access_token( $clientid, $secretkey );
		if ( $response && $response->result == 'success' ) {
			$this->update_blog_option(
				$this->blog_id,
				'patchstack_api_token',
				[
					'token'     => $response->message,
					'expiresin' => $response->expiresin,
				]
			);
			return $response->message;
		}

		// If we reach this, it means we were not able to get the access token.
		$this->update_blog_option( $this->blog_id, 'patchstack_api_token', '' );
		return null;
	}

	/**
	 * Fetch the API Token from API Server.
	 *
	 * @param string $clientid The API client ID.
	 * @param string $secretkey The API secret key.
	 * @return string|array
	 */
	public function fetch_access_token( $clientid = '', $secretkey = '' ) {
		// Skeleton for the response data.
		$response_data = (object) [
			'result'    => '',
			'message'   => '',
			'expiresin' => '',
		];

		// Determine if the license id/key is set.
		$client_id = $this->get_blog_option( $this->blog_id, 'patchstack_clientid', $clientid );

		// Decrypt the secret key, if it is encrypted.
		$client_secret = $this->get_blog_option( $this->blog_id, 'patchstack_secretkey', $secretkey );
		$client_nonce = $this->get_blog_option( $this->blog_id, 'patchstack_secretkey_nonce', false );
		if ( $client_nonce ) {
			$client_secret = $this->decrypt( $client_secret, $client_nonce );
		}

		// Make sure these values are set.
		if ( empty( $client_id ) || empty( $client_secret ) ) {
			$response_data->result  = 'failed';
			$response_data->message = __( 'API keys missing! Unable to obtain an access token.', 'patchstack' );
			return $response_data;
		}

		// Send a request to our server to obtain the access token.
		$response = wp_remote_post(
			$this->plugin->auth_url . '/oauth/token',
			[
				'method'      => 'POST',
				'timeout'     => 60,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => [],
				'body'        => [
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'grant_type'    => 'client_credentials',
				],
				'cookies'     => [],
			]
		);

		// Stop if we received an error from the API.
		if ( is_wp_error( $response ) ) {
			$response_data->result  = 'failed';
			$response_data->message = __( 'Unexpected error! Unable to obtain an access token.', 'patchstack' ) . $response->get_error_message();
			return $response_data;
		}

		// Parse the result.
		$result = json_decode( wp_remote_retrieve_body( $response ) );
		if ( isset( $result->access_token ) ) {
			$response_data->result    = 'success';
			$response_data->message   = $result->access_token;
			$response_data->expiresin = $result->expires_in;

			// We need to know when the token expires.
			// Defer to 'expires' if it is provided instead.
			if ( isset( $result->expires_in ) ) {
				if ( ! is_numeric( $result->expires_in ) ) {
					$response_data->message = 'expires_in value must be an integer';
					return $response_data;
				}
				$response_data->expiresin = $result->expires_in != 0 ? time() + $result->expires_in : 0;
			}

			return $response_data;
		} elseif ( isset( $result->error ) ) {
			$response_data->result  = $result->error;
			$response_data->message = __( 'Unexpected error! Unable to obtain an access token.', 'patchstack' ) . $result->message;
			return $response_data;
		}
	}

	/**
	 * Checks if the API token has expired.
	 *
	 * @param integer $expiresin API token expiry.
	 * @return boolean If the token has expired.
	 */
	public function has_expired( $expiresin ) {
		return ( $expiresin < ( time() + 30 ) );
	}

	/**
	 * Retrieve the status of a license.
	 *
	 * @return void|array
	 */
	public function update_license_status() {
		// Get current license status.
		$response = $this->send_request( '/api/license/verify', 'GET' );

		// Update the representing options.
		if ( isset( $response['expires_at'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_license_expiry', $response['expires_at'] );
		}

		if ( isset( $response['free'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_license_free', $response['free'] == false ? 0 : 1 );

			if ( $response['free'] == true ) {
				$this->update_blog_option( $this->blog_id, 'patchstack_show_settings', 0 );
				$this->update_blog_option( $this->blog_id, 'patchstack_firewall_rules_v3', '[]' );
			} else {
				$this->send_header_request();
			}
		}

		if ( isset( $response['active'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_license_activated', $response['active'] == true );
		}

		if ( isset( $response['class'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_subscription_class', $response['class'] );
			$this->update_blog_option( $this->blog_id, 'patchstack_last_license_check', time() );
		}

		if ( isset( $response['managed'], $response['managed_string'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_managed', $response['managed'] );
			$this->update_blog_option( $this->blog_id, 'patchstack_managed_text', $response['managed_string'] );
		}

		if ( isset( $response['site_id'] ) ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_site_id', $response['site_id'] );
		}

		return $response;
	}

	/**
	 * Send a request to the API with optionally POST data.
	 *
	 * @param string $url
	 * @param string $request
	 * @param array  $data
	 * @return void|array If successful array, otherwise void.
	 */
	public function send_request( $url, $request, $data = [] ) {
		// Attempt to get the access token.
		$token = $this->get_access_token();
		if ( empty( $token ) ) {
			return;
		}

		// Send the remote request using the WordPress built-in method.
		$response = wp_remote_request(
			$this->plugin->api_url . $url,
			[
				'method'      => $request,
				'timeout'     => 60,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => [
					'Authorization' => 'Bearer ' . $token,
					'LicenseID'     => $this->get_blog_option( $this->blog_id, 'patchstack_clientid', 0 ),
					'Source-Host'   => get_site_url(),
				],
				'body'        => $data,
				'cookies'     => [],
			]
		);

		// Check error or status code.
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
			$this->update_blog_option( $this->blog_id, 'patchstack_api_token', '' );
			return;
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Send a request to our API for the IP address header.
	 */
	public function send_header_request()
	{
		$header = get_option( 'patchstack_firewall_ip_header', '' );
		$computed = get_option( 'patchstack_ip_header_computed', 0 );

		if ( $header == '' && ! $computed ) {
			// Create an OTT token.
			$ott = md5( wp_generate_password( 32, true, true ) );
			update_option( 'patchstack_ott_action', $ott );
	
			// Tell our API.
			wp_remote_request(
				$this->plugin->api_url . '/api/header',
				[
					'method'      => 'POST',
					'timeout'     => 60,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => [
						'Source-Host'   => get_site_url(),
					],
					'body'        => [
						'token' => $ott,
						'url' => get_site_url()
					],
					'cookies'     => [],
				]
			);
		}
	}

	/**
	 * Get the firewall rules.
	 *
	 * @return array The firewall rules.
	 */
	public function post_firewall_rule_json() {
		return $this->send_request( '/api/get-rules/3', 'POST' );
	}

	/**
	 * Get the .htaccess rules.
	 *
	 * @param array $settings The settings on which .htaccess rules to get.
	 * @return array The .htaccess rules.
	 */
	public function post_firewall_rule( $settings ) {
		return $this->send_request( '/api/rules', 'POST', $settings );
	}

	/**
	 * Get the .htaccess firewall rules.
	 *
	 * @return array The .htaccess rules.
	 */
	public function post_firewall_htaccess_rule() {
		return $this->send_request( '/api/rules/htaccess', 'POST' );
	}

	/**
	 * Send the firewall logs to the API.
	 *
	 * @param array $logs
	 * @return array
	 */
	public function upload_firewall_logs( $logs ) {
		return $this->send_request( '/api/logs/log', 'POST', $logs );
	}

	/**
	 * Send the activity logs to the server.
	 *
	 * @param array $logs
	 * @return array
	 */
	public function upload_activity_logs( $logs ) {
		return $this->send_request( '/api/activity/log', 'POST', $logs );
	}

	/**
	 * Send WordPress core, theme, plugins versions and information to the API.
	 *
	 * @param array $software
	 * @return array
	 */
	public function upload_software( $software ) {
		return $this->send_request( '/api/sw/json', 'POST', $software );
	}

	/**
	 * Update the firewall status.
	 *
	 * @param array $status
	 * @return array
	 */
	public function update_firewall_status( $status ) {
		if ( $this->get_option( 'patchstack_license_free', 0 ) == 1 ) {
			return;
		}

		return $this->send_request( '/api/firewall/update/status', 'POST', $status );
	}

	/**
	 * Update the URL on the API.
	 *
	 * @param array $url The current URL of the site.
	 * @return array
	 */
	public function update_url( $url ) {
		return $this->send_request( '/api/plugin/update/url', 'POST', $url );
	}

	/**
	 * Send list of sites and get the id and secret key in response.
	 *
	 * @param array $sites
	 * @return array
	 */
	public function get_site_licenses( $sites ) {
		return $this->send_request( '/api/multisite-keys', 'POST', $sites );
	}

	/**
	 * Send a ping to the Patchstack API every 3 hours to make sure that the plugin is still running.
	 *
	 * @return void
	 */
	public function ping() {
		$this->send_request( '/api/ping', 'POST', [ 'firewall' => $this->get_option( 'patchstack_basic_firewall' ) == 1 ? 1 : 0 ] );
	}
}
