<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine if the subscription of the account is expired.
$status = $this->plugin->client_id != 'PATCHSTACK_CLIENT_ID' || get_option( 'patchstack_clientid', false ) != false;
$free   = get_option( 'patchstack_license_free', 0 ) == 1;
if ( isset( $_GET['activated'] ) && $status ) {
	echo "<script>window.location = 'admin.php?page=patchstack&tab=license&active=1';</script>";
}

// Generate the link to turn on settings management.
$url = '?page=' . esc_attr( $page ) . '&tab=license&action=enable_settings&patchstack_settings_nonce=' . wp_create_nonce( 'patchstack_settings_nonce' );
?>
<div>
	<div class="patchstack-plan patchstack-plan2">
		<p class="ps-p1"><a href="https://app.patchstack.com/apps/overview" target="_blank">Log in</a> to our App to finish connecting your application.</p>

		<div class="form-table patchstack-form-table">
			<input class="regular-text" type="text" id="patchstack_api_key" placeholder="Enter API key">
		</div>

		<div class="patchstack-license-button">
			<input type="submit" id="patchstack-activate" value="<?php echo $status ? __( 'Save', 'patchstack' ) : __( 'Activate', 'patchstack' ); ?>" class="button-primary ps-b3 <?php echo ! $status ? 'patchstack-fullwidth' : ''; ?>" />
			<a href="https://app.patchstack.com/dashboard" class="patchstack-activate button-primary ps-b4" style="<?php echo ! $status ? 'display: none;' : ''; ?>" target="_blank"><?php echo __( 'Go to App', 'patchstack' ); ?></a>
		</div>

		<div style="clear: both;"></div>

		<div class="patchstack-loading"><div></div><div></div><div></div><div></div></div>
	</div>
	<div style="clear: both;"></div>
</div>

<p class="patchstack-upsell">
	New to Patchstack?
	<a href="https://app.patchstack.com/register" target="_blank">Create an account for free.</a>
</p>
