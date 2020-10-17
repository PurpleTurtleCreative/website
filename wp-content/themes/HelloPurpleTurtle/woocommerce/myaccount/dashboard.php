<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h2 class="ptc-welcome-back">It's great to see you, <?php echo esc_html( $current_user->display_name ); ?>!</h2>
<p>What can I do for you today?</p>

<ul class="ptc-quicklinks">

  <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">
    <li class="quicklink-orders">
      <i class="fas fa-shopping-basket"></i>
      Orders
    </li>
  </a>

  <a href="<?php echo esc_url( wc_get_endpoint_url( 'api-keys' ) ); ?>">
    <li class="quicklink-api-keys">
      <i class="fas fa-key"></i>
      License Keys
    </li>
  </a>

  <a href="<?php echo esc_url( wc_get_endpoint_url( 'api-downloads' ) ); ?>">
    <li class="quicklink-api-downloads">
      <i class="fas fa-download"></i>
      Downloads
    </li>
  </a>

</ul>

<h2 class="ptc-request-support"><i class="fas fa-life-ring"></i> Request Support</h2>
<p>If you ever need any help with your Purple Turtle Creative account or products, please use the following Support Request form. Your account information should already be populated, but here it is just in case:</p>

<p>
  User ID: <mark><?php echo esc_html( $current_user->ID ); ?></mark>
</p>
<p>
  Account Email: <mark><?php echo esc_html( $current_user->user_email ); ?></mark>
</p>

<p class="ptc-refund-request-note">**If you'd like to return a product, please use the <strong>Refund Request</strong> form at the bottom of <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">your order</a>'s details.**</p>

<?php
$support_request_gform_url = 'https://docs.google.com/forms/d/e/1FAIpQLSe0SYHveNtKlUI2wzU8S-oqMKhUYTmEWOh5HySPpCHoJdwpmg/viewform?embedded=true&usp=pp_url&entry.308376246=' . urlencode( $current_user->ID ) . '&entry.1151111361=' . urlencode( $current_user->user_email );
?>
<iframe class="ptc-refund-request-gform" src="<?php echo esc_url( $support_request_gform_url ); ?>" width="850" height="720" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
