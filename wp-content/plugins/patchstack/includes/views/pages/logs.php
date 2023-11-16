<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine the log table that should be shown.
$logtype  = ( isset( $_GET['logtype'] ) && $_GET['logtype'] == 'activity' ? 'activity' : 'firewall' );
$url      =  $_SERVER['REQUEST_URI'];
$site_id = get_option( 'patchstack_site_id', 0 );
$app_url = $site_id != 0 ? 'https://app.patchstack.com/app/' . $site_id . '/"' : 'https://app.patchstack.com/apps/overview';
if ( strpos( $url, 'logtype' ) === false ) {
	$url .= '&logtype=' . $logtype;
}
?>
<div class="patchstack-font">
	<h2 class="patchstack-logs-nav">
		<a href="<?php echo esc_url( str_replace( 'activity', 'firewall', $url ) ); ?>" class="nav-tab patchstack-nav-tab <?php echo $logtype == 'firewall' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
			<?php echo __( 'Firewall Log', 'patchstack' ); ?>
		</a>

		<?php if ( $class >= 1 || $class === '' ) { ?>
		<a href="<?php echo esc_url( str_replace( 'firewall', 'activity', $url ) ); ?>" class="nav-tab patchstack-nav-tab <?php echo $logtype == 'activity' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
			<?php echo __( 'Activity Log', 'patchstack' ); ?>
		</a>
		<?php } ?>
	</h2>
	<div class="patchstack-content-inner-table">
		<div class="patchstack-font">
			<h4>Log-in at <a href="<?php echo $app_url; ?>" target="_blank">Patchstack App</a> to view more logs.</h4>
			<?php if ( $logtype == 'firewall' ) { ?>
				<table class="table table-lg table-hover table-firewall-log">
					<thead>
						<tr>
							<th>REASON</th>
							<th>URL REQUESTED</th>
							<th>METHOD</th>
							<th>ORIGIN</th>
							<th>DATE</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			<?php } elseif ( $logtype == 'activity' ) { ?>
				<table class="table table-lg table-hover table-user-log dt-table">
					<thead>
						<tr>
							<th>Username</th>
							<th>Action</th>
							<th>Object</th>
							<th>Object name</th>
							<th>IP</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
</div>
