<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class provides the firewall functionality.
 */
class P_Firewall extends P_Core {

	/**
	 * Parse the firewall and whitelist rules and determine if it's valid.
	 * Then set the types with all server/client variables and launch the processor.
	 *
	 * @param bool       $from_main Whether or not the firewall is loaded from the main script or not.
	 * @param Patchstack $core
	 * @param bool       $skip Whether or not to process and execute the rules.
	 * @return void
	 */
	public function __construct( $from_main = false, $core = null, $skip = false ) {
		if ( ! $from_main || ! $core ) {
			if ( $core ) {
				parent::__construct( $core );
			}
			return;
		}

		parent::__construct( $core );

		// If we only want to initialize the firewall but not execute the rules.
		if ( $skip ) {
			return;
		}

		// Process the firewall rules.
		$this->processor();
	}

	/**
	 * Check the custom whitelist rules defined in the backend of WordPress
	 * and attempt to match it with the request.
	 *
	 * @return boolean
	 */
	private function is_custom_whitelisted() {
		$whitelist = str_replace( '<?php exit; ?>', '', get_option( 'patchstack_whitelist', '' ) );
		if ( empty( $whitelist ) ) {
			return false;
		}

		// Loop through all lines.
		$lines = explode( "\n", $whitelist );
		$ip    = $this->get_ip();

		foreach ( $lines as $line ) {
			$t = explode( ':', $line );

			if ( count( $t ) == 2 ) {
				$val = strtolower( trim( $t[1] ) );
				switch ( strtolower( $t[0] ) ) {
					// IP address match.
					case 'ip':
						if ( $ip == $val ) {
							return true;
						}
						break;
					// Payload match.
					case 'payload':
						if ( count( $_POST ) > 0 && strpos( strtolower( print_r( $_POST, true ) ), $val ) !== false ) {
							return true;
						}

						if ( count( $_GET ) > 0 && strpos( strtolower( print_r( $_GET, true ) ), $val ) !== false ) {
							return true;
						}
						break;
					// URL match.
					case 'url':
						if ( strpos( strtolower( $_SERVER['REQUEST_URI'] ), $val ) !== false ) {
							return true;
						}
						break;
				}
			}
		}

		return false;
	}

	/**
	 * Determine if the request should be whitelisted.
	 *
	 * @return boolean
	 */
	private function is_whitelisted() {
		// First check if the user has custom whitelist rules configured.
		if ( $this->is_custom_whitelisted() ) {
			return true;
		}

		// Load the whitelist.
		$whitelists = get_option( 'patchstack_whitelist_rules', '' );
		if ( $whitelists == null || $whitelists == '' ) {
			return false;
		}

		// Parse the whitelist.
		$whitelists = json_decode( str_replace( '<?php exit; ?>', '', $whitelists ), true );

		// Grab visitor's IP address and request data.
		$client_ip = $this->get_ip();
		$requests  = $this->capture_request();

		foreach ( $whitelists as $whitelist ) {
			$whitelist_rule = json_decode( $whitelist['rule'] );
			$matched_rules  = 0;

			// If matches on all request methods, only 1 rule match is required to whitelist.
			if ( $whitelist_rule->method === 'ALL' ) {
				$count_rules = 1;
			} else {
				if ( ! is_null( $whitelist_rule ) ) {
					$count_rules = $whitelist_rule->rules;
					$count_rules = $this->count_rules( $count_rules );
				}
			}

			// If an IP address match is given, determine if it matches.
			$ip = isset( $whitelist_rule->rules, $whitelist_rule->rules->ip_address ) ? $whitelist_rule->rules->ip_address : null;
			if ( ! is_null( $ip ) ) {
				if ( strpos( $ip, '*' ) !== false ) {
					$whitelisted_ip = $this->plugin->ban->check_wildcard_rule( $client_ip, $ip );
				} elseif ( strpos( $ip, '-' ) !== false ) {
					$whitelisted_ip = $this->plugin->ban->check_range_rule( $client_ip, $ip );
				} elseif ( strpos( $ip, '/' ) !== false ) {
					$whitelisted_ip = $this->plugin->ban->check_subnet_mask_rule( $client_ip, $ip );
				} elseif ( $client_ip == $ip ) {
					$whitelisted_ip = true;
				} else {
					$whitelisted_ip = false;
				}
			} else {
				$whitelisted_ip = true;
			}

			foreach ( $requests as $key => $request ) {

				// Treat the raw POST data string as the body contents of all values combined.
				if ( $key == 'rulesRawPost' ) {
					$key = 'rulesBodyAll';
				}
				
				if ( $whitelist_rule->method == $requests['method'] || $whitelist_rule->method == 'ALL' ) {
					$test = strtolower( preg_replace( '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', '->$0', $key ) );
					$rule = array_reduce(
						explode( '->', $test ),
						function ( $o, $p ) {
							if ( ! isset( $o->$p ) ) {
								return null;
							}
							
							return $o->$p;
						},
						$whitelist_rule
					);

					if ( ! is_null( $rule ) && substr( $key, 0, 4 ) == 'rule' && $this->is_rule_match( $rule, $request ) ) {
						$matched_rules++;
					}
				}
			}

			if ( $matched_rules >= $count_rules && $whitelisted_ip ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Retrieve all HTTP headers that start with HTTP_.
	 *
	 * @return array
	 */
	private function get_headers() {
		$headers = array();
		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
				$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
			}
		}

		return $headers;
	}

	/**
	 * Retrieve information about any file uploads.
	 *
	 * @return array
	 */
	private function get_file_upload_data() {
		if ( ! isset( $_FILES ) || ! is_array( $_FILES ) || count( $_FILES ) == 0 ) {
			return '';
		}

		// Extract the information we need from $_FILES.
		$return = array();
		foreach ( $_FILES as $key => $data ) {
			foreach ( $data as $key2 => $data2 ) {

				// We only want the name and type.
				if ( ! in_array( $key2, array( 'name', 'type' ) ) ) {
					continue;
				}

				if ( ! is_array( $data2 ) ) {
					$return[] = $key2 . '=' . $data2;
				} else {
					$return[] = $key2 . '=' . @$this->multi_implode( $data2, '&' . $key2 . '=' );
				}
			}
		}

		return implode( '&', $return );
	}


	/**
	 * Returns all request methods and parameters
	 *
	 * @return string
	 */
	private function capture_request() {
		$data = $this->capture_keys();

		// Get the method and URL.
		$method   = isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$rulesUri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

		// Store the header values in different formats.
		$rulesHeadersKeys         = array();
		$rulesHeadersValues       = array();
		$rulesHeadersCombinations = array();

		// Retrieve the headers.
		$headers         = $this->get_headers();
		$rulesHeadersAll = implode( ' ', $headers );
		foreach ( $headers as $name => $value ) {
			$rulesHeadersKeys[]         = $name;
			$rulesHeadersValues[]       = $value;
			$rulesHeadersCombinations[] = $name . ': ' . $value;
		}

		// Store the $_POST values in different formats.
		$rulesBodyKeys         = array();
		$rulesBodyValues       = array();
		$rulesBodyCombinations = array();

		// Retrieve the $_POST values.
		$rulesBodyAll = urldecode( http_build_query( $data['POST'] ) );
		foreach ( $data['POST'] as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = @$this->multi_implode( $value, ' ' );
			}
			$rulesBodyKeys[]         = $key;
			$rulesBodyValues[]       = $value;
			$rulesBodyCombinations[] = $key . '=' . $value;
		}

		// Store the $_GET values in different formats.
		$rulesParamsKeys         = array();
		$rulesParamsValues       = array();
		$rulesParamsCombinations = array();

		// Retrieve the $_GET values.
		$rulesParamsAll = urldecode( http_build_query( $data['GET'] ) );
		foreach ( $data['GET'] as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = @$this->multi_implode( $value, ' ' );
			}
			$rulesParamsKeys[]         = $key;
			$rulesParamsValues[]       = $value;
			$rulesParamsCombinations[] = $key . '=' . $value;
		}

		// Raw POST data.
		$rulesRawPost = @file_get_contents( 'php://input' );

		// Data about file uploads.
		$rulesFile = $this->get_file_upload_data();

		// Return each value as its own array.
		return compact(
			'method',
			'rulesFile',
			'rulesRawPost',
			'rulesUri',
			'rulesHeadersAll',
			'rulesHeadersKeys',
			'rulesHeadersValues',
			'rulesHeadersCombinations',
			'rulesBodyAll',
			'rulesBodyKeys',
			'rulesBodyValues',
			'rulesBodyCombinations',
			'rulesParamsAll',
			'rulesParamsKeys',
			'rulesParamsValues',
			'rulesParamsCombinations'
		);
	}

	/**
	 * Capture the keys of the request.
	 *
	 * @return array
	 */
	private function capture_keys() {
		// Data we want to go through.
		$data = array(
			'POST' => isset( $_POST ) ? $_POST : array(),
			'GET'  => isset( $_GET ) ? $_GET : array(),
		);

		// Determine if there are any keys we should remove from the data set.
		if ( get_option( 'patchstack_whitelist_keys_rules', '' ) == '' ) {
			return $data;
		}

		// Must be valid JSON and decodes to at least 2 primary data arrays.
		$keys = json_decode( get_option( 'patchstack_whitelist_keys_rules' ), true );
		if ( ! $keys || ! is_array( $keys ) || $keys && count( $keys ) < 2 ) {
			return $data;
		}

		// Remove the keys where necessary, go through all data types (GET, POST).
		foreach ( $keys as $type => $entries ) {

			// Go through all whitelisted actions.
			foreach ( $entries as $entry ) {
				$t = explode( '.', $entry );

				// For non-multidimensional array checks.
				if ( count( $t ) == 1 ) {
					// If the value itself exists.
					if ( isset( $data[ $type ][ $t[0] ] ) ) {
						unset( $data[ $type ][ $t[0] ] );
					}

					// For pattern checking.
					if ( strpos( $t[0], '*' ) !== false ) {
						$star = explode( '*', $t[0] );

						// Loop through all $_POST, $_GET values.
						foreach ( $data as $method => $values ) {
							foreach ( $values as $key => $value ) {
								if ( ! is_array( $value ) && strpos( $key, $star[0] ) !== false ) {
									unset( $data[ $method ][ $key ] );
								}
							}
						}
					}
					continue;
				}

				// For multidimensional array checks.
				$end  =& $data[ $type ];
				$skip = false;
				foreach ( $t as $var ) {
					if ( ! isset( $end[ $var ] ) ) {
						$skip = true;
						break;
					}
					$end =& $end[ $var ];
				}

				// Since we cannot unset it due to it being a reference variable,
				// we just set it to an empty string instead.
				if ( ! $skip ) {
					$end = '';
				}
			}
		}

		return $data;
	}

	/**
	 * Implode array recursively.
	 *
	 * @param $array
	 * @param $glue
	 * @return bool|string
	 */
	private function multi_implode( $array, $glue ) {
		$ret = '';

		foreach ( $array as $item ) {
			if ( is_array( $item ) ) {
				$ret .= $this->multi_implode( $item, $glue ) . $glue;
			} else {
				$ret .= $item . $glue;
			}
		}

		return substr( $ret, 0, 0 - strlen( $glue ) );
	}

	/**
	 * Determine if the request matches the given firewall or whitelist rule.
	 *
	 * @param string       $rule
	 * @param string|array $request
	 * @return bool
	 */
	private function is_rule_match( $rule, $request ) {
		$is_matched = false;
		if ( is_array( $request ) ) {
			foreach ( $request as $key => $value ) {
				$is_matched = $this->is_rule_match( $rule, $value );
				if ( $is_matched ) {
					return $is_matched;
				}
			}
		} else {
			return preg_match( $rule, urldecode( $request ) );
		}

		return $is_matched;
	}

	/**
	 * Count the number of rules.
	 *
	 * @param array $array
	 * @return integer
	 */
	private function count_rules( $array ) {
		$counter = 0;
		if ( is_object( $array ) ) {
			$array = (array) $array;
		}

		if ( $array['uri'] ) {
			$counter++;
		}

		foreach ( array( 'body', 'params', 'headers' ) as $type ) {
			foreach ( $array[ $type ] as $key => $value ) {
				if ( ! is_null( $value ) ) {
					$counter++;
				}
			}
		}

		return $counter;
	}

	/**
	 * Runs the firewall rules processor.
	 *
	 * @return void
	 */
	private function processor() {
		// Load the firewall rules.
		$rules = json_decode( get_option( 'patchstack_firewall_rules', '' ), true );
		if ( $rules == '' || is_null( $rules ) ) {
			return;
		}

		// Determine if the user is temporarily blocked from the site.
		if ( $this->is_auto_ip_blocked() > $this->get_option( 'patchstack_autoblock_attempts', 10 ) && ! $this->is_authenticated() ) {
			$this->display_error_page( 22 );
		}

		// Check for whitelist.
		$is_whitelisted = $this->is_whitelisted();

		// Obtain the IP address and request data.
		$client_ip = $this->get_ip();
		$requests  = $this->capture_request();

		// Iterate through all root objects.
		foreach ( $rules as $firewall_rule ) {
			$blocked_count                     = 0;
			$firewall_rule['bypass_whitelist'] = isset( $firewall_rule['bypass_whitelist'] ) ? $firewall_rule['bypass_whitelist'] : false;

			// Do we need to skip the whitelist for a particular rule?
			if ( isset( $firewall_rule['bypass_whitelist'] ) && ! $firewall_rule['bypass_whitelist'] && $is_whitelisted ) {
				continue;
			}

			$rule_terms = json_decode( $firewall_rule['rule'] );

			// Determine if we should match the IP address.
			$ip = isset( $rule_terms->rules->ip_address ) ? $rule_terms->rules->ip_address : null;
			if ( ! is_null( $ip ) ) {
				$matched_ip = false;
				if ( strpos( $ip, '*' ) !== false ) {
					$matched_ip = $this->plugin->ban->check_wildcard_rule( $client_ip, $ip );
				} elseif ( strpos( $ip, '-' ) !== false ) {
					$matched_ip = $this->plugin->ban->check_range_rule( $client_ip, $ip );
				} elseif ( strpos( $ip, '/' ) !== false ) {
					$matched_ip = $this->plugin->ban->check_subnet_mask_rule( $client_ip, $ip );
				} elseif ( $client_ip == $ip ) {
					$matched_ip = true;
				}

				if ( ! $matched_ip ) {
					continue;
				}
			}

			// If matches on all request methods, only 1 rule match is required to block
			if ( $rule_terms->method === 'ALL' ) {
				$count_rules = 1;
			} else {
				$count_rules = json_decode( json_encode( $rule_terms->rules ), true );
				$count_rules = $this->count_rules( $count_rules );
			}

			// Loop through all request data that we captured.
			foreach ( $requests as $key => $request ) {

				// Treat the raw POST data string as the body contents of all values combined.
				if ( $key == 'rulesRawPost' ) {
					$key = 'rulesBodyAll';
				}

				// Determine if the requesting method matches.
				if ( $rule_terms->method == $requests['method'] || $rule_terms->method == 'ALL' || $rule_terms->method == 'GET' || ( $rule_terms->method == 'FILES' && $this->is_file_upload() ) ) {
					$test = strtolower( preg_replace( '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', '->$0', $key ) );
					$exp  = explode( '->', $test );

					// Determine if a rule exists for this request.
					$rule = array_reduce(
						$exp,
						function ( $o, $p ) {
							if ( ! isset( $o->$p ) ) {
								return null;
							}

							return $o->$p;
						},
						$rule_terms
					);

					// Determine if the rule matches the request.
					if ( ! is_null( $rule ) && substr( $key, 0, 4 ) == 'rule' && $this->is_rule_match( $rule, $request ) ) {
						$blocked_count++;
					}
				}
			}

			// Determine if the user should be blocked.
			if ( $blocked_count >= $count_rules ) {
				if ( $rule_terms->type == 'BLOCK' ) {
					$this->block_user( $firewall_rule['id'], (bool) $firewall_rule['bypass_whitelist'] );
				} elseif ( $rule_terms->type == 'LOG' ) {
					$this->log_user( $firewall_rule['id'] );
				} elseif ( $rule_terms->type == 'REDIRECT' ) {
					$this->redirect_user( $firewall_rule['id'], $rule_terms->type_params );
				}
			}
		}
	}

	/**
	 * Determine if the current request is a file upload.
	 *
	 * @return boolean
	 */
	private function is_file_upload() {
		return isset( $_FILES ) && count( $_FILES ) > 0;
	}

	/**
	 * Automatically block the user if there are many blocked requests in a short period of time.
	 *
	 * @return integer
	 */
	public function is_auto_ip_blocked() {
		// Calculate block time.
		$minutes = (int) $this->get_option( 'patchstack_autoblock_minutes', 30 );
		$timeout = (int) $this->get_option( 'patchstack_autoblock_blocktime', 60 );
		if ( empty( $minutes ) || empty( $timeout ) ) {
			$time = 30 + 60;
		} else {
			$time = $minutes + $timeout;
		}

		// Determine if the user should be blocked.
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT COUNT(*) as numIps FROM ' . $wpdb->prefix . "patchstack_firewall_log WHERE block_type = 'BLOCK' AND apply_ban = 1 AND ip = '%s' AND log_date >= ('" . current_time( 'mysql' ) . "' - INTERVAL %d MINUTE)", array( $this->get_ip(), $time ) ),
			OBJECT
		);

		if ( ! isset( $results, $results[0], $results[0]->numIps ) ) {
			return 0;
		}
		return $results[0]->numIps;
	}

	/**
	 * Block the user, and log, do whatever is necessary.
	 *
	 * @param string $rule
	 * @param bool   $bypass
	 * @return void
	 */
	private function block_user( $rule, $bypass = false ) {
		if ( ! $this->is_authenticated( $bypass ) ) {
			$this->display_error_page( '55' . intval( $rule ) );
		}
	}

	/**
	 * Log the user action.
	 *
	 * @param string $rule
	 * @return void
	 */
	private function log_user( $rule ) {
		$this->log_hacker( $rule, '', 'LOG' );
	}

	/**
	 * Log the user action and redirect.
	 *
	 * @param integer $rule_id
	 * @param string  $redirect
	 * @return void
	 */
	private function redirect_user( $rule_id, $redirect ) {
		$this->log_hacker( $rule_id, '', 'REDIRECT' );

		// Don't redirect an invalid URL.
		if ( ! $redirect || stripos( $redirect, 'http' ) === false ) {
			return;
		}

		ob_start();
		header( 'Location: ' . $redirect );
		ob_end_flush();
		exit;
	}

	/**
	 * Determine if the user is authenticated and in the list of whitelisted roles.
	 *
	 * @param bool $bypass
	 * @return bool
	 */
	public function is_authenticated( $bypass = false ) {
		if ( $bypass || ! is_user_logged_in() ) {
			return false;
		}

		// Get the whitelisted roles.
		$roles = $this->get_option( 'patchstack_basic_firewall_roles', array( 'administrator', 'editor', 'author' ) );
		if ( ! is_array ( $roles ) ) {
			return false;
		}
		
		// Special scenario for super admins on a multisite environment.
		if ( in_array( 'administrator', $roles ) && is_multisite() && is_super_admin() ) {
			return true;
		}

		// User is logged in, determine the role.
		$user = wp_get_current_user();
		if ( ! isset( $user->roles ) || count( (array) $user->roles ) == 0 ) {
			return false;
		}

		// Is the user in the whitelist roles list?
		$role_count = array_intersect( $user->roles, $roles );
		return count( $role_count ) != 0;
	}

	/**
	 * Log the blocked request.
	 *
	 * @param integer $fid firewall
	 * @param array   $query_vars
	 * @param string  $block_type
	 * @param array   $block_params
	 * @return void
	 */
	private function log_hacker( $fid = 1, $post_data = '', $block_type = 'BLOCK' ) {
		global $wpdb;
		if ( ! $wpdb || $fid == 22 || $fid == 23 ) {
			return;
		}

		// Insert into the logs.
		$wpdb->insert(
			$wpdb->prefix . 'patchstack_firewall_log',
			array(
				'ip'          => $this->get_ip(),
				'request_uri' => isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '',
				'user_agent'  => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '',
				'method'      => isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '',
				'fid'         => $fid,
				'flag'        => '',
				'post_data'   => $post_data != '' ? json_encode( $post_data ) : $this->get_post_data(),
				'block_type'  => $block_type,
			)
		);
	}

	/**
	 * Get POST data.
	 *
	 * @return string|NULL
	 */
	private function get_post_data() {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			return null;
		}

		return json_encode( $_POST );
	}

	/**
	 * Display error page.
	 *
	 * @param integer $fid
	 * @return void
	 */
	public function display_error_page( $fid = 1 ) {
		if ( $fid != 22 && $fid != 23 && $fid != 24 && $fid != 'login' ) {
			$this->log_hacker( $fid );
		}

		// Supported by a number of popular caching plugins.
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		// Send forbidden headers and no-caching headers as well.
		status_header(403);
		send_nosniff_header();
		nocache_headers();

		if ( $fid == 'login' ) {
			require_once dirname( __FILE__ ) . '/views/access-denied-login.php';
		} else {
			require_once dirname( __FILE__ ) . '/views/access-denied.php';
		}
		
		exit;
	}
}
