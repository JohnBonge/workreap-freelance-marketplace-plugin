(function( $ ) {

	'use strict';
	var loader_html	= '<div class="wr-preloader-section"><div class="wr-preloader-holder"><div class="wr-loader"></div></div></div>';
	var image_crop = '';
	//convert bytes to KB< MB,GB,TB
	function bytesToSize(bytes) {
		var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if (bytes == 0) return '0 Byte';
		var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	};


	jQuery(document).on('click','#wr-btn-search',function(e){
        e.preventDefault();
        let url_link    = jQuery('#wr-list-type').find(':selected').attr('data-url');
        jQuery('#wr-header-form').attr('action', url_link).submit();
    });

	jQuery(document).on('keydown','.wr-aichat_input > input',function(e){
		if(e.target.value.length > 0){
			$(this).parent().find('.wr-aibtn_request').removeClass('wr-aidisabled');
		}else{
			$(this).parent().find('.wr-aibtn_request').addClass('wr-aidisabled');
		}
		if (e.key === 'Enter') {
			e.preventDefault();
			$(this).parent().find('.wr-aibtn_request').click();
		}
	});

	jQuery(document).on('click','.wr-aibtn_request',function(e){
        e.preventDefault();
		let _this   = jQuery(this);
        let ai_type	=	_this.data('ai_type');
		let ai_id	=	_this.data('ai_id');
		let ai_reply_type	=	_this.data('ai_reply_type');
		let ai_content	= '';
		if(ai_reply_type === 'regenerate'){
			let parent_id	=	_this.data('parent_id');
			ai_content	= jQuery('#wr-ai-contenet-'+parent_id).text();
		} else {
			ai_content	= jQuery('#wr-aiText-'+ai_id).val();
		}

		jQuery('#wr-ailist_empty-'+ai_id).addClass('d-none');

		if (ai_content.trim() === '') {
			//alert("ai_content");
			jQuery('#wr-aiText-'+ai_id).focus();
		} else {
			if(ai_reply_type === 'reply'){
				jQuery('#wr-aiText-'+ai_id).val('');
			}
			if(ai_reply_type != 'regenerate'){
				var counter1 	= Math.floor((Math.random() * 999999) + 999);
				var load_task 	= wp.template('load-aiUserReply-'+ai_id);
				var data 		= {counter: counter1, content: ai_content, type: 'ai-user_reply'};
				load_task 		= load_task(data);
				jQuery('#wr-ailist-'+ai_id).append(load_task);
			}
			jQuery('#wr-aiText-'+ai_id).addClass('wr-aidisabled');
			jQuery('#wr-ailist_loader-'+ai_id).appendTo('#wr-ailist-'+ai_id);
			jQuery('#wr-ailist_loader-'+ai_id).removeClass('d-none');
			_this.addClass('wr-aidisabled');
			jQuery('#wr-ailist-'+ai_id).scrollTop(jQuery('#wr-ailist-'+ai_id).prop("scrollHeight"));
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreapGetAIContent',
					'security'	: scripts_vars.ajax_nonce,
					'ai_type'	: ai_type,
					'ai_reply_type':ai_reply_type,
					'ai_content': ai_content
				},
				dataType: "json",
				success: function (response) {
					_this.removeClass('wr-aidisabled');
					jQuery('#wr-aiText-'+ai_id).removeClass('wr-aidisabled');
					jQuery('#wr-ailist_loader-'+ai_id).addClass('d-none');
					if (response.type === 'success') {
						if(response.content){
							var counter 	= Math.floor((Math.random() * 999999) + 999);
							var load_task 	= wp.template('load-aiUserReply-'+ai_id);
							let main_content = response.content.replace(/\r?\n/g, "<br>");
							main_content	= main_content.replace(/(<br\s*\/?>\s*){2,}/gi, '<br>');
							var data 		= {counter: counter, content: main_content, type: 'ai_reply',parent_counter:counter1};
							load_task 		= load_task(data);
							if(ai_reply_type === 'regenerate'){
								let section_id	=	_this.data('id');
								jQuery("#aiUserReply-"+section_id).remove();
							}
							jQuery('#wr-ailist-'+ai_id).append(load_task);
							var currentIndex = 0;
							var intervalId = null;
							let box			= jQuery('#wr-ai-contenet-'+counter);

							intervalId = setInterval(function() {
								if (currentIndex >= main_content.length) {
									box.parents('.wr-user-reply').removeClass('wr-ai-content-laoding');
									clearInterval(intervalId);
									return;
								}
								var char = main_content[currentIndex];
								if (char === "<") {
								  var tagEndIndex = main_content.indexOf(">", currentIndex + 1);
								  box.append(main_content.substring(currentIndex, tagEndIndex + 1));
								  currentIndex = currentIndex+3;
								} else {
								  box.append(char);
								}
								currentIndex++;
								jQuery('#wr-ailist-'+ai_id).scrollTop(jQuery('#wr-ailist-'+ai_id).prop("scrollHeight"));
							}, 10);
						}
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			});
		}
    });

	jQuery(document).on('click', '.wr-ai_copy', function(e) {
		e.preventDefault();
		var _this = jQuery(this);
		let id = _this.data('id');
		let ai_id = _this.data('ai_id');
		let content = jQuery('#wr-ai-contenet_hiden-' + id).html();

		// Format content for plain text
		let formattedContent = content.replace(/<br\s*\/?>/gi, '\n');
		let plainTextContent = formattedContent.replace(/<\/?[^>]+(>|$)/g, "");

		// Try using Clipboard API
		navigator.clipboard.writeText(plainTextContent)
		  .then(function() {
			// Success - Update button visuals
			_this.addClass('wr-copy_color');
			_this.html('<i class="wr-icon-saved" aria-hidden="true"></i>' + scripts_vars.copied);
			window.setTimeout(function() {
			  _this.html('<i class="wr-icon-save" aria-hidden="true"></i>' + scripts_vars.copy);
			  _this.removeClass('wr-copy_color');
			}, 2000);
		  })
		  .catch(function(err) {
			// Clipboard API error - Fallback (optional)
			console.error('Failed to copy to clipboard:', err);
			// Implement fallback functionality here (e.g., display an error message or use a legacy copy method)
		  });
	  });

	// jQuery(document).on('click','.wr-ai_copy',function(e){
	// 	e.preventDefault();
	// 	var _this   = jQuery(this);
	// 	let id = _this.data('id');
	// 	let ai_id = _this.data('ai_id');
	// 	let content = jQuery('#wr-ai-contenet-' + id).html();
	// 	let formattedContent = content.replace(/<br\s*\/?>/gi, '\n');
	// 	// Remove HTML tags for plain text
	// 	let plainTextContent = formattedContent.replace(/<\/?[^>]+(>|$)/g, "");
	// 	navigator.clipboard.writeText(plainTextContent).then(function() {
	// 		_this.addClass('wr-copy_color');
	// 		_this.html('<i class="wr-icon-saved" aria-hidden="true"></i>'+scripts_vars.copied);
	// 		window.setTimeout(function() {
	// 			_this.html('<i class="wr-icon-save" aria-hidden="true"></i>'+scripts_vars.copy);
	// 			_this.removeClass('wr-copy_color');
	// 		}, 2000);
	// 	}, function(err) {
	// 	});
	// });

	jQuery(document).on('click', '.wr-ai_insert', function(e) {
		e.preventDefault();
		let _this = jQuery(this);
		let id = _this.data('id');
		let ai_id = _this.data('ai_id');
		let content = jQuery('#wr-ai-contenet-' + id).html();
		var elements = jQuery("[data-ai_content_id='" + ai_id + "']");
		if(!elements.length){
			elements = $(document).find("#"+ai_id);
		}
		jQuery('#workreap-aicontent-' + ai_id).modal('hide');
		let old_content = elements.val();
		let dbcontent = content.replace(/<br>/g, '\n');
		var cleanedText = old_content.replace(/\s+/g, ' ').trim() + dbcontent.replace(/\s+/g, ' ').trim();
		elements.val(cleanedText);

		//Editor
		if (typeof tinymce !== 'undefined' && tinymce.activeEditor !== null) {
			var activeEditor = tinymce.activeEditor;
			if (activeEditor.id === ai_id) {
				activeEditor.setContent(cleanedText);
			}
		}

	});

	jQuery(document).on('click','.wr-ai_replace',function(e){
		e.preventDefault();
		let _this   = jQuery(this);
        let id	=	_this.data('id');
		let ai_id	=	_this.data('ai_id');
		let content	= jQuery('#wr-ai-contenet-'+id).html();
		let dbcontent = content.replace(/<br>/g, '\n');
		var elements = jQuery("[data-ai_content_id='" + ai_id + "']");
		if(!elements.length){
			elements = $(document).find("#"+ai_id);
		}
		jQuery('#workreap-aicontent-'+ai_id).modal('hide');
		var cleanedText = dbcontent.replace(/\s+/g, ' ').trim();
		elements.val(cleanedText);

		//Editor
		if (typeof tinymce !== 'undefined' && tinymce.activeEditor !== null) {
			var activeEditor = tinymce.activeEditor;
			if (activeEditor.id === ai_id) {
				activeEditor.setContent(cleanedText);
			}
		}
	});
	// Project cration
	jQuery('.wr-save-project').on('click', function(){

		let _this       	= jQuery(this);
		let step_id      	= _this.data('step_id');
		let project_id      = _this.data('project_id');

		if (typeof tinymce !== 'undefined'){
			let textarea = $(document).find('[name="details"]');
			let tiny_editor = tinyMCE.get(textarea.attr('id'));
			if(tiny_editor){
				let editorContent = tiny_editor.getContent({format: 'raw'});
				textarea.val(editorContent).trigger('change');
			}
		}

		let _serialized   	= jQuery('.wr-project-form').serialize();

		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_project',
				'security'	: scripts_vars.ajax_nonce,
				'step_id'	: step_id,
				'project_id': project_id,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					if(response.step_id == 3 ){
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
						if(response.redirect){
							window.setTimeout(function() {
								window.location.href = response.redirect
							}, scripts_vars.alertbox_autoclose / 2 || 2500);
						}
					}  else if(response.redirect){
						window.location.href = response.redirect
					}
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});

	});

	// featured/unfeatured project
	$(document).on('click', '.wr_project_featured', function (e) {
		let _this			= $(this);
		let id 				= _this.data('id');
		let value 			= _this.data('value');

		let model_title			= scripts_vars.featured_title;
		let model_description	= '';
		if( value === 'yes' ){
			model_description	= scripts_vars.featured_details;
		} else {
			model_description	= scripts_vars.unfeatured_details;
		}
		jQuery.confirm({
			icon: 'wr-icon-flag',
			content: model_description,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${model_title}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.yes_btntext,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'	: 'workreap_project_featured',
								'security'	: scripts_vars.ajax_nonce,
								'id'		: id,
								'value'		: value
							},
							dataType: "json",
							success: function (response) {
								jQuery('.wr-preloader-section').remove();

								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});

					}
				},
				no: {
					text: scripts_vars.no_btntext,
					btnClass: 'wr-btnvthree'
				}
			}
		});
	});

	// hired project with wallet
	$(document).on('click', '.wr_proposal_hiring', function (e) {
		let _this			= $(this);
		let id 				= _this.data('id');
		let key 			= _this.data('key');
		jQuery.confirm({
			icon: 'wr-icon-bell',
			content: scripts_vars.wallet_account_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.wallet_account}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.btn_with_wallet,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'	: 'workreap_project_hiring',
								'security'	: scripts_vars.ajax_nonce,
								'id'		: id,
								'key'		: key,
								'wallet'	: true
							},
							dataType: "json",
							success: function (response) {
								jQuery('.wr-preloader-section').remove();

								if (response.type === 'success') {
									window.location.replace(response.checkout_url);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});

					}
				},
				no: {
					text: scripts_vars.btn_without_wallet,
					btnClass: 'wr-btnvthree',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'	: 'workreap_project_hiring',
								'security'	: scripts_vars.ajax_nonce,
								'id'		: id,
								'key'		: key,
							},
							dataType: "json",
							success: function (response) {
								jQuery('.wr-preloader-section').remove();

								if (response.type === 'success') {
									window.location.replace(response.checkout_url);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});

					}
				}
			}
		});
	});

	// hired project without wallet
	$(document).on('click', '.wr_hire_proposal', function (e) {
		let _this			= $(this);
		let id 				= _this.data('id');
		let key 			= _this.data('key');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_project_hiring',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'key'		: key,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					window.location.replace(response.checkout_url);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	$(document).on('click', '.wr_decline_proposal', function (e) {
		let _this			= $(this);
		let id 				= _this.data('id');
		let detail 			= jQuery('#wr_decline_detail').val();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_decline_proposal',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'detail'	: detail,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					window.location.replace(response.redirect_url);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// oUpdate milestone status
	jQuery(document).on('click', '.wr_update_milestone', function(e){
		e.preventDefault();
		var _this    	= jQuery(this);
		var id      	= _this.data('id');
		var key      	= _this.data('key');
		var status      = _this.data('status');

		jQuery.confirm({
			icon: 'wr-icon-bell',
			content: scripts_vars.milestone_request_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.milestone_title}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.yes_btntext,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'	: 'workreap_update_milestone',
								'security'	: scripts_vars.ajax_nonce,
								'id'		: id,
								'key'		: key,
								'status'	: status,
							},
							dataType: "json",
							success: function (response) {
							   jQuery('.wr-preloader-section').remove();

							   if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});

					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree'
				}
			}
		});


	});

	// Decline milestone
	jQuery(document).on('click', '.wr_decline_milestone', function(e){
		e.preventDefault();
		var _this    	= jQuery(this);
		var id      	= _this.data('id');
		var key      	= _this.data('key');
		var status      = _this.data('status');
		let	decline_reason	= jQuery('#milestone_declinereason-'+key).val()
		jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_update_milestone',
					'security'	: scripts_vars.ajax_nonce,
					'id'		: id,
					'key'		: key,
					'status'	: status,
					'decline_reason' : decline_reason
				},
				dataType: "json",
				success: function (response) {
				   jQuery('.wr-preloader-section').remove();

				   if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
						window.setTimeout(function() {
							window.location.reload();
						}, scripts_vars.alertbox_autoclose / 2 || 2500);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			});


	});
	// Project activity
	jQuery(document).on('click', '.wr-submit-project-commetns', function(e){
		e.preventDefault();
		var _this    	  = jQuery(this);
		var _id      	  = _this.data('id');
		var _serialized = jQuery('.wr-project-comment-form').serialize();
		var dataString 	= 'security='+scripts_vars.ajax_nonce+'&'+_serialized+ '&id='+_id+'&action=workreap_project_activities';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type     : "POST",
			url      : scripts_vars.ajaxurl,
			data     : dataString,
			dataType : "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success'){
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					jQuery('.wr-project-comment-form').get(0).reset();

					setTimeout(function(){
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				}else{
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	jQuery('.cus-modal').hide();

	//Remove param from URL
	function removeParam(key, sourceURL) {
		var rtn = sourceURL.split("?")[0],
			param,
			params_arr = [],
			queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

		if (queryString !== "") {
			params_arr = queryString.split("&");
			for (var i = params_arr.length - 1; i >= 0; i -= 1) {
				param = params_arr[i].split("=")[0];

				if (param === key) {
					params_arr.splice(i, 1);
				}
			}

			if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
		}

		return rtn;
	}
	/**
	 * All of the code for your public-facing JavaScript source should reside in this file.
	 *
	 */
$(function() {

	//convert bytes to KB< MB,GB,TB
	function inputfieldreset(id) {
		$('#'+id).val('');
	};

	//trigger change image click
	jQuery(document).on('click', '#profile-avatar-trigger', function (e) {
		jQuery('#profile-avatar').trigger("change");
	});

	// User Active account
	jQuery(document).on('click', '.wr-active-account', function (e) {
		let id	= jQuery(this).data('id');
		e.preventDefault();
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.active_account_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.active_account}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.active_account,
					btnClass: 'wr-btn',
					action: function () {
						$('body').append(loader_html);
						$.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_active_account',
								'id'			: id,
								'security'		: scripts_vars.ajax_nonce,
							},
							dataType: "json",
							success: function (response) {
								$('.wr-preloader-section').remove();
								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.no,
					btnClass: 'wr-btnvthree',
				}
			}
		});

	});
	//Save profile identity verification
	$(document).on('click', '.wr_profile_verification', function (e) {
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#wr_identity_settings').serialize();
		var _id      	  = _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_verification',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: _id,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
				StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				window.setTimeout(function() {
					window.location.reload();
				}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task refund request
	$(document).on('click', '#wr-add_milestone', function (e) {
		jQuery(".wr-contract-list").slideUp();
		jQuery('#tbaddmilestone').modal('show');
		jQuery('#tbaddmilestone').removeClass('hidden');
		e.preventDefault();
	});

	//Task refund request
	$(document).on('click', '#taskrefundrequest', function (e) {

		var load_download_temp = wp.template('load-task-refund-request');
		var load_download_item = load_download_temp();
		$('#workreap-popup').html(load_download_item);
		jQuery(".wr-contract-list").slideUp();
		jQuery('#workreap-popup').modal('show');
		jQuery('#workreap-popup').removeClass('hidden');
		e.preventDefault();
	});


	//Task dispute request action
	$(document).on('click', '.project-dispute-action', function (e) {
		$('.project-dispute-action').removeClass('active');
		var action_type	= $(this).data('action');
		var submit_title	= $(this).data('submit_title');
		$('#project-dispute-reply-btn').text(submit_title);
		$('#action_type').val(action_type);
		$('.wr-refundform').show();
		$('.wr-refundform').attr('id', 'wr-form-'+action_type);
		$(this).addClass('active');
		$( "#dispute_comment" ).focus();
		e.preventDefault();
	});

	//Add milestone
	$(document).on('click', '#wr_add_milestonebtn', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#wr_submit_milestone').serialize();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_add_milestone',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task dispute reply
	$(document).on('click', '#project-dispute-reply-btn', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#dispute-reply-form').serialize();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_submit_project_dispute_reply',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					jQuery('#dispute_comment').val('');
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task dispute request action
	$(document).on('click', '.dispute-action', function (e) {
		$('.dispute-action').removeClass('active');
		var action_type	= $(this).data('action');
		var submit_title	= $(this).data('submit_title');
		$('#dispute-reply-btn').text(submit_title);
		$('#action_type').val(action_type);
		$('.wr-refundform').show();
		$('.wr-refundform').attr('id', 'wr-form-'+action_type);
		$(this).addClass('active');
		$( "#dispute_comment" ).focus();
		e.preventDefault();
	});

	//Task dispute reply
	$(document).on('click', '#dispute-reply-btn', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#dispute-reply-form').serialize();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_submit_dispute_reply',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					jQuery('#dispute_comment').val('');
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task dispute request
	$(document).on('click', '#taskdisputerequest', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var dispute_id   	= _this.data('dispute_id');

		jQuery('body').append(loader_html);

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_dispute_request_submit',
				'security'		: scripts_vars.ajax_nonce,
				'dispute_id'	: dispute_id,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					jQuery('#dispute_comment').val('');
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);

				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task refund submit by freelancer
	$(document).on('click', '#freelancer-request-submit', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#task-refund-request').serialize();

		jQuery('body').append(loader_html);

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_freelancer_submit_dispute',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});
	//Task refund submit
	$(document).on('click', '#projectrefundrequest-submit', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#project-refund-request').serialize();

		jQuery('body').append(loader_html);

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_submit_project_dispute',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task refund submit
	$(document).on('click', '#taskrefundrequest-submit', function (e) {
		e.preventDefault();
		var _this 			= jQuery(this);
		var _serialized   	= jQuery('#task-refund-request').serialize();

		jQuery('body').append(loader_html);

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_submit_dispute',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task dispute input search
	jQuery(document).on('change','input#dispute-search',function(){
		let search_val = $('#dispute-search').val();
		if(search_val){
			var url = window.location.href;
			var url = removeParam("search", url);
			if (url.indexOf('?') > -1){
				url += '&search='+search_val
			}else{
				url += '?search='+search_val
			}
			window.location.href = url;
		}
		e.preventDefault();
	});

	//Saved item change
	// $(document).on('#wr-select-saveditems', '.dispute-search-date', function (e) {
	// 	let search_date = $(this).val();

	// 	if(search_date){
	// 		var url = window.location.href;
	// 		var url = removeParam("days", url);
	// 		if (url.indexOf('?') > -1){
	// 			url += '&days='+search_date
	// 		}else{
	// 			url += '?days='+search_date
	// 		}
	// 		window.location.href = url;
	// 	}
	// 	e.preventDefault();
	// });

	//Task dispute search button
	$(document).on('click', '#dispute-search-btn', function (e) {
		let search_val = $('#dispute-search').val();
		if(search_val){
			var url = window.location.href;
				url = removeParam("search", url);
			if (url.indexOf('?') > -1){
				url += '&search='+search_val
			}else{
				url += '?search='+search_val
			}
			window.location.href = url;
		}
		e.preventDefault();
	});

	//Task dispute search by date
	$(document).on('change', '.dispute-search-date', function (e) {
		let search_date = $(this).val();

		if(search_date){
			var url = window.location.href;
			var url = removeParam("sortby", url);
			if (url.indexOf('?') > -1){
				url += '&sortby='+search_date
			}else{
				url += '?sortby='+search_date
			}
			window.location.href = url;
		}
		e.preventDefault();
	});

	//Task add download file
	$(document).on('click', '.wr-addlink', function (e) {
		$(this).closest('li').children('.wr-uploadfile-list').addClass('d-flex')
		$(this).closest('.wr-uploadbar-content').addClass('d-none')
	});

	//Task edit download file
	$(document).on('click', '.wr-checked', function (e) {
		let title	= jQuery(this).closest('li').find('.media-title-input').val();
		jQuery(this).closest('li').find('.media-title-text').html(title);
		jQuery(this).closest('a.media-title-text').html(title);
		jQuery(this).closest('li').children('.wr-uploadbar-content').removeClass('d-none')
		jQuery(this).closest('.wr-uploadfile-list').removeClass('d-flex')
	});

	//Remove parent list item
	$(document).on('click', '.wr-trashlink', function (e) {
		$(this).closest('li').remove();
	});
	//Remove parent list item
	$(document).on('click', '.wr-invite-bid', function (e) {
		let	_this		= jQuery(this);
		let project_id	= _this.data('project_id');
		let profile_id	= _this.data('profile_id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'			: 'workreap_project_invitation',
				'security'			: scripts_vars.ajax_nonce,
				'project_id'		: project_id,
				'profile_id'		: profile_id,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					jQuery(_this).html(response.message_desc);
					jQuery(_this).removeClass('wr-invite-bid');
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Task introduction form
	$(document).on('submit', '#service-introduction-form', function (e) {
		$('body').append(loader_html);
		var data = new FormData( this );
		data.append('action', 'workreap_add_service_inroduction_save' );
		data.append('ajax_nonce', scripts_vars.ajax_nonce );
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: data,
			dataType: "json",
			contentType: false,
			processData: false,
			success: function (response) {
				let extra_params = {};
				if (response.type === 'success') {

					if(response.post_id){
						workreap_add_service_next_step(response.post_id, 2);
					} else {
						jQuery('.wr-preloader-section').remove();
						extra_params['note_desc'] = response.message_desc;
						if (response.type){}
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				} else {
					jQuery('.wr-preloader-section').remove();
					if (response.button){
						StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}
		});

		e.preventDefault();
	});

	//Task media attachment form
	$(document).on('submit', '#service-media-attachments-form', function (e) {
		let _serialized   = $(this).serialize();
		let data = {
			'action'	: 'workreap_add_service_media_attachments_save',
			'security'	: scripts_vars.ajax_nonce,
			'data'		: _serialized,
		};
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_add_service_media_attachments_save',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {

				if (response.type === 'success') {

					if(response.post_id){
						workreap_add_service_next_step(response.post_id, response.step);
					} else {
						jQuery('.wr-preloader-section').remove();
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				} else {
					jQuery('.wr-preloader-section').remove();
					if (response.button){
						StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}
		});
		e.preventDefault();
	});

	//Task media attachment display downloadables fields
	jQuery(document).on('change', '#downloadables', function ($) {

		if (jQuery(this).is(':checked')) {
			jQuery('#service-downloads').show();
		} else {
			jQuery('#service-downloads').hide();
		}

	});

	// Task media attachment input download files
	$(document).on('change', '#service-media-attachments-form input.downloadfile', function (e) {

		let inputurl = $(this).attr('id');
		var fileName = e.target.files[0].name;
		if(fileName == ''){
			return;
		}
		jQuery('body').append(loader_html);
		var file = this.files[0];
		let formData = new FormData();
		formData.append( 'action', 'workreap_temp_file_uploader');
		formData.append( 'ajax_nonce', scripts_vars.ajax_nonce );
		formData.append('file_name', file);
		$('body').append(loader_html);
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: formData,
			dataType: "json",
			contentType: false,
			processData: false,
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				let extra_params = {};
				if (response.type === 'success') {
					$('.'+inputurl).val(response.thumbnail);
					if($('.'+inputurl+'-title').val() == ''){
						$('.'+inputurl+'-title').val(fileName);
					}
				} else {
					$(this).val('');
					extra_params['note_desc'] = response.message_desc;
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});

				}
			}
		});
		e.preventDefault();
	});

	//Task media attachment input video file
	$(document).on('change', '#service-media-attachments-form input[name="videofile"]', function (e) {

		let inputurl = $(this).attr('id');
		var fileName = e.target.files[0].name;
		if(fileName == ''){
			return;
		}
		jQuery('body').append(loader_html);
		var file = this.files[0];
		let formData = new FormData();
		formData.append( 'action', 'workreap_temp_file_uploader');
		formData.append( 'ajax_nonce', scripts_vars.ajax_nonce );
		formData.append('file_name', file);

		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: formData,
			dataType: "json",
			contentType: false,
			processData: false,
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				let extra_params = {};
				if (response.type === 'success') {
					$('#videourl').val(response.url);
					inputfieldreset('custom_video_upload');
					$('#custom_video_upload').val(1);
				} else {
					$(this).val('');
					extra_params['note_desc'] = response.message_desc;
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});

				}
			}
		});
		e.preventDefault();
	});

	//Task media add video link
	$(document).on('click', '.add-videolink-btn', function (e) {
		let videolink_title = $('#videolink_title').val();
		let videolink_url = $('.videolink_url').val();
		if(videolink_title == '' || videolink_url == ''){
			return;
		}
		var load_videolinks_temp = wp.template('load-videolinks');
		var data = { title: videolink_title, url: videolink_url };
		var load_videolinks_item = load_videolinks_temp(data);
		$('#tbslothandlevideo').append(load_videolinks_item);
		$('#videolink_title').val('');
		$('#videolink_url').val('');
		e.preventDefault();
	});

	//Task media attachment add download option
	$(document).on('click', '.add-downloads-btn', function (e) {
		let download_title = $('#download_title').val();
		let download_url = $('.download_file_url').val();
		if(download_title == '' || download_url == ''){
			return;
		}
		let rand_id	= Date.now();
		var load_download_temp = wp.template('load-dwonloads');
		var data = { id: rand_id, title: download_title, url: download_url };
		var load_download_item = load_download_temp(data);
		$('#tbslothandle').append(load_download_item);
		$('#download_title').val('');
		$('.download_file_url').val('');
		e.preventDefault();
	});

	//Task plans form
	$(document).on('submit', '#service-plans-form', function (e) {

		jQuery('#service-plans-form').validate();
		let _serialized   = $('#service-plans-form').serialize();

		var myCheckboxes = new Array();
		$("input.wr-service-subtask:checked").each(function() {
			myCheckboxes.push($(this).val());
		});
		let dataString = _serialized + '&subtasks_selected_ids=' + myCheckboxes
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_add_service_plans_save',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: dataString,
			},
			dataType: "json",
			success: function (response) {

			if (response.type === 'success') {

					if(response.post_id){
						workreap_add_service_next_step(response.post_id, response.step);
					} else {
						jQuery('.wr-preloader-section').remove();
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}

				} else {
					jQuery('.wr-preloader-section').remove();
					if (response.button){
						StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}
		});

		e.preventDefault();
	});

	jQuery(document).on('click', '.wr-cancel-identity', function (e) {
		e.preventDefault();
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.cancel_verification_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.cancel_verification}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.cancel_verification,
					btnClass: 'wr-btn',
					action: function () {
						$('body').append(loader_html);
						$.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_cancel_verification_request',
								'security'		: scripts_vars.ajax_nonce,
							},
							dataType: "json",
							success: function (response) {
								$('.wr-preloader-section').remove();
								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});

	});
	//Task add subtask list
	$(document).on('click', '#wr-subtask-addlist', function (e) {

		let subtask_id	= $('#additional_service_subtasks').val();

		if($('#tbslothandle #subtask-'+subtask_id).length>0){
			alert('subtask alerady added to list');
		}

		$('body').append(loader_html);
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_get_service_subtask',
				'security'		: scripts_vars.ajax_nonce,
				'subtask_id'	: subtask_id,
			},
			dataType: "json",
			success: function (response) {
				$('.wr-preloader-section').remove();

				if (response.type === 'success') {

					if(response.subtask_data){
						$("#additional_service_subtasks option[value='"+subtask_id+"']").attr("disabled","disabled");
						let subtask_template = wp.template('load-service-subtask');
						let subtask_data = subtask_template(response.subtask_data);

						if($('#tbslothandle #subtask-'+response.subtask_id).length>0){
							subtask_data = '<div>'+subtask_data+'<div>';
							var htmlVal = subtask_data;
							var spanElement = $(htmlVal).find('li#subtask-'+response.subtask_id);
							var spanVal = spanElement.html();
							let subtask_list_data = $(subtask_data).find('li#subtask-'+response.subtask_id);
							$('li#subtask-'+response.subtask_id).html(subtask_list_data);
						} else {
							$('#tbslothandle').append(subtask_data);
						}
					}

					$('#additional_service_subtasks').val('');
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}

			}
		});
		e.preventDefault();
	});
	//Task load subtask popup
	$(document).on('click', '#wr_add_customfields', function (e) {
		let count_fields	= jQuery('#wr-customfields-ul li').length;
		let	option_limit	= scripts_vars.maxnumber_fields;
		let empty_field		= false;
		$.each($('#wr-customfields-ul li .wr-cf-title-input'),function() {
			if ($(this).val().length == 0) {
				empty_field		= true;

				return false;
			}
		});
		if( empty_field == true ){
			StickyAlert(scripts_vars.error_title, scripts_vars.empty_custom_field, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
		}else if(count_fields >= option_limit){
			StickyAlert(scripts_vars.error_title, scripts_vars.max_custom_fieds_error, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
		} else {
			var load_subtask_popup = wp.template('load-service-custom_fields');
			var counter 	= Math.floor((Math.random() * 999999) + 999);
			var data = {
				id: counter,
			};
			load_subtask_popup = load_subtask_popup(data);
			jQuery('#wr-customfields-ul').append(load_subtask_popup);
		}
	});
	//Task load subtask popup
	$(document).on('click', '#wr_add_new_task', function (e) {
		let heading	= $(this).data('heading');
		$('body').append(loader_html);
		var load_subtask_popup = wp.template('load-service-add-subtask');
		var data = {
			title: '',
			price: '',
			id: '',
			content: '',
		};
		load_subtask_popup = load_subtask_popup(data);
		jQuery('#workreap-taskaddon-popup').find('.wr-popuptitle h4').html(heading);
		jQuery('#workreap-taskaddon-popup').find('#workreap-model-body').html(load_subtask_popup);
		jQuery('#workreap-taskaddon-popup').modal('show');
		jQuery('#workreap-taskaddon-popup').removeClass('hidden');
		$('.wr-preloader-section').remove();
		e.preventDefault();
	});

	//Task  modal popup empty title
	$('#workreap-modal-popup').on('hidden.bs.modal', function () {
		jQuery('#workreap-modal-popup').find('.wr-popuptitle h4').html('');
	});

	//Save subtask
	$(document).on('click', '#wr-add-subtask-service', function (e) {
		let subtask_title   	= $('#subtask-title').val();
		let subtask_price   	= $('#subtask-price').val();
		let subtask_content   = $('#subtask-description').val();
		let service_id   		  = $('#service_id').val();
		let subtask_id   		  = $('#subtask_id').val();

		$('body').append(loader_html);

		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_service_subtask_save',
				'security'		: scripts_vars.ajax_nonce,
				'service_id'	: service_id,
				'subtask_id'	: subtask_id,
				'title'			: subtask_title,
				'price'			: subtask_price,
				'content'		: subtask_content,
			},
			dataType: "json",
			success: function (response) {
				$('.wr-preloader-section').remove();
				if (response.type === 'success') {
					if(response.subtask_data){
						let subtask_template = wp.template('load-service-subtask');
						let subtask_data = subtask_template(response.subtask_data);
						if($('#tbslothandle #subtask-'+subtask_id).length>0){
							$("#additional_service_subtasks option[value='"+subtask_id+"']").attr("disabled","disabled");
							subtask_data = '<div>'+subtask_data+'<div>';
							var htmlVal = subtask_data;
							var spanElement = $(htmlVal).find('li#subtask-'+subtask_id);
							var spanVal = spanElement.html();
							$('li#subtask-'+subtask_id).html(spanVal);
						} else {
							$('#tbslothandle').prepend(subtask_data);
						}
					}
					$('#subtask-title').val('');
					$('#subtask-price').val('');
					$('#subtask-description').val('');
					jQuery('#workreap-taskaddon-popup').find('#wr-popuptitle h4').html('');
					jQuery('#workreap-taskaddon-popup').modal('hide');

					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					if (response.button){
						StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}
		});
		e.preventDefault();
	});

	//Subtask edit
	$(document).on('click', '.wr-subtask-edit', function (e) {
		let heading	= $(this).data('heading');
		jQuery('#workreap-subadon-popup').find('.wr-popuptitle h4').html(heading);
		let subtask_id   	= $(this).attr('data-subtask_id');
		$('body').append(loader_html);
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_get_service_subtask',
				'security'		: scripts_vars.ajax_nonce,
				'subtask_id'	: subtask_id,
			},
			dataType: "json",
			success: function (response) {

				if (response.type === 'success') {
					if(response.subtask_data){
						$('body').append(loader_html);

						var load_subtask_popup = wp.template('load-service-add-subtask');
						var data = response.subtask_data;
						load_subtask_popup = load_subtask_popup(data);
						jQuery('#workreap-taskaddon-popup').find('#workreap-model-body').html(load_subtask_popup);
						jQuery('#workreap-taskaddon-popup').find('.wr-popuptitle h4').html(heading);
						jQuery('#workreap-taskaddon-popup').modal('show');
						jQuery('#workreap-taskaddon-popup').removeClass('hidden');
					}
					$('.wr-preloader-section').remove();

				} else {
					$('.wr-preloader-section').remove();
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}

			}
		});
		e.preventDefault();
	});

	//Task hire toggle class
	jQuery('#wr-hiretask').on('click',function(e){
		$("#overlay").css("display", "block");
		$(".wr-fixsidebar").toggleClass("wr-fixsidebarshow");
		});

		//Task complete action
		jQuery(document).on('click','.wr_complete_task',function(e){
		e.preventDefault();
		let user_id			= jQuery(this).data('user_id');
		let task_id			= jQuery(this).data('task_id');
		let order_id		= jQuery(this).data('order_id');

		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_complete_task_order',
				'security'		: scripts_vars.ajax_nonce,
				'task_id'		: task_id,
				'order_id'		: order_id,
				'user_id'		: user_id
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
		});

		//Project complete action
		jQuery(document).on('click','.wr_complete_project',function(e){
		e.preventDefault();
		let user_id			= jQuery(this).data('user_id');
		let proposal_id		= jQuery(this).data('proposal_id');
		jQuery.confirm({
			icon: 'wr-icon-flag',
			content: scripts_vars.complate_without_project_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.complate_without_project}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.yes,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_complete_project_order',
								'security'	: scripts_vars.ajax_nonce,
								'proposal_id'	: proposal_id,
								'user_id'		: user_id
							},
							dataType: "json",
							success: function (response) {
								jQuery('.wr-preloader-section').remove();

								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
	});

	jQuery('.wr_view_cancellation').on('click', function(){
	var _this       = jQuery(this);
	var order_id    = _this.data('order_id');
	jQuery('body').append(loader_html);
	jQuery.ajax({
		type: 'POST',
		url: scripts_vars.ajaxurl,
		data: {
			'action'		: 'workreap_wr_cancelled_view',
			'security'		: scripts_vars.ajax_nonce,
			'order_id'		: order_id,
		},
		dataType: 'json',
		success: function (response) {
			jQuery('.wr-preloader-section').remove();

			if (response.type === 'success') {
				jQuery('#wr_wr_viewrating').html(response.html);
				jQuery('#wr_excfreelancerpopup').modal('show');
				jQuery('#wr_excfreelancerpopup').removeClass('hidden');
			} else {
				StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
			}
		}
	});
});

		// Task action
	jQuery('.wr_proposal_completed').on('click', function(){
		var _this       	= jQuery(this);
		var proposal_id    	= _this.data('proposal_id');
		var title     		= _this.data('title');
		var counter 		= Math.floor((Math.random() * 999999) + 999);
		var load_task 		= wp.template('load-completedproject_form');
		var data 			= {counter: counter, proposal_id: proposal_id};
		load_task 			= load_task(data);
		jQuery(".wr-contract-list").slideUp();
		jQuery('#wr_taskcomplete_form').html(load_task);
		jQuery('#wr_ratingtitle').html(title);
		jQuery('#wr_completetask').modal('show');
		jQuery('#wr_completetask').removeClass('hidden');
		wr_rating_options();
	});

	// Task action
	jQuery('.wr_task_action').on('click', function(){
		var _this       = jQuery(this);
		var status		= jQuery('#wr_order_status').val();
		var order_id    = _this.data('order_id');
		var task_id     = _this.data('task_id');
		var title     = _this.data('title');

		if( status == 'completed' ) {
			var counter 	= Math.floor((Math.random() * 999999) + 999);
			var load_task 	= wp.template('load-completedtask_form');
			var data 		= {counter: counter, task_id: task_id, order_id: order_id};
			load_task 		= load_task(data);
			jQuery('#wr_taskcomplete_form').html(load_task);
			jQuery('#wr_ratingtitle').html(title);
			jQuery('#wr_completetask').modal('show');
			jQuery('#wr_completetask').removeClass('hidden');
			wr_rating_options();
		} else if( status == 'cancelled' ) {
			var counter 	= Math.floor((Math.random() * 999999) + 999);
			var load_task 	= wp.template('load-cancelledtask_form');
			var data 		= {counter: counter, task_id: task_id, order_id: order_id};
			load_task 		= load_task(data);
			jQuery('#wr_taskcomplete_form').html(load_task);
			jQuery('#wr_ratingtitle').html(title);
			jQuery('#wr_completetask').modal('show');
			jQuery('#wr_completetask').removeClass('hidden');
			wr_rating_options();
		}
	});

	// Task accept from final revision approval top bar
	jQuery('.wr_approval_task_action').on('click', function(){
	var _this       = jQuery(this);
	var order_id    = _this.data('order_id');
	var task_id     = _this.data('task_id');
	var title     = _this.data('title');
		var counter 	= Math.floor((Math.random() * 999999) + 999);
		var load_task 	= wp.template('load-completedtask_form');
		var data 		= {counter: counter, task_id: task_id, order_id: order_id};
		load_task 		= load_task(data);
		jQuery('#wr_taskcomplete_form').html(load_task);
		jQuery('#wr_ratingtitle').html(title);
		jQuery('#wr_completetask').modal('show');
		jQuery('#wr_completetask').removeClass('hidden');
		wr_rating_options();
	});

	//Duplicate project
	jQuery('.wr-duplicate-project').on('click', function(){
		var _this       = jQuery(this);
		var _id    		= _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_duplicate_project',
				'security'		: scripts_vars.ajax_nonce,
				'id'			: _id,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					window.location.replace(response.redirect_url);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Add project rating
	jQuery('.wr_add_project_rating').on('click', function(){
		var _this       = jQuery(this);
		var proposal_id = _this.data('proposal_id');
		var title     	= _this.data('title');
		var counter 	= Math.floor((Math.random() * 999999) + 999);
		var load_task 	= wp.template('load-project-rating');
		var data 		= {counter: counter, proposal_id: proposal_id};
		load_task 		= load_task(data);

		jQuery('#wr_taskcomplete_form').html(load_task);
		jQuery('#wr_completetask').modal('show');
		jQuery('#wr_completetask').removeClass('hidden');
		jQuery('#wr_ratingtitle').html(title);
		jQuery('#wr_without_feedback').hide();
		wr_rating_options();
	});

	//Add task rating
	jQuery('.wr_add_rating').on('click', function(){
		var _this       = jQuery(this);
		var order_id    = _this.data('order_id');
		var task_id     = _this.data('task_id');
		var title     	= _this.data('title');
		var counter 	= Math.floor((Math.random() * 999999) + 999);
		var load_task 	= wp.template('load-rating_form');
		var data 		= {counter: counter, task_id: task_id, order_id: order_id};
		load_task 		= load_task(data);

		jQuery('#wr_taskcomplete_form').html(load_task);
		jQuery('#wr_completetask').modal('show');
		jQuery('#wr_completetask').removeClass('hidden');;
		jQuery('#wr_ratingtitle').html(title);
		jQuery('#wr_without_feedback').hide();
		wr_rating_options();
	});

	// on change task
	$(document).on('change', '.wr-top-service', function (e) {
		let _this	= $(this);
		let id		= _this.val();
		jQuery('#wr-task-category-level1 .wr-select').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
		jQuery('#wr_sub_category').html('');
		jQuery('#wr_category_level3').html('');
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_get_terms',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id
			},
			dataType: "json",
			success: function (response) {
				jQuery('#wr-task-category-level1 .wr-select span.form-loader').remove();
				if (response.type === 'success') {
					jQuery('#wr_sub_category').html(response.categories);
					jQuery('#wr_category_level3').html('');

					if ( $.isFunction($.fn.select2) ) {
						jQuery('#wr-service-level2').select2({
							theme: "default wr-select2-dropdown",
							placeholder: {
								id: '', // the value of the option
								text: scripts_vars.choose_sub_category
							},
							allowClear: true,
							language: {
								noResults: function(){
									return scripts_vars.select_not_found
								},
								searching: function(){
									return scripts_vars.select_searching
								},
							}
						});
						jQuery('#wr-service-level2').on('select2:open', function (e) {
							jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
						});
					}

				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// Get sub categories
	jQuery(document).on('change', '#wr-service-level2', function (e) {
		let _this = $(this);
		let id = _this.val();
		jQuery('#wr_sub_category .wr-select').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
		jQuery('#wr_category_level3').html('');
		let type	= jQuery('#wr_category_level3').data('type');
		let placeholder	= scripts_vars.service_type;
		if(type === 'project' ){
			placeholder	= scripts_vars.project_type;
		}
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action': 'workreap_get_terms_subcategories',
				'security': scripts_vars.ajax_nonce,
				'id': id,
				'type':type
			},
			dataType: "json",
			success: function (response) {
				jQuery('#wr_sub_category .wr-select span.form-loader').remove();
				if (response.type === 'success') {
					jQuery('#wr_category_level3').html(response.categories);
					if ( $.isFunction($.fn.select2) ) {
						jQuery('.wr-service-select2').select2({
							theme: "default wr-select2-dropdown",
							multiple: true,
							placeholder: placeholder,
							language: {
								noResults: function(){
									return scripts_vars.select_not_found
								},
								searching: function(){
									return scripts_vars.select_searching
								},
							}
						});

						jQuery('.wr-service-select-single').select2({
							theme: "default wr-select2-dropdown",
							placeholder: placeholder,
							allowClear: true,
							language: {
								noResults: function(){
									return scripts_vars.select_not_found
								},
								searching: function(){
									return scripts_vars.select_searching
								},
							}
						});
					}
					jQuery('.wr-service-select-single').on('select2:open', function (e) {
						jQuery('input.select2-search__field').prop('placeholder', placeholder);
					});

					jQuery('.wr-service-select2> option').removeAttr("selected");
					jQuery('.wr-service-select2').trigger("change");

				} else {
					jQuery('#wr_category_level3').html('');
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});

				}
			}
		});
	});

	//Subtask delete
	$(document).on('click', '.wr_project_remove', function (e) {
		let project_id      = jQuery(this).data('id');
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_project_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_project}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.remove_project,
					btnClass: 'wr-btn',
					action: function () {
						$('body').append(loader_html);
						$.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_remove_project',
								'security'		: scripts_vars.ajax_nonce,
								'id'			: project_id,
							},
							dataType: "json",
							success: function (response) {
								$('.wr-preloader-section').remove();
								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
		e.preventDefault();
	});

	//Subtask delete
	$(document).on('click', '.wr-subtask-delete', function (e) {
		let subtask_id   	= $(this).attr('data-subtask_id');
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_subtask_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_subtask}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.remove_subtask,
					btnClass: 'wr-btn',
					action: function () {
						$('body').append(loader_html);
						$.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_service_subtask_delete',
								'security'		: scripts_vars.ajax_nonce,
								'subtask_id'	: subtask_id,
							},
							dataType: "json",
							success: function (response) {
								$('.wr-preloader-section').remove();
								if (response.type === 'success') {
									$('#tbslothandle #subtask-'+response.subtask_id).remove();
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
		e.preventDefault();
	});

	//Task load FAQ modal form
	$(document).on('click', '#wr-faqs-addlist', function (e) {
		$('body').append(loader_html);
		let service_question  	= $('#service-question').val();
		let service_answer   	= $('#service-answer').val();
		let uniqueid = Date.now()+Math.floor(Math.random() * 100);
		var load_faq_popup = wp.template('load-service-faq');
		var data = {
			id: uniqueid,
			question: service_question,
			answer: service_answer,
		};
		load_faq_popup = load_faq_popup(data);
		$('#tbslothandle').append(load_faq_popup);
		$('#service-question').val('');
		$('#service-answer').val('');
		$('.wr-preloader-section').remove();
		$('#addnewfaq').modal('hide');
		e.preventDefault();
	});

	// Enter key press in popup
	$(document).on('keypress', '#addnewfaq input, wr-subtask-form input', function (event) {
		var keyPressed = event.keyCode || event.which;
		if (keyPressed === 13) {
			event.preventDefault();
			return false;
		}
	});

	//Task FAQ form save
	$(document).on('submit', '#service-faqs-form', function (e) {
		let _serialized   = $(this).serialize();
		jQuery('body').append(loader_html);

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_add_service_faqs_save',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: _serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					$('#service_faq_id').val(response.faq_id);
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					if(response.redirect){
						window.setTimeout(function() {
							window.location.href = response.redirect;
						}, scripts_vars.alertbox_autoclose / 2 || 2500);
					}
				} else {
					if (response.button){
						StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}
		});
		e.preventDefault();
	});

	//Task FAQ delete
	$(document).on('click', '.workreap-faq-delete', function (e) {
		let faq_key = $(this).data('faq_key');
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_faq_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_faq}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.remove_faq,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery('#workreap-faq-'+faq_key).remove();
						jQuery('.wr-preloader-section').remove();
						jQuery('.wr-preloader-section').remove();
						e.preventDefault();
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
	});

	//Portfolio delete
	$(document).on('click', '.workreap-portfolio-delete', function (e) {
		let service_id = $(this).attr('data-id');
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_portfolio_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_task}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.remove_portfolio,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'			: 'workreap_portfolio_delete',
								'security'			: scripts_vars.ajax_nonce,
								'service_id'		: service_id,
							},
							dataType: "json",
							success: function (response) {
							jQuery('.wr-preloader-section').remove();
							if (response.type === 'success') {
									$('ul.wr-savelisting li#post-'+service_id).remove();
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
								window.setTimeout(function() {
									window.location.reload();
								}, scripts_vars.alertbox_autoclose / 2 || 2500);
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
		e.preventDefault();
	});

	//Task delete
	$(document).on('click', '.workreap-service-delete', function (e) {
		let service_id = $(this).attr('data-id');
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_task_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_task}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.remove_task,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'			: 'workreap_service_delete',
								'security'			: scripts_vars.ajax_nonce,
								'service_id'		: service_id,
							},
							dataType: "json",
							success: function (response) {
							jQuery('.wr-preloader-section').remove();
							if (response.type === 'success') {
									$('ul.wr-savelisting li#post-'+service_id).remove();
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
								window.setTimeout(function() {
									window.location.reload();
								}, scripts_vars.alertbox_autoclose / 2 || 2500);
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
		e.preventDefault();
	});

	//Task update publish status
	$(document).on('change', 'input[type=checkbox][name=service-enable-disable]', function (e) {
		let service_enable_value = 'disable';
		if($(this).is(':checked')){
			service_enable_value = 'enable';
		}
		let service_id = $(this).attr('data-id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'			: 'workreap_service_status_update',
				'security'			: scripts_vars.ajax_nonce,
				'service_id'		: service_id,
				'service_enable'	: service_enable_value,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				}
			}
		});
		e.preventDefault();
	});

	//Task update featured status
	$(document).on('change', 'input[type=checkbox][name=service-featured-disable]', function (e) {
		let service_enable_value = 'disable';
		if($(this).is(':checked')){
			service_enable_value = 'enable';
		}
		let service_id = $(this).attr('data-id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'			: 'workreap_task_featured_update',
				'security'			: scripts_vars.ajax_nonce,
				'service_id'		: service_id,
				'service_enable'	: service_enable_value,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				}
			}
		});
		e.preventDefault();
	});

	//Add task load next step
	function workreap_add_service_next_step(postid, step){
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action': 'workreap_add_service_next_step_template',
				'ajax_nonce': scripts_vars.ajax_nonce,
				'post_id': postid,
				'step': step,
			},
			dataType: "html",
			success: function (response) {
				$('html, body').animate({
					scrollTop: $(".wr-addservice-steps").offset().top
				}, 100);
				$('#wr-services-steps').html(response);
				$('.wr-addservice-step').removeClass('wr-addservice-step-fill');

				for (var n = 1; n < step; ++n){

					if(!$('.task-step-'+n).hasClass('wr-addservice-step-complete')){
						$('.task-step-' + n).addClass('wr-addservice-step-complete');
					}
				}

				$('.wr-addservice-step-' + step).addClass('wr-addservice-step-fill');
				workreapchangeQuery('?post=' + postid + '&step=' + step);
				workreap_upload_multiple_doc('workreap-attachment-btn', 'workreap-upload-attachment', 'workreap-droparea', 'file_name', 'workreap-fileprocessing', 'load-service-media-attachments', true, scripts_vars.default_image_extensions, scripts_vars.task_max_images);
				// Make drop-down select2
				if ( $.isFunction($.fn.select2) ) {
					jQuery('.wr-select select').select2({
						theme: "default wr-select2-dropdown",
						minimumResultsForSearch: Infinity,
						language: {
							noResults: function(){
								return scripts_vars.select_not_found
							},
							searching: function(){
								return scripts_vars.select_searching
							},
						}
					});
					// Make multiple drop-down select2
					jQuery('.wr-select select[multiple]').select2({
						theme: "default wr-select2-dropdown",
						multiple: true,
						placeholder: scripts_vars.select_option,
						language: {
							noResults: function(){
								return scripts_vars.select_not_found
							},
							searching: function(){
								return scripts_vars.select_searching
							},
						}
					});
					jQuery('.wr-select select').on('select2:open', function (e) {
						jQuery('.select2-results__options').mCustomScrollbar('destroy');
						setTimeout(function () {
						jQuery('.select2-results__options').mCustomScrollbar();
						}, 0);
					});
				}

				if(jQuery('#tbslothandle').length > 0){
					new Sortable(tbslothandle, {
						animation: 150
					});
				}
				window.setTimeout(function() {

					jQuery('.wr-preloader-section').remove();
				}, 400);
			}
		});
	}

	//Task add media attachments
	workreap_upload_multiple_doc('workreap-attachment-btn', 'workreap-upload-attachment', 'workreap-droparea', 'file_name', 'workreap-fileprocessing', 'load-service-media-attachments', true, scripts_vars.default_image_extensions, scripts_vars.task_max_images);
	workreap_upload_multiple_doc('workreap-documents-btn', 'workreap-upload-documents', 'workreap-documents-droparea', 'file_name', 'workreap-fileprocessing', 'load-documents-attachments', true, scripts_vars.default_file_extensions, 3);
	workreap_upload_multiple_doc('workreap-verification-btn', 'workreap-upload-verification', 'workreap-verification-droparea', 'file_name', 'workreap-fileprocessing', 'load-verification-attachments', true, scripts_vars.default_file_extensions, 3);
	//Task activity details feedback attachments
	workreap_upload_multiple_doc('workreap-attachment-btn-clicked', 'workreap-upload-attachment', 'workreap-droparea', 'file_name', 'workreap-fileprocessing', 'load-chat-media-attachments', true, scripts_vars.default_file_extensions, 3);


	workreap_upload_multiple_doc('workreap-attachment-btn-port', 'workreap-upload-attachment-port', 'workreap-droparea-port', 'file_name', 'workreap-fileprocessing-port', 'load-service-media-attachments', true, scripts_vars.default_image_extensions, scripts_vars.portf_max_images);
	workreap_upload_multiple_doc('workreap-attachment-btn-clicked-doc', 'workreap-upload-attachment-doc', 'workreap-droparea-doc', 'file_name', 'workreap-fileprocessing-doc', 'load-chat-media-attachments-doc', true, scripts_vars.default_file_extensions, 1);
	// multiple fiel upload
	function workreap_upload_multiple_doc(btnID, containerID, dropareaID, type, previewID, templateID, _type, filetype = "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,php,html,txt", max_file_count= 0) {
		if (typeof plupload === 'object') {
			var sys_upload_nonce = scripts_vars.sys_upload_nonce;
			var ProjectUploaderArguments = {
				browse_button: btnID, // this can be an id of a DOM element or the DOM element itself
				file_data_name: type,
				container: containerID,
				drop_element: dropareaID,
				multipart_params: {
					"type": type,
				},
				multi_selection: _type,
				url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
				filters: {
					mime_types: [
						{ title: 'file', extensions: filetype }
					],
					max_file_size: scripts_vars.upload_size,
					max_file_count: max_file_count,
					prevent_duplicates: false
				}
			};

			var ProjectUploader = new plupload.Uploader(ProjectUploaderArguments);
			ProjectUploader.init();
			//bind
			ProjectUploader.bind('FilesAdded', function (up, files) {

				var _Thumb = "";
				if (max_file_count > 1 ) {
					let prevous_files = jQuery('#'+previewID+' li').length;
					let file_count  = max_file_count - prevous_files;

					if (file_count > 1 && files.length > file_count) {
						up.files.splice(5, up.files.length - 5);
						let extra_params = {};
						extra_params['note_desc'] = '';
						StickyAlert(scripts_vars.error_title, scripts_vars.upload_max_images+max_file_count, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
						return false;
					}
					if (files.length >= file_count) {
						jQuery('#'+dropareaID).addClass('d-none');
					}
				}

				let counter = 0;
				plupload.each(files, function (file) {

					let prevous_files = jQuery('#'+previewID+' li').length;
					let file_count  = max_file_count - prevous_files;

					if (max_file_count < 1 ||  counter < file_count) {
						var load_thumb = wp.template(templateID);
						var _size = bytesToSize(file.size);
						var data = { id: file.id, size: _size, name: file.name, percentage: file.percent };

						load_thumb = load_thumb(data);
						_Thumb += load_thumb;
					}
					if (max_file_count > 1){
						counter++;
					}
				});

				if (_type == false) {
					jQuery('#' + previewID).html(_Thumb);
				} else {
					jQuery('#' + previewID).append(_Thumb);
				}
				jQuery('#' + previewID).removeClass('workreap-empty-uploader');
				jQuery('#' + previewID).addClass('workreap-infouploading');
				up.refresh();
				ProjectUploader.start();
			});

			//FilesRemoved
			ProjectUploader.bind('FilesRemoved', function(up, files) {

				if (max_file_count > 1 ) {

					let prevous_files = jQuery('#'+previewID+' li').length;
					if (up.files.length >= max_file_count) {
						jQuery('#'+dropareaID).removeClass('d-none');
					}
				}
			});

			//bind
			ProjectUploader.bind('UploadProgress', function (up, file) {
				var _html = ' <span class="progress-bar uploadprogressbar" style="width:' + file.percent + '%"></span>';
				jQuery('#thumb-' + file.id + ' .progress .uploadprogressbar').replaceWith(_html);
			});

			//Error
			ProjectUploader.bind('Error', function (up, err) {

				var errorMessage = err.message;

				if (err.code == '-600') {
					errorMessage = scripts_vars.file_size_error;
				}

				StickyAlert(scripts_vars.file_size_error_title, errorMessage, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
			});

			//display data
			ProjectUploader.bind('FileUploaded', function (up, file, ajax_response) {
				var response = jQuery.parseJSON(ajax_response.response);
				if (response.type === 'success') {
					var successIcon = '<a href="javascript:void(0);"><i class="wr-icon-check-circle"></i></a>';
					jQuery('#thumb-' + file.id + ' .workreap-filedesciption .workreap-filedesciption__icon').append(successIcon);
					jQuery('#thumb-' + file.id).removeClass('workreap-uploading');
					jQuery('#thumb-' + file.id).removeClass('wr-uploading');
					jQuery('#thumb-' + file.id).addClass('workreap-file-uploaded');
					jQuery('#thumb-' + file.id + ' .attachment_url').val(response.thumbnail);
					jQuery('#thumb-' + file.id).find('.workreap-filedesciption__details a').attr("href", response.thumbnail);
					if(templateID === "load-service-media-attachments"){
						jQuery('#thumb-' + file.id).find('.venobox-gallery').attr("data-href", response.thumbnail);
						let venobox = document.querySelector(".venobox-gallery");
						if (venobox !== null) {
							jQuery(".venobox-gallery").venobox({
								spinner : "cube-grid",
							});
						}
					}
				} else {
					jQuery('#thumb-' + file.id).remove();
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			});

			//Delete Gallery images
			jQuery(document).on('click', '.wr-gallery-attachment', function (e) {

				e.preventDefault();
				var _this 			= jQuery(this);
				var listParent 		= _this.parents('li').parent('ul');
				let total_docs		= listParent.find('li').length;
				let	max_file_count	= scripts_vars.task_max_images;
				if (total_docs >= max_file_count) {
					jQuery('#workreap-droparea').removeClass('d-none');
				} else {
					jQuery('#workreap-droparea').removeClass('d-none');
					jQuery('#workreap-droparea').show();
					listParent.addClass('wr-empty-uploader');
				}
				_this.parents('li').remove();
			});

			jQuery(document).on('click', '.wr-gallery-attachment-port', function (e) {

				e.preventDefault();
				var _this 			= jQuery(this);
				var listParent 		= _this.parents('li').parent('ul');
				let total_docs		= listParent.find('li').length;
				let	max_file_count	= scripts_vars.task_max_images;
				if (total_docs >= max_file_count) {
					jQuery('#workreap-droparea-port').removeClass('d-none');
				} else {
					jQuery('#workreap-droparea-port').removeClass('d-none');
					jQuery('#workreap-droparea-port').show();
					listParent.addClass('wr-empty-uploader');
				}
				_this.parents('li').remove();
			});

			//Delete Award Image
			jQuery(document).on('click', '.wr-remove-attachment', function (e) {

				e.preventDefault();
				var _this = jQuery(this);
				var listParent 	= _this.parents('li').parent('ul');
				let total_docs	= listParent.find('li').length;

				if (total_docs < max_file_count) {
					jQuery('#'+dropareaID).removeClass('d-none');
				} else if(total_docs == 0) {
					jQuery('#'+dropareaID).removeClass('d-none');
					listParent.addClass('wr-empty-uploader')
				}
				_this.parents('li').remove();
			});

			//Delete document
			jQuery(document).on('click', '.wr-remove-document', function (e) {

				e.preventDefault();
				var _this = jQuery(this);
				var listParent 	= _this.parents('li').parent('ul');
				let total_docs	= listParent.find('li').length;
				if (total_docs < max_file_count) {
					jQuery('#'+dropareaID).removeClass('d-none');
				} else if(total_docs == 0) {
					jQuery('#'+dropareaID).removeClass('d-none');
					listParent.addClass('wr-empty-uploader')
				}
				_this.parents('li').remove();
			});

		}
	}

	// Change query param
	function workreapchangeQuery(searchString, documentTitle) {
		documentTitle = typeof documentTitle !== 'undefined' ? documentTitle : document.title;
		var urlSplit = (window.location.href).split("?");
		let _url = urlSplit[0];
		_url = _url.replace(/page.*/g,'');
		var obj = { Title: documentTitle, Url:_url + searchString };
		history.pushState(obj, obj.Title, obj.Url);
	}

	/* ================================================*/

	// Image upload
	function workreap_upload_profile_image( parems ) {
		let {
				btnID, containerID, dropareaID,
				type, previewID, templateID, _type,
				defaultTemplateID, filetype, isCropped
			} = parems; // extends variables

		if ( typeof plupload === 'object' ) {
			var sys_upload_nonce = scripts_vars.sys_upload_nonce;

			var ProjectUploaderArguments = {
					browse_button: btnID, // this can be an id of a DOM element or the DOM element itself
					file_data_name: type,
					container: containerID,
					drop_element: dropareaID,
					multipart_params: {
						"type": type,
					},
					multi_selection: _type,
					url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
					filters: {
						mime_types: [
							{ title: 'file', extensions: filetype }
						],
						max_file_size: scripts_vars.upload_size,
						max_file_count: 1,
						prevent_duplicates: false
					}
				};
			var ProjectUploader = new plupload.Uploader(ProjectUploaderArguments);

			ProjectUploader.init();
			//bind
			ProjectUploader.bind('FilesAdded', function (up, files) {
				var _Thumb = "";
				plupload.each(files, function (file) {
					var load_thumb = wp.template(defaultTemplateID);
					var _size = bytesToSize(file.size);
					var data = { id: file.id, size: _size, name: file.name, percentage: file.percent };
					load_thumb = load_thumb(data);
					_Thumb += load_thumb;
				});

				if ( _type == false ) {
					jQuery('#' + previewID).html(_Thumb);
				} else {
					jQuery('#' + previewID).append(_Thumb);
				}

				jQuery('#' + previewID).removeClass('workreap-empty-uploader');
				jQuery('#' + previewID).addClass('workreap-infouploading');
				up.refresh();
				ProjectUploader.start();

			});

			//bind
			ProjectUploader.bind('UploadProgress', function (up, file) {
				jQuery('body').append(loader_html);
				var _html = ' <span class="progress-bar uploadprogressbar" style="width:' + file.percent + '%"></span>';
				jQuery('#thumb-' + file.id + ' .progress .uploadprogressbar').replaceWith(_html);
			});

			//Error
			ProjectUploader.bind('Error', function (up, err) {
				var errorMessage = err.message
				if (err.code == '-600') {
					errorMessage = scripts_vars.file_size_error
				}
				let extra_params = {};
				extra_params['note_desc'] = errorMessage;

			});

			//display data
			ProjectUploader.bind('FileUploaded', function (up, file, ajax_response) {
				jQuery('.wr-preloader-section').remove();
				var response = jQuery.parseJSON(ajax_response.response);
				if ( response.type === 'success' ) {
					var load_thumb = wp.template(templateID);
					var _size = bytesToSize(file.size);
					var data = { id: file.id, size: _size, name: file.name, percentage: file.percent, url: response.thumbnail };

					if( isCropped ) {
						cropImagePopup(data);
					} else {
						var load_thumb = load_thumb(data);
						jQuery("#thumb-" + file.id).html(load_thumb);

						if (btnID == 'wr-profile-attachment-btn') {
							jQuery('.wr-remove-profile-img').css('display', '');
						}
					}

				} else {
					jQuery('#thumb-' + file.id).remove();
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			});
		}
	} // Image upload fun end
	init_image_uploader_v2("11", "profile","jpg,jpeg,gif,png");
	init_image_uploader_v2("1122", "document","pdf");
	//Image uploader
function init_image_uploader_v2(current_uploader, current_type,extensions_option) {
    var uploadSize          = scripts_vars.upload_size;
    var awardImage          = 'Upload image';


    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'wr-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: false,
        url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: awardImage, extensions: extensions_option}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";

		var load_thumb = wp.template('load-default-image');

        plupload.each(files, function (file) {
			var _size = bytesToSize(file.size);
            var data = {id: file.id,size:_size,name:file.name,percentage:file.percent};
            load_thumb = load_thumb(data);
            imageThumb += load_thumb;
        });


        jQuery('#wr-img-' + current_uploader + ' .uploaded-placeholder').html(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#wr-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        plupload_error_display(err);
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = $.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {
            if( current_type == 'document' ){
                var load_thumb = wp.template('load-document-image');
            } else {
                var load_thumb = wp.template('load-profile-image');
            }
            var counter = current_uploader;
            var data = {count: counter, name: response.name, url:response.thumbnail, size:response.size};
            var load_thumb = load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);
            jQuery('#image-drag-'+ current_uploader).removeClass('wr-infouploading');
            jQuery('#wr-img-' + current_uploader + ' .img-thumb').find('img').attr('src', response.thumbnail);
        } else {
			jQuery('#thumb-'+file.id).remove();
			StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
        }
    });

    jQuery(document).on('click', '.wr-remove-image', function(){
        var _this = jQuery(this);
        _this.parents('ul').remove();
	});

	jQuery(document).on('click', '.wr-remove-gallery-image', function(){
        var _this = jQuery(this);
        _this.parents('li').remove();
    });
}

	let paremsDoc = {
		btnID       : 'profile-avatar',
		containerID : 'wr-asideprostatusv2',
		dropareaID  : 'workreap-droparea',
		type        : 'file_name',
		previewID   : 'wr-profile-upload-attachment-preview',
		templateID  : 'load-profile-image',
		_type       : 'true',
		filetype    : 'jpg,jpeg,gif,png',
		isCropped   : true,
		defaultTemplateID : 'load-default-image',
	}
	///workreap_upload_singleDoc(paremsDoc);
	// init profile avatar object parems
	let parems = {
		btnID       : 'profile-avatar',
		containerID : 'wr-asideprostatusv2',
		dropareaID  : 'workreap-droparea',
		type        : 'file_name',
		previewID   : 'wr-profile-upload-attachment-preview',
		templateID  : 'load-profile-image',
		_type       : 'true',
		filetype    : 'jpg,jpeg,gif,png',
		isCropped   : true,
		defaultTemplateID : 'load-default-image',
	}

	let parems_v2 = {
		btnID       : 'profile-avatar-btn',
		containerID : 'wr-asideprostatusv2',
		dropareaID  : 'workreap-droparea',
		type        : 'file_name',
		previewID   : 'wr-profile-upload-attachment-preview',
		templateID  : 'load-profile-image',
		_type       : 'true',
		filetype    : 'jpg,jpeg,gif,png',
		isCropped   : true,
		defaultTemplateID : 'load-default-image',
	}

	// init profile avatar object
	workreap_upload_profile_image( parems );
	workreap_upload_profile_image( parems_v2 );

	// cropped image popup modal
	function cropImagePopup(data){
		let load_profile_avatar = wp.template('load-profile-avatar');
		jQuery('#workreap-modal-popup #workreap-model-body').html(load_profile_avatar);
		jQuery('#workreap-modal-popup').modal('show');
		jQuery('#workreap-modal-popup').removeClass('hidden');

		setTimeout(function() {
			image_crop = jQuery('#crop_img_area').croppie({
				enableExif: true,
				viewport: {
					width: 200,
					height: 200,
					type: 'square'
				},
				boundary: {
					width: 300,
					height: 300
				},
				url: data.url,
			});
		}, 500);


	}

	//  Download PDF
	jQuery(document).on('click', '.wr-download-pdf', function (e) {
		e.preventDefault();
		var _this    	  		= jQuery(this);
		var order_id      	  	= _this.data('order_id');
		var dataString 			= 'security='+scripts_vars.ajax_nonce+'&order_id='+order_id+'&action=tasbkot_download_invoice';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type     : "POST",
			url      : scripts_vars.ajaxurl,
			data     : dataString,
			dataType : "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success'){
					var file_path   = response.file_path;
					var link 		= document.createElement('a');
					link.href 		=  atob(response.file_name);
					link.download 	= setInterval(Math.random()) + order_id+".pdf";
					link.click();
					link.remove();
					workreap_remove_pdf(file_path);
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}else{
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//  Cropped Image
	jQuery(document).on('click', '#save-profile-img', function (e) {
		jQuery('body').append(loader_html);
		image_crop.croppie('result', {type: 'base64',quality: 1, format: 'png',size: "original"}).then(function(base64) {
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					action: 'workreap_update_avatar',
					image_url : base64
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						jQuery('#profile-avatar-menue-icon img').attr('src', response.avatar_50_x_50);
						jQuery('#user_profile_avatar').attr('src', response.avatar_150_x_150);
						jQuery('#workreap-modal-popup').modal('hide');
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			}, true);
		});
	});



	// on post new activity message
	jQuery(document).on('click', '.wr-submit-project-chat', function(e){
		e.preventDefault();
		var _this    	  = jQuery(this);
		var _id      	  = _this.data('id');
		var _serialized = jQuery('.wr-project-chat-form').serialize();
		var dataString 	= 'security='+scripts_vars.ajax_nonce+'&'+_serialized+ '&id='+_id+'&action=workreap_task_activity';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type     : "POST",
			url      : scripts_vars.ajaxurl,
			data     : dataString,
			dataType : "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success'){
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					jQuery('.wr-project-chat-form').get(0).reset();
					setTimeout(function(){
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				}else{
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// on post rejection activity message
	jQuery(document).on('click', '.wr-rejected-reason', function(e){
		e.preventDefault();
		var _this		= jQuery(this);
		var _reason		= _this.data('reason');
		StickyAlert("", _reason, {classList: 'danger'});
	});
	// on post rejection activity message
	jQuery(document).on('click', '.wr-submit-revision-reject-request', function(e){
		e.preventDefault();
		var _this	= jQuery(this);
		var _id		= _this.data('id');
		var _activity_id	= _this.data('activity_id');
		var _serialized		= jQuery('.wr-activity-reject-chat-form').serialize();
		var dataString		= 'security='+scripts_vars.ajax_nonce+'&'+_serialized+ '&id='+_id+'&activity_id='+_activity_id+'&action=workreap_submit_task_rejection_chat';
		jQuery.ajax({
			type     : "POST",
			url      : scripts_vars.ajaxurl,
			data     : dataString,
			dataType : "json",
			success: function (response) {
				if (response.type === 'success'){
					jQuery('.wr-project-chat-form').get(0).reset();
					setTimeout(function(){
						window.location.replace(response.redirect_url+'#chat_box_section');
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				}else{
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// download attachments
	jQuery(document).on('click', '.wr-download-attachment', function(e){
		e.preventDefault();
		var _this = jQuery(this);
		var _comments_id = _this.data('id');

		if( _comments_id == '' || _comments_id == 'undefined' || _comments_id == null ){
			StickyAlert(scripts_vars.message_error, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
			return false;
		}

		//Send request
		var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&comments_id='+_comments_id+'&action=workreap_download_chat_attachments';
		jQuery('body').append(loader_html);
			jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.wr-preloader-section').remove();

				if (response.type === 'success') {
					window.location = response.attachment;
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Change password
	$(document).on('click', '#wr_change_password', function (e) {
		let _this			= $(this);
		let serialized   	= _this.parents('#wr_cp_form').serialize();
		let id 				= _this.attr('data-id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_change_password',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: serialized,
				'id'		: id
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// update billing information
	$(document).on('click', '.wr_update-billing', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		let serialized  = _this.parents('.wr-billing-user-form').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_billing_settings',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// update education
	$(document).on('click', '#wr_add_education', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		let key			= _this.data('key');
		let mode			= _this.data('mode');
		if (document.getElementById('edu_end_date')) {
			var edu_start_date 	= document.getElementById('edu_start_date');
			var edu_end_date 	= document.getElementById('edu_end_date');
			var startDate 		= new Date(edu_start_date.value);
			var endDate 		= new Date(edu_end_date.value);

			if (startDate.getTime() > endDate.getTime()) {
				StickyAlert(scripts_vars.edu_date_error_title, scripts_vars.edu_date_error, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				return false;
			}
		}
		let serialized  = _this.parents('#wr_update_education').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_education',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'key'		: key,
				'mode'		: mode,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// update experience
	$(document).on('click', '#wr_add_experience', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		let key			= _this.data('key');
		let mode			= _this.data('mode');
		if (document.getElementById('edu_end_date')) {
			var edu_start_date 	= document.getElementById('edu_start_date');
			var edu_end_date 	= document.getElementById('edu_end_date');
			var startDate 		= new Date(edu_start_date.value);
			var endDate 		= new Date(edu_end_date.value);

			if (startDate.getTime() > endDate.getTime()) {
				StickyAlert(scripts_vars.edu_date_error_title, scripts_vars.edu_date_error, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				return false;
			}
		}
		let serialized  = _this.parents('#wr_update_experience').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_experience',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'key'		: key,
				'mode'		: mode,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});
	// update freelancer settings
	$(document).on('click', '.wr_profile_settings', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');

		if (typeof tinymce !== 'undefined'){
			let textarea = $(document).find('[name="description"]');
			let tiny_editor = tinyMCE.get(textarea.attr('id'));
			if(tiny_editor){
				let editorContent = tiny_editor.getContent({format: 'raw'});
				textarea.val(editorContent).trigger('change');
			}
		}

		let serialized  = _this.parents('#wr_save_settings').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_profile_settings',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// update portfolio
	$(document).on('click', '.wr_update_portfolio', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		let serialized  = _this.parents('#wr_save_port-form').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_portfolio',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					if(response.redirect){
						window.setTimeout(function() {
							window.location.href = response.redirect;
						}, scripts_vars.alertbox_autoclose / 2 || 2500);
					}
					// location.reload();
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});
	// update employer settings
	$(document).on('click', '.wr_employer_settings', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		let serialized  = _this.parents('#wr_save_settings').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_employer_settings',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id,
				'data'		: serialized,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// update profile privacy
	$(document).on('click', '#wr_update_profile', function (e) {
		let _this			= $(this);
		let serialized   	= _this.parents('#wr_privacy_form').serialize();
		let id 				= _this.attr('data-id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_save_account_settings',
				'security'	: scripts_vars.ajax_nonce,
				'data'		: serialized,
				'id'		: id
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	// Education
	$(document).on('click', '.wr_show_education', function (e) {
		let _this			= $(this);
		let dataType		= _this.data('type');
		var counter 		= Math.floor((Math.random() * 99999) + 999);;
		var load_education 	= wp.template('load-education');

		if(dataType == 'edit' && typeof profile_education !== 'undefined' && profile_education !== ''){
			let data_key			= _this.data('key');
			var title				= profile_education[data_key].title;
			var start_date			= profile_education[data_key].start_date;
			var end_date			= profile_education[data_key].end_date;
			var description			= profile_education[data_key].description;
			var key					= data_key;
			var institute 			= profile_education[data_key].institute;
			var mode				= 'edit';
		} else {
			var title				= '';
			var start_date			= '';
			var end_date			= '';
			var description			= '';
			var key					= '';
			var institute 			= '';
			var mode				= '';
		}
		var data 		= { institute: institute, mode:mode, key:key, counter: counter,title : title,start_date:start_date,end_date:end_date,description:description};
		load_education 	= load_education(data);
		jQuery('#wr_add_education_frm').html('');
		jQuery('#wr_add_education_frm').append(load_education);
		init_datepicker_max(counter,'wr-start-pick','wr-end-pick');
		jQuery('#wr_educationaldetail').modal('show');
		jQuery('#wr_educationaldetail').removeClass('hidden');
	});

	// experience
	$(document).on('click', '.wr_show_experience', function (e) {

		let _this			= $(this);
		let dataType		= _this.data('type');
		var counter 		= Math.floor((Math.random() * 99999) + 999);;
		var load_experience 	= wp.template('load-experience');

		if(dataType == 'edit' && typeof profile_experience !== 'undefined' && profile_experience !== ''){
			let data_key			= _this.data('key');
			var job_title			= profile_experience[data_key].job_title;
			var start_date			= profile_experience[data_key].start_date;
			var end_date			= profile_experience[data_key].end_date;
			var description			= profile_experience[data_key].description;
			var location			= profile_experience[data_key].location;
			var key					= data_key;
			var company 			= profile_experience[data_key].company;
			var mode				= 'edit';
		} else {
			var job_title				= '';
			var start_date			= '';
			var end_date			= '';
			var description			= '';
			var key					= '';
			var company 			= '';
			var mode				= '';
			var location			= '';
		}
		var data 		= { location:location,company: company, mode:mode, key:key, counter: counter,job_title : job_title,start_date:start_date,end_date:end_date,description:description};
		load_experience 	= load_experience(data);
		jQuery('#wr_add_experience_frm').html('');
		jQuery('#wr_add_experience_frm').append(load_experience);
		init_datepicker_max(counter,'wr-start-pick','wr-end-pick');
		if(location!=''){
			jQuery('#experience_location option[value='+location+']').prop('selected', true);
		}

		jQuery('#wr_experiencedetail').modal('show');

		jQuery('#experience_location').select2({
			theme: "default wr-select2-dropdown",
			dropdownParent: jQuery('#experience_location').parent(),
			language: {
				noResults: function(){
					return scripts_vars.select_not_found
				},
				searching: function(){
					return scripts_vars.select_searching
				},
			}
		});

		jQuery('.wr-select select, .wr-select select').on('select2:open', function (e) {
			jQuery('.select2-results__options').mCustomScrollbar('destroy');

			setTimeout(function () {
				jQuery('.select2-results__options').mCustomScrollbar();
			}, 0);
		});

		jQuery('#wr_experiencedetail').removeClass('hidden');
	});
	// Remove education
	$(document).on('click', '.wr_remove_edu', function (e) {
		let _this			= $(this);
		let key 			= _this.data('key');
		let id 				= _this.data('id');
		executeConfirmAjaxRequest(
			{
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_remove_education',
					'security'	: scripts_vars.ajax_nonce,
					'key'		: key,
					'id'		: id
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						_this.parents('li').remove();
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			},
			scripts_vars.remove_education,
			scripts_vars.remove_education_message,
			loader_html,
			'danger'
		)
	});

	// Remove Experience
	$(document).on('click', '.wr_remove_exp', function (e) {
		let _this			= $(this);
		let key 			= _this.data('key');
		let id 				= _this.data('id');
		executeConfirmAjaxRequest(
			{
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_remove_experience',
					'security'	: scripts_vars.ajax_nonce,
					'key'		: key,
					'id'		: id
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						_this.parents('li').remove();
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			},
			scripts_vars.remove_experience,
			scripts_vars.remove_experience_message,
			loader_html,
			'danger'
		)
	});

	// deactive account
	$(document).on('click', '#wr_deactive_profile', function (e) {
		let _this			= $(this);
		let serialized   	= _this.parents('#wr_deactive_form').serialize();
		let id 				= _this.attr('data-id');
		executeConfirmAjaxRequest(
			{
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_deactive_account',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: serialized,
					'id'		: id
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
						window.setTimeout(function() {
							window.location.reload();
						}, scripts_vars.alertbox_autoclose / 2 || 2500);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
					}
				}
			},
			scripts_vars.deactivate_account,
			scripts_vars.deactivate_account_message,
			loader_html,
			'danger'
		)
	});

	/*==============================================*/

		/**
		 * Payout Methods
		 * Titles
	 */
	$(document).on('click', '.wr-paypalcontent', function () {
		let _this = $(this);
		let payout_methods_title = "<h5 class=\'wr-banktitle wr-bankpayouttitle\'>" + _this.attr('data-payout_methods_title') + "</h5>";
		$('#wr_bankpayouttitle_heading').html(payout_methods_title);
	});



	$(document).on('click', '.wr_remove_payout', function () {
		let _this 	= $(this);
		let key		= _this.attr('data-key');
		let cache 	= false;
		jQuery.confirm({
			icon: 'wr-icon-trash',
			content: scripts_vars.remove_paymentmethod_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.remove_paymentmethod}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.yes_btntext,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_remove_paymentmethod',
								'security'		: scripts_vars.ajax_nonce,
								'key'			: key,
							},
							dataType: "json",
							cache: cache,
							success: function (response) {
								jQuery('.wr-preloader-section').remove();
								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});

	});

	/**
	 * Payout Methods
	 * Settings
	 */
	$(document).on('click', '.wr-payrols-settings', function (e) {
		e.preventDefault();
		let _this = $(this);
		let cache = false;
		let user_id = _this.attr('data-id');
		let key 	= _this.attr('data-key');
		let serialized = jQuery('.wr-payout-user-form-'+key).serialize();
		let dataString = 'security=' + scripts_vars.ajax_nonce + '&' + serialized + '&' + 'user_id=' + user_id + '&action=workreap_payout_settings';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			cache: cache,
			success: function (response) {
				jQuery('.wr-preloader-section').remove();
				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	//Payout Request for withdraw
	$(document).on('click', '.wr-withdraw-money', function (e) {
		e.preventDefault();
		var consent_selected = $('input[name="withdraw_consent"]:checked').length > 0;

		if (!consent_selected){
			StickyAlert(scripts_vars.payment_validation, scripts_vars.payment_validation_message, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
			return false;
		}

		var _this 	= $(this);
		var _id		= _this.data('id');
		var _serialized = jQuery('.wr-withdrawform').serialize();
		var dataString = 'id='+_id+'&security=' + scripts_vars.ajax_nonce + '&' + _serialized + '&action=workreap_money_withdraw';
		$('body').append(loader_html);
		$.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});
});




})( jQuery );

var $ = jQuery.noConflict();

//Task rating response value
function responseMessage(ratingValue,order_id) {
	jQuery('.wr-my-ratingholder > span').html(ratingValue);
	jQuery('#wr_task_rating-'+order_id).val(ratingValue);
}

//Task rating options
function wr_rating_options() {
	jQuery('.wr_cancelled_task').on('click',function(e){
		let user_id			= jQuery(this).data('user_id');
		let task_id			= jQuery(this).data('task_id');
		let order_id		= jQuery(this).data('order_id');
		let details	= jQuery('#wr_details-'+order_id).val();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_task_cancellation',
				'security'		: scripts_vars.ajax_nonce,
				'task_id'		: task_id,
				'order_id'		: order_id,
				'details'		: details,
				'user_id'		: user_id
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	jQuery('.wr_taskrating_task').on('click',function(e){
		let task_id			= jQuery(this).data('task_id');
		let order_id		= jQuery(this).data('order_id');
		let rating			= jQuery('#wr_task_rating-'+order_id).val();
		let rating_title	= jQuery('#wr_rating_title-'+order_id).val();
		let rating_details	= jQuery('#wr_rating_details-'+order_id).val();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_task_order_rating',
				'security'		: scripts_vars.ajax_nonce,
				'task_id'		: task_id,
				'order_id'		: order_id,
				'rating'		: rating,
				'rating_title'	: rating_title,
				'rating_details': rating_details
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});

	jQuery('.wr_stars li').on('click', function(){
		var _this       = jQuery(this);
		var order_id    = _this.data('id');
		var onStar      = parseInt(_this.data('value'), 10);
		var stars       = _this.parent().children('li.wr-star');
		for (var i = 0; i < stars.length; i++) {
			jQuery(stars[i]).removeClass('active');
		}

		for (var i = 0; i < onStar; i++) {
			jQuery(stars[i]).addClass('active');
		}
		var ratingValue = parseInt(jQuery('#wr_stars-'+order_id+' li.active').last().data('value'), 10);

		responseMessage(ratingValue,order_id);
	});

	jQuery('.wr_rating_task').on('click',function(e){
		let user_id			= jQuery(this).data('user_id');
		let task_id			= jQuery(this).data('task_id');
		let order_id		= jQuery(this).data('order_id');
		let rating			= jQuery('#wr_task_rating-'+order_id).val();
		let rating_title	= jQuery('#wr_rating_title-'+order_id).val();
		let rating_details	= jQuery('#wr_rating_details-'+order_id).val();
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'		: 'workreap_complete_task_order',
				'security'		: scripts_vars.ajax_nonce,
				'task_id'		: task_id,
				'order_id'		: order_id,
				'type'			: 'rating',
				'rating'		: rating,
				'user_id'		: user_id,
				'rating_title'	: rating_title,
				'rating_details': rating_details
			},
			dataType: "json",
			success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
					StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
					window.setTimeout(function() {
						window.location.reload();
					}, scripts_vars.alertbox_autoclose / 2 || 2500);
				} else {
					StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
				}
			}
		});
	});
	jQuery('.wr_rating_project').on('click',function(e){
		let user_id			= jQuery(this).data('user_id');
		let proposal_id		= jQuery(this).data('proposal_id');
		let rating			= jQuery('#wr_task_rating-'+proposal_id).val();
		let rating_title	= jQuery('#wr_rating_title-'+proposal_id).val();
		let rating_details	= jQuery('#wr_rating_details-'+proposal_id).val();
		jQuery.confirm({
			icon: 'wr-icon-flag',
			content: scripts_vars.complate_project_message,
			title: false,
			onOpenBefore: function(){
				var self = this;
				self.$body.addClass('wr-confirm-modern-alert');
				self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.complate_project}</h4>`);
			},
			closeIcon: true,
			boxWidth: '600px',
			theme: 'modern',
			draggable: false,
			useBootstrap: false,
			typeAnimated: true,
			buttons: {
				yes: {
					text: scripts_vars.yes,
					btnClass: 'wr-btn',
					action: function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								'action'		: 'workreap_complete_project_order',
								'security'		: scripts_vars.ajax_nonce,
								'proposal_id'	: proposal_id,
								'type'			: 'rating',
								'rating'		: rating,
								'user_id'		: user_id,
								'rating_title'	: rating_title,
								'rating_details': rating_details
							},
							dataType: "json",
							success: function (response) {
								jQuery('.wr-preloader-section').remove();

								if (response.type === 'success') {
									StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
									window.setTimeout(function() {
										window.location.reload();
									}, scripts_vars.alertbox_autoclose / 2 || 2500);
								} else {
									StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
								}
							}
						});
					}
				},
				no: {
					text: scripts_vars.btntext_cancelled,
					btnClass: 'wr-btnvthree',
				}
			}
		});
	});

}

//Query variable
function workreap_getQueryVariable(url, variable) {
	var query = url.substring(1);
	var vars = query.split('&');
	for (var i=0; i<vars.length; i++) {
		var pair = vars[i].split('=');

		if (pair[0] == variable) {
			return pair[1];
		}
	}

	return false;
}

/**
 * on change earning page withdraw search sort by drop down change
 *
 */
function submit_withdraw_search(){
	// get the selected option of sort by drop-down
	var selectedOption = $('#wr-withdraw-sort').find(":selected").val();

	// set selected value in hidden field
	jQuery('#wr_sort_by_filter').val(selectedOption)

	// submit search form
	jQuery('#withdraw_search_form').submit();

}


//Date picker
function init_datepicker_max(_counter, _start,_end){
	jQuery('.dateinit-'+_counter+_start).datetimepicker({
		format: scripts_vars.date_format,
		datepicker: true,
		maxDate:new Date(),
		timepicker:false,
		dayOfWeekStart:scripts_vars.startweekday,
	});

	jQuery('.dateinit-'+_counter+_end).datetimepicker({
		format: scripts_vars.date_format,
		datepicker: true,
		timepicker:false,
		maxDate:new Date(),
		dayOfWeekStart:scripts_vars.startweekday,
		onShow:function( ct ){
		this.setOptions({
			minDate: jQuery('.dateinit-'+_counter+_start).val() ? _change_date_format(  jQuery('.dateinit-'+_counter+_start).val() ):false
		})
		},
	});
}

//Date format
function _change_date_format(dateStr) {
	var calendar_format	= scripts_vars.date_format;

	if( calendar_format === 'd-m-Y' || calendar_format === 'd-m-Y H:i:s' ){
		var parts = dateStr.split("-");
		var _date	= parts[2]+'-'+parts[1]+'-'+parts[0];
		return _date;
	} else if( calendar_format === 'd/m/Y' || calendar_format === 'd/m/Y H:i:s' ){
		var parts 	= dateStr.split("/");
		var _date	= parts[2]+'/'+parts[1]+'/'+parts[0];
		return _date;
	} else if(calendar_format === 'F j, Y'){
		var formattedDate = new Date(dateStr);
		var date = formattedDate.getDate();
		var month =  formattedDate.getMonth();
		month += 1;  // in JS months are 0-11
		var year 	= formattedDate.getFullYear();
		_date	= year + "-" + month + "-" + date;
		return _date;
	} else {
		return dateStr;
	}
}

//Date picker
function init_datepicker(_class){
	jQuery('.'+_class).datetimepicker({
		format: scripts_vars.date_format,
		datepicker: true,
		timepicker: false,
		dayOfWeekStart:scripts_vars.startweekday,
		maxDate: 0,
	});
}

// Countdown for taskdetail
function workreap_countdown_by_date(date_time_value,gmt_offset){
	var countdown = document.querySelector(".wr-countdownno");

	if (countdown !== null){
		jQuery(".wr-countdownno").downCount(
			{
				date: date_time_value,
				offset: gmt_offset,
			},
			function () {

			}
		);
	}
	//  COMINGSOON COUNTER
	var daysContainer = document.querySelector('.days')
	var hoursContainer = document.querySelector('.hours')
	var minutesContainer = document.querySelector('.minutes')
	var secondsContainer = document.querySelector('.seconds')

	if(daysContainer !== null){
		var last = new Date(0)
		last.setUTCHours(-1)

		var tickState = true

		function updateTime () {
			var now = new Date

			var lastDays = last.getDay().toString()
			var nowDays = now.getDay().toString()
			if (lastDays !== nowDays) {
				updateContainer(daysContainer, nowDays)
			}
			var lastHours = last.getHours().toString()
			var nowHours = now.getHours().toString()
			if (lastHours !== nowHours) {
				updateContainer(hoursContainer, nowHours)
			}

			var lastMinutes = last.getMinutes().toString()
			var nowMinutes = now.getMinutes().toString()
			if (lastMinutes !== nowMinutes) {
				updateContainer(minutesContainer, nowMinutes)
			}

			var lastSeconds = last.getSeconds().toString()
			var nowSeconds = now.getSeconds().toString()
			if (lastSeconds !== nowSeconds) {
				updateContainer(secondsContainer, nowSeconds)
			}

			last = now
		}

		function updateContainer (container, newTime) {
			var time = newTime.split('')

			if (time.length === 1) {
				time.unshift('0')
			}


			var first = container.firstElementChild
			if (first !== null){

				if (first.lastElementChild.textContent !== time[0]) {
					updateNumber(first, time[0])
				}

				var last = container.lastElementChild
				if (last.lastElementChild.textContent !== time[1]) {
					updateNumber(last, time[1])
				}
			}
		}

		function updateNumber (element, number) {
			var second = element.lastElementChild.cloneNode(true)
			second.textContent = number

			element.appendChild(second)
			element.classList.add('move')

			setTimeout(function () {
				element.classList.remove('move')
			}, 990)
			setTimeout(function () {
				element.removeChild(element.firstElementChild)
			}, 990)
		}

		setInterval(updateTime, 100);
	}
}

function workreap_remove_pdf(url){
	var dataString = 'file_path=' + url + '&action=workreap_remove_invoice';
    jQuery.ajax({
        type: "POST",
        url: scripts_vars.ajaxurl,
        data: dataString,
        dataType: "json",
        success: function (response) {
        }
    });
}

// function workreapAppendCharacter(box, text, index) {
// 	var char = text[index];
// 	if (char === "<") {
// 	  var tagEndIndex = text.indexOf(">", index + 1);
// 	  box.append(text.substring(index, tagEndIndex + 1));
// 	  index = index+3;
// 	} else {
// 	  // Use .append() to add individual characters
// 	  box.append(char);
// 	}
//   }
