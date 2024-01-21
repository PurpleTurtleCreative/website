<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine the active tab and account activation state.
$tabs 		   = [ 'hardening', 'firewall', 'login', 'cookienotice', 'logs', 'license', 'multisite' ];
$active_tab    = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $tabs ) ? esc_attr( $_GET['tab'] ) : 'license'; // default active tab
$activated     = ( ( isset( $_GET['ps_activated'] ) && $_GET['ps_activated'] == 1 ) || ( isset( $_GET['active'] ) && $_GET['active'] == 1 ) );
$status        = ( get_option( 'patchstack_license_expiry', '' ) == '' || time() >= strtotime( get_option( 'patchstack_license_expiry', '' ) ) );
$show_settings = $this->get_option( 'patchstack_show_settings', 0 ) == 1 && !isset($_GET['tab']) || isset($_GET['tab']) && $_GET['tab'] != 'license';
$is_free = $this->get_option( 'patchstack_license_free', 0 ) == 1;
$class = get_option( 'patchstack_subscription_class', '' );
$managed = get_option( 'patchstack_managed', false );
$managed_text = get_option( 'patchstack_managed_text', '' );

if ( ( ! $show_settings && $_GET['page'] != 'patchstack-multisite-settings' ) || ( $status && $active_tab != 'license' && $_GET['page'] != 'patchstack-multisite-settings' ) ) {
	$_GET['tab'] = $active_tab = 'license';
}

if ( ( $is_free || ! $is_free && $status ) && $active_tab != 'license' && $_GET['page'] == 'patchstack-multisite-settings' ) {
	$_GET['tab'] = $active_tab = 'multisite';
}

if ($active_tab == 'license') {
	$show_settings = false;
}

// Determine the URL's.
$page = $_GET['page'] == 'patchstack-multisite-settings' ? 'patchstack-multisite-settings' : $this->plugin->name;
?>
<div class="patchstack-content-wrap<?php echo ( ! $show_settings || $this->get_option( 'patchstack_license_free', 0 ) == 1 ? ' patchstack-free' : '' ); ?>">
	<div class="patchstack-top">
		<div class="patchstack-top-logo"
		<?php
		if ( $show_settings && $this->get_option( 'patchstack_license_free', 0 ) == 1 ) {
			echo ' style="float: right; width: 80%; margin-right: -15px;"';
		}
		?>
		>
			<img src="<?php echo esc_url( $this->plugin->url ); ?>assets/images/logo.svg" alt="">
		</div>
		<h1 <?php echo $show_settings || !$managed ? 'style="display: none;"' : ''; ?>>
			<?php echo $managed_text; ?>
		</h1>
		<?php
		if ( $_GET['page'] != 'patchstack-multisite-settings' && $show_settings && is_multisite() ) {
			$site_info = get_blog_details();
			echo "<h2 style='color:white;padding-left: 95px; margin-top: -12px; margin-left: 150px;'>" . esc_html( $site_info->domain ) . '</h2>';
		}
		?>
	</div>

	<div class="patchstack-content-table">
		<?php
		if ( is_multisite() && $show_settings ) {
			?>
			<div class="notice notice-warning">
				<p>
				<?php
				if ( isset( $_GET['page'] ) && $_GET['page'] == 'patchstack-multisite-settings' ) {
					_e( 'Note that because this is a multisite/network environment, this settings page will define all default settings of all sites.<br />If you would like to change the settings of a specific site, visit the administration panel of the site in question. Click <a href="' . network_admin_url( 'admin.php?page=patchstack-multisite' ) . '">here</a> for an overview of sites.', 'patchstack' );
				} else {
					_e( 'Note that because this is a multisite/network environment, certain settings can only be managed by the super administrator of the WordPress network.', 'patchstack' );
				}
				?>
				</p>
			</div><br />
		<?php } ?>
		<h2 class="nav-tab-wrapper patchstack-nav-tab-wrapper"
		<?php
		if ( ! $show_settings ) {
			echo ' style="display: none;"'; }
		?>
		>
			<?php if ( $class >= 1 || $class === '' ) { ?>
			<a href="?page=<?php echo esc_attr( $page ); ?>&tab=hardening" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'hardening' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
				<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-services white"></span></span>
				<span class="patchstack-icon-text"><?php echo __( 'Hardening', 'patchstack' ); ?><br /><span>General Security Tweaks</span></span>
			</a>
			<?php } ?>

			<a href="?page=<?php echo esc_attr( $page ); ?>&tab=firewall" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'firewall' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
				<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-firewall white"></span></span>
				<span class="patchstack-icon-text"><?php echo __( 'Firewall', 'patchstack' ); ?><br /><span>Whitelist & Blacklist & Firewall</span></span>
			</a>

			<?php if ( $class >= 1 || $class === '' ) { ?>
			<a href="?page=<?php echo esc_attr( $page ); ?>&tab=login" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
				<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-login white"></span></span>
				<span class="patchstack-icon-text"><?php echo __( 'Login Protection', 'patchstack' ); ?><br /><span>Protect your login page</span></span>
			</a>
			<?php } ?>

			<?php if ( $class >= 1 || $class === '' ) { ?>
			<a href="?page=<?php echo esc_attr( $page ); ?>&tab=cookienotice" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'cookienotice' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
				<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-cookies white"></span></span>
				<span class="patchstack-icon-text"><?php echo __( 'Cookie Notice', 'patchstack' ); ?><br /><span>Inform your users</span></span>
			</a>
			<?php } ?>

			<?php if ( $page != 'patchstack-multisite-settings' ) { ?>
				<a href="?page=<?php echo esc_attr( $page ); ?>&tab=logs" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'logs' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
					<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-logs white"></span></span>
					<span class="patchstack-icon-text"><?php echo __( 'Logs', 'patchstack' ); ?><br /><span>Firewall &amp; Activity Logs</span></span>
				</a>
			<?php } ?>

			<?php if ( ! is_multisite() || ( isset( $_GET['page'] ) && $_GET['page'] != 'patchstack-multisite-settings' ) ) { ?>
				<a href="?page=<?php echo esc_attr( $page ); ?>&tab=license" class="nav-tab patchstack-nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active patchstack-nav-tab-active' : ''; ?>">
					<span class="patchstack-icon-wrapper"><span class="patchstack-nav-tab-icon ic-license white"></span></span>
					<span class="patchstack-icon-text"><?php echo __( 'License', 'patchstack' ); ?><br /><span>Your license information</span></span>
				</a>
			<?php } ?>
		</h2>
		<div class="patchstack-content-inner patchstack-active-tab-<?php echo esc_attr( $active_tab ); ?> <?php echo ! $show_settings && $this->get_option( 'patchstack_license_free', 0 ) == 0 ? 'patchstack-premium' : ''; ?>">
			<?php
				if ( $status && $_GET['page'] != 'patchstack-multisite-settings' ) {
					require 'license.php';
				} else {
					$form_action = is_multisite() ? '' : 'options.php';
					switch ( $active_tab ) {
						case 'hardening':
							require 'hardening.php';
							break;
						case 'firewall':
							require 'firewall.php';
							break;
						case 'login':
							require 'login.php';
							break;
						case 'cookienotice':
							require 'cookie-notice.php';
							break;
						case 'logs':
							require 'logs.php';
							break;
						case 'license':
							require 'license.php';
							break;
						case 'multisite':
							require 'multisite-activation.php';
							break;
						default:
							require 'license.php';
							break;
					}
				}
			?>
		</div>
	</div>
</div>
