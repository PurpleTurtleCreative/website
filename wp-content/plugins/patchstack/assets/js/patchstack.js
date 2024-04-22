/**
 * Patchstack
 * https://patchstack.com
 */

window.Patchstack = window.Patchstack || {};

( function( window, document, $, plugin ) {
	var $c = {};
	var polling = null;
	var isPolling = false;
	var pollingDelay = 0;

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body = $( document.body );
	};

	plugin.poller = function() {
		polling = setInterval(() => {
			if (!isPolling && pollingDelay == 0) {
				plugin.autoActivate();
				pollingDelay = 5;
			}

			pollingDelay--;
		}, 1000);
	};

	plugin.autoActivate = function() {
		isPolling = true;

		var postData = {
			action: 'patchstack_activate_auto',
			PatchstackNonce: PatchstackVars.nonce
		};

		$.post( PatchstackVars.ajaxurl, postData, function( response ) {
			var postData = {
				action: 'patchstack_activation_status',
				PatchstackNonce: PatchstackVars.nonce
			};
			
			$.post ( PatchstackVars.ajaxurl, postData, function( response ) {
				if ( response.activated ) {
					window.location = window.location.href.replace('&ps_autoa=1', '') + '&resync=1';
				}

				isPolling = false;
			});
		});
	};

	plugin.bindEvents = function() {
		$('input#patchstack-activate').on( 'click', function(e) {
			e.preventDefault();
			var postData = {
				action: 'patchstack_activate_license',
				key: $('.patchstack-auto-activate').attr('style') == '' ? $( '#patchstack_api_key2' ).val() : $( '#patchstack_api_key' ).val(),
				PatchstackNonce: PatchstackVars.nonce
			};

			if ( $.trim( postData.key ).length == 0 ) {
				alert( 'Please enter the API key.' );
				return false;
			}

			$( '.patchstack-resync' ).hide();
			$( '#patchstack-activate' ).hide();
			$( '.patchstack-loading' ).show();
			$( '.is-polling-section' ).hide();
			isPolling = true

			$.post( PatchstackVars.ajaxurl, postData, function( response ) {
				isPolling = false
				pollingDelay = 5
				if ( response.result == 'error' ) {
					$( '#patchstack-activate, .is-polling-section' ).show();
					$( '.patchstack-loading' ).hide();
					if ( response.error_message ) {
						alert( response.error_message );
					} else {
						alert( PatchstackVars.error_message );
					}
				} else {
					window.location = window.location.href + '&resync=1';
				}
			});
		});

		if($("#patchstack_captcha_type").val() == "v2"){
			$("#patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3, #patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").hide();
			$("#patchstack_captcha_public_key, #patchstack_captcha_private_key").show();
		}else if($("#patchstack_captcha_type").val() == "invisible"){
			$("#patchstack_captcha_public_key, #patchstack_captcha_private_key, #patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").hide();
			$("#patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3").show();
		}else{
			$("#patchstack_captcha_public_key, #patchstack_captcha_private_key, #patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3").hide();
			$("#patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").show();
		}

		$("#patchstack_captcha_type").change(function() {
			if($("#patchstack_captcha_type").val() == "v2"){
				$("#patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3, #patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").hide();
				$("#patchstack_captcha_public_key, #patchstack_captcha_private_key").show();
			}else if($("#patchstack_captcha_type").val() == "invisible"){
				$("#patchstack_captcha_public_key, #patchstack_captcha_private_key, #patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").hide();
				$("#patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3").show();
			}else{
				$("#patchstack_captcha_public_key, #patchstack_captcha_private_key, #patchstack_captcha_public_key_v3, #patchstack_captcha_private_key_v3").hide();
				$("#patchstack_captcha_public_key_v3_new, #patchstack_captcha_private_key_v3_new").show();
			}
		});

		if($('#geo-countries').length > 0){
			var items = []
			if($('#geo-countries').data('selected') != ''){
				items = $('#geo-countries').data('selected').split(',')
			}

			$('#geo-countries').selectize({
				maxItems: null,
				delimiter: ',',
				plugins: ['remove_button'],
				items: items
			});
		}
	};

	$( plugin.init );
}( window, document, jQuery, window.Patchstack ) );