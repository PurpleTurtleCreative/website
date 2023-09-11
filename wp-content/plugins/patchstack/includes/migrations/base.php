<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Create firewall log table.
$sql = 'CREATE TABLE IF NOT EXISTS `' . $prefix . "patchstack_firewall_log` (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    ip tinytext NOT NULL,
    flag tinytext NOT NULL,
    fid mediumint(4),
    request_uri tinytext,
    referer tinytext,
    user_agent tinytext,
    protocol tinytext,
    method tinytext,
    query_string tinytext,
    query_vars tinytext,
    post_data LONGTEXT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    apply_ban INT NOT NULL DEFAULT '1',
    block_type VARCHAR(255) NOT NULL DEFAULT '',
    block_params VARCHAR(255) NOT NULL DEFAULT '',
    UNIQUE KEY id (id)
) $charset_collate;";
dbDelta( $sql );

// Create logic table that will store the firewall rules descriptions.
$sql = 'CREATE TABLE IF NOT EXISTS `' . $prefix . "patchstack_logic` (
    id mediumint(9) NOT NULL,
    cname varchar(20),
    description tinytext,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY id (id)
) $charset_collate;";
dbDelta( $sql );

// Create table that will store all events.
$sql = 'CREATE TABLE IF NOT EXISTS `' . $prefix . "patchstack_event_log` (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    author tinytext  NULL,
    ip tinytext  NULL,
    flag tinytext NULL,
    object tinytext  NULL,
    object_id tinytext  NULL,
    object_name text  NULL,
    action tinytext NULL,
    date datetime  NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";
dbDelta( $sql );

// Insert base firewall rules.
$result = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $prefix . 'patchstack_logic' );
if ( ! $result ) {
	$logics = array(
		array(
			'id'          => 400,
			'cname'       => 'Bad Request',
			'description' => 'The server cannot or will not process the request due to an apparent client error',
		),
		array(
			'id'          => 401,
			'cname'       => 'Unauthorized',
			'description' => 'Authentication is required and has failed or has not yet been provided.',
		),
		array(
			'id'          => 402,
			'cname'       => 'Invalid URL',
			'description' => 'Payment Required. Reserved for future use.',
		),
		array(
			'id'          => 403,
			'cname'       => 'Forbidden',
			'description' => 'The request was valid, but the server is refusing action.',
		),
		array(
			'id'          => 404,
			'cname'       => 'Not Found',
			'description' => 'The requested resource could not be found but may be available in the future.',
		),
		array(
			'id'          => 405,
			'cname'       => 'Not Allowed',
			'description' => 'A request method is not supported for the requested resource.',
		),
		array(
			'id'          => 410,
			'cname'       => 'Gone',
			'description' => 'Indicates that the resource requested is no longer available and will not be available again.',
		),
		array(
			'id'          => 411,
			'cname'       => 'String Injection',
			'description' => 'A request strings attack.',
		),
		array(
			'id'          => 501,
			'cname'       => 'Pingback',
			'description' => 'Pingback protection.',
		),
		array(
			'id'          => 502,
			'cname'       => 'Unauthorized',
			'description' => 'Blocked debug log access',
		),
		array(
			'id'          => 101,
			'cname'       => 'Restricted Files',
			'description' => 'Trying to access readme file.',
		),
		array(
			'id'          => 102,
			'cname'       => 'Restricted Files',
			'description' => 'Trying to access license file.',
		),
		array(
			'id'          => 103,
			'cname'       => 'Restricted Files',
			'description' => 'Trying to access wp-config file.',
		),
		array(
			'id'          => 104,
			'cname'       => 'Restricted Files',
			'description' => 'Trying to access robots_txt file.',
		),
		array(
			'id'          => 108,
			'cname'       => 'Bad Char',
			'description' => 'Advanced character string filtered.',
		),
		array(
			'id'          => 109,
			'cname'       => 'Gone',
			'description' => 'Trying to access readme files htaccess, htpasswd, errordocs or logs.',
		),
		array(
			'id'          => 2,
			'cname'       => 'Dir Exploit',
			'description' => 'Blocked restricted wordpres file access.',
		),
		array(
			'id'          => 3,
			'cname'       => 'Dir Exploit',
			'description' => 'Blacklist Bots detected.',
		),
		array(
			'id'          => 4,
			'cname'       => 'HTTP Ref Attack',
			'description' => 'Abusive HTTP Referrer Blocking.',
		),
		array(
			'id'          => 5,
			'cname'       => 'Blacklist',
			'description' => 'Known blacklist attacks.',
		),
		array(
			'id'          => 6,
			'cname'       => 'Trace',
			'description' => 'Trace and track method detected.',
		),
		array(
			'id'          => 7,
			'cname'       => 'Proxy Commenting',
			'description' => 'Forbid proxy comment posting.',
		),
		array(
			'id'          => 8,
			'cname'       => 'SQLI',
			'description' => 'Deny bad query strings.',
		),
		array(
			'id'          => 10,
			'cname'       => 'SQLI',
			'description' => 'Deny bad query strings.',
		),
		array(
			'id'          => 11,
			'cname'       => 'Request',
			'description' => 'Deny bad query strings.',
		),
		array(
			'id'          => 12,
			'cname'       => 'Referrers',
			'description' => 'Deny bad query strings.',
		),
		array(
			'id'          => 13,
			'cname'       => 'Request',
			'description' => 'Deny bad query strings.',
		),
		array(
			'id'          => 16,
			'cname'       => 'RFI',
			'description' => 'Forbid RFI.',
		),
		array(
			'id'          => 17,
			'cname'       => 'Spam',
			'description' => 'Block spambot.',
		),
		array(
			'id'          => 18,
			'cname'       => 'Hotlinks',
			'description' => 'Image hotlinking.',
		),
		array(
			'id'          => 19,
			'cname'       => 'readme',
			'description' => 'Readme.txt Scan.',
		),
		array(
			'id'          => 22,
			'cname'       => 'Bots',
			'description' => 'Deny bad bots.',
		),
		array(
			'id'          => 23,
			'cname'       => 'XSS',
			'description' => 'Cross site scripting.',
		),
	);

	foreach ( $logics as $logic ) {
		$result = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $prefix . 'patchstack_logic WHERE id = %s', $logic['id'] ) );
		if ( ! $result ) {
			$wpdb->insert(
				$prefix . 'patchstack_logic',
				array(
					'id'          => $logic['id'],
					'cname'       => $logic['cname'],
					'description' => $logic['description'],
				)
			);
		}
	}
}

update_option( 'patchstack_firewall_log_lastid', 0 );
update_option( 'patchstack_eventlog_lastid', 0 );
add_option( 'patchstack_db_version', $this->plugin->version );
