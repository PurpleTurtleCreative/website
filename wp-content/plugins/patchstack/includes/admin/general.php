<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class is used for any general admin functionality.
 * For example to display general errors on all pages or event listeners.
 */
class P_Admin_General extends P_Core {

	/**
	 * Add any general actions for the backend.
	 *
	 * @param Patchstack $core
	 * @return void
	 */
	public function __construct( $core ) {
		parent::__construct( $core );

		// Add admin and network notices.
		add_action( 'admin_notices', [ $this, 'file_error_notice' ] );
		add_action( 'network_admin_notices', [ $this, 'file_error_notice' ] );
		add_action( 'update_option_siteurl', [ $this, 'update_option_url' ], 10, 2 );

		// If the firewall or whitelist rules do not exist, attempt to pull fresh.
		$token = get_option( 'patchstack_api_token', false );
		if ( ! empty( $token ) && ( get_option( 'patchstack_firewall_rules', '' ) == '' || get_option( 'patchstack_whitelist_keys_rules' ) == '' ) && get_option( 'patchstack_license_free', 0 ) != 1 ) {
			do_action( 'patchstack_post_dynamic_firewall_rules' );
		}
	}

	/**
	 * Display error message if file/folder permissions are not set properly.
	 *
	 * @return void
	 */
	public function file_error_notice() {
		// No need to display this error if the .htaccess functionality has been disabled.
		if ( get_site_option( 'patchstack_disable_htaccess', 0 ) ) {
			return;
		}

		// No need to display if a free user without protection.
		if ( get_option( 'patchstack_license_free', 0 ) == 1 ) {
			return;
		}

		// No need to display on nginx.
		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && stripos( $_SERVER['SERVER_SOFTWARE'], 'nginx' ) !== false ) {
			return;
		}

		// Check root .htaccess file and data folder writability.
		$files = [];
		if ( file_exists( ABSPATH . '.htaccess' ) && ! wp_is_writable( ABSPATH . '.htaccess' ) ) {
			array_push( $files, ABSPATH . '.htaccess' );
		}

		// Are there any errors to display?
		if ( count( $files ) > 0 ) {
			?>
		<div class="error notice">
			<h2>Patchstack File Permission Error</h2>
			<p><?php esc_html_e( 'The following file/folder could not be written to:<br />' . implode( '<br />', $files ), 'patchstack' ); ?></p>
			<?php
			foreach ( $files as $file ) {
				echo wp_kses( '<p><b>Debug info: </b>' . $file . ' chmod permissions: <b>' . substr( decoct( fileperms( $file ) ), -3 ) . '</b>, owned by <b>' . posix_getpwuid( fileowner( $file ) )['name'] . '</b></p>', $this->allowed_html );
			}
			?>
			<p><?php esc_html_e( '<strong>How to fix?</strong><br />CHMOD the file/folder to <strong>755</strong> through a <a href="http://www.dummies.com/web-design-development/wordpress/navigation-customization/how-to-change-file-permissions-using-filezilla-on-your-ftp-site/" target="_blank">FTP client</a>, <a href="http://support.hostgator.com/articles/cpanel/how-to-change-permissions-chmod-of-a-file" target="_blank">CPanel</a>, <a href="https://www.inmotionhosting.com/support/website/managing-files/change-file-permissions" target="_blank">WHM</a> or ask your hosting provider. Make sure file or folder ownership is set to <b>' . posix_getpwuid( fileowner( ABSPATH . 'index.php' ) )['name'] . '</b> user .', 'patchstack_file_error_notice' ); ?></p>
			<p><?php esc_html_e( '<strong>CHMOD properly set but still not working?</strong><br />Make sure the group/owner (chown) settings of the /wp-content/plugins/patchstack/ folder is properly setup, you may have to ask your host to fix this.', 'patchstack_file_error_notice' ); ?></p>
		</div>
			<?php
		}
	}

	/**
	 * When the user updates the site URL, update it on the API side as well.
	 * This needs to be done so we can communicate with the site properly.
	 *
	 * @param mixed $old_value
	 * @param mixed $new_value
	 * @return void
	 */
	public function update_option_url( $old_value, $new_value ) {
		if ( $old_value != $new_value ) {
			$this->plugin->api->update_url( [ 'plugin_url' => $new_value ] );
		}
	}
}
