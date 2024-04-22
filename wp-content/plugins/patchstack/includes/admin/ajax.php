<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class is used for any admin AJAX interactions.
 */
class P_Admin_Ajax extends P_Core {

	/**
	 * Add the actions required for AJAX interactions.
	 *
	 * @param Patchstack $core
	 * @return void
	 */
	public function __construct( $core ) {
		parent::__construct( $core );
		if ( isset( $_POST['PatchstackNonce'] ) && current_user_can( 'manage_options' ) && wp_verify_nonce( $_POST['PatchstackNonce'], 'patchstack-nonce' ) ) {
			// License related actions.
			add_action( 'wp_ajax_patchstack_activate_license', [ $this, 'activate_license' ] );

			// Auto license activator.
			add_action( 'wp_ajax_patchstack_activate_auto', [ $this, 'auto_activate' ] );
			add_action( 'wp_ajax_patchstack_activation_status', [ $this, 'activation_status' ] );
		}
	}

	/**
	 * Test and activate a new license.
	 *
	 * @return void
	 */
	public function activate_license() {
		if ( ! isset( $_POST['key'] ) || strpos( $_POST['key'], '-' ) === false) {
			wp_send_json(
				[ 
					'result' => 'error',
					'error_message' => 'An invalid API key was provided.'
				]
			);
		}

		// Since we have the keys combined into one now, split them up here.
		$split = explode('-', $_POST['key']);
		$secretkey = $split[0];
		$clientid = $split[1];

		// Test the new keys.
		update_option( 'patchstack_api_token', '' );
		$results = $this->plugin->activation->alter_license( wp_filter_nohtml_kses( $clientid ), wp_filter_nohtml_kses( $secretkey ), 'activate' );
		if ( $results ) {
			$response            = $this->plugin->api->update_license_status();
			$results['response'] = $response;
			wp_send_json( $results );
		}
	}

	/**
	 * Attempt to auto activate the license after a plugin activation, if no current license exists.
	 * 
	 * @return void
	 */
	public function auto_activate() {
		$secretToken = get_option( 'patchstack_activation_secret', '' );

		// Only continue if we have a secret token.
		$autoActivated = false;
		if ( ! empty( $secretToken ) ) {
			$autoActivated = $this->plugin->api->send_secret_token( $secretToken );
		}

		wp_send_json( [
			'result'  => $autoActivated || get_option( 'patchstack_clientid', false ) != false ? 'success' : 'error'
		] );
	}

	/**
	 * Get the current license activation status.
	 * 
	 * @return void
	 */
	public function activation_status() {
		wp_send_json( [
			'activated' => get_option( 'patchstack_clientid', false ) != false
		] );
	}
}
