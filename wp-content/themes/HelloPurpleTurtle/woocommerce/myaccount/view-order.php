<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>
<p>
<?php
printf(
  /* translators: 1: order number 2: order date 3: order status */
  esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
  '<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  '<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  '<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
?>
</p>

<?php if ( $notes ) : ?>
  <h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
  <ol class="woocommerce-OrderUpdates commentlist notes">
    <?php foreach ( $notes as $note ) : ?>
    <li class="woocommerce-OrderUpdate comment note">
      <div class="woocommerce-OrderUpdate-inner comment_container">
        <div class="woocommerce-OrderUpdate-text comment-text">
          <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
          <div class="woocommerce-OrderUpdate-description description">
            <?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          </div>
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
    </li>
    <?php endforeach; ?>
  </ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>

<h2>Refund Request</h2>
<p>
  <?php
  $order_dt = $order->get_date_created();
  $order_dt->setTime( 0, 0 );
  $dt_today = new \DateTime( 'today', $order_dt->getTimezone() );
  $dt_today->setTime( 0, 0 );
  $days_diff = $dt_today->diff( $order_dt )->days;

  $qualifies_for_refund = FALSE;
  if ( 0 === $order->get_parent_id() ) {
    $order_status = $order->get_status();
    if ( 'completed' === $order_status ) {
      if ( $order_dt <= $dt_today && $days_diff <= 30 ) {
        echo 'Your order was placed <mark>' . esc_html( $days_diff ) . ' days ago</mark> which means it currently qualifies for a refund. Please complete the following form if you would like to cancel your product license(s) and receive a full refund.';
        $qualifies_for_refund = TRUE;
      } else {
        echo 'Your order was not placed within the past 30 days, so it does not qualify for a refund.';
      }
    } elseif ( 'refunded' === $order_status ) {
      echo 'Your order was successfully refunded. Thank you for trying out some of my software!';
    } else {
      echo 'Your order does not have a status of <em>Completed</em>, so it does not qualify for a refund.';
    }
  } else {
    $parent_order = wc_get_order( $order->get_parent_id() );
    $parent_order_url = $parent_order->get_view_order_url();
    echo 'Renewal orders do not qualify for a refund. Please see the <a href="' . esc_url( $parent_order_url ) . '">parent order</a> to request a refund.';
  }
  ?>
</p>

<?php
if ( $qualifies_for_refund === TRUE ) :

  $billing_email_autofill = '';
  if ( method_exists( $order, 'get_billing_email' ) ) {
    $billing_email_autofill = urlencode( $order->get_billing_email() );
  }

  $order_id_autofill = '';
  if ( method_exists( $order, 'get_order_number' ) ) {
    $order_id_autofill = urlencode( $order->get_order_number() );
  }

  $line_item_autofill = '';
  $order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
  if ( ! empty( $order_items ) ) {
    $first_item = reset( $order_items );
    if ( method_exists( $first_item, 'get_name' ) ) {
      $first_item_name = $first_item->get_name();
      if ( is_string( $first_item_name ) ) {
        $line_item_autofill = urlencode( $first_item_name );
      }
    }
  }

  $refund_request_gform_url = 'https://docs.google.com/forms/d/e/1FAIpQLSfVklpoinPgng7dYRVRoV2nUR2irG33MAXrT2MGMQM14JUr0w/viewform?embedded=true&usp=pp_url&entry.308376246=' . $order_id_autofill . '&entry.1151111361=' . $billing_email_autofill . '&entry.1083316797=' . $line_item_autofill;

?>
<p class="woocommerce-error">Please note that <strong>issuing a refund will revoke your returned licenses.</strong> Please refer to the <em>Refund Policy</em> section of my <a href="https://purpleturtlecreative.com/terms-conditions/" target="_blank">Terms of Service</a> for more details.</p>
<iframe class="ptc-refund-request-gform" src="<?php echo esc_url( $refund_request_gform_url ); ?>" width="850" height="720" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
<?php endif; ?>
