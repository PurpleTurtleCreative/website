<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine if the subscription of the account is expired.
$status = $this->plugin->client_id != 'PATCHSTACK_CLIENT_ID' || get_option( 'patchstack_clientid', false ) != false;
$free   = get_option( 'patchstack_license_free', 0 ) == 1;
$plan   = get_option( 'patchstack_subscription_class', '');
if ( isset( $_GET['activated'] ) && $status ) {
	echo "<script>window.location = 'admin.php?page=patchstack&tab=license&active=1';</script>";
}

// Generate the link to turn on settings management.
$url = '?page=' . esc_attr( $page ) . '&tab=license&action=enable_settings&patchstack_settings_nonce=' . wp_create_nonce( 'patchstack_settings_nonce' );
$url_enabled = '?page=patchstack' . ($plan != '' && (int) $plan === 0 ? '&tab=firewall' : '');

if (!$show_settings) {
?>
<div class="patchstack-free" style="<?php echo $show_settings ? 'display: none;' : ''; ?>">
	<div>
		<div class="patchstack-plan patchstack-plan2">
			<div class="patchstack-status">
				<span>Plugin status</span>
				<?php
					if ($this->is_connected()) {
						echo '<span class="patchstack-status-label">Connected</span>';
					} else {
						echo '<span class="patchstack-status-label patchstack-status-label-error">Disconnected</span>';
					}
				?>
			</div>

			<div style="clear: both;"></div>

			<div class="patchstack-protection" <?php echo !$this->is_protected() && empty($plan) ? 'style="display: none;"' : ''; ?>>
				<img src="<?php echo esc_url( $this->plugin->url ); ?>assets/images/shield.svg">
				<span>Protection enabled</span>
			</div>

			<div class="patchstack-protection" <?php echo $this->is_protected() || $plan > 0 ? 'style="display: none;"' : ''; ?>>
				<span>Protection disabled</span>
				<a href="https://app.patchstack.com/apps/overview" target="_blank" class="button-primary">
					Activate for $9 on the App
				</a>
			</div>

			<div class="form-table patchstack-form-table">
				<label for="patchstack_api_key">
					API key
					<?php
						if ($plan != '') {
							$plan = $this->get_subscription_name( $plan );
					?>
						<span><?php echo $plan; ?> plan</span>
					<?php
						}
					?>
				</label>
				<input class="regular-text" type="text" id="patchstack_api_key" value="<?php echo get_option( 'patchstack_clientid', false ) ? esc_attr( $this->get_secret_key() . '-' . get_option( 'patchstack_clientid', false ) ) : ''; ?>" placeholder="Enter your API key here...">
			</div>

			<div class="patchstack-license-button">
				<input type="submit" id="patchstack-activate" value="<?php echo $status ? __( 'Re-sync', 'patchstack' ) : __( 'Activate', 'patchstack' ); ?>" class="button-primary patchstack-fullwidth" />
			</div>

			<div style="clear: both;"></div>

			<?php
				if (isset($_GET['resync'])) {
					echo '<p class="patchstack-resync">The plugin is connected and synchronized.';
				}
			?>

			<div class="patchstack-loading"><div></div><div></div><div></div><div></div></div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<?php if ( ! $free ) { ?>
	<p class="patchstack-upsell">
		Click <a href="<?php echo $this->get_option( 'patchstack_show_settings', 0 ) == 1 ? $url_enabled : esc_url( $url ); ?>">here</a> to manage the Patchstack plugin settings through WordPress.
	</p>
	<?php } ?>
</div>
<?php
}
?>