<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class is used to log any user related action.
 */
class P_Event_Users extends P_Event_Log {

	/**
	 * Hook into the actions so we can log it.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_login', array( &$this, 'login' ), 10, 2 );
		add_action( 'delete_user', array( &$this, 'deleteUser' ) );
		add_action( 'user_register', array( &$this, 'register' ) );
		add_action( 'profile_update', array( &$this, 'profileUpdate' ) );
		add_action( 'validate_password_reset', array( &$this, 'resetPassword' ), 10, 2 );
		add_filter( 'wp_login_failed', array( &$this, 'wrongPassword' ) );
	}

	/**
	 * When a user is logging in.
	 *
	 * @param string $user_login
	 * @param object $user
	 * @return void
	 */
	public function login( $user_login, $user ) {
		$this->insert(
			array(
				'action'      => 'logged in',
				'object'      => 'user',
				'object_id'   => $user->ID,
				'object_name' => esc_html( $user->user_nicename ),
			)
		);
	}

	/**
	 * When a new account has been created.
	 *
	 * @param integer $user_id
	 * @return void
	 */
	public function register( $user_id ) {
		$user = get_user_by( 'id', $user_id );
		$this->insert(
			array(
				'action'      => 'registered',
				'object'      => 'user',
				'object_id'   => $user->ID,
				'object_name' => esc_html( $user->user_nicename ),
			)
		);
	}

	/**
	 * When a user is deleted.
	 *
	 * @param integer $user_id
	 * @return void
	 */
	public function deleteUser( $user_id ) {
		$user = get_user_by( 'id', $user_id );
		$this->insert(
			array(
				'action'      => 'deleted',
				'object'      => 'user',
				'object_id'   => $user->ID,
				'object_name' => esc_html( $user->user_nicename ),
			)
		);
	}

	/**
	 * When a profile is updated.
	 *
	 * @param integer $user_id
	 * @return void
	 */
	public function profileUpdate( $user_id ) {
		$user = get_user_by( 'id', $user_id );
		$this->insert(
			array(
				'action'      => 'updated',
				'object'      => 'user',
				'object_id'   => $user->ID,
				'object_name' => esc_html( $user->user_nicename ),
			)
		);
	}

	/**
	 * When a failed login attempt was made due to wrong password.
	 *
	 * @param string $username
	 * @return void
	 */
	public function wrongPassword( $username ) {
		$this->insert(
			array(
				'action'      => 'failed login',
				'object'      => 'user',
				'object_id'   => 0,
				'object_name' => esc_html( $username ),
			)
		);
	}

	/**
	 * When a password is being reset through the reset password procedure.
	 *
	 * @param object $errors
	 * @param object $user
	 * @return void
	 */
	public function resetPassword( $errors, $user ) {
		if ( is_a( $user, 'WP_User' ) ) {
			$this->insert(
				array(
					'action'      => 'password reset',
					'object'      => 'user',
					'object_id'   => 0,
					'object_name' => esc_html( $user->user_login ),
				)
			);
		}
	}
}
