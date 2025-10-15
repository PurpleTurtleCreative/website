<?php
/**
 * Plugin Name: PTC Mailgun SMTP
 * Description: Send emails via Mailgun SMTP.
 */

namespace Purple_Turtle_Creative\Mailgun_SMTP;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'acf/init', __NAMESPACE__ . '\register_options_page', 10, 0 );
add_action( 'acf/options_page/save', __NAMESPACE__ . '\handle_options_page_save', 10, 2 );
add_action( 'acf/include_fields', __NAMESPACE__ . '\include_acf_fields', 10, 2 );
add_action( 'phpmailer_init', __NAMESPACE__ . '\configure_phpmailer', 10, 1 );
add_filter( 'wp_mail_from', __NAMESPACE__ . '\set_mail_from_address', 10, 1 );
add_filter( 'wp_mail_from_name', __NAMESPACE__ . '\set_mail_from_name', 10, 1 );

if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_action(
		'wp_mail_failed',
		function ( $wp_error ) {
			wp_trigger_error( 'Mail failed to send with error: ' . $wp_error->get_error_message(), E_USER_WARNING );
		},
		10,
		1
	);
	add_action(
		'wp_mail_succeeded',
		function ( $mail_data ) {
			wp_trigger_error( 'Mail successfully sent with data: ' . print_r( $mail_data, true ), E_USER_NOTICE );
		},
		10,
		1
	);
}

/**
 * Register options page for Mailgun SMTP settings.
 */
function register_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title'  => 'Mailgun SMTP',
				'menu_slug'   => 'ptc-mailgun-smtp',
				'parent_slug' => 'options-general.php',
				'position'    => '',
				'redirect'    => false,
			)
		);
	}
}

/**
 * Handle saving of options page.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-options_page-save/
 *
 * @param int|string $post_id   The ID of the page being edited.
 * @param string     $menu_slug The current options page menu slug.
 */
function handle_options_page_save( $post_id, $menu_slug ) {
	if ( 'options' === $post_id && 'ptc-mailgun-smtp' === $menu_slug ) {
		// Check if a test email should be sent.
		$test_connection = get_field( 'ptc_mailgun_test', 'options' );
		if ( ! empty( $test_connection ) ) {
			wp_mail(
				$test_connection['to'],
				'[Test] ' . get_bloginfo( 'name' ) . ' Mailgun SMTP Test',
				'This is a test email sent from the ' . get_bloginfo( 'name' ) . ' website at ' . home_url() . ' to confirm the Mailgun SMTP connection is working.'
			);
			wp_trigger_error( 'Sent Mailgun SMTP test email to ' . $test_connection['to'], E_USER_NOTICE );
		}
	}
}

/**
 * Configure PHPMailer to use Mailgun SMTP.
 *
 * @link https://www.mailgun.com/blog/email/improve-wordpress-email/#chapter-3
 *
 * @param \PHPMailer\PHPMailer\PHPMailer $m The PHPMailer instance (passed by reference).
 */
function configure_phpmailer( $m ) {
	$ptc_mailgun_smtp = get_field( 'ptc_mailgun_smtp', 'options' );
	if (
		! empty( $ptc_mailgun_smtp['enable'] ) &&
		! empty( $ptc_mailgun_smtp['username'] ) &&
		! empty( $ptc_mailgun_smtp['password'] )
	) {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$m->Host     = 'smtp.mailgun.org';
		$m->Port     = 587;
		$m->Username = $ptc_mailgun_smtp['username'];
		$m->Password = $ptc_mailgun_smtp['password'];
		$m->isSMTP();
		$m->SMTPAuth   = true;
		$m->SMTPSecure = 'tls';
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
}

/**
 * Set the "from" email address.
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_mail_from/
 *
 * @param string $from_email The original email address.
 *
 * @return string The modified email address.
 */
function set_mail_from_address( $from_email ) {
	$ptc_mailgun_appearance = get_field( 'ptc_mailgun_appearance', 'options' );
	if ( ! empty( $ptc_mailgun_appearance['from_address'] ) ) {
		return $ptc_mailgun_appearance['from_address'];
	}
	return $from_email;
}

/**
 * Set the "from" name.
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_mail_from_name/
 *
 * @param string $from_name The original email from name.
 *
 * @return string The modified email from name.
 */
function set_mail_from_name( $from_name ) {
	$ptc_mailgun_appearance = get_field( 'ptc_mailgun_appearance', 'options' );
	if ( ! empty( $ptc_mailgun_appearance['from_name'] ) ) {
		return $ptc_mailgun_appearance['from_name'];
	}
	return $from_name;
}

/**
 * Include ACF fields for Mailgun SMTP settings.
 */
function include_acf_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// phpcs:disable
	acf_add_local_field_group(
		array(
			'key' => 'group_68ef102cdc673',
			'title' => 'PTC Mailgun SMTP',
			'fields' => array(
				array(
					'key' => 'field_68ef11b13961d',
					'label' => 'SMTP Credentials',
					'name' => 'ptc_mailgun_smtp',
					'aria-label' => '',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_68ef1417b30a5',
							'label' => 'Enable',
							'name' => 'enable',
							'aria-label' => '',
							'type' => 'true_false',
							'instructions' => 'Email and Password are both required to enable Mailgun SMTP sending. This toggle helps prevent sending through Mailgun to prevent service charges, for example.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'message' => 'If emails should be sent via Mailgun SMTP',
							'default_value' => 1,
							'allow_in_bindings' => 0,
							'ui_on_text' => '',
							'ui_off_text' => '',
							'ui' => 1,
						),
						array(
							'key' => 'field_68ef11f03961e',
							'label' => 'Username',
							'name' => 'username',
							'aria-label' => '',
							'type' => 'email',
							'instructions' => 'Required for sending. This email address will not be used for To or From addressing.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'allow_in_bindings' => 0,
							'placeholder' => 'postmaster@yourdomain.org',
							'prepend' => '',
							'append' => '',
						),
						array(
							'key' => 'field_68ef12073961f',
							'label' => 'Password',
							'name' => 'password',
							'aria-label' => '',
							'type' => 'password',
							'instructions' => 'Required for sending.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'allow_in_bindings' => 0,
							'placeholder' => 'your secret password',
							'prepend' => '',
							'append' => '',
						),
					),
				),
				array(
					'key' => 'field_68ef193994b88',
					'label' => 'Appearance',
					'name' => 'ptc_mailgun_appearance',
					'aria-label' => '',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_68ef196b94b89',
							'label' => 'From Address',
							'name' => 'from_address',
							'aria-label' => '',
							'type' => 'email',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'allow_in_bindings' => 0,
							'placeholder' => 'noreply@yourdomain.org',
							'prepend' => '',
							'append' => '',
						),
						array(
							'key' => 'field_68ef1a2894b8a',
							'label' => 'From Name',
							'name' => 'from_name',
							'aria-label' => '',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'maxlength' => '',
							'allow_in_bindings' => 0,
							'placeholder' => 'Website Admin',
							'prepend' => '',
							'append' => '',
						),
					),
				),
				array(
					'key' => 'field_68ef15e61d0ef',
					'label' => 'Test Connection',
					'name' => 'ptc_mailgun_test',
					'aria-label' => '',
					'type' => 'group',
					'instructions' => 'Send a test email to the specified address each time the settings are updated. This tests wp_mail() deliverability in general—not necessarily the Mailgun SMTP credentials specifically.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_68ef16511d0f1',
							'label' => 'To',
							'name' => 'to',
							'aria-label' => '',
							'type' => 'email',
							'instructions' => 'Leave this field empty to stop sending a test email.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'allow_in_bindings' => 0,
							'placeholder' => 'you@proton.me',
							'prepend' => '',
							'append' => '',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'ptc-mailgun-smtp',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		)
	);
	// phpcs:enable
}
