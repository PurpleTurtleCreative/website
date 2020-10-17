<?php
/*This file is part of HelloPurpleTurtle, hello-elementor child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function HelloPurpleTurtle_enqueue_child_styles() {
$parent_style = 'parent-style';
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style(
		'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version') );
	}
add_action( 'wp_enqueue_scripts', 'HelloPurpleTurtle_enqueue_child_styles' );

/* Write here your own functions */

/* Try to get Yoast SEO to not recognize code content. */
function ptc_strip_pre_tags_filter( $post_content ) {

  $modified_content = preg_replace( '/<pre[^>]*>.+?<\/pre>/s', '', $post_content );

  if ( is_string( $modified_content ) && $modified_content !== NULL ) {
    return $modified_content;
  }

  return $post_content;

}
add_filter( 'wpseo_pre_analysis_post_content', 'ptc_strip_pre_tags_filter' );
add_filter( 'wpseo_post_content_for_recalculation', 'ptc_strip_pre_tags_filter' );

/*
Stop WordPress's default character conversions like
dashes to lines and three dots to ellipsis.

Source: https://wordpress.stackexchange.com/questions/60379/how-to-prevent-automatic-conversion-of-dashes-to-ndash
*/
remove_filter( 'the_title', 'wptexturize' );
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_excerpt', 'wptexturize' );

/* WooCommerce - Checkout Fields */
add_filter( 'woocommerce_checkout_fields', function( $fields ) {
  unset( $fields['billing']['billing_phone'] );
  return $fields;
});

/* WooCommerce - My Account */
add_filter( 'woocommerce_account_menu_items', function( $account_menu ) {
  $account_menu['api-keys'] = 'License Keys';
  $account_menu['api-downloads'] = 'Downloads';
  return $account_menu;
});

/* WooCommerce - Apply coupon codes via GET param */
add_action( 'woocommerce_after_calculate_totals', function() {

  if ( isset( $_GET['coupon_code'] ) && ! empty( $_GET['coupon_code'] ) ) {

    $coupon_code = filter_var(
      wp_unslash( $_GET['coupon_code'] ),
      FILTER_SANITIZE_STRING,
      FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH
    );

    if (
      ! empty( $coupon_code )
      && class_exists( 'WooCommerce' )
      && function_exists( 'WC' )
      && ! WC()->cart->has_discount( $coupon_code )
      && WC()->cart->get_total( 'edit' ) > 0
    ) {
      WC()->cart->apply_coupon( $coupon_code );
    }

  }//end if coupon exists

});

/* Hide admin menu bar for all users except editors */
if ( ! current_user_can( 'edit_pages' ) ) {
  add_filter( 'show_admin_bar', '__return_false' );
}

/* Auto Complete all WooCommerce orders */
add_action( 'woocommerce_thankyou', 'ptc_wc_auto_complete_order' );
function ptc_wc_auto_complete_order( $order_id ) {

  if ( ! $order_id ) {
    return;
  }

  $order = wc_get_order( $order_id );
  $order->update_status( 'completed' );

}
