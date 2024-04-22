<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine if the subscription of the account is expired.
$status = ($this->plugin->client_id != 'PATCHSTACK_CLIENT_ID' || get_option( 'patchstack_clientid', false ) != false);
$free = get_option( 'patchstack_license_free', 0 ) == 1;
$planClass = get_option( 'patchstack_subscription_class', '');
$managed = get_option( 'patchstack_managed', false );
$site_id = (int) get_option( 'patchstack_site_id', 0 );
$app_url = $site_id != 0 ? 'https://app.patchstack.com/app/' . $site_id . '/"' : 'https://app.patchstack.com/apps/overview';
if ( isset( $_GET['ps_activated'] ) && $status ) {
	echo "<script>window.location = 'admin.php?page=patchstack&tab=license&active=1';</script>";
}

// Generate the link to turn on settings management.
$url = '?page=' . esc_attr( $page ) . '&tab=license&action=enable_settings&patchstack_settings_nonce=' . wp_create_nonce( 'patchstack_settings_nonce' );
$url_enabled = '?page=patchstack' . (!$free ? '&tab=firewall' : '');

if (!$show_settings) {
	$secretToken = get_option( 'patchstack_activation_secret', '' );
	$secretTime = get_option( 'patchstack_activation_time', '' );
	if (!empty($secretToken)) {
		echo '<script>jQuery(function(){ window.Patchstack.poller(); });</script>';
	}
?>
<div class="patchstack-free patchstack-auto-activate" style="<?php echo $status ? 'display: none;' : ''; ?>">
	<div>
		<div class="is-polling-section has-text-centered">
			<img src="<?php echo esc_url( $this->plugin->url ); ?>assets/images/polling.svg" class="rotate">
			<p class="is-text-medium">
				Checking sync status
			</p>
		</div>

		<?php
			$mins = ! empty( $secretTime ) ? floor( ( ( 1800 ) - ( time() - $secretTime ) ) / 60 ) : 0;
			if ( $mins >= 1 ) {
				echo '<p class="patchstack-upsell patchstack-has-text-white">This plugin is only a connector. Add this website to the Patchstack dashboard within ' . $mins . ' minutes to initiate automatic sync.</p>';
			} else {
				echo '<p class="patchstack-upsell patchstack-has-text-white">This plugin is only a connector. Add this website to the Patchstack dashboard to sync your unique API key.</p>';
			}
		?>

		<div class="patchstack-plan patchstack-plan2">
			<div class="form-table patchstack-form-table">
				<div class="patchstack-activate-wrapper">
					<label for="patchstack_api_key">Paste API key for manual sync</label>
					<input class="regular-text" type="text" id="patchstack_api_key2" placeholder="Unique API key">
					<input type="submit" id="patchstack-activate" value="<?php echo esc_attr__( 'Sync', 'patchstack' ); ?>" class="button-primary" />
				</div>
			</div>


			<div style="clear: both;"></div>

			<div class="patchstack-loading"><div></div><div></div><div></div><div></div></div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>

<p class="patchstack-upsell" style="<?php echo $status ? 'display: none;' : ''; ?>">
	<a href="https://app.patchstack.com/login" target="_blank">Log in to dashboard</a>
</p>

<div class="patchstack-free" style="<?php echo !$status ? 'display: none;' : ''; ?>">
	<div>
		<p class="patchstack-upsell patchstack-has-text-white" style="<?php echo $managed ? 'display: none;' : ''; ?>">This plugin is only a connector. <a href="<?php echo $app_url; ?>" target="_blank"><?php echo esc_attr__( 'Log in to our dashboard', 'patchstack' ); ?></a> to access vulnerability data, protection modules and remote settings.</p>

		<div class="patchstack-plan patchstack-plan2">
			<div class="patchstack-status">
				<span>Sync status</span>
				<?php
					if ($this->is_connected()) {
						echo '<span class="patchstack-status-label">Connected</span>';
					} else {
						echo '<span class="patchstack-status-label patchstack-status-label-error">Disconnected</span>';
					}
				?>
			</div>

			<div style="clear: both;"></div>

			<div class="patchstack-protection" <?php echo !$this->is_protected() && empty($planClass) ? 'style="display: none;"' : ''; ?>>
				<img src="<?php echo esc_url( $this->plugin->url ); ?>assets/images/shield.svg">
				<span>Protection enabled</span>
			</div>

			<div class="patchstack-protection" <?php echo $this->is_protected() || $planClass > 0 || $managed ? 'style="display: none;"' : ''; ?>>
				<span>Protection disabled</span>
				<a href="<?php echo $app_url; ?>" target="_blank" class="button-primary">
					Activate for $5 / mo
				</a>
			</div>

			<?php 
				if (!isset($_GET['ps_autoa'])) {
			?>
			<div class="form-table patchstack-form-table" <?php echo $managed ? 'style="margin-top: 32px;"' : ''; ?>>
				<label for="patchstack_api_key">
					API key
					<?php
						if ($planClass != '' && !$managed ) {
							$plan = $this->get_subscription_name( $planClass );
					?>
						<span><?php echo $plan; ?> plan</span>
					<?php
						}
					?>
				</label>
				<input class="regular-text" type="text" id="patchstack_api_key" value="<?php echo get_option( 'patchstack_clientid', false ) ? esc_attr( $this->get_secret_key() . '-' . get_option( 'patchstack_clientid', false ) ) : ''; ?>" placeholder="Unique API key">
			</div>

			<?php 
				if ( ! get_option( 'patchstack_clientid', false ) ) {
					echo '<p class="patchstack-error">To use this plugin, login and add it to the App dashboard and sync using the API key provided there.</p>';
				}
			?>

			<div class="patchstack-license-button">
				<input type="submit" id="patchstack-activate" value="<?php echo $status ? esc_attr__( 'Re-sync with App', 'patchstack' ) : esc_attr__( 'Activate', 'patchstack' ); ?>" class="button-primary patchstack-fullwidth" />
			</div>

			<div style="clear: both;"></div>

			<?php
				if (isset($_GET['resync'])) {
					echo '<p class="patchstack-resync">The plugin is connected and synchronized.</p>';
				}
			?>
			<?php
				} 
			?>
			<div class="patchstack-loading"><div></div><div></div><div></div><div></div></div>
			<?php
				if (isset($_GET['ps_autoa'])) {
					echo '<p class="patchstack-resync">Attempting to auto-activate the plugin, this will take a moment...</p>';
				}
			?>
		</div>
		<div style="clear: both;"></div>
	</div>

	<p class="patchstack-upsell" style="<?php echo $managed ? 'display: none;' : ''; ?>">
		<?php if ( ! $free && $planClass >= 1 ) { ?>
			<a href="<?php echo $this->get_option( 'patchstack_show_settings', 0 ) == 1 ? $url_enabled : esc_url( $url ); ?>">Manage</a> the Patchstack plugin settings through WordPress.
		<?php } ?>
	</p>
</div>
<?php
}
?>