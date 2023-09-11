<?php
// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="patchstack-cookie-notice" role="banner" style="opacity: <?php echo ( $this->get_option( 'patchstack_cookie_notice_opacity' ) < 100 ? '.' : '' ) . (int) $this->get_option( 'patchstack_cookie_notice_opacity' ); ?>;color: #<?php echo esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ); ?>; background-color: #<?php echo esc_attr( $this->get_option( 'patchstack_cookie_notice_backgroundcolor' ) ); ?>; visibility: hidden;">
	<div class="patchstack-cookie-notice-container">
		<div class="patchstack-cn-notice-text-container">
			<span id="patchstack-cn-notice-text">
				<?php
					echo esc_html( $this->get_option( 'patchstack_cookie_notice_message' ) );
					echo ( $this->get_option( 'patchstack_cookie_notice_privacypolicy_enable' ) == 1 ? ' - <a class="patchstack-cn-notice-link" style="text-decoration: underline !important; color: #' . esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ) . '; " href="' . esc_url( $this->get_option( 'patchstack_cookie_notice_privacypolicy_link' ) ) . '">' . esc_html( $this->get_option( 'patchstack_cookie_notice_privacypolicy_text' ) ) . '</a>' : '' );
				?>
			</span>
		</div>
		<div class="patchstack-cn-notice-button-container">
			<button style="border-color: #<?php echo esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ); ?>; color: #<?php echo esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ); ?>; <?php echo ( $this->get_option( 'patchstack_cookie_notice_credits' ) == 1 ? ' margin-bottom: 20px; ' : ' ' ); ?>" onclick="setCookieForNotice('<?php echo esc_attr( $this->get_option( 'patchstack_cookie_notice_cookie_expiration' ) ); ?>')" id="patchstack-cn-accept-cookie" data-cookie-set="accept" class="patchstack-cn-set-cookie patchstack-cn-button button"><?php echo esc_html( $this->get_option( 'patchstack_cookie_notice_accept_text' ) ); ?></button>
			<?php
				echo ( $this->get_option( 'patchstack_cookie_notice_credits' ) == 1 ? '<a class="patchstack-cn-protected-by" target="_blank" style="color: #' . esc_attr( $this->get_option( 'patchstack_cookie_notice_textcolor' ) ) . ';" href="https://patchstack.com" target="_blank"><img style="width:13px; float: left; margin-right: 5px; margin-top: 5px;" src="' . esc_url( $this->plugin->url ) . 'assets/images/logo-mini.svg" alt="Patchstack Logo"> Protected by Patchstack</a>' : ' ' );
			?>
		</div>
	</div>
</div>
