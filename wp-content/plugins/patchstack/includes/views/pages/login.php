<?php

// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="patchstack-font">
	<form class="patchstack-form-wrap" method="post" action="<?php echo esc_attr( $form_action ); ?>">
		<input type="hidden" value="<?php echo wp_create_nonce( 'patchstack-option-page' ); ?>" name="PatchstackNonce">
		<input type="hidden" value="patchstack_login_settings_group" name="option_page">
		<?php
			do_settings_sections( 'patchstack_login_settings' );
			settings_fields( 'patchstack_login_settings_group' );
			submit_button( __( 'Save settings', 'patchstack' ) );
		?>
	</form>
</div>
