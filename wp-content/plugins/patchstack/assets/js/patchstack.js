/**
 * Patchstack
 * https://patchstack.com
 */

window.Patchstack = window.Patchstack || {};

( function( window, document, $, plugin ) {
	var $c = {};

	plugin.init = function() {
		plugin.cache();
		plugin.bindEvents();
	};

	plugin.cache = function() {
		$c.window = $( window );
		$c.body = $( document.body );
	};

	plugin.bindEvents = function() {
		$('input#patchstack-activate').on( 'click', function(e) {
			e.preventDefault();
			var postData = {
				action: 'patchstack_activate_license',
				key: $( '#patchstack_api_key' ).val(),
				PatchstackNonce: PatchstackVars.nonce
			};

			if ( $.trim( postData.key ).length == 0 ) {
				alert( 'Please enter the API key.' );
				return false;
			}

			$( '.patchstack-resync' ).hide();
			$( '#patchstack-activate' ).hide();
			$( '.patchstack-loading' ).show();

			$.post( PatchstackVars.ajaxurl, postData, function( response ) {
				if ( response.result == 'error' ) {
					$( '#patchstack-activate' ).show();
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

		$('#patchstack_send_mail_url').on( 'click', function(e) {
			e.preventDefault();
			var postData = {
				action: 'patchstack_send_new_url_email',
				PatchstackNonce: PatchstackVars.nonce
			};
			$.post( PatchstackVars.ajaxurl, postData, function( response ) {
				if ( response == 'fail') {
					alert( PatchstackVars.error_message );
				} else {
					alert( 'Email Sent!' );
				}
			});
		});

		// Don't load this part if DataTables is not loaded.
		if(typeof jQuery.fn.dataTable !== 'undefined' && window.location.href.indexOf('patchstack') !== -1){

			// Initialize datatables.
			$('.table-firewall-log').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": PatchstackVars.ajaxurl,
					"type": "POST",
					"data": function(d){
						d.action = 'patchstack_firewall_log_table';
						d.PatchstackNonce = $("meta[name=patchstack_nonce]").attr("value");
					}
				},
				"responsive": true,
				"columns": [
					{ "data": "fid" },
					{ "data": "referer" },
					{ "data": "method" },
					{ "data": "ip", render: $.fn.dataTable.render.text() },
					{ "data": "log_date" }
				],
				"order": [[0, "desc"]],
				"searching": false,
				"ordering": false,
				"drawCallback": function( settings ) {
					$('.titletip').tooltip();
				},
				"columnDefs": [
					{
						"render": function ( data, type, row ) {
							var title = (data ? data : 'Unknown');
							var description = (row.description ? row.description : 'No Explanation');

							return '<span class=" titletip" title="' + description + '">' + title + '</span>';
						},
						"targets": 0
					},
					{
						"render": function ( data, type, row ) {
							return decodeURIComponent(data).replace(/</g,"&lt;").replace(/>/g,"&gt;").replace('+', ' ');
						},
						"targets": 1
					}
				]
			});

			$('.table-user-log').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": PatchstackVars.ajaxurl,
					"type": "POST",
					"data": function(d){
						d.action = 'patchstack_users_log_table';
						d.PatchstackNonce = $("meta[name=patchstack_nonce]").attr("value");
					}
				},
				"responsive": true,
				"columns": [
					{ "data": "author" },
					{ "data": "action", render: $.fn.dataTable.render.text() },
					{ "data": "object", render: $.fn.dataTable.render.text() },
					{ "data": "object_name", render: $.fn.dataTable.render.text() },
					{ "data": "ip", render: $.fn.dataTable.render.text() },
                    { "data": "date", render: $.fn.dataTable.render.text() }
				],
				"order": [[0, "desc"]],
				"searching": true,
				"ordering": false
			});
		}

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