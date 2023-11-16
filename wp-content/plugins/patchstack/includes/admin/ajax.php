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
			// Log tables actions.
			add_action( 'wp_ajax_patchstack_users_log_table', [ $this, 'users_log_table' ] );
			add_action( 'wp_ajax_patchstack_firewall_log_table', [ $this, 'firewall_log_table' ] );

			// License related actions.
			add_action( 'wp_ajax_patchstack_activate_license', [ $this, 'activate_license' ] );

			// Hide login related actions.
			add_action( 'wp_ajax_patchstack_send_new_url_email', [ $this, 'send_new_url_email' ] );
		}
	}

	/**
	 * Firewall logs pagination.
	 *
	 * @return array
	 */
	public function firewall_log_table() {
		if ( ! isset( $_POST['start'], $_POST['length'] ) || !ctype_digit( $_POST['start'] ) || !ctype_digit( $_POST['length'] ) ) {
			exit;
		}

		// Pull all entries, given parameters.
		global $wpdb;
		$entries = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.id, a.ip, a.flag, a.method, a.log_date, a.request_uri as referer, a.fid, b.description 
				FROM " . $wpdb->prefix . 'patchstack_firewall_log AS a
				LEFT JOIN ' . $wpdb->prefix . 'patchstack_logic AS b ON b.id = a.fid
				ORDER BY a.id DESC
				LIMIT %d, %d
			',
				[ wp_filter_nohtml_kses( $_POST['start'] ), wp_filter_nohtml_kses( $_POST['length'] ) ]
			)
		);

		// Get total amount of rows.
		$count          = $wpdb->get_var( 'SELECT COUNT(id) FROM ' . $wpdb->prefix . 'patchstack_firewall_log' );
		$firewall_rules = json_decode( get_option( 'patchstack_firewall_rules', '' ), true );
		$firewall_rules_v3 = json_decode( get_option( 'patchstack_firewall_rules_v3', [] ), true );
		$firewall_rules = array_merge($firewall_rules, $firewall_rules_v3);

		// Modify data if necessary.
		$list = [];
		foreach ( $entries as $entry ) {
			foreach ( $entry as $key => $value ) {
				if ( ! in_array( $key, [ 'referer' ] ) ) {
					$entry->$key = sanitize_textarea_field( $value );
				}
			}

			// Attempt to find the block reason.
			$reason = $wpdb->get_var( $wpdb->prepare( 'SELECT cname FROM ' . $wpdb->prefix . 'patchstack_logic WHERE id = %d LIMIT 1', [ $entry->fid ] ) );
			if ( $reason ) {
				$entry->fid = $reason;
			} elseif ( $firewall_rules != '' ) {
				foreach ( $firewall_rules as $rule ) {
					if ( isset( $rule['title'], $rule['cat'] ) && '55' . $rule['id'] == $entry->fid ) {
						$entry->fid         = $rule['cat'];
						$entry->description = $rule['title'];
					}
				}
			} else {
				$entry->fid = 'Unknown';
			}

			$list[] = $entry;
		}

		// Return output.
		wp_send_json(
			[
				'data'            => $list,
				'recordsFiltered' => $count,
				'recordsTotal'    => $count
			]
		);
	}

	/**
	 * Activity logs pagination.
	 *
	 * @return void
	 */
	public function users_log_table() {
		if ( ! isset( $_POST['start'], $_POST['length'] ) || !ctype_digit( $_POST['start'] ) || !ctype_digit( $_POST['length'] ) ) {
			exit;
		}

		// Determine if searching?
		global $wpdb;
		$searching = false;
		$likes     = [];
		if ( isset( $_POST['search'], $_POST['search']['value'] ) && $_POST['search']['value'] != '' ) {
			$val       = wp_filter_nohtml_kses( $_POST['search']['value'] );
			$searching = true;
			$columns   = [ 'author', 'ip', 'object', 'object_name', 'action' ];
			$search    = 'WHERE 1=2 ';
			foreach ( $columns as $column ) {
				array_push( $likes, '%' . $wpdb->esc_like( $val ) . '%' );
				$search .= 'OR ' . $column . ' LIKE %s';
			}
		}

		$logs = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT *
				FROM ' . $wpdb->prefix . 'patchstack_event_log ' . ( $searching ? $search : '' ) . '
				ORDER BY id DESC
				LIMIT %d, %d
			',
				array_merge( $likes, [ wp_filter_nohtml_kses( $_POST['start'] ), wp_filter_nohtml_kses( $_POST['length'] ) ] )
			)
		);

		$count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(id) FROM ' . $wpdb->prefix . 'patchstack_event_log ' . ( $searching ? $search : '' ), $likes ) );

		// Modify data if necessary.
		$list = [];
		foreach ( $logs as $log ) {
			$list[] = $log;
		}

		// Return output.
		wp_send_json(
			[
				'data'            => $list,
				'recordsFiltered' => $count,
				'recordsTotal'    => $count
			]
		);
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
	 * Send an email to the current logged in user that contains the new admin page URL.
	 *
	 * @return void
	 */
	public function send_new_url_email() {
		global $current_user;
		$subject = __( 'New Login URL', 'patchstack' );
		$message = '<br /><br />Your login page is now  here: <strong> <a href="' . get_site_url() . '/' . get_site_option( 'patchstack_rename_wp_login' ) . '">' . get_site_url() . '/' . get_site_option( 'patchstack_rename_wp_login' ) . '</strong></a>';
		$email_sent = wp_mail( $current_user->user_email, $subject, $message );
		die( $email_sent ? 'success' : 'fail' );
	}
}
