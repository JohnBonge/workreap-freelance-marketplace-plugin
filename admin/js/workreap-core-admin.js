var loader_html	= '<div class="wr-preloader-section"><div class="wr-preloader-holder"><div class="wr-loader"></div></div></div>';
(function( $ ) {

	'use strict';

	$(function() {

        if ( $.isFunction($.fn.select2) ) {

            if(jQuery('.wr-select select').length>0){
                // Make drop-down select2
                jQuery('.wr-select select').select2({
                    theme: "default wr-select2-dropdown",
                    language: {
                        noResults: function(){
                            return admin_scripts_vars.select_not_found
                        },
                        searching: function(){
                            return admin_scripts_vars.select_searching
                        },
                    }
                });
                // Make drop-down multiple  select2
                jQuery('.wr-select select[multiple]').select2({
                    theme: "default wr-select2-dropdown",
                    multiple: true,
                    placeholder: admin_scripts_vars.select_option,
                    language: {
                        noResults: function(){
                            return admin_scripts_vars.select_not_found
                        },
                        searching: function(){
                            return admin_scripts_vars.select_searching
                        },
                    }
                });
            }
        }

        // Verify item purchase
		jQuery(document).on('click', '#workreap_verify_btn', function(e){
			e.preventDefault();
			let _this	= jQuery(this);
			let workreap_purchase_code = jQuery('#workreap_purchase_code').val();

			if(workreap_purchase_code == '' || workreap_purchase_code == null){
				let workreap_purchase_code_title = jQuery('#workreap_purchase_code').attr('title');
				StickyAlert('', workreap_purchase_code_title, {classList: 'important', autoclose: 3000});
				return false;
			} else {
				_this.attr('disabled', 'disabled');
                if(loader_html){jQuery('body').append(loader_html);}
			}
			jQuery.ajax({
				type: "POST",
				url: admin_scripts_vars.ajaxurl,
				data: {
					purchase_code:	workreap_purchase_code,
					security:	admin_scripts_vars.ajax_nonce,
					action:	'workreap_verifypurchase',
				},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.wr-preloader-section').remove();
					if (response.type === 'success') {
						StickyAlert(response.title, response.message, {classList: 'success', autoclose: 3000});
						setTimeout(function(){
							window.location = response.redirect_url;
						 }, 2000);
					} else {
						_this.removeAttr("disabled");
						StickyAlert(response.title, response.message, {classList: 'important', autoclose: 3000});
					}
				},
                error: function(requestObject, error, errorThrown) {
                    jQuery('body').find('.wr-preloader-section').remove();
                    _this.removeAttr('disabled');
                    StickyAlert('', error, {classList: 'important', autoclose: 3000});
                }
			});
		});

        // Verify item purchase
		jQuery(document).on('click', '.generate-and-link', function(e){
			e.preventDefault();
            jQuery('body').append(loader_html);
			let _this	= jQuery(this);

			jQuery.ajax({
				type: "POST",
				url: admin_scripts_vars.ajaxurl,
				data: {
					security:	admin_scripts_vars.ajax_nonce,
                    user_id:	_this.data('id'),
                    type:	_this.data('profile_type'),
					action:	'workreap_generate_profile',
				},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.wr-preloader-section').remove();
					if (response.type === 'success') {
						StickyAlert(response.title, response.message, {classList: 'success', autoclose: 3000});
						setTimeout(function(){
							window.location.reload();
						 }, 2000);
					} else {
						StickyAlert(response.title, response.message, {classList: 'important', autoclose: 3000});
					}
				}, error: function(requestObject, error, errorThrown) {
                    jQuery('body').find('.wr-preloader-section').remove();
                }
			});
		})

		//Remove license
		jQuery(document).on('click', '#workreap_remove_license_btn', function(e){
			e.preventDefault();
			let _this	= jQuery(this);
			let workreap_purchase_code = jQuery('#workreap_purchase_code').val();

			if(workreap_purchase_code == '' || workreap_purchase_code == null){
				let workreap_purchase_code_title = jQuery('#workreap_purchase_code').attr('title');
				StickyAlert('', workreap_purchase_code_title, {classList: 'important', autoclose: 3000});
				return false;
			} else {
				_this.attr('disabled', 'disabled');
                if(loader_html){jQuery('body').append(loader_html);}
			}

			jQuery.ajax({
				type: "POST",
				url: admin_scripts_vars.ajaxurl,
				data: {
					purchase_code:	workreap_purchase_code,
					security:	admin_scripts_vars.ajax_nonce,
					action:	'workreap_remove_license',
				},
				dataType: "json",
				success: function (response) {
                    jQuery('body').find('.wr-preloader-section').remove();
					if (response.type === 'success') {
						StickyAlert(response.title, response.message, {classList: 'success', autoclose: 3000});
						setTimeout(function(){
							window.location = response.redirect;
						 }, 2000);
					} else {
						_this.removeAttr("disabled");
						StickyAlert(response.title, response.message, {classList: 'important', autoclose: 3000});
					}
				},
                error: function(requestObject, error, errorThrown) {
                    jQuery('body').find('.wr-preloader-section').remove();
                    _this.removeAttr('disabled');
                    StickyAlert('', error, {classList: 'important', autoclose: 3000});
                }
			});
		});

		//Display ACF dynamic fields on product categories checkbox
		if($('input[name="tax_input[product_cat][]"]').length>0){
			$('.am-plans-category').hide();

			$('input[name="tax_input[product_cat][]"]:checked').each(function() {

				if(this.value){
					$('.am-plans-category.am-category-'+this.value).show();
				}
			});
		}

        jQuery(document).on('click', ".upload_button_wgt", function () {
            var _this = jQuery(this);
            var inputfield = _this.parent().find('input').attr('id');
            var custom_uploader = wp.media({
                title: 'Select File',
                button: {
                    text: 'Add File'
                },
                multiple: false
            })
                .on('select', function () {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    var itemurl = attachment.url;
                    jQuery('#' + inputfield).val(itemurl);
                }).open();

        });

        //import dummy users
        jQuery(document).on('click', '.doc-import-users', function() {
            jQuery.confirm({
                title: admin_scripts_vars.import,
                content: admin_scripts_vars.import_message,
                boxWidth: '500px',
                useBootstrap: false,
                typeAnimated: true,
                closeIcon: 'aRandomButton',
                buttons: {
                yes: {
                    text: admin_scripts_vars.yes,
                    action: function () {
                        var jc	= this;
                        jc.showLoading();
                        var dataString = 'security='+admin_scripts_vars.ajax_nonce+'&action=workreap_import_users';
                        var $this = jQuery(this);
                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            dataType:"json",
                            data: dataString,
                            success: function(response) {
                                jQuery('#import-users').find('.inportusers').remove();
                                window.location.reload();
                            }
                        });

                        return false;
                    }
                },
                no: {
                    close: {
                    text: admin_scripts_vars.close
                    }
                }
                }
            });
        });

        //Woocommerce product cat show/hide plans
		$(document).on('change', 'input[name="tax_input[product_cat][]"]', function() {
			if(this.checked) {
				$('.am-plans-category.am-category-'+this.value).show();
			} else {
				$('.am-plans-category.am-category-'+this.value).hide();
			}
		});

        // Woocommerce General tab show price fields
        jQuery('#general_product_data .pricing').addClass('show_if_tasks');
        jQuery('#general_product_data .pricing').addClass('show_if_funds');
        jQuery('#general_product_data .pricing').addClass('show_if_packages');
        jQuery('#general_product_data .pricing').addClass('show_if_employer_packages');
        jQuery('#general_product_data .pricing').addClass('show_if_subtasks');

        //product data video links
        var is_video_links	= $( 'input#_video_links:checked' ).length;
        if ( is_video_links ) {
            $( '.show_if_video_links' ).show();
        }

        //Woocommerce tabs show/hide
		$(document).on('change', 'input#_video_links', function() {
			workreap_show_and_hide_panels();
		});

        //Woocommerce tabs show/hide
		function workreap_show_and_hide_panels() {
			var product_type    = $( 'select#product-type' ).val();
			var is_video_links	= $( 'input#_video_links:checked' ).length;
			// Hide/Show all with rules.
			var hide_classes = '.hide_if_video_links';
			var show_classes = '.show_if_video_links';
			$.each( woocommerce_admin_meta_boxes.product_types, function( index, value ) {
				hide_classes = hide_classes + ', .hide_if_' + value;
				show_classes = show_classes + ', .show_if_' + value;
			});

			$( hide_classes ).show();
			$( show_classes ).hide();
			// Shows rules.
			if ( is_video_links ) {
				$( '.show_if_video_links' ).show();
			}

			$( '.show_if_' + product_type ).show();
			// Hide rules.
			if ( is_video_links ) {
				$( '.hide_if_video_links' ).hide();
			}
			$( '.hide_if_' + product_type ).hide();
		}

        //Task add faq
		$(document).on('click', 'a.faq-insert', function(e) {
			let uniqueid = Date.now()+Math.floor(Math.random() * 100);
			var load_faq_popup = wp.template('load-faq-tr');
			var data = {
				key: uniqueid
			};
			load_faq_popup = load_faq_popup(data);
			$('.faq-data table tbody').append(load_faq_popup);
			e.preventDefault();

		});

        //Resolve Dispute Ajax
		jQuery(document).on('click', '.wr_apply_colors', function(e) {
			e.preventDefault();
			jQuery('body').append(loader_html);
            jQuery("input.second").trigger("click");
			var dataString 	=  'security='+admin_scripts_vars.ajax_nonce+'&action=workreap_change_colors';

			jQuery.ajax({
				type: "POST",
				url: admin_scripts_vars.ajaxurl,
				dataType:"json",
				data: dataString,
				success: function(response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						$("#admin-dispute-resolve-form").trigger("reset");
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'important', autoclose: 2000});
					}
				}
			});
		});

        //View documents
        jQuery(document).on('click', '.do_download_identity', function() {
            var _this 		= jQuery(this);
            var user_id		= _this.data('user');

            jQuery.confirm({
                title: admin_scripts_vars.account_verification,
                content: '',
                boxWidth: '500px',
                useBootstrap: false,
                typeAnimated: true,
                closeIcon: 'aRandomButton',
                onOpenBefore: function(data, status, xhr){
                    var jc	= this;
                    jc.showLoading();
                },
                onContentReady: function () {
                    var jc		= this;
                    var html	= '';
                    console.log(jc);

                    var dataString = 'security='+admin_scripts_vars.ajax_nonce+'&user_id='+user_id+'&action=workreap_view_identity_detail';

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        dataType:"json",
                        data: dataString,
                        success: function(response) {
                            if( response.type === 'success' ){
                                html = response.html;
                                console.log(html);
                                jc.hideLoading();
                                jc.setContent(html);

                            } else{
                                jc.hideLoading();
                                jc.setContent(response.message);
                            }

                        }
                    });
                },
                buttons: {
                    close: {
                        text: admin_scripts_vars.close
                    }
                },
            });
        });

        jQuery(document).on('click', '.do_verify_identity', function() {
            var _this 		= jQuery(this);
            var _type		= _this.data('type');

            if( _type === 'inprogress' ){
                var localize_title = admin_scripts_vars.approve_identity;
                var localize_vars_message = admin_scripts_vars.approve_identity_message;

            }else{
                var localize_title = admin_scripts_vars.reject_identity;
                var localize_vars_message = admin_scripts_vars.reject_identity_message;
            }

            var _user_id	= _this.data('id');

            jQuery.confirm({
                title: localize_title,
                content: localize_vars_message,
                boxWidth: '500px',
                useBootstrap: false,
                typeAnimated: true,
                closeIcon: 'aRandomButton',
                onAction: function (btnName) {
                    var jc	= this;
                    if(btnName === 'reject'){
                        jc.showLoading();
                        var formdata =	'<form class="reject-identity-form">' +
                                            '<div class="form-group jconfirm-buttons">' +
                                                '<p>'+admin_scripts_vars.reason+'</p>' +
                                                '<textarea class="form-control reason-content" required /></textarea>' +
                                                '<button type="submit" class="btn btn-red reject-identity">'+admin_scripts_vars.reject+'</button>' +
                                            '</div>' +
                                        '</form>';
                        console.log(formdata);
                        this.setContent(formdata);
                        this.buttons.accept.hide();
                        this.buttons.reject.hide();
                        jc.hideLoading();

                        jQuery(document).on('click', '.reject-identity', function(e) {
                            e.preventDefault();
                            jc.showLoading();
                            var reason	= jQuery('.reason-content').val();
                            var dataString  = 'security='+admin_scripts_vars.ajax_nonce+'&reason='+reason+'&type=reject&user_id='+_user_id+'&action=workreap_identity_verification';

                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                dataType:"json",
                                data: dataString,
                                success: function(response) {
                                    jc.hideLoading();
                                    jc.$content.html(response.message);
                                    jc.buttons.accept.hide();
                                    jc.buttons.reject.hide();
                                    window.location.reload();
                                }
                            });

                            return false;
                        });
                    }
                },

                buttons: {
                    accept: {
                        text: admin_scripts_vars.accept,
                        action: function () {
                            var jc	= this;
                            var dataString  = 'security='+admin_scripts_vars.ajax_nonce+'&type=approve&user_id='+_user_id+'&action=workreap_identity_verification';
                            jc.showLoading();
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                dataType:"json",
                                data: dataString,
                                success: function(response) {
                                    jc.hideLoading();
                                    jc.$content.html(response.message);
                                    jc.buttons.accept.hide();
                                    jc.buttons.reject.hide();
                                    window.location.reload();
                                }
                            });

                            return false;
                        }
                    },
                    reject: {
                        text: admin_scripts_vars.reject,
                        action: function () {
                            return false;
                        }
                    },
                },
            });
        });

        //Remove notification
		jQuery(document).on('click', '.wr-remove-notification', function(e){
			let _this	= jQuery(this);
			let type 	= _this.data('type');
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: admin_scripts_vars.ajaxurl,
				data: {
					'action'		: 'workreap_update_admin_notification',
					'type'			: type,
					'security'		: admin_scripts_vars.ajax_nonce,
				},
				dataType: "json",
				success: function (response) {
				   jQuery('.wr-preloader-section').remove();

				    if (response.type === 'success') {
						_this.closest('.wr-admin-notification').remove();
                        StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 5000});
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		 });

        //veryfy profiles
        jQuery(document).on('click', '.do_verify_user_confirm', function(e) {

            var _this 		= jQuery(this);
            var _type		= _this.data('type');
            var _id			= _this.data('id');
            var _user_id	= _this.data('user_id');
            var dataString  = 'security='+admin_scripts_vars.ajax_nonce+'&type='+_type+'&id='+_id+'&user_id='+_user_id+'&action=workreap_approve_profile';
            let approve_text_title    = admin_scripts_vars.approve_account;
            let approve_text    = admin_scripts_vars.approve_account;

            if(_type == 'approve'){
                approve_text_title      = admin_scripts_vars.approve_account;
                approve_text            = admin_scripts_vars.approve_account_message;
            } else {
                approve_text_title      = admin_scripts_vars.reject_account;
                approve_text            = admin_scripts_vars.reject_account_message;
            }

            jQuery.confirm({
                title: approve_text_title,
                content: approve_text,
                boxWidth: '500px',
                useBootstrap: false,
                typeAnimated: true,
                closeIcon: 'aRandomButton',
                buttons: {
                yes: {
                    text: admin_scripts_vars.yes,
                    action: function () {
                        if(loader_html){jQuery('body').append(loader_html);}
                        jQuery.ajax({
                            type     : "POST",
                            url      : admin_scripts_vars.ajaxurl,
                            data     : dataString,
                            dataType : "json",
                            success: function (response) {
                                jQuery('body').find('.wr-preloader-section').remove();
                                if (response.type === 'success'){
                                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 2000);
                                }else{
                                    $('body').find('.sticky-queue').remove();
                                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});

                                }
                            }
                        });

                    }
                },
                no: {
                    close: {
                    text: admin_scripts_vars.close
                    }
                }
                }
            });
            e.preventDefault();
        });

        //Migrate Data
        jQuery(document).on('click', '#wr-migrate', function (e) {
            e.preventDefault();

            var _this = $(this);

            $.confirm({
                title: admin_scripts_vars.migrate,
                content: admin_scripts_vars.migrate_message,
                boxWidth: '500px',
                useBootstrap: false,
                typeAnimated: true,
                closeIcon: 'aRandomButton',
                buttons: {
                    yes: {
                        text: admin_scripts_vars.yes,
                        action: function () {
                            var self	= this;
                            self.$body.hide().css({transform: 'scale(0)'});
                            var progress_markup = `<div class="wr-migrate-progress">
                                                        <div class="wr-migrate-bar" style="width:10%">
                                                        <p class="wr-migrate-percent">10%</p>
                                                        </div>
                                                    </div>
                                                    <p class="wr-migrations-queue-text">${admin_scripts_vars.migration_progress_message}</p>`;
                            self.setTitle(admin_scripts_vars.migration_start);
                            self.setContent(admin_scripts_vars.migration_start_message);
                            self.setContentAppend(progress_markup);
                            self.$body.addClass('wr-migration-popup');
                            self.$body.find('.jconfirm-buttons').remove();
                            self.$body.find('.jconfirm-closeIcon').remove();
                            self.$body.show().css({transform: 'scale(1)'});
                            workreap_migration_requests(1);
                            return false;

                        }
                    },
                    no: {
                        close: {
                            text: admin_scripts_vars.close
                        }
                    }
                }
            });
        });

        //Extension
        jQuery(document).on('click', '#wr-extension-action-btn', function (e){
           e.preventDefault();

           var _this = $(this);

           if(_this.data('action') === 'buy' ){
               window.open(_this.attr('href'), '_blank');
               return;
           }

            _this.addClass('active');
            _this.attr('disabled', true);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: admin_scripts_vars.ajaxurl,
                data: {
                    action: "workreap_extension_action",
                    nonce: admin_scripts_vars.ajax_nonce,
                    name: _this.data('name'),
                    type: _this.data('action'),
                    slug: _this.data('slug'),
                },
                success: function (response) {
                    if(response.success){
                        location.reload();
                    }else if(!response.success && response.data.message){
                        alert(response.data.message);
                        location.reload();
                    }
                }
            });

        });

	});

    //Alert the notification
    function StickyAlert($title='',$message='',data){
        var $icon	= 'wr-icon-check';
        var $class	= 'dark';

        if(data.classList === 'success'){
            $icon	= 'wr-icon-check';
            $class	= 'green';
        }else if(data.classList === 'danger'){
            $icon	= 'wr-icon-x';
            $class	= 'red';
        }

        $.confirm({
            icon		: $icon,
            closeIcon	: true,
            theme		: 'modern',
            animation	: 'scale',
            type		: $class, //red, green, dark, orange
            title		: false,
            content		: $message,
            autoClose	: 'close|'+ data.autoclose,
            onOpenBefore: function(){
                var self = this;
                self.$body.addClass('wr-confirm-modern-alert');
                self.setContentPrepend(`<h4 class="jconfirm-custom-title">${$title}</h4>`);
            },
            buttons: {
                close: {btnClass: 'wr-sticky-alert'}
            }
        });
    }

    //Confirm before submit
    function executeConfirmAjaxRequest(ajax, title='Confirm', message='',loader) {
        $.confirm({
            title: title,
            content: message,
            class: 'blue',
            theme		: 'modern',
            animation	: 'scale',
            closeIcon: true, // hides the close icon.
            'buttons': {
                'Yes': {
                    'btnClass': 'btn-dark wr-yesbtn',
                    'text': admin_scripts_vars.yes,
                    'action': function () {
                        if(loader_html){jQuery('body').append(loader_html);}
                        jQuery.ajax(ajax);
                    }
                },
                'No': {
                    'btnClass': 'btn-default wr-nobtn',
                    'text': admin_scripts_vars.no,
                    'action': function () {
                        return true;
                    }
                },
            }
        });

    }

    //Migrations
    function workreap_migration_requests(step){
        var submit_btn = $('#wr-migrate');
        var queue_txt = $('.wr-migrations-queue-text');
        var queue_message = queue_txt.text();
        var status = true;

        if(!step){
            return;
        }

        var total_steps = 11;

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: admin_scripts_vars.ajaxurl,
            data: {
                step: step,
                action: "workreap_migrate_data",
                nonce: admin_scripts_vars.ajax_nonce,
            },
            success: function (response) {
                if(response.next_step && response.next_message){
                    step = response.next_step;
                    queue_txt.text(response.next_message);
                }else{
                    status = false;
                }
            },
            error: function(xhr, er, error) {
                status = false;
                console.log(error);
            },
            complete: function() {
                let current_step = step - 1;
                let percentage = (current_step * 100)/total_steps;
                $(document).find('.wr-migrate-bar').width(`${percentage.toFixed(0)}%`);
                $(document).find('.wr-migrate-percent').text(`${percentage.toFixed(0)}%`);

                if(status && step > 1 && step <= total_steps){
                    workreap_migration_requests(step);
                }else{
                    window.location.reload();
                    return false;
                }
            }
        });
    }

})( jQuery );



