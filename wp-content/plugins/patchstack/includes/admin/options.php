<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class is used to register all the options that Patchstack uses and anything
 * related to options.
 */
class P_Admin_Options extends P_Core {

	/**
	 * Add the actions required for the otions.
	 *
	 * @param Patchstack $core
	 * @return void
	 */
	public function __construct( $core ) {
		parent::__construct( $core );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
	}

	/**
	 * All options and their default values.
	 *
	 * @var array
	 */
	public $options = [
		// Hardening options.
		'patchstack_pluginedit'                         => 1,
		'patchstack_userenum'                           => 1,
		'patchstack_basicscanblock'                     => 1,
		'patchstack_hidewpcontent'                      => 1,
		'patchstack_hidewpversion'                      => 1,
		'patchstack_rm_readme'                          => 1,
		'patchstack_activity_log_is_enabled'            => 1,
		'patchstack_activity_log_failed_logins'         => 1,
		'patchstack_activity_log_failed_logins_db'      => 0,
		'patchstack_xmlrpc_is_disabled'                 => 1,
		'patchstack_captcha_type'                       => 'v2',
		'patchstack_captcha_public_key_v3'              => '',
		'patchstack_captcha_private_key_v3'             => '',
		'patchstack_captcha_public_key_v3_new'          => '',
		'patchstack_captcha_private_key_v3_new'         => '',
		'patchstack_captcha_public_key'                 => '',
		'patchstack_captcha_private_key'                => '',
		'patchstack_captcha_login_form'                 => 0,
		'patchstack_captcha_registration_form'          => 0,
		'patchstack_captcha_reset_pwd_form'             => 0,
		'patchstack_captcha_on_comments'                => 0,
		'patchstack_prevent_default_file_access'        => 1,
		'patchstack_register_email_blacklist'           => '',
		'patchstack_json_is_disabled'                   => 0,
		'patchstack_auto_update'                        => [],
		'patchstack_application_passwords_disabled'     => 1,

		// The firewall and whitelist rules.
		'patchstack_firewall_rules'                     => '',
		'patchstack_firewall_rules_v3'                  => '[]',
		'patchstack_whitelist_rules'                    => '',
		'patchstack_whitelist_rules_v3'                 => '[]',
		'patchstack_whitelist_keys_rules'               => '',

		// Firewall options.
		'patchstack_basic_firewall'                     => 1,
		'patchstack_basic_firewall_roles'               => [ 'administrator', 'editor', 'author', 'contributor' ],
		'patchstack_firewall_ip_header'                 => '',
		'patchstack_ip_header_computed'					=> 0,
		'patchstack_disable_htaccess'                   => 0,
		'patchstack_known_blacklist'                    => 0,
		'patchstack_block_debug_log_access'             => 1,
		'patchstack_block_fake_bots'                    => 1,
		'patchstack_index_views'                        => 1,
		'patchstack_proxy_comment_posting'              => 1,
		'patchstack_bad_query_strings'                  => 0,
		'patchstack_advanced_character_string_filter'   => 0,
		'patchstack_advanced_blacklist_firewall'        => 0,
		'patchstack_forbid_rfi'                         => 0,
		'patchstack_image_hotlinking'                   => 0,
		'patchstack_firewall_custom_rules'              => '',
		'patchstack_firewall_custom_rules_loc'          => 'bottom',
		'patchstack_add_security_headers'               => 1,
		'patchstack_blocked_attacks'                    => 0,
		'patchstack_ip_block_list'                      => '',
		'patchstack_geo_block_enabled'                  => 0,
		'patchstack_geo_block_inverse'                  => 0,
		'patchstack_geo_block_countries'                => [],

		// Cookie notice options.
		'patchstack_enable_cookie_notice_message'		=> 0,
		'patchstack_cookie_notice_message'              => 'We use cookies for various purposes including analytics and personalized marketing. By continuing to use the service, you agree to our use of cookies.',
		'patchstack_cookie_notice_backgroundcolor'      => '222222',
		'patchstack_cookie_notice_textcolor'            => 'ffffff',
		'patchstack_cookie_notice_privacypolicy_enable' => 0,
		'patchstack_cookie_notice_privacypolicy_text'   => 'Cookie Policy',
		'patchstack_cookie_notice_privacypolicy_link'   => '#',
		'patchstack_cookie_notice_cookie_expiration'    => 'after_exit',
		'patchstack_cookie_notice_opacity'              => '100',
		'patchstack_cookie_notice_accept_text'          => 'I agree',
		'patchstack_cookie_notice_credits'              => 1,

		// Login and firewall brute force options.
		'patchstack_block_bruteforce_ips'               => 0,
		'patchstack_anti_bruteforce_attempts'           => 10,
		'patchstack_anti_bruteforce_minutes'            => 5,
		'patchstack_anti_bruteforce_blocktime'          => 60,
		'patchstack_autoblock_attempts'                 => 10,
		'patchstack_autoblock_minutes'                  => 30,
		'patchstack_autoblock_blocktime'                => 60,
		'patchstack_login_time_block'                   => 0,
		'patchstack_login_time_start'                   => '00:00',
		'patchstack_login_time_end'                     => '23:59',
		'patchstack_login_2fa'                          => 0,
		'patchstack_login_whitelist'                    => '',

		// General options.
		'patchstack_blackhole_log'                      => '',
		'patchstack_software_data_hash'                 => '',
		'patchstack_firewall_htaccess_hash'             => '',
		'patchstack_license_expiry'						=> '',
		'patchstack_clientid'                           => false,
		'patchstack_secretkey'                          => false,
		'patchstack_secretkey_nonce'                    => '',
		'patchstack_license_free'                       => 0,
		'patchstack_api_token'                          => '',
		'patchstack_subscription_class'					=> '',
		'patchstack_last_license_check'					=> 0,
		'patchstack_whitelist'                          => '',
		'patchstack_show_settings'                      => 0,
		'patchstack_firewall_log_lastid'                => 0,
		'patchstack_eventlog_lastid'                    => 0,
		'patchstack_ott_action'							=> '',
		'patchstack_enc_nonce'							=> '',
		'patchstack_managed'							=> false,
		'patchstack_managed_text'						=> '',
		'patchstack_latest_vulnerable'					=> [],
		'patchstack_site_id'							=> 0,

		// Admin page rename options.
		'patchstack_mv_wp_login'                        => 0,
		'patchstack_rename_wp_login'                    => 'swlogin',
		'patchstack_rename_wp_login_whitelist'			=> []
	];

	/**
	 * Register all the options, if not set already.
	 *
	 * @return void
	 */
	public function settings_init() {

		// Add the options.
		foreach ( $this->options as $name => $value ) {
			add_option( $name, $value );

			// Clone settings for multisite so we can set defaults.
			if ( is_multisite() ) {
				add_site_option( $name, $value );
			}
		}

		// Multisite options
		add_network_option( null, 'patchstack_multisite_installed', 0 );

		// Get the class value and convert.
		$class = get_option( 'patchstack_subscription_class', '' );
		$is_community = $class != '' && (int) $class === 0;

		// All (sub)sections that show up.
		add_settings_section( 'patchstack_settings_section_hardening', __( 'Security Configurations', 'patchstack' ), false, 'patchstack_hardening_settings' );
		add_settings_section( 'patchstack_settings_section_hardening_captcha', __( '<hr style="height:1px;border:none;background-color: rgba(170, 189, 215, 0.1);"><br /> reCAPTCHA<br /><span style="font-size: 13px; color: #d0d0d0;">It should be noted that the reCAPTCHA feature only applies to WordPress its core features at this time. Not custom forms or of third party plugins.</span>', 'patchstack' ), false, 'patchstack_hardening_settings' );
		add_settings_section( 'patchstack_settings_section_firewall', __( 'Firewall settings', 'patchstack' ), false, 'patchstack_firewall_settings' );
		if ( ( ! is_multisite() || ( isset( $_GET['page'] ) && $_GET['page'] == 'patchstack-multisite-settings' ) ) && ! $is_community ) {
			add_settings_section( 'patchstack_settings_section_firewall_htaccess', __( '.htaccess Features', 'patchstack' ), false, 'patchstack_firewall_settings' );
		}

		if (! $is_community ) {
			add_settings_section( 'patchstack_settings_section_firewall_geo', __( 'Country Blocking', 'patchstack' ), false, 'patchstack_firewall_settings' );
		}

		add_settings_section( 'patchstack_settings_section_firewall_wlbl', __( 'IP Whitelist &amp; Blacklist', 'patchstack' ), false, 'patchstack_firewall_settings' );
		add_settings_section( 'patchstack_settings_section_cookienotice', __( 'Cookie Notice Settings', 'patchstack' ), false, 'patchstack_cookienotice_settings' );
		add_settings_section( 'patchstack_settings_section_login', __( 'Login Protection', 'patchstack' ), false, 'patchstack_login_settings' );
		add_settings_section( 'patchstack_settings_section_login_2fa', __( '<hr style="height:1px;border:none;background-color: rgba(170, 189, 215, 0.1);"><br /> Two Factor Authentication', 'patchstack' ), false, 'patchstack_login_settings' );
		add_settings_section( 'patchstack_settings_section_login_blocked', __( '<hr style="height:1px;border:none;background-color: rgba(170, 189, 215, 0.1);"><br /> Currently Blocked IP Addresses', 'patchstack' ), false, 'patchstack_login_settings' );
		add_settings_section( 'patchstack_settings_section_login_whitelist', __( '<hr style="height:1px;border:none;background-color: rgba(170, 189, 215, 0.1);"><br /> Whitelisted IP Addresses', 'patchstack' ), false, 'patchstack_login_settings' );

		// Hardening.
		if ( ! is_multisite() || ( isset( $_GET['page'] ) && $_GET['page'] == 'patchstack-multisite-settings' ) ) {
			add_settings_field( 'patchstack_rm_readme', __( 'Remove readme.html', 'patchstack' ), [ $this, 'patchstack_rm_readme_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
			add_settings_field( 'patchstack_auto_update', __( 'Auto Update Software', 'patchstack' ), [ $this, 'patchstack_auto_update_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		}
		add_settings_field( 'patchstack_basicscanblock', __( 'Stop readme.txt Scans', 'patchstack' ), [ $this, 'patchstack_basicscanblock_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_pluginedit', __( 'Disable theme editor', 'patchstack' ), [ $this, 'patchstack_pluginedit_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_userenum', __( 'Disable user enumeration', 'patchstack' ), [ $this, 'patchstack_userenum_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_hidewpversion', __( 'Hide WordPress version', 'patchstack' ), [ $this, 'patchstack_hidewpversion_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_activity_log_is_enabled', __( 'Enable activity log', 'patchstack' ), [ $this, 'patchstack_activity_log_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_activity_log_failed_logins', __( 'Log failed logins', 'patchstack' ), [ $this, 'patchstack_activity_log_failed_logins_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_activity_log_failed_logins_db', __( 'Upload failed logins to Patchstack', 'patchstack' ), [ $this, 'patchstack_activity_log_failed_logins_db_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_application_passwords_disabled', __( 'Block Application Passwords', 'patchstack' ), [ $this, 'patchstack_application_passwords_disabled_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_xmlrpc_is_disabled', __( 'Restrict XML-RPC Access', 'patchstack' ), [ $this, 'patchstack_xmlrpc_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_json_is_disabled', __( 'Restrict WP REST API Access', 'patchstack' ), [ $this, 'patchstack_json_is_disabled_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );
		add_settings_field( 'patchstack_register_email_blacklist', __( 'Registration Email Blacklist', 'patchstack' ), [ $this, 'patchstack_register_email_blacklist_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening' );

		// reCAPTCHA.
		add_settings_field( 'patchstack_captcha_on_comments', __( 'Post comments form', 'patchstack' ), [ $this, 'patchstack_captcha_on_comments_callback' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_login_form', __( 'Login form', 'patchstack' ), [ $this, 'patchstack_captcha_login_form_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_registration_form', __( 'Registration form', 'patchstack' ), [ $this, 'patchstack_captcha_registration_form_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_reset_pwd_form', __( 'Password reset form', 'patchstack' ), [ $this, 'patchstack_captcha_reset_pwd_form_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_type', __( 'reCAPTCHA version (invisible/normal)' ), [ $this, 'patchstack_captcha_type_callback' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_public_key', __( 'Site Key ', 'patchstack' ), [ $this, 'patchstack_captcha_public_key_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );
		add_settings_field( 'patchstack_captcha_private_key', __( 'Secret Key', 'patchstack' ), [ $this, 'patchstack_captcha_private_key_input' ], 'patchstack_hardening_settings', 'patchstack_settings_section_hardening_captcha' );

		// Firewall.
		add_settings_field( 'patchstack_basic_firewall', __( 'Enable firewall', 'patchstack' ), [ $this, 'patchstack_basic_firewall_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall' );
		add_settings_field( 'patchstack_basic_firewall_roles', __( 'Firewall user role whitelist', 'patchstack' ), [ $this, 'patchstack_basic_firewall_roles_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall' );
		
		if ( ! $is_community ) {
			add_settings_field( 'patchstack_basic_firewall_geo_enabled', __( 'Country Blocking Enabled', 'patchstack' ), [ $this, 'patchstack_basic_firewall_geo_enabled_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_geo' );
			add_settings_field( 'patchstack_basic_firewall_geo_inverse', __( 'Inversed Check', 'patchstack' ), [ $this, 'patchstack_basic_firewall_geo_inverse_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_geo' );
			add_settings_field( 'patchstack_basic_firewall_geo_countries', __( 'Countries To Block', 'patchstack' ), [ $this, 'patchstack_basic_firewall_geo_countries_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_geo' );
		}

		add_settings_field( 'patchstack_firewall_ip_header', __( 'IP Address Header Override', 'patchstack' ), [ $this, 'patchstack_firewall_ip_header_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall' );

		if ( ( ! is_multisite() || ( isset( $_GET['page'] ) && $_GET['page'] == 'patchstack-multisite-settings' ) ) && ! $is_community ) {
			add_settings_field( 'patchstack_disable_htaccess', __( 'Disable .htaccess features', 'patchstack' ), [ $this, 'patchstack_disable_htaccess_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_add_security_headers', __( 'Add security headers', 'patchstack' ), [ $this, 'patchstack_add_security_headers_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_prevent_default_file_access', __( 'Prevent default WordPress file access', 'patchstack' ), [ $this, 'patchstack_prevent_default_file_access_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_block_debug_log_access', __( 'Block access to debug.log file', 'patchstack' ), [ $this, 'patchstack_block_debug_log_access_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_index_views', __( 'Disable index views', 'patchstack' ), [ $this, 'patchstack_index_views_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_proxy_comment_posting', __( 'Forbid proxy comment posting', 'patchstack' ), [ $this, 'patchstack_proxy_comment_posting_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_image_hotlinking', __( 'Prevent image hotlinking', 'patchstack' ), [ $this, 'patchstack_image_hotlinking_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_firewall_custom_rules', __( 'Add custom .htaccess rules here', 'patchstack' ), [ $this, 'patchstack_firewall_custom_rules_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
			add_settings_field( 'patchstack_firewall_custom_rules_loc', __( 'Custom .htaccess rules location', 'patchstack' ), [ $this, 'patchstack_firewall_custom_rules_loc_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_htaccess' );
		}
		add_settings_field( 'patchstack_blackhole_log', __( 'Block IP List', 'patchstack' ), [ $this, 'patchstack_blackhole_log_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_wlbl' );
		add_settings_field( 'patchstack_whitelist', __( 'Whitelist', 'patchstack' ), [ $this, 'patchstack_whitelist_input' ], 'patchstack_firewall_settings', 'patchstack_settings_section_firewall_wlbl' );

		// Login protection.
		if ( ( ! is_multisite() || ( isset( $_GET['page'] ) && $_GET['page'] == 'patchstack-multisite-settings' ) ) && floatval( substr( phpversion(), 0, 5 ) ) > 5.5 ) {
			add_settings_field( 'patchstack_mv_wp_login', __( 'Block access to wp-login.php', 'patchstack' ), [ $this, 'patchstack_hidewplogin_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login' );
			add_settings_field( 'patchstack_rename_wp_login', '', [ $this, 'patchstack_hidewplogin_rename_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login' );
		}
		add_settings_field( 'patchstack_block_bruteforce_ips', __( 'Automatic brute-force IP ban', 'patchstack' ), [ $this, 'patchstack_block_bruteforce_ips_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login' );
		add_settings_field( 'patchstack_login_time_block', __( 'Logon hours', 'patchstack' ), [ $this, 'patchstack_login_time_block_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login' );
		add_settings_field( 'patchstack_login_2fa', __( 'Two Factor Authentication', 'patchstack' ), [ $this, 'patchstack_login_2fa_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login_2fa' );
		add_settings_field( 'patchstack_login_blocked', __( 'Blocked', 'patchstack' ), [ $this, 'patchstack_login_blocked_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login_blocked' );
		add_settings_field( 'patchstack_login_whitelist', __( 'Whitelist', 'patchstack' ), [ $this, 'patchstack_login_whitelist_input' ], 'patchstack_login_settings', 'patchstack_settings_section_login_whitelist' );

		// Cookie notice.
		add_settings_field( 'patchstack_enable_cookie_notice_message', 'Enable Cookie Notice', [ $this, 'patchstack_enable_cookie_notice_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_message', 'Enter message for displaying', [ $this, 'patchstack_cookie_notice_message_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_accept_text', 'Cookie acceptance button text', [ $this, 'patchstack_cookie_notice_accept_text_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_backgroundcolor', 'Background color (HEX)', [ $this, 'patchstack_cookie_notice_backgroundcolor_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_textcolor', 'Text color (HEX)', [ $this, 'patchstack_cookie_notice_textcolor_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_privacypolicy_enable', 'Enable Policy Link', [ $this, 'patchstack_cookie_notice_privacypolicy_enable_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_privacypolicy_text', 'Enter Policy Text', [ $this, 'patchstack_cookie_notice_privacypolicy_text_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_privacypolicy_link', 'Enter Policy Link', [ $this, 'patchstack_cookie_notice_privacypolicy_link_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_cookie_expiration', 'When to ask user permission again', [ $this, 'patchstack_cookie_notice_cookie_expiration_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_opacity', 'Background opacity (in percentage)', [ $this, 'patchstack_cookie_notice_opacity_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );
		add_settings_field( 'patchstack_cookie_notice_credits', 'Display Patchstack credits', [ $this, 'patchstack_cookie_notice_credits_callback' ], 'patchstack_cookienotice_settings', 'patchstack_settings_section_cookienotice' );

		// Register the group settings.
		$settings = [
			'hardening'    => [ 'patchstack_auto_update', 'patchstack_json_is_disabled', 'patchstack_register_email_blacklist', 'patchstack_pluginedit', 'patchstack_basicscanblock', 'patchstack_userenum', 'patchstack_rm_readme', 'patchstack_hidewpcontent', 'patchstack_hidewpversion', 'patchstack_activity_log_is_enabled', 'patchstack_activity_log_failed_logins', 'patchstack_activity_log_failed_logins_db', 'patchstack_movewpconfig', 'patchstack_captcha_on_comments', 'patchstack_captcha_login_form', 'patchstack_captcha_registration_form', 'patchstack_captcha_reset_pwd_form', 'patchstack_captcha_public_key', 'patchstack_captcha_private_key', 'patchstack_captcha_type', 'patchstack_captcha_public_key_v3', 'patchstack_captcha_private_key_v3', 'patchstack_captcha_public_key_v3_new', 'patchstack_captcha_private_key_v3_new', 'patchstack_xmlrpc_is_disabled', 'patchstack_application_passwords_disabled' ],
			'firewall'     => [ 'patchstack_geo_block_enabled', 'patchstack_geo_block_inverse', 'patchstack_basic_firewall_geo_countries', 'patchstack_ip_block_list', 'patchstack_prevent_default_file_access', 'patchstack_basic_firewall', 'patchstack_firewall_ip_header', 'patchstack_basic_firewall_roles', 'patchstack_disable_htaccess', 'patchstack_add_security_headers', 'patchstack_known_blacklist', 'patchstack_block_debug_log_access', 'patchstack_block_fake_bots', 'patchstack_index_views', 'patchstack_proxy_comment_posting', 'patchstack_bad_query_strings', 'patchstack_advanced_character_string_filter', 'patchstack_advanced_blacklist_firewall', 'patchstack_forbid_rfi', 'patchstack_image_hotlinking', 'patchstack_firewall_custom_rules', 'patchstack_firewall_custom_rules_loc', 'patchstack_blackhole_log', 'patchstack_whitelist', 'patchstack_autoblock_blocktime', 'patchstack_autoblock_attempts', 'patchstack_autoblock_minutes' ],
			'cookienotice' => [ 'patchstack_enable_cookie_notice_message', 'patchstack_cookie_notice_message', 'patchstack_cookie_notice_backgroundcolor', 'patchstack_cookie_notice_textcolor', 'patchstack_cookie_notice_privacypolicy_enable', 'patchstack_cookie_notice_privacypolicy_text', 'patchstack_cookie_notice_privacypolicy_link', 'patchstack_cookie_notice_cookie_expiration', 'patchstack_cookie_notice_opacity', 'patchstack_cookie_notice_accept_text', 'patchstack_cookie_notice_credits' ],
			'login'        => [ 'patchstack_mv_wp_login', 'patchstack_rename_wp_login', 'patchstack_block_bruteforce_ips', 'patchstack_anti_bruteforce_attempts', 'patchstack_anti_bruteforce_minutes', 'patchstack_anti_bruteforce_blocktime', 'patchstack_login_time_block', 'patchstack_login_time_start', 'patchstack_login_time_end', 'patchstack_login_2fa', 'patchstack_login_blocked', 'patchstack_login_whitelist' ],
		];

		foreach ( $settings as $key => $setting ) {
			foreach ( $setting as $option ) {
				register_setting( 'patchstack_' . $key . '_settings_group', $option );
			}
		}
	}

	public function patchstack_auto_update_input() {
		if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED ) {
			echo wp_kses( '<p style="color: red;">The auto update feature cannot be enabled because a plugin or code change forces automatic updates to be disabled. (AUTOMATIC_UPDATER_DISABLED)</p>', $this->allowed_html );
			return;
		}

		$selected = get_site_option( 'patchstack_auto_update', [] );
		$selected = ! is_array( $selected ) ? [] : $selected;
		$options  = [
			'core'       => 'WordPress Core',
			'plugin'     => 'Plugins',
			'theme'      => 'Themes',
			'vulnerable' => 'Vulnerable Plugins',
		];
		$out      = '';
		foreach ( $options as $option => $text ) {
			$out .= '<input type="checkbox" id="patchstack_auto_update_' . $option . '" name="patchstack_auto_update[]" value="' . $option . '" ' . checked( 1, in_array( $option, $selected ), false ) . '/>'
					   . '<label for="patchstack_auto_update_' . $option . '"><i>' . $text . '</i></label><br>';
		}

		$string1 = __( 'Select what needs to be automatically updated each time WordPress looks for updates in the background.<br />Keep in mind that if a plugin update contains a bug or a fatal error, it could break your site.', 'patchstack' );
		echo wp_kses( ( '<label for="patchstack_auto_update"><i>' . $string1 . '</i></label><br /><br />' . $out ), $this->allowed_html );
	}

	public function patchstack_basic_firewall_geo_enabled_input() {
		$string1 = __( 'If enabled and valid countries are specified to be blocked, will block these countries.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_geo_block_enabled" id="patchstack_geo_block_enabled" value="1" ' . checked( 1, $this->get_option( 'patchstack_geo_block_enabled' ), false ) . '/><label for="patchstack_geo_block_enabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_basic_firewall_geo_inverse_input() {
		$string1 = __( 'If enabled, instead of checking if the country of the visitor is in the list, check if it is not in the list instead.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_geo_block_inverse" id="patchstack_geo_block_inverse" value="1" ' . checked( 1, $this->get_option( 'patchstack_geo_block_inverse' ), false ) . '/><label for="patchstack_geo_block_inverse"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_basic_firewall_geo_countries_input() {
		$string1 = __( 'Specify which countries should be blocked.<br />Note that this will also block any type of (legitimate) bot traffic coming from this country. IP to country resolution might also not be 100% accurate.', 'patchstack' );
		$countries    = $this->get_option( 'patchstack_geo_block_countries', [] );
		$country_list = '';
		if ( ! empty( $countries ) ) {
			foreach ( $countries as $country ) {
				$country_list .= esc_attr( $country ) . ",";
			}
		}
		echo wp_kses( '<select id="geo-countries" data-selected="' . substr( $country_list, 0, -1 ) . '" name="patchstack_geo_block_countries[]" placeholder="Select a country..."><option value="">Select a country...</option><option value="AF">Afghanistan</option> <option value="AX">&Aring;land Islands</option> <option value="AL">Albania</option> <option value="DZ">Algeria</option> <option value="AS">American Samoa</option> <option value="AD">Andorra</option> <option value="AO">Angola</option> <option value="AI">Anguilla</option> <option value="AQ">Antarctica</option> <option value="AG">Antigua and Barbuda</option> <option value="AR">Argentina</option> <option value="AM">Armenia</option> <option value="AW">Aruba</option> <option value="AU">Australia</option> <option value="AT">Austria</option> <option value="AZ">Azerbaijan</option> <option value="BS">Bahamas</option> <option value="BH">Bahrain</option> <option value="BD">Bangladesh</option> <option value="BB">Barbados</option> <option value="BY">Belarus</option> <option value="BE">Belgium</option> <option value="BZ">Belize</option> <option value="BJ">Benin</option> <option value="BM">Bermuda</option> <option value="BT">Bhutan</option> <option value="BO">Bolivia, Plurinational State of</option> <option value="BA">Bosnia and Herzegovina</option> <option value="BW">Botswana</option> <option value="BV">Bouvet Island</option> <option value="BR">Brazil</option> <option value="IO">British Indian Ocean Territory</option> <option value="BN">Brunei Darussalam</option> <option value="BG">Bulgaria</option> <option value="BF">Burkina Faso</option> <option value="BI">Burundi</option> <option value="KH">Cambodia</option> <option value="CM">Cameroon</option> <option value="CA">Canada</option> <option value="CV">Cape Verde</option> <option value="KY">Cayman Islands</option> <option value="CF">Central African Republic</option> <option value="TD">Chad</option> <option value="CL">Chile</option> <option value="CN">China</option> <option value="CX">Christmas Island</option> <option value="CC">Cocos (Keeling) Islands</option> <option value="CO">Colombia</option> <option value="KM">Comoros</option> <option value="CG">Congo</option> <option value="CD">Congo, the Democratic Republic of the</option> <option value="CK">Cook Islands</option> <option value="CR">Costa Rica</option> <option value="CI">C&ocirc;te d\'Ivoire</option> <option value="HR">Croatia</option> <option value="CU">Cuba</option> <option value="CY">Cyprus</option> <option value="CZ">Czech Republic</option> <option value="DK">Denmark</option> <option value="DJ">Djibouti</option> <option value="DM">Dominica</option> <option value="DO">Dominican Republic</option> <option value="EC">Ecuador</option> <option value="EG">Egypt</option> <option value="SV">El Salvador</option> <option value="GQ">Equatorial Guinea</option> <option value="ER">Eritrea</option> <option value="EE">Estonia</option> <option value="ET">Ethiopia</option> <option value="FK">Falkland Islands (Malvinas)</option> <option value="FO">Faroe Islands</option> <option value="FJ">Fiji</option> <option value="FI">Finland</option> <option value="FR">France</option> <option value="GF">French Guiana</option> <option value="PF">French Polynesia</option> <option value="TF">French Southern Territories</option> <option value="GA">Gabon</option> <option value="GM">Gambia</option> <option value="GE">Georgia</option> <option value="DE">Germany</option> <option value="GH">Ghana</option> <option value="GI">Gibraltar</option> <option value="GR">Greece</option> <option value="GL">Greenland</option> <option value="GD">Grenada</option> <option value="GP">Guadeloupe</option> <option value="GU">Guam</option> <option value="GT">Guatemala</option> <option value="GG">Guernsey</option> <option value="GN">Guinea</option> <option value="GW">Guinea-Bissau</option> <option value="GY">Guyana</option> <option value="HT">Haiti</option> <option value="HM">Heard Island and McDonald Islands</option> <option value="VA">Holy See (Vatican City State)</option> <option value="HN">Honduras</option> <option value="HK">Hong Kong</option> <option value="HU">Hungary</option> <option value="IS">Iceland</option> <option value="IN">India</option> <option value="ID">Indonesia</option> <option value="IR">Iran, Islamic Republic of</option> <option value="IQ">Iraq</option> <option value="IE">Ireland</option> <option value="IM">Isle of Man</option> <option value="IL">Israel</option> <option value="IT">Italy</option> <option value="JM">Jamaica</option> <option value="JP">Japan</option> <option value="JE">Jersey</option> <option value="JO">Jordan</option> <option value="KZ">Kazakhstan</option> <option value="KE">Kenya</option> <option value="KI">Kiribati</option> <option value="KP">Korea, Democratic People\'s Republic of</option> <option value="KR">Korea, Republic of</option> <option value="KW">Kuwait</option> <option value="KG">Kyrgyzstan</option> <option value="LA">Lao People\'s Democratic Republic</option> <option value="LV">Latvia</option> <option value="LB">Lebanon</option> <option value="LS">Lesotho</option> <option value="LR">Liberia</option> <option value="LY">Libyan Arab Jamahiriya</option> <option value="LI">Liechtenstein</option> <option value="LT">Lithuania</option> <option value="LU">Luxembourg</option> <option value="MO">Macao</option> <option value="MK">Macedonia, the former Yugoslav Republic of</option> <option value="MG">Madagascar</option> <option value="MW">Malawi</option> <option value="MY">Malaysia</option> <option value="MV">Maldives</option> <option value="ML">Mali</option> <option value="MT">Malta</option> <option value="MH">Marshall Islands</option> <option value="MQ">Martinique</option> <option value="MR">Mauritania</option> <option value="MU">Mauritius</option> <option value="YT">Mayotte</option> <option value="MX">Mexico</option> <option value="FM">Micronesia, Federated States of</option> <option value="MD">Moldova, Republic of</option> <option value="MC">Monaco</option> <option value="MN">Mongolia</option> <option value="ME">Montenegro</option> <option value="MS">Montserrat</option> <option value="MA">Morocco</option> <option value="MZ">Mozambique</option> <option value="MM">Myanmar</option> <option value="NA">Namibia</option> <option value="NR">Nauru</option> <option value="NP">Nepal</option> <option value="NL">Netherlands</option> <option value="AN">Netherlands Antilles</option> <option value="NC">New Caledonia</option> <option value="NZ">New Zealand</option> <option value="NI">Nicaragua</option> <option value="NE">Niger</option> <option value="NG">Nigeria</option> <option value="NU">Niue</option> <option value="NF">Norfolk Island</option> <option value="MP">Northern Mariana Islands</option> <option value="NO">Norway</option> <option value="OM">Oman</option> <option value="PK">Pakistan</option> <option value="PW">Palau</option> <option value="PS">Palestinian Territory, Occupied</option> <option value="PA">Panama</option> <option value="PG">Papua New Guinea</option> <option value="PY">Paraguay</option> <option value="PE">Peru</option> <option value="PH">Philippines</option> <option value="PN">Pitcairn</option> <option value="PL">Poland</option> <option value="PT">Portugal</option> <option value="PR">Puerto Rico</option> <option value="QA">Qatar</option> <option value="RE">R&eacute;union</option> <option value="RO">Romania</option> <option value="RU">Russian Federation</option> <option value="RW">Rwanda</option> <option value="BL">Saint Barth&eacute;lemy</option> <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option> <option value="KN">Saint Kitts and Nevis</option> <option value="LC">Saint Lucia</option> <option value="MF">Saint Martin (French part)</option> <option value="PM">Saint Pierre and Miquelon</option> <option value="VC">Saint Vincent and the Grenadines</option> <option value="WS">Samoa</option> <option value="SM">San Marino</option> <option value="ST">Sao Tome and Principe</option> <option value="SA">Saudi Arabia</option> <option value="SN">Senegal</option> <option value="RS">Serbia</option> <option value="SC">Seychelles</option> <option value="SL">Sierra Leone</option> <option value="SG">Singapore</option> <option value="SK">Slovakia</option> <option value="SI">Slovenia</option> <option value="SB">Solomon Islands</option> <option value="SO">Somalia</option> <option value="ZA">South Africa</option> <option value="GS">South Georgia and the South Sandwich Islands</option> <option value="ES">Spain</option> <option value="LK">Sri Lanka</option> <option value="SD">Sudan</option> <option value="SR">Suriname</option> <option value="SJ">Svalbard and Jan Mayen</option> <option value="SZ">Swaziland</option> <option value="SE">Sweden</option> <option value="CH">Switzerland</option> <option value="SY">Syrian Arab Republic</option> <option value="TW">Taiwan, Province of China</option> <option value="TJ">Tajikistan</option> <option value="TZ">Tanzania, United Republic of</option> <option value="TH">Thailand</option> <option value="TL">Timor-Leste</option> <option value="TG">Togo</option> <option value="TK">Tokelau</option> <option value="TO">Tonga</option> <option value="TT">Trinidad and Tobago</option> <option value="TN">Tunisia</option> <option value="TR">Turkey</option> <option value="TM">Turkmenistan</option> <option value="TC">Turks and Caicos Islands</option> <option value="TV">Tuvalu</option> <option value="UG">Uganda</option> <option value="UA">Ukraine</option> <option value="AE">United Arab Emirates</option> <option value="GB">United Kingdom</option> <option value="US">United States</option> <option value="UM">United States Minor Outlying Islands</option> <option value="UY">Uruguay</option> <option value="UZ">Uzbekistan</option> <option value="VU">Vanuatu</option> <option value="VE">Venezuela, Bolivarian Republic of</option> <option value="VN">Viet Nam</option> <option value="VG">Virgin Islands, British</option> <option value="VI">Virgin Islands, U.S.</option> <option value="WF">Wallis and Futuna</option> <option value="EH">Western Sahara</option> <option value="YE">Yemen</option> <option value="ZM">Zambia</option> <option value="ZW">Zimbabwe</option></select>', $this->allowed_html );
		echo wp_kses( '<label for="patchstack_geo_block_enabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_xmlrpc_input() {
		$string1 = __( 'Restrict access to xmlrpc.php by only allowing authenticated users to access it.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_xmlrpc_is_disabled" id="patchstack_xmlrpc_is_disabled" value="1" ' . checked( 1, $this->get_option( 'patchstack_xmlrpc_is_disabled' ), false ) . '/><label for="patchstack_xmlrpc_is_disabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_application_passwords_disabled_input() {
		$string1 = __( 'Disables the application passwords feature introduced in WordPress 5.6.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_application_passwords_disabled" id="patchstack_application_passwords_disabled" value="1" ' . checked( 1, $this->get_option( 'patchstack_application_passwords_disabled' ), false ) . '/><label for="patchstack_application_passwords_disabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_json_is_disabled_input() {
		$string1 = __( 'Restrict access to the WP Rest API by only allowing authenticated users to access it.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_json_is_disabled" id="patchstack_json_is_disabled" value="1" ' . checked( 1, $this->get_option( 'patchstack_json_is_disabled' ), false ) . '/><label for="patchstack_json_is_disabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_register_email_blacklist_input() {
		$string1 = __( '<br /><br />Enter patterns here, seperated by commas, which email addresses we should block upon registration.<br />For example if you enter @badsite.com it will block all email addresses that contain @badsite.com.', 'patchstack' );
		echo wp_kses( '<input type="text" name="patchstack_register_email_blacklist" id="patchstack_register_email_blacklist" value="' . esc_attr( $this->get_option( 'patchstack_register_email_blacklist', '' ) ) . '"/><label for="patchstack_register_email_blacklist"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_activity_log_input() {
		$string1 = __( 'If enabled, a large number of user related activities will be logged.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_activity_log_is_enabled" id="patchstack_activity_log_is_enabled" value="1" ' . checked( 1, $this->get_option( 'patchstack_activity_log_is_enabled' ), false ) . '/><label for="patchstack_activity_log_is_enabled"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_activity_log_failed_logins_input() {
		$string1 = __( 'If this is checked along with the activity logs, we will also log failed login attempts.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_activity_log_failed_logins" id="patchstack_activity_log_failed_logins" value="1" ' . checked( 1, $this->get_option( 'patchstack_activity_log_failed_logins' ), false ) . '/><label for="patchstack_activity_log_failed_logins"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_activity_log_failed_logins_db_input() {
		$string1 = __( 'If this is checked along with the failed login logger, we will also upload the failed login logs to Patchstack.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_activity_log_failed_logins_db" id="patchstack_activity_log_failed_logins_db" value="1" ' . checked( 1, $this->get_option( 'patchstack_activity_log_failed_logins_db' ), false ) . '/><label for="patchstack_activity_log_failed_logins_db"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_captcha_on_comments_callback() {
		$string1 = __( 'Check this if you want to enable reCAPTCHA on post comments.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_captcha_on_comments" id="patchstack_captcha_on_comments" value="1" ' . checked( 1, $this->get_option( 'patchstack_captcha_on_comments' ), false ) . '/><label for="patchstack_captcha_on_comments"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_cookie_notice_credits_callback() {
		$string1 = __( 'Check this if you want to display "Powered by Patchstack"', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_cookie_notice_credits" id="patchstack_cookie_notice_credits" value="1" ' . checked( 1, $this->get_option( 'patchstack_cookie_notice_credits' ), false ) . '/><label for="patchstack_cookie_notice_credits"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_enable_cookie_notice_callback() {
		$string1 = __( 'Check this if you want to enable cookie notice message.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_enable_cookie_notice_message" id="patchstack_enable_cookie_notice_message" value="1" ' . checked( 1, $this->get_option( 'patchstack_enable_cookie_notice_message' ), false ) . '/><label for="patchstack_index_views"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_cookie_notice_message_callback() {
		echo wp_kses( '<textarea name="patchstack_cookie_notice_message" id="patchstack_cookie_notice_message" rows="20" cols="50">' . esc_textarea( $this->get_option( 'patchstack_cookie_notice_message' ) ) . '</textarea>', $this->allowed_html );
	}

	public function patchstack_cookie_notice_accept_text_callback() {
		echo wp_kses( "<input type='text' name='patchstack_cookie_notice_accept_text' id='patchstack_cookie_notice_accept_text' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_accept_text' ) ) . "'>", $this->allowed_html );
	}

	public function patchstack_cookie_notice_backgroundcolor_callback() {
		echo wp_kses( "<input type='text' class='jscolor' name='patchstack_cookie_notice_backgroundcolor' id='patchstack_cookie_notice_backgroundcolor' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_backgroundcolor' ) ) . "'>", $this->allowed_html );
	}

	public function patchstack_cookie_notice_textcolor_callback() {
		echo wp_kses( "<input type='text' class='jscolor' name='patchstack_cookie_notice_textcolor' id='patchstack_cookie_notice_textcolor' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ) . "'>", $this->allowed_html );
	}

	public function patchstack_cookie_notice_privacypolicy_enable_callback() {
		$string1 = __( 'Check this if you want to enable policy link.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_cookie_notice_privacypolicy_enable" id="patchstack_cookie_notice_privacypolicy_enable" value="1" ' . checked( 1, $this->get_option( 'patchstack_cookie_notice_privacypolicy_enable' ), false ) . '/><label for="patchstack_index_views"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_cookie_notice_privacypolicy_text_callback() {
		echo wp_kses( "<input type='text' name='patchstack_cookie_notice_privacypolicy_text' id='patchstack_cookie_notice_privacypolicy_text' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_privacypolicy_text' ) ) . "'>", $this->allowed_html );
	}

	public function patchstack_cookie_notice_privacypolicy_link_callback() {
		echo wp_kses( "<input type='text' name='patchstack_cookie_notice_privacypolicy_link' id='patchstack_cookie_notice_privacypolicy_link' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_privacypolicy_link' ) ) . "'>" , $this->allowed_html );
		echo wp_kses( '<br /><label for="patchstack_cookie_notice_privacypolicy_link"><i>Starting with http(s)://</i></label>', $this->allowed_html );
	}

	public function patchstack_cookie_notice_cookie_expiration_callback() {
		echo wp_kses ( '
            <select name="patchstack_cookie_notice_cookie_expiration" id="patchstack_cookie_notice_cookie_expiration">
              <option ' . ( $this->get_option( 'patchstack_cookie_notice_cookie_expiration' ) == 'after_exit' ? 'selected="selected"' : '' ) . ' value="after_exit">After user re-open browser</option>
              <option ' . ( $this->get_option( 'patchstack_cookie_notice_cookie_expiration' ) == '1week' ? 'selected="selected"' : '' ) . ' value="1week">After 1 week</option>
              <option ' . ( $this->get_option( 'patchstack_cookie_notice_cookie_expiration' ) == '1month' ? 'selected="selected"' : '' ) . ' value="1month">After 1 month</option>
              <option ' . ( $this->get_option( 'patchstack_cookie_notice_cookie_expiration' ) == '1year' ? 'selected="selected"' : '' ) . ' value="1year">After 1 year</option>
            </select>
        ', $this->allowed_html );
	}

	public function patchstack_cookie_notice_opacity_callback() {
		echo wp_kses( "<input min=1 max=100 type='number' name='patchstack_cookie_notice_opacity' id='patchstack_cookie_notice_opacity' value='" . esc_attr( $this->get_option( 'patchstack_cookie_notice_opacity' ) ) . "'>", $this->allowed_html );
		echo wp_kses( '<br /><label for="patchstack_cookie_notice_opacity"><i>min: 1 - max: 99 - no opacity: 100</i></label>', $this->allowed_html );
	}

	public function patchstack_hidewplogin_input() {
		$string1 = __( 'Block access to the default wp-login.php page. This will require you to visit the URL below which will whitelist your IP address for 10 minutes to login.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_mv_wp_login" id="patchstack_mv_wp_login" value="1" ' . checked( 1, $this->get_option( 'patchstack_mv_wp_login' ), false ) . '/><label for="patchstack_mv_wp_login"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_hidewplogin_rename_input() {
		if ( $this->get_option( 'patchstack_mv_wp_login' ) == 0 && $this->get_option( 'patchstack_rename_wp_login' ) == 'swlogin' ) {
			update_site_option( 'patchstack_rename_wp_login', md5( wp_generate_password( 32, true, true ) ) );
		}

		echo wp_kses( '<label><i style="color:red;">This feature should not be used if you have renamed your login page already or when you make use of a system that allows regular users to login.</i></label><br /><br /><label style="font-weight: 300; color: #d0d0d0;"> ' . get_site_url() . '/ </label><input type="text" style="width: 350px;" name="patchstack_rename_wp_login" id="patchstack_rename_wp_login" value="' . esc_attr( $this->get_option( 'patchstack_rename_wp_login' ) ) . '" />' , $this->allowed_html );
		if ( $this->get_option( 'patchstack_mv_wp_login' ) && $this->get_option( 'patchstack_rename_wp_login' ) ) {
			echo wp_kses( '<br /><br /><div style="font-weight: 300; color: #d0d0d0;">Your login access page is here:  <a href="' . get_site_url() . '/' . esc_attr( $this->get_option( 'patchstack_rename_wp_login' ) ) . '">' . get_site_url() . '/' . esc_attr( $this->get_option( 'patchstack_rename_wp_login' ) ) . '</div></a>', $this->allowed_html );
			echo wp_kses( '<br /><input type="submit" id="patchstack_send_mail_url" name="patchstack_send_mail_url" value="Send the link to your admin email." class="button-primary" />', $this->allowed_html );
		}
	}

	public function patchstack_pluginedit_input() {
		$string1 = __( 'Disable the theme editor. This could protect you from potential automated attacks that involve the theme editor.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_pluginedit" id="patchstack_pluginedit" value="1" ' . checked( 1, $this->get_option( 'patchstack_pluginedit' ), false ) . '/><label for="patchstack_pluginedit"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_add_security_headers_input() {
		$string1 = __( 'Add security headers to the response by your webserver.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_add_security_headers" id="patchstack_add_security_headers" value="1" ' . checked( 1, $this->get_option( 'patchstack_add_security_headers' ), false ) . '/><label for="patchstack_add_security_headers"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_basicscanblock_input() {
		$string1 = __( 'This will attempt to stop basic readme.txt scans. These scans are generally used to determine the version of installed plugins on the site.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_basicscanblock" id="patchstack_basicscanblock" value="1" ' . checked( 1, $this->get_option( 'patchstack_basicscanblock' ), false ) . '/><label for="patchstack_basicscanblock"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_userenum_input() {
		$string1 = __( 'Make it harder for malicious people to find your WordPress username.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_userenum" id="patchstack_userenum" value="1" ' . checked( 1, $this->get_option( 'patchstack_userenum' ), false ) . '/><label for="patchstack_userenum"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_hidewpversion_input() {
		$string1 = __( 'Removes the WordPress version in the meta tag in the HTML output.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_hidewpversion" id="patchstack_hidewpversion" value="1" ' . checked( 1, $this->get_option( 'patchstack_hidewpversion' ), false ) . '/><label for="patchstack_hidewpversion"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_rm_readme_input() {
		$string1 = __( 'Removes the readme.html file from the WordPress root folder.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_rm_readme" id="patchstack_rm_readme" value="1" ' . checked( 1, $this->get_option( 'patchstack_rm_readme' ), false ) . '/><label for="patchstack_rm_readme"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_prevent_default_file_access_input() {
		$string1 = __( 'Prevent direct access to files such as license.txt, readme.html and wp-config.php', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_prevent_default_file_access" id="patchstack_prevent_default_file_access" value="1" ' . checked( 1, $this->get_option( 'patchstack_prevent_default_file_access' ), false ) . '/><label for="patchstack_prevent_default_file_access"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_basic_firewall_input() {
		$string1 = __( 'Check this if you want to turn on the advanced firewall protection on your site.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_basic_firewall" id="patchstack_basic_firewall" value="1" ' . checked( 1, $this->get_option( 'patchstack_basic_firewall' ), false ) . '/><label for="patchstack_basic_firewall"><i>' . $string1 . '</i></label><br /><br /><i style="color:#d0d0d0">Block IP for <input style="width: 50px;" type="number" name="patchstack_autoblock_blocktime" value="' . esc_attr( $this->get_option( 'patchstack_autoblock_blocktime', 60 ) ) . '" id="patchstack_autoblock_blocktime"> minutes after <input style="width: 50px;" type="number" name="patchstack_autoblock_attempts" value="' . esc_attr( $this->get_option( 'patchstack_autoblock_attempts', 10 ) ) . '" id="patchstack_autoblock_attempts"> blocked requests over a period of <input style="width: 50px;" type="number" name="patchstack_autoblock_minutes" value="' . esc_attr( $this->get_option( 'patchstack_autoblock_minutes', 30 ) ) . '" id="patchstack_autoblock_minutes"> minutes</i>', $this->allowed_html);
	}

	public function patchstack_basic_firewall_roles_input() {
		$selected = $this->get_option( 'patchstack_basic_firewall_roles', [ 'administrator', 'editor', 'author' ] );
		$selected = ! is_array( $selected ) ? [] : $selected;
		$roles    = wp_roles();
		$roles    = $roles->get_names();
		$text     = '';
		foreach ( $roles as $key => $val ) {
			$text .= '<input type="checkbox" id="patchstack_basic_firewall_roles-' . esc_attr( $key ) . '" name="patchstack_basic_firewall_roles[]" value="' . esc_attr( $key ) . '" ' . checked( 1, in_array( $key, $selected ), false ) . '/><label for="patchstack_basic_firewall_roles-' . esc_attr( $key ) . '"><i>' . esc_html( $val ) . '</i></label><br>';
		}

		$string1 = __( 'Against which user roles should the firewall not run against?<br />The firewall will always run against guests.<br />', 'patchstack' );
		echo wp_kses( ( '<label for="patchstack_basic_firewall_roles"><i>' . $string1 . '</i></label><br />' . $text ), $this->allowed_html );
	}

	public function patchstack_known_blacklist_input() {
		$string1 = __( 'Check this if you want to block known malicious connections.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_known_blacklist" id="patchstack_known_blacklist" value="1" ' . checked( 1, $this->get_option( 'patchstack_known_blacklist' ), false ) . '/><label for="patchstack_known_blacklist"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_firewall_ip_header_input() {
		$string1 = __( 'If you would like to override the IP address header that we use to grab the IP address of the visitor, enter the value here. This must be a valid value in the $_SERVER array, for example HTTP_X_FORWARDED_FOR. If the $_SERVER value you enter does not exist, it will fallback to the Patchstack IP grab function so ask your hosting company if you are unsure. Leave this empty to use the Patchstack IP address grabbing function.', 'patchstack' );
		echo wp_kses( '<input type="text" name="patchstack_firewall_ip_header" id="patchstack_firewall_ip_header" value="' . esc_attr( $this->get_option( 'patchstack_firewall_ip_header' ) ) . '"/><br /><br /><label for="patchstack_firewall_ip_header"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_disable_htaccess_input() {
		$string1 = __( 'Check this if you want to stop us from writing to your .htaccess file. Note that the current changes to the .htaccess file will remain.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_disable_htaccess" id="patchstack_disable_htaccess" value="1" ' . checked( 1, $this->get_option( 'patchstack_disable_htaccess' ), false ) . '/><label for="patchstack_disable_htaccess"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_block_debug_log_access_input() {
		$string1 = __( 'Check this if you want to block access to the debug.log file that WordPress creates when debug logging is enabled.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_block_debug_log_access" id="patchstack_block_debug_log_access" value="1" ' . checked( 1, $this->get_option( 'patchstack_block_debug_log_access' ), false ) . '/><label for="patchstack_block_debug_log_access"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_index_views_input() {
		$string1 = __( 'Check this if you want to disable directory and file listing.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_index_views" id="patchstack_index_views" value="1" ' . checked( 1, $this->get_option( 'patchstack_index_views' ), false ) . '/><label for="patchstack_index_views"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_proxy_comment_posting_input() {
		$string1 = __( 'Check this if you want to forbid proxy comment posting.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_proxy_comment_posting" id="patchstack_proxy_comment_posting" value="1" ' . checked( 1, $this->get_option( 'patchstack_proxy_comment_posting' ), false ) . '/><label for="patchstack_proxy_comment_posting"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_block_bruteforce_ips_input() {
		$string1 = __( 'Check this if you want to automatically ban IP addresses that fail to login multiple times in a short span of time.<br />For this feature to work, make sure that "Log failed logins" is turned on at the hardening settings page.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_block_bruteforce_ips" id="patchstack_block_bruteforce_ips" value="1" ' . checked( 1, $this->get_option( 'patchstack_block_bruteforce_ips' ), false ) . '/><label for="patchstack_block_bruteforce_ips"><i>' . $string1 . '</i></label><br /><br /><i style="color:#d0d0d0">Block IP for <input style="width: 50px;" type="number" name="patchstack_anti_bruteforce_blocktime" value="' . esc_attr( $this->get_option( 'patchstack_anti_bruteforce_blocktime', 60 ) ) . '" id="patchstack_anti_bruteforce_blocktime"> minutes after <input style="width: 50px;" type="number" name="patchstack_anti_bruteforce_attempts" value="' . esc_attr( $this->get_option( 'patchstack_anti_bruteforce_attempts', 10 ) ) . '" id="patchstack_anti_bruteforce_attempts"> failed login attempts over a period of <input style="width: 50px;" type="number" name="patchstack_anti_bruteforce_minutes" value="' . esc_attr( $this->get_option( 'patchstack_anti_bruteforce_minutes', 5 ) ) . '" id="patchstack_anti_bruteforce_minutes"> minutes</i>' , $this->allowed_html );
	}

	public function patchstack_login_time_block_input() {
		$string1 = __( 'Check this if you want to enforce specific logon hours.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_login_time_block" id="patchstack_login_time_block" value="1" ' . checked( 1, $this->get_option( 'patchstack_login_time_block' ), false ) . '/><label for="patchstack_login_time_block"><i>' . $string1 . '</i></label><br /><br /><i style="color:#d0d0d0">Allow login between <input style="width: 70px;" type="text" name="patchstack_login_time_start" value="' . esc_attr( $this->get_option( 'patchstack_login_time_start', '00:00' ) ) . '" id="patchstack_login_time_start" autocomplete="off"> and <input style="width: 70px;" type="text" name="patchstack_login_time_end" value="' . esc_attr( $this->get_option( 'patchstack_login_time_end', '23:59' ) ) . '" id="patchstack_login_time_end" autocomplete="off"><br />Times must be in the 24 hour clock format.<br />The logon hours are also based on the current time of your site: ' . current_time( 'H:i:s' ) . '</i>', $this->allowed_html );
	}

	public function patchstack_login_2fa_input() {
		$string1 = __( 'Check this if you want to make it possible for users to enable two factor authentication (2FA) on their account.', 'patchstack' );
		echo wp_kses(  '<input type="checkbox" name="patchstack_login_2fa" id="patchstack_login_2fa" value="1" ' . checked( 1, $this->get_option( 'patchstack_login_2fa' ), false ) . '/><label for="patchstack_login_2fa"><i>' . $string1 . '<br />Once enabled, users can configure 2FA on the "Edit My Profile" page which is located <a href="' . admin_url( 'profile.php' ) . '">here</a>.</i></label><br />' , $this->allowed_html );
	}

	public function patchstack_login_blocked_input() {
		// Calculate block time.
		$minutes = (int) $this->get_option( 'patchstack_anti_bruteforce_minutes', 30 );
		$timeout = (int) $this->get_option( 'patchstack_anti_bruteforce_blocktime', 60 );
		if ( empty( $minutes ) || empty( $timeout ) ) {
			$time = 30 + 60;
		} else {
			$time = $minutes + $timeout;
		}

		// Check if X failed login attempts were made.
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT id, ip, date FROM ' . $wpdb->prefix . "patchstack_event_log WHERE action = 'failed login' AND date >= ('" . current_time( 'mysql' ) . "' - INTERVAL %d MINUTE) GROUP BY ip HAVING COUNT(ip) >= %d ORDER BY date DESC", [ $time, $this->get_option( 'patchstack_anti_bruteforce_attempts', 10 ) ] ),
			OBJECT
		);

		// Render the rows.
		$rows = '';
		if ( count( $results ) == 0 ) {
			$rows = '<tr><td>No blocked IP addresses.</td><td></td><td></td><td></td></tr>';
		} else {
			$nonce = wp_create_nonce( 'patchstack-nonce-alter-ips' );
			foreach ( $results as $result ) {
				$rows .= '<tr><td>' . esc_html( $result->ip ) . '</td><td>' . ( isset( $result->log_date ) ? $result->log_date : $result->date ) . '</td><td><a href="' . esc_url( add_query_arg(
					[
						'PatchstackNonce' => $nonce,
						'action'          => 'patchstack_unblock',
						'id'              => $result->id,
					]
				) ) . '">Unblock</a></td><td><a href="' . esc_url( add_query_arg(
					[
						'PatchstackNonce' => $nonce,
						'action'          => 'patchstack_unblock_whitelist',
						'id'              => $result->id,
					]
				) ) . '">Unblock &amp; Whitelist</a></td></tr>';
			}
		}

		$string1 = __( 'These are the IP addresses that are currently blocked because of too many failed login attempts.<br />These are not the IP addresses banned by the firewall itself.<br /><br />', 'patchstack' );
		echo wp_kses( '<label><i>' . $string1 . '</i></label>', $this->allowed_html );
		echo wp_kses( '<div class="patchstack-content-inner-table"><table class="table dataTable patchstack-bi" style="margin: 0 !important;"><thead><tr><th>IP Address</th><th style="padding-left: 0 !important;">Last Attempt</th><th style="padding-left: 0 !important;">Unblock</th><th style="padding-left: 0 !important;">Unbock &amp; Whitelist</th></tr></thead><tbody>' . $rows . '</table></div>', $this->allowed_html );
	}

	public function patchstack_login_whitelist_input() {
		$ip_list = esc_textarea( rtrim( $this->get_option( 'patchstack_login_whitelist', '' ) ) );
		echo wp_kses( '<label><i>These IP addresses will never be blocked from logging in, no matter the amount of failed logins.</i></label><br /><br /><p><textarea rows="5" id="patchstack_login_whitelist"  name="patchstack_login_whitelist">' . $ip_list . '</textarea>Each entry must be on its own line.<br />Your current IP address is: ' . esc_html( $this->get_ip() ) . '<br /><br /><strong>Following formats are accepted:</strong><p>127.0.0.1</p><p>127.0.0.*</p><p>127.0.0.0/24</p><p>127.0.0.0-127.0.0.255</p></p>', $this->allowed_html );
	}

	public function patchstack_image_hotlinking_input() {
		$string1 = __( 'Check this if you want to prevent hotlinking to images on your site.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_image_hotlinking" id="patchstack_image_hotlinking" value="1" ' . checked( 1, $this->get_option( 'patchstack_image_hotlinking' ), false ) . '/><label for="patchstack_image_hotlinking"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_firewall_custom_rules_input() {
		$string1 = __( 'Add custom .htaccess rules here if you know what you are doing, otherwise you may break your site. So be careful.', 'patchstack' );
		echo wp_kses( '<textarea name="patchstack_firewall_custom_rules" id="patchstack_firewall_custom_rules" rows="20" cols="50" placeholder="' . $string1 . '">', $this->allowed_html );
		$rules = $this->get_option( 'patchstack_firewall_custom_rules' );
		if ( isset( $rules ) ) {
			if ( is_array( $rules ) ) {
				foreach ( $rules as $rule ) {
					echo wp_kses( esc_textarea( $rule ), $this->allowed_html );
				}
			} else {
				echo wp_kses( esc_textarea( $rules ), $this->allowed_html );
			}
		}
		echo wp_kses( '</textarea>', $this->allowed_html );
		echo wp_kses( '<p style="color:red;">If the custom .htaccess rules above cause an error, they will be removed automatically.</p>', $this->allowed_html );
	}

	public function patchstack_firewall_custom_rules_loc_input() {
		echo wp_kses( '<select name="patchstack_firewall_custom_rules_loc" id="patchstack_firewall_custom_rules_loc"><option ' . ( $this->get_option( 'patchstack_firewall_custom_rules_loc' ) == 'top' ? 'selected="selected"' : '' ) . ' value="top">Top - above Patchstack rules</option><option ' . ( $this->get_option( 'patchstack_firewall_custom_rules_loc' ) == 'bottom' ? 'selected="selected"' : '' ) . ' value="bottom">Bottom - under Patchstack rules</option></select>', $this->allowed_html );
	}

	public function patchstack_captcha_login_form_input() {
		$string1 = __( 'Check this if you want to enable reCAPTCHA on user login.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_captcha_login_form" id="patchstack_captcha_login_form" value="1" ' . checked( 1, $this->get_option( 'patchstack_captcha_login_form' ), false ) . '/><label for="patchstack_captcha_login_form"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_captcha_registration_form_input() {
		$string1 = __( 'Check this if you want to enable reCAPTCHA on registration.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_captcha_registration_form" id="patchstack_captcha_registration_form" value="1" ' . checked( 1, $this->get_option( 'patchstack_captcha_registration_form' ), false ) . '/><label for="patchstack_captcha_registration_form"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_captcha_reset_pwd_form_input() {
		$string1 = __( 'Check this if you want to enable reCAPTCHA on password reset.', 'patchstack' );
		echo wp_kses( '<input type="checkbox" name="patchstack_captcha_reset_pwd_form" id="patchstack_captcha_reset_pwd_form" value="1" ' . checked( 1, $this->get_option( 'patchstack_captcha_reset_pwd_form' ), false ) . '/><label for="patchstack_captcha_reset_pwd_form"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_captcha_type_callback() {
		echo wp_kses( '
            <select name="patchstack_captcha_type" id="patchstack_captcha_type">
              <option ' . ( $this->get_option( 'patchstack_captcha_type' ) == 'v2' ? 'selected="selected"' : '' ) . ' value="v2">Checkbox (v2)</option>
              <option ' . ( $this->get_option( 'patchstack_captcha_type' ) == 'invisible' ? 'selected="selected"' : '' ) . ' value="invisible">Invisible (v2)</option>
              <option ' . ( $this->get_option( 'patchstack_captcha_type' ) == 'v3' ? 'selected="selected"' : '' ) . ' value="v3">Invisible (v3)</option>
           </select>', $this->allowed_html );
	}

	public function patchstack_captcha_public_key_input() {
		$string1 = __( '<br /><br />Enter the reCAPTCHA site key here.<br />Click <a href="https://docs.patchstack.com/discuss/62e286cf7040390013b73bf7" target="_blank">here</a> for a guide on how to get the site / secret key.', 'patchstack' );
		echo wp_kses( '<input style="display:none;" type="text" name="patchstack_captcha_public_key" id="patchstack_captcha_public_key" value="' . esc_attr( $this->get_option( 'patchstack_captcha_public_key', '' ) ) . '"/><input style="display:none;" type="text" name="patchstack_captcha_public_key_v3" id="patchstack_captcha_public_key_v3" value="' . esc_attr( $this->get_option( 'patchstack_captcha_public_key_v3', '' ) ) . '"/><input style="display:none;" type="text" name="patchstack_captcha_public_key_v3_new" id="patchstack_captcha_public_key_v3_new" value="' . esc_attr( $this->get_option( 'patchstack_captcha_public_key_v3_new', '' ) ) . '"/><label for="patchstack_captcha_public_key"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_captcha_private_key_input() {
		$string1 = __( '<br /><br />Enter the reCAPTCHA secret key here.<br />Click <a href="https://docs.patchstack.com/discuss/62e286cf7040390013b73bf7" target="_blank">here</a> for a guide on how to get the site / secret key.', 'patchstack' );
		echo wp_kses( '<input style="display:none;" type="text" name="patchstack_captcha_private_key" id="patchstack_captcha_private_key" value="' . esc_attr( $this->get_option( 'patchstack_captcha_private_key', '' ) ) . '"/><input style="display:none;" type="text" name="patchstack_captcha_private_key_v3" id="patchstack_captcha_private_key_v3" value="' . esc_attr( $this->get_option( 'patchstack_captcha_private_key_v3', '' ) ) . '"/><input style="display:none;" type="text" name="patchstack_captcha_private_key_v3_new" id="patchstack_captcha_private_key_v3_new" value="' . esc_attr( $this->get_option( 'patchstack_captcha_private_key_v3_new', '' ) ) . '"/><label for="patchstack_captcha_private_key"><i>' . $string1 . '</i></label>' , $this->allowed_html );
	}

	public function patchstack_blackhole_log_input() {
		$ip_list = esc_textarea( rtrim( $this->get_option( 'patchstack_ip_block_list', '' ) ) );
		echo wp_kses( '<p><textarea rows="5" id="patchstack_ip_block_list"  name="patchstack_ip_block_list">' . $ip_list . '</textarea>Each entry must be on its own line.<br /><br /><strong>Following formats are accepted:</strong><p>127.0.0.1</p><p>127.0.0.*</p><p>127.0.0.0/24</p><p>127.0.0.0-127.0.0.255</p>', $this->allowed_html );
	}

	public function patchstack_whitelist_input() {
		echo wp_kses( '<textarea name="patchstack_whitelist" id="patchstack_whitelist" rows="20" cols="50">' . esc_textarea( $this->get_option( 'patchstack_whitelist' ) ) . '</textarea>', $this->allowed_html );
		echo wp_kses( __( '<p>Each rule must be on a new line.<br /><br /><strong>The following keywords are accepted</strong><br />IP:IPADDRESS<br />PAYLOAD:someval<br />URL:/someurl<br /><br /><strong>Definitions</strong><br />IP = firewall will not run against the IP<br />PAYLOAD = if the entire payload contains the keyword, the firewall will not proceed<br />URL = if the URL contains given URL, firewall will not proceed<br /><br /><strong>Example</strong><br />IP:192.168.1.1<br />PAYLOAD:contact_form<br />URL:water<br />URL:/some-form<br /><br />In this scenario, the firewall will not run if the IP address is 192.168.1.1 or if the payload contains contact_form or if the URL contains water or if the URL contains /some-form.</p>', 'patchstack' ), $this->allowed_html );
	}
}
