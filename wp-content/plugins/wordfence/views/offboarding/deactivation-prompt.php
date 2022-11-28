<?php
if (!defined('WORDFENCE_VERSION')) exit;
?>
<div style="display: none;">
	<div class="wf-modal" id="wf-offboarding-delete-prompt-template">
		<div class="wf-modal-header">
			<div class="wf-modal-header-content">
				<div class="wf-modal-title"><strong><?php esc_html_e('Delete Wordfence Tables and Data', 'wordfence') ?></strong></div>
			</div>
		</div>
		<div class="wf-modal-content">
			<p><?php esc_html_e('You are about to deactivate Wordfence. Would you like to delete its data and tables or leave them in place?', 'wordfence') ?></p>
			<p><?php esc_html_e('If you choose to delete Wordfence\'s data and have optimized the Wordfence firewall, we recommend using the "Remove Extended Protection" button on the Firewall Options page before proceeding in order to avoid temporary PHP errors on some hosts.', 'wordfence') ?></p>
		</div>
		<div class="wf-modal-footer">
			<button id="wf-deactivate-delete" class="wf-btn wf-btn-danger"><?php esc_html_e('Delete', 'wordfence') ?></button>
			<button id="wf-deactivate-cancel" class="wf-btn wf-btn-default"><?php esc_html_e('Cancel', 'wordfence') ?></button>
			<button id="wf-deactivate-retain" class="wf-btn wf-btn-primary"><?php esc_html_e('Retain', 'wordfence') ?></button>
		</div>
	</div>
	<div class="wf-modal" id="wf-offboarding-delete-error-template">
		<div class="wf-modal-header">
			<div class="wf-modal-header-content">
				<div class="wf-modal-title"><strong><?php esc_html_e('Error', 'wordfence') ?></strong></div>
			</div>
		</div>
		<div class="wf-modal-content"><span class="message"><?php esc_html_e('An unexpected error occurred while attempting to configure Wordfence to delete its data on deactivation.', 'wordfence') ?></span></div>
		<div class="wf-modal-footer wf-modal-footer-center">
			<button onclick="jQuery.wfcolorbox.close(); return false;" class="wf-btn wf-btn-primary"><?php esc_html_e('Close', 'wordfence') ?></a>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function($) {

		function showOffboardingModal(id) {
			var content = $("#wf-offboarding-" + id + "-template").clone().attr('id', null);
			$.wfcolorbox({
				width: (wordfenceExt.isSmallScreen ? '300px' : '500px'),
				html: content[0].outerHTML,
				overlayClose: false,
				closeButton: false,
				className: 'wf-modal'
			});
		}

		function deactivate() {
			$.wfcolorbox.close();
			$(document).off('click.wf-deactivate');
			$('#deactivate-wordfence').get(0).click();
		}

		var processingWithDelete = false;

		$(document)
			.on('click', '#wf-deactivate-delete', function (event) {
				if (processingWithDelete)
					return;
				processingWithDelete = true;
				$(this).prop('disabled', true).addClass('disabled');
				function endProcessing() {
					$(this).prop('disabled', false).removeClass('disabled');
					processingWithDelete = false;
				}
				wordfenceExt.setOption(
					'deleteTablesOnDeact',
					true,
					function() {
						deactivate();
						endProcessing();
					},
					function() {
						showOffboardingModal('delete-error');
						endProcessing();
					}
				);
			})
			.on('click', '#wf-deactivate-cancel', function (event) {
				$.wfcolorbox.close();
			})
			.on('click', '#wf-deactivate-retain', function (event) {
				deactivate();
			})
			.on('click.wf-deactivate', '#deactivate-wordfence', function (event) {
				event.preventDefault();
				showOffboardingModal('delete-prompt');
			});

	})(jQuery);
</script>