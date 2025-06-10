
var $isClickedNotify = false;
var loader_html	= '<div class="wr-preloader-section"><div class="wr-preloader-holder"><div class="wr-loader"></div></div></div>';
jQuery(document).ready(function($){
	'use strict';
    jQuery('.wr-load-more').on('click', function (e) {
        let _this   = jQuery(this);
        _this.addClass('d-none');
        _this.parents('ul').find('.wt-edu-hide').removeClass('d-none');
    });
    jQuery('.wr-read-more-link').click(function(){

        jQuery(this).closest('.wr-description-container').find('.wr-full-description').toggle();
        jQuery(this).remove();
        return false;
    });
	//Sidebar Toggle
    $('.wr-dashboard-sidebar-toggle-btn').on('click',function (e) {
       e.preventDefault();
       if($(window).width() < 1200){
            $(this).parents('.wr-dashboard-sidebar-wrapper').toggleClass('wr-expanded');
        }else{
            $(this).parents('.wr-dashboard-sidebar-wrapper').toggleClass('wr-folded');
        }
    });

    $('.wr-dashboard-sidebar-wrapper .menu-item-has-children > a').on('click',function (e) {
        e.preventDefault();
        if(!$('.wr-dashboard-sidebar-wrapper').hasClass('wr-folded')){
            $(this).parents('.menu-item-has-children').toggleClass('wr-active');
            $(this).parents('.menu-item-has-children').find('.sub-menu').slideToggle();
        }
    });

    // $('.wr-dashboard-sidebar-setting-menu').mCustomScrollbar();

    $(window).on('resize', function() {
        if($(window).width() < 992){
            $('.wr-dashboard-sidebar-wrapper.wr-folded').removeClass('wr-folded');
        }
    }).resize();

    jQuery(window).on("click",function(){
        $("#rangecollapse").collapse({toggle: false});
    });

    // blog sort by
    jQuery(document).on('change', '#blog-sort', function (e) {
        let sort_val = jQuery(this).val();
        if (sort_val) {
            var url = window.location.href;
            url = removeParam("sort_by", url);
            if (url.indexOf('?') > -1) {
                url += '&sort_by=' + sort_val
            } else {
                url += '?sort_by=' + sort_val
            }
            window.location.href = url;
        }
        e.preventDefault();
    });

    // Load More
    let classes = [
        '.wr-languagetermsfilter',
        '.wr-skillstermsfilter',
        '.wr-expertisetermsfilter',
    ];
    for ( let i = 0; i < classes.length; ++i) {
        if (classes[i].length <= 5) {
            jQuery(".wr-show_more").hide();
        }
        else if (classes[i].length >= 5) {

            jQuery(".wr-languagetermsfilter li:nth-child(n+6)").hide();
            jQuery(".wr-skillstermsfilter li:nth-child(n+6)").hide();
            jQuery(".wr-expertisetermsfilter li:nth-child(n+6)").hide();
        }
    }


    //load more sub categories
    jQuery(document).on('click','.wr-show_more',function(e){
        let show_more   = jQuery(this).data('show_more');
        let show_less   = jQuery(this).data('show_less');
        jQuery(this).text($(this).text() == show_less ? show_more : show_less);
        jQuery(this).closest(".wr-aside-holder").find(".wr-languagetermsfilter li:nth-child(n+6)").slideToggle();
        jQuery(this).closest(".wr-aside-holder").find(".wr-skillstermsfilter li:nth-child(n+6)").slideToggle();
        jQuery(this).closest(".wr-aside-holder").find(".wr-expertisetermsfilter li:nth-child(n+6)").slideToggle();
    });

    jQuery(document).on('mouseenter','.wr-tooltip-data',function(e){
        let id  = jQuery(this).attr('id');
        tooltipInit('#'+id);
    });

    jQuery(document).on('mouseenter','[data-class="wr-tooltip-data"]',function(e){
        let id  = jQuery(this).attr('id');
        tooltipInit('#'+id);
    });


    // NOTIFICATION
	workreap_notification_options();
    workreap_tippy_options();
    // Author checkout page

    jQuery('.wr_btn_author').on('click', function (e) {
        StickyAlert(scripts_vars.error_title, scripts_vars.post_author_option, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
    });
    if (jQuery(window).width() < 1200 && $isClickedNotify === false) {
        $isClickedNotify  = false;
        jQuery('.wr-notifyheader').addClass('wr-page-link');
    } else {
        jQuery('.wr-notifyheader').removeClass('wr-page-link')
    }
    // Responsive menu
    function collapseMenu(){
        jQuery('.wr-navbarnav:not(.wr-menu-navbarnav) li.menu-item-has-children > a').prepend('<span class="wr-dropdowarrow"><i class="wr-icon-chevron-right"></i></span>');
        jQuery('.wr-navbarnav:not(.wr-menu-navbarnav) li.menu-item-has-children span.wr-dropdowarrow').on('click', function(e) {
            e.preventDefault();
            jQuery(this).parent().toggleClass('wr-menuopen');
            jQuery(this).parent().next().slideToggle(300);
        });
    }
    collapseMenu();

    // Submit proposal
    jQuery('.wr_submit_task').on('click',function () {
        let _this           = jQuery(this);
        let status          = _this.data('type');
        let project_id      = _this.data('project_id');
        let proposal_id     = _this.data('proposal_id');
        var _serialize      = jQuery('#tasbkot-submit-proposal').serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action'    : 'workreap_submit_proposal',
                'security'  : scripts_vars.ajax_nonce,
                'project_id': project_id,
                'proposal_id': proposal_id,
                'status'    : status,
                'data'      : _serialize,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function() {
                        window.location.replace(response.redirect);
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });

    });

    //Newsletter form submit
	jQuery(document).on('click', '.subscribe_me', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader_html);

        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+_this.parents('form').serialize() + '&action=workreap_subscribe_mailchimp',
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

    //Update mailchimp list
	jQuery(document).on('click', '.wr-latest-mailchimp-list', function(event) {
		event.preventDefault();
		var dataString = 'security='+scripts_vars.ajax_nonce+'&action=workreap_mailchimp_array';

		var _this = jQuery(this);
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: scripts_vars.alertbox_autoclose || 5000});
                window.setTimeout(function() {
                    window.location.reload();
                }, scripts_vars.alertbox_autoclose / 2 || 2500);
			}
		});
	});

    //page redirect
    jQuery('.wr-page-link').on('click',function () {
        let page_url    = jQuery(this).data('url');
        window.location.href = page_url;
    });

    //page redirect
    jQuery('.wr-redirect-url').on('click',function () {
        StickyAlert(scripts_vars.apply_now, scripts_vars.login_required_apply, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
    });

    //Delay in time while typing
    function TBdelayTime(callback, timer) {
        var delayTime = 0;

        return function() {
          var context   = this;
          var args      = arguments;
          clearTimeout(delayTime);

          delayTime = setTimeout(function () {
            callback.apply(context, args);
          }, timer || 0);
        };
    }

    // price calculation
    jQuery('input.wr_proposal_price').on('keyup change',TBdelayTime(function(e){
        let _this       = jQuery(this);
        let post_id     = _this.data('post_id');
        let price       = _this.val();
        jQuery('.wr-input-price').addClass('wr-input-loader');
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action'		: 'workreap_calculate_price',
                'security'		: scripts_vars.ajax_nonce,
                'post_id'	    : post_id,
                'price'		    : price,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-input-price').removeClass('wr-input-loader');
                if (response.type === 'success') {
                    jQuery('#wr_total_rate').html(response.price);
                    jQuery('#wr_service_fee').html(response.admin_shares);
                    jQuery('#wr_user_share').html(response.user_shares);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    },500));

    //toggle two classes on mobile menu
    jQuery('.wr_user_profile').on('click',function () {
        jQuery('.wr-user-menu').toggleClass('wr-opendbmenu');
    });
    //toggle div on mobile menu
    jQuery('.wr-filtericon').on('click',function () {
        jQuery('.wr-searchlist').slideToggle(300);
        jQuery('.wr-mt0').toggleClass('wr-mt');

    });
    //toggle two classes on mobile menu
    jQuery('.wr-login-user').on('click',function () {
        StickyAlert(scripts_vars.error_title, scripts_vars.login_required, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
    });

    jQuery('.wr-login-freelancer').on('click',function () {
        StickyAlert(scripts_vars.error_title, scripts_vars.login_required_apply, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
    });
    jQuery('.wr-authorization-required').on('click',function () {
        StickyAlert(scripts_vars.error_title, scripts_vars.post_author_option, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
    });

    //Download files
    jQuery('.wr_download_files').on('click',function(e){
        let product_id			= jQuery(this).data('id');
        let order_id			= jQuery(this).data('order_id');
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action'		: 'workreap_download_zip_file',
                'security'		: scripts_vars.ajax_nonce,
                'product_id'	: product_id,
                'order_id'		: order_id,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();

                if (response.type === 'success') {
                    window.location = response.attachment;
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

     // Send message
     jQuery('.wr_sent_msg_task').on('click', function (e) {
        var reciver_id    = $(this).data('reciver_id');
        var _message    = $('#wr_message').val();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_send_user_msg',
                'security': scripts_vars.ajax_nonce,
                'reciver_id': reciver_id,
                'message' : _message
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function () {
                        window.location.href = response.redirect;
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Send message on task detail
    jQuery('.wr_sentmsg_task').on('click', function (e) {
        var _post_id    = $(this).data('post_id');
        var _message    = $('#wr_message').val();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_send_message',
                'security': scripts_vars.ajax_nonce,
                'post_id': _post_id,
                'message' : _message
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function () {
                        window.location.href = response.redirect;
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    jQuery('.wr_proposal_chat').on('click', function (e) {
        var _post_id    = $(this).data('reciver_id');
        jQuery('body').append(loader_html);

        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'wp_guppy_start_chat',
                'security': scripts_vars.ajax_nonce,
                'post_id': _post_id
            },
            dataType: "json",
            success: function (response) {
                if (response.type === 'success') {
                    window.location.href = response.redirect;
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // update switch user
	$(document).on('click', '.wr_switch_user', function (e) {
		$('body').append(loader_html);
		let _this		= $(this);
		let id			= _this.data('id');
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				'action'	: 'workreap_switch_user_settings',
				'security'	: scripts_vars.ajax_nonce,
				'id'		: id
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

    // Checkout page
    jQuery('.wr_btn_checkout').on('click', function (e) {
        var _type = $(this).data('type');
        var _url = $(this).data('url');
        if (scripts_vars.user_type == '' || scripts_vars.user_type == null || scripts_vars.user_type == undefined) {
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    'action': 'workreap_redirect_page',
                    'security': scripts_vars.ajax_nonce,
                    'type': _type,
                    'page_url': _url,
                },
                dataType: "json",
                success: function (response) {
                    jQuery('.wr-preloader-section').remove();
                    if (response.type === 'success') {
                        StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                        window.setTimeout(function () {
                            if(scripts_vars.view_type === 'popup' ){
                                jQuery('#wr-signup-model').modal('show');
                                jQuery('#wr-signup-model').removeClass('hidden');
                            } else {
                                window.location.href = response.redirect;
                            }
                        }, scripts_vars.alertbox_autoclose / 2 || 2500);
                    } else {
                        StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    }
                }
            });
        } else if (scripts_vars.user_type != 'employers') {
            StickyAlert(scripts_vars.error_title, scripts_vars.only_employer_option, {
                classList: 'danger',
                autoclose: scripts_vars.alertbox_autoclose || 5000
            });
        } else {
            window.location.href = _url;
        }
    });

    // Post a tag without login
    jQuery('#wr_post_task').on('click', function (e) {
        var _type = $(this).data('type');
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_redirect_page',
                'security': scripts_vars.ajax_nonce,
                'type': _type,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    if(scripts_vars.view_type === 'popup' ){
                        jQuery('#wr-signup-model').modal('show');
                        jQuery('#wr-signup-model').removeClass('hidden');
                    } else {
                        window.location.href = response.redirect;
                    }
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Get categories
    jQuery(document).on('change', '.wr-top-service-task-option', function (e) {
        let _this   = $(this);
        let id      = _this.val();
        jQuery('#task_search_wr_parent_category').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_task_search_get_terms',
                'security': scripts_vars.ajax_nonce,
                'id': id,
                'option' : 'title'
            },
            dataType: "json",
            success: function (response) {
                jQuery('#task_search_wr_parent_category span.form-loader').remove();
                if (response.type === 'success') {
                    jQuery('#task_search_wr_sub_category').html(response.categories);
                    jQuery('#task_search_wr_category_level3').html('');
                    if ( $.isFunction($.fn.select2) ) {
                        jQuery('#wr-top-service-task-option-level-2').select2({
                            theme: "default wr-select2-dropdown",
                            placeholder: {
                                id: '', // the value of the option
                                text: scripts_vars.select_sub_category
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

                        jQuery('#wr-top-service-task-option-level-2').on('select2:open', function (e) {
                            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
                        });
                    }
                } else {
                    jQuery('#task_search_wr_sub_category').html();
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Get categories
    jQuery(document).on('change', '.wr-top-service-task-search', function (e) {
        let _this   = $(this);
        let id      = _this.val();
        jQuery('#task_search_wr_parent_category').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_task_search_get_terms',
                'security': scripts_vars.ajax_nonce,
                'id': id
            },
            dataType: "json",
            success: function (response) {
                jQuery('#task_search_wr_parent_category span.form-loader').remove();
                if (response.type === 'success') {
                    jQuery('#task_search_wr_sub_category').html(response.categories);
                    jQuery('#task_search_wr_category_level3').html('');
                    if ( $.isFunction($.fn.select2) ) {
                        jQuery('#wr-top-service-task-search-level-2').select2({
                            theme: "default wr-select2-dropdown",
                            placeholder: {
                                id: '', // the value of the option
                                text: scripts_vars.select_sub_category
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

                        jQuery('#wr-top-service-task-search-level-2').on('select2:open', function (e) {
                            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
                        });
                    }
                } else {
                    jQuery('#task_search_wr_sub_category').html();
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    jQuery(document).on('click', '.wr_view_rating', function (e) {
        let rating_id		= jQuery(this).data('rating_id');

        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: {
                'action'		: 'workreap_wr_rating_view',
                'security'		: scripts_vars.ajax_nonce,
                'rating_id'		: rating_id,
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

    jQuery(document).on('click', '.wr-login-poup', function (e) {
        workreapHideRegModel();
        jQuery('#wr-login-model').modal('show');
        jQuery('#wr-login-model').removeClass('hidden');
    });

    jQuery(document).on('click', '.wr-signup-poup-btn', function (e) {
        workreapHideRegModel();
        jQuery('#wr-signup-model').modal('show');
        jQuery('#wr-signup-model').removeClass('hidden');
    });

    jQuery(document).on('click', '.wr-pass-poup-btn', function (e) {
        workreapHideRegModel();
        jQuery('#wr-pass-model').modal('show');
        jQuery('#wr-pass-model').removeClass('hidden');
    });
    if(scripts_vars.enable_state){
        jQuery(document).on('change', '#tklocation #task_location', function (e) {
            let country_val= jQuery('#tklocation #task_location option:selected').val();
            if(country_val){
                jQuery.ajax({
                    type: "POST",
                    url: scripts_vars.ajaxurl,
                    data: {
                        'action': 'workreap_get_states',
                        'security': scripts_vars.ajax_nonce,
                        'country_val': country_val
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.states > 0){
                            jQuery('.wr-state-parent').removeClass('d-none');
                            jQuery('.wr-country-state').find('option').not(':first').remove()
                            jQuery('.wr-country-state').append(response.states_html);
                        } else {
                            jQuery('.wr-state-parent').addClass('d-none');
                        }
                    }
                });
            }
        });
        jQuery(document).on('change', '#service-introduction-form #wr_country', function (e) {
            let country_val= jQuery('#service-introduction-form #wr_country option:selected').val();
            if(country_val){
                jQuery.ajax({
                    type: "POST",
                    url: scripts_vars.ajaxurl,
                    data: {
                        'action': 'workreap_get_states',
                        'security': scripts_vars.ajax_nonce,
                        'country_val': country_val
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.states > 0){
                            jQuery('.wr-state-parent').removeClass('d-none');
                            jQuery('.wr-country-state').find('option').not(':first').remove()
                            jQuery('.wr-country-state').append(response.states_html);
                        } else {
                            jQuery('.wr-state-parent').addClass('d-none');
                        }
                    }
                });
            }
        });
        jQuery(document).on('change', 'select[name="country"]', function (e) {
            let country_val= jQuery('select[name="country"] option:selected').val();
            if(country_val){
                jQuery.ajax({
                    type: "POST",
                    url: scripts_vars.ajaxurl,
                    data: {
                        'action': 'workreap_get_states',
                        'security': scripts_vars.ajax_nonce,
                        'country_val': country_val
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.states > 0){
                            jQuery('.wr-state-parent').removeClass('d-none');
                            jQuery('.wr-country-state').find('option').not(':first').remove()
                            jQuery('.wr-country-state').append(response.states_html);
                        } else {
                            jQuery('.wr-state-parent').addClass('d-none');
                        }
                    }
                });
            }
        });
        /* add state in dashboard billing information */
        jQuery(document).on('change', 'select[name="billing[billing_country]"]', function (e) {
            let country_val= jQuery('select[name="billing[billing_country]"] option:selected').val();
            if(country_val){
                jQuery('body').append(loader_html);
                jQuery.ajax({
                    type: "POST",
                    url: scripts_vars.ajaxurl,
                    data: {
                        'action': 'workreap_get_states',
                        'security': scripts_vars.ajax_nonce,
                        'country_val': country_val
                    },
                    dataType: "json",
                    success: function (response) {
                        jQuery('.wr-preloader-section').remove();
                        if(response.states > 0){
                            jQuery('.wr-state-parent').removeClass('d-none');
                            jQuery('.wr-country-state').find('option').not(':first').remove()
                            jQuery('.wr-country-state').append(response.states_html);
                        } else {
                            jQuery('.wr-state-parent').addClass('d-none');
                        }
                    }
                });
            }
        });
    }
    // Get services
    jQuery(document).on('change', '#wr-top-service-task-option-level-2', function (e) {
        let _this = $(this);
        let id = _this.val();
        jQuery('#sub_category_container').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_task_search_get_terms_subcategories',
                'security': scripts_vars.ajax_nonce,
                'id': id,
                'option' : 'title'
            },
            dataType: "json",
            success: function (response) {
                if (response.type === 'success') {
                    jQuery('#sub_category_container span.form-loader').remove();
                    jQuery('#task_search_wr_category_level3').html(response.terms_html);
                    var wr_categoriesfilter = document.querySelector(".wr-categoriesfilter");
                    if (wr_categoriesfilter !== null) {
                        wr_categoriesfilter = {
                            collapsedHeight: 180,
                            moreLink: '<a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_more+'</a>',
                            lessLink: '<a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_less+'</a>',
                        };
                        $('.wr-categoriesfilter').readmore(wr_categoriesfilter);
                    }
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Get services
    jQuery(document).on('change', '#wr-top-service-task-search-level-2', function (e) {
        let _this = $(this);
        let id = _this.val();
        jQuery('#sub_category_container').append('<span class="form-loader"><i class="fas fa-spinner fa-spin"></i></span>');
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_task_search_get_terms_subcategories',
                'security': scripts_vars.ajax_nonce,
                'id': id
            },
            dataType: "json",
            success: function (response) {
                if (response.type === 'success') {
                    jQuery('#sub_category_container span.form-loader').remove();
                    jQuery('#task_search_wr_category_level3').html(response.terms_html);
                    var wr_categoriesfilter = document.querySelector(".wr-categoriesfilter");
                    if (wr_categoriesfilter !== null) {
                        wr_categoriesfilter = {
                            collapsedHeight: 180,
                            moreLink: '<a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_more+'</a>',
                            lessLink: '<a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_less+'</a>',
                        };
                        $('.wr-categoriesfilter').readmore(wr_categoriesfilter);
                    }
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Registration
    jQuery(document).on('click', '.wr-signup-now', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        var _serialized = jQuery('#userregistration-from').serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_registeration',
                'security': scripts_vars.ajax_nonce,
                'data': '&' + _serialized,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function() {
                        window.location.replace(response.redirect);
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Login Ajax
    jQuery(document).on('click', '.wr-signin-now', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var _serialize = _this.parents('form.wr-formlogin').serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_signin',
                'security': scripts_vars.ajax_nonce,
                'data': _serialize,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function() {
                        window.location.replace(response.redirect);
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Forgot password ajax
    jQuery(document).on('click', '.btn-forget-pass', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        var _serialize = _this.parents('form.wr-forgot-password-form').serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_forgot',
                'security': scripts_vars.ajax_nonce,
                'data': _serialize,
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

    // Reset password ajax
    jQuery(document).on('click', '.btn-reset-pass', function (event) {
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader_html);
        var _serialize = _this.parents('form.wr-forgot-password-form').serialize();

        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_reset',
                'security': scripts_vars.ajax_nonce,
                'data': _serialize,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    window.setTimeout(function() {
                        window.location.replace(response.redirect_url);
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Show password
    jQuery(document).on('click', '.password-hide-show', function (event) {
        event.preventDefault();
        var pass_type = document.getElementById("user_password");
        if (pass_type.type === "password") {
            pass_type.type = "text";
        } else {
            pass_type.type = "password";
        }
    });

    // Re send email verification link
    jQuery(document).on('click', '.re-send-email', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        var dataString = 'security=' + scripts_vars.ajax_nonce + '&action=workreap_resend_verification';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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

    // Contact and question page
    jQuery(document).on('submit', '#questions_form', function (e) {
        e.preventDefault();
        let _serialized = $(this).serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_contact_send_question',
                'security': scripts_vars.ajax_nonce,
                'data': _serialized,
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

    // Update billing information
    jQuery(document).on('click', '#wr_submit_fund', function (e) {
        let _this = $(this);
        let _url = document.location.href;
        let wallet_amount = jQuery('#wr_wallet_amount').val();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_wallet_checkout',
                'security': scripts_vars.ajax_nonce,
                'wallet_amount': wallet_amount,
                'url': _url
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    window.location.replace(response.checkout_url);
                } else {
                    if (response.button){
                        StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
                    } else {
                        StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    }
                }
            }
        });
    });

    // compare packages
    jQuery(document).on('click', '.wr-recommend', function (event) {
        event.preventDefault();
        var target = $('#workreap-price-plans');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top
            }, 200);
            return false;
        }
    });

    // Task buy package
    jQuery('.wr-buy-package').on('click', function (e) {
        let package_id = jQuery(this).data('package_id');
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_package_checkout',
                'security': scripts_vars.ajax_nonce,
                'package_id': package_id,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    window.setTimeout(function() {
                        window.location.replace(response.checkout_url);
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    });

    // Task add cart button
    jQuery('#wr_btn_cart').on('click', function (e) {
        /* getting checked subtasks */
        var subtask_checked_values = [];
        jQuery("input.wr_subtask_check:checked").each(function () {
            subtask_checked_values.push($(this).val());
        });
        let wallet = jQuery('#wr_wallet_option:checked').val() ? 1 : 0;
        let _serialized = jQuery('#wr_cart_form').serialize();
        let id = jQuery(this).data('id');
        let dataString = _serialized + '&id=' + id + '&subtasks=' + subtask_checked_values;
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_service_checkout',
                'security': scripts_vars.ajax_nonce,
                'data': dataString,
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    window.location.replace(response.checkout_url);
                } else {
                    if (response.button){
                        StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000,button:response.button});
                    } else {
                        StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    }
                }
            }
        });
    });

    // Task order status change
    jQuery('.wr_project_task').on("change", function () {
        let task_key = jQuery('.wr_project_task').find('option:selected').val();
        jQuery('.wr-product-package').addClass('d-none');
        jQuery('#wr-pkg-' + task_key).removeClass('d-none');
        workreap_totlaprice();
    });

    // Subtask update price and toggle
    jQuery('.wr_subtask_check').on("change", function () {
        let _this = jQuery(this);
        let id = _this.data('id');

        if (_this.prop('checked') == true) {
            jQuery('#additionalservice-list-' + id).prop("checked", true);
            _this.closest('li').addClass('wr-services-checked');
        } else {
            jQuery('#additionalservice-list-' + id).prop("checked", false);
            _this.closest('li').removeClass('wr-services-checked');
        }

        workreap_totlaprice();
        jQuery("#wr-fixsidebar").css("display", "block");
        jQuery(".wr-fixsidebar").toggleClass("wr-fixsidebarshow");
    });

    // Get child element text parrent text
    jQuery(document).on('click', '.wr-pakagelist li', function (e) {
        e.preventDefault();
        let _this = jQuery(this);
        let package_key = _this.data('package_key');
        let pkg_img = _this.data('img');
        jQuery("#wr_project_task_key").attr("data-task_key", package_key);
        jQuery("#wr_project_task_key").val(package_key);

        var title = jQuery(this).find('span').text();
        var price = jQuery(this).find('em').text();

        jQuery('.wr-pakagedetail .wr-pakageinfo h6').html(title);
        jQuery('.wr-pakagedetail .wr-pakageinfo h4').html(price);

        if (jQuery(this).hasClass('active')) {
            jQuery(this).removeClass('active');
        } else {
            jQuery('.wr-pakagelist li').removeClass('active');
            jQuery(this).addClass('active');
        }
        workreap_totlaprice();
        jQuery('#wr_pkg_image').attr('src', pkg_img);
    });

    // Task hired click
    jQuery('.wr_hired_btn').on('click', function (e) {
        let _this = jQuery(this);
        let id = _this.data('id');
        jQuery('#wr-op-' + id).attr("selected", "selected");
        jQuery("#wr-fixsidebar").css("display", "block");
        jQuery(".wr-fixsidebar").toggleClass("wr-fixsidebarshow");
        workreap_totlaprice();
    });

    // Subtask update price and toggle
    jQuery('.wr_subtasks').on("change", function () {
        let _this = jQuery(this);
        let id = _this.data('id');
        if (_this.prop('checked') == true) {
            jQuery('#additionalservice-' + id).prop("checked", true);
        } else {
            jQuery('#additionalservice-' + id).prop("checked", false);
        }
        workreap_totlaprice();
        jQuery("#wr-fixsidebar").css("display", "block");
        jQuery(".wr-fixsidebar").toggleClass("wr-fixsidebarshow");
    });

    // Total Price
    function workreap_totlaprice() {

        let task_id = jQuery('#wr_task_cart').data('task_id');
        let task_key = jQuery('#wr_project_task_key').val();
        let sub_tasks = [];
        let sub_tasks_html = '';
        var task_selected_html = '';
        var processing_fee_html = '';
        var boxes = jQuery('.wr_subtask_check:checked');
        jQuery('body').append(loader_html);

        boxes.each(function () {
            sub_tasks.push(jQuery(this).val());
            sub_tasks_html += '<li><span>' + jQuery(this).data('title') + '</span><em>(' + jQuery(this).data('price') + ')</em></li>';
        });

        var url = scripts_vars.tpl_dashboard;
        var params = {'key': task_key, 'ref': 'cart', 'id': task_id, 'sub_tasks': sub_tasks};
        var new_url = url + '?' + jQuery.param(params);
        history.pushState({}, null, new_url);

        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_get_tasks_total',
                'security': scripts_vars.ajax_nonce,
                'task_id': task_id,
                'task_key': task_key,
                'sub_tasks': sub_tasks
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery('#wr-pakagedetail').collapse('hide');
                    jQuery('#wr_package_price').html(response.task_price);
                    task_selected_html = '<li><span>' + response.task_title + '</span><em>(' + response.task_price + ')</em></li>';

                    if(response.processing_fee_val > 0){
                        processing_fee_html = '<li><span>' + response.processing_fee_title + '</span><em>(' + response.processing_fee + ')</em></li>';
                    }

                    jQuery('#wr-planlist').html(task_selected_html + sub_tasks_html+processing_fee_html);
                    jQuery('#wr_task_total').html(response.totalPrice);
                    jQuery('.wr-mainlistvtwo').addClass('d-none');
                    jQuery('#wr-pkg-'+task_key).removeClass('d-none');

                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                }
            }
        });
    }

    // Task budget
    jQuery('.wr-tasktotalbudget .close').on('click', function (e) {
        $("#overlay").css("display", "none");
        $(".wr-fixsidebar").toggleClass("wr-fixsidebarshow");
    });

    // Add to saved items
    jQuery(document).on('click', '.wr_saved_items', function (e) {
        let _this = $(this);
        let id = _this.data('id');
        let post_id = _this.data('post_id');
        let type = _this.data('type');
        let action = _this.data('action');
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_saved_items',
                'security': scripts_vars.ajax_nonce,
                'id': id,
                'post_id': post_id,
                'type': type,
                'option': action
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();

                if (response.type === 'success') {
                    StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    _this.addClass('bg-heart');
                    window.setTimeout(function() {
                        window.location.reload();
                    }, scripts_vars.alertbox_autoclose / 2 || 2500);
                } else {
                    StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                    _this.removeClass('bg-heart');
                }
            }
        });
    });

    // Add to saved items
    jQuery(document).on('click', '.wr_read_notification', function (e) {
        let _this       = jQuery(this);
        let post_id     = _this.data('post_id');

        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'workreap_update_notifications',
                'security': scripts_vars.ajax_nonce,
                'post_id': post_id
            },
            dataType: "json",
            success: function (response) {
                jQuery('.wr-preloader-section').remove();

                if (response.type === 'success') {
                    _this.remove();
                    jQuery('.wr_notify_'+post_id).removeClass('wr-noti-unread');
                    let count_number    = parseInt(jQuery('.wr-remaining-notification').html());
                    jQuery('.wr-remaining-notification').html(count_number-1);
                }
            }
        });
    });

    //show hide tags
    $(document).on('click', '.wr-selected__showmore a', function(){
        jQuery(this).closest('ul').children().removeClass('d-none');
        jQuery(this).closest('li').addClass('d-none');
    });

    // Make drop-down select2
    if ( $.isFunction($.fn.select2) ) {

        jQuery('.wr-select select').select2({
            theme: 'default wr-select2-dropdown',
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        // Make dashboard country drop down select2
        jQuery('.wr-select-country select').select2({
            theme: 'default wr-select2-dropdown',
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        // Make category drop-down select2
        jQuery('#wr_order_type').select2({
            theme: "default wr-select2-dropdown",
            width:'200',
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
        jQuery('#wr_order_type').on('select2:open', function (e) {
            jQuery('.select2-results__options').mCustomScrollbar('destroy');

            setTimeout(function () {
                jQuery('.select2-results__options').mCustomScrollbar();
            }, 0);
        });
        // Make drop-down select2
        jQuery('#wr_country', '#category', '#select_location').select2({
            theme: 'default wr-select2-dropdown',
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        // Make sub category drop-down select2 on search
        jQuery('#wr-top-service-task-search-level-2').select2({
            theme: "default wr-select2-dropdown",
            placeholder: {
                id: '', // the value of the option
                text: scripts_vars.select_sub_category
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

        // Add place holder in select search element
        jQuery('#wr-top-service-task-search-level-2').on('select2:open', function (e) {
            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
        });

         // Make sub category drop-down select2 on search
         jQuery('#wr-top-service-task-option-level-2').select2({
             theme: "default wr-select2-dropdown",
            placeholder: {
                id: '', // the value of the option
                text: scripts_vars.select_sub_category
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

        // Add place holder in select search element
        jQuery('#wr-top-service-task-option-level-2').on('select2:open', function (e) {
            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
        });

        // Make category drop-down select2 on search task
        jQuery('#task_category').select2({
            theme: "default wr-select2-dropdown",
            minimumResultsForSearch: Infinity,
            placeholder: {
                id: '-1', // the value of the option
                text: scripts_vars.select_category
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
        jQuery('#task_location').select2({
            theme: "default wr-select2-dropdown",
            placeholder: {
                id: '-1', // the value of the option
                text: scripts_vars.select_location
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

        jQuery('#wr_project_type').select2({
            theme: "default wr-select2-dropdown",
            placeholder: {
                id: '-1', // the value of the option
                text: scripts_vars.select_location
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

        jQuery('#wr-search-state').select2({
            theme: "default wr-select2-dropdown",
            placeholder: {
                id: '-1', // the value of the option
                text: scripts_vars.select_state
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
        // Make category drop-down select2 on add service
        jQuery('#wr-top-service').select2({
            theme: "default wr-select2-dropdown",
            placeholder: {
                id: '', // the value of the option
                text: scripts_vars.choose_category
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

        // Add place holder in select search element
        jQuery('#wr-top-service').on('select2:open', function (e) {
            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_category);
        });

        // Make sub category drop-down select2 on add service
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

        // Add place holder in select search element
        jQuery('#wr-service-level2').on('select2:open', function (e) {
            jQuery('input.select2-search__field').prop('placeholder', scripts_vars.search_sub_category);
        });

         // Make search sort drop-down select2
        $("#wr-sort").select2({
            theme: "default wr-select2-dropdown",
            width: '196' ,
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth:true,
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

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

        // Make invoice sort drop-down select2
        $("#wr-invoice-sort").select2({
            theme: "default wr-select2-dropdown",
            width: '196' ,
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth:true,
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        // Make withdeaw sort drop-down select2
        $("#wr-withdraw-sort").select2({
            theme: "default wr-select2-dropdown",
            width: '196' ,
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth:true,
            language: {
                noResults: function(){
                    return scripts_vars.select_not_found
                },
                searching: function(){
                    return scripts_vars.select_searching
                },
            }
        });

        //Select2 placeholder
        jQuery('.wr-select [data-placeholderinput], .wr-select [data-placeholderinput]').each(function (item) {
            var data_placeholder = jQuery('[data-placeholderinput]')[item]
            var wr_id = jQuery(data_placeholder).attr('id')
            var wr_placeholder = jQuery(data_placeholder).attr('data-placeholderinput')
            jQuery('#' + wr_id).on('select2:open', function (e) {
               jQuery('#select2-'+wr_id+'-results').parent().parent().find('.select2-search__field').prop('placeholder', wr_placeholder);//.remove();
            });
        });

        //Select2 dropdown scrollbar
        jQuery('.wr-select select, .wr-select select').on('select2:open', function (e) {
            jQuery('.select2-results__options').mCustomScrollbar('destroy');

            setTimeout(function () {
                jQuery('.select2-results__options').mCustomScrollbar();
            }, 0);
        });
        //Select2 placeholder
        // jQuery('.wr-select-country [data-placeholderinput]').each(function (item) {
        //     var data_placeholder = jQuery('[data-placeholderinput]')[item]
        //     var wr_id = jQuery(data_placeholder).attr('id')
        //     var wr_placeholder = jQuery(data_placeholder).attr('data-placeholderinput')
        //     jQuery('#' + wr_id).on('select2:open', function (e) {
        //         jQuery('input.select2-search__field').prop('placeholder', wr_placeholder);
        //     });
        // });

        //Select2 dropdown scrollbar
        jQuery('.wr-select-country select,.wr-select select').on('select2:open', function (e) {
            jQuery('.select2-results__options').mCustomScrollbar('destroy');

            setTimeout(function () {
                jQuery('.select2-results__options').mCustomScrollbar();
            }, 0);
        });

        //Select2 dropdown scrollbar
        jQuery('#task_location','#wr_country', '#task_category', '#wr-sort','.wr-select select').on('select2:open', function (e) {
            jQuery('.select2-results__options').mCustomScrollbar('destroy');

            setTimeout(function () {
                jQuery('.select2-results__options').mCustomScrollbar();
            }, 0);
        });

    }

    // Input asteric
    jQuery('.wr-placeholder').on('click', function (e) {
        jQuery(this).siblings('.form-control').focus();
        e.stopPropagation();
    });

    jQuery('.wr-propsal-list-show').on("click", function(){
        jQuery('.wr-prouserslist').slideToggle(300);
        jQuery('.wr-prouserslist').mCustomScrollbar('destroy');
        setTimeout(function () {
            jQuery('.wr-prouserslist').mCustomScrollbar();
        }, 2000);
    })

    // Earning page payout methods
    jQuery(".wr-radioholder").on("click", function (e) {
        let key         = jQuery(this).attr('data-key');
        jQuery('.wr-li_payouts-'+key).addClass("wr-radio-checked");
        jQuery(this).closest(".wr-payoutmethodholder").addClass("wr-slide");
        jQuery(this).closest(".wr-radiobox").children(".wr-steppaypal").addClass("wr-slidecontent");
        e.stopPropagation();
    });

    //Dashboard paypal payout click
    jQuery(".wr-paypalcontent").on("click", function (e) {
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children().removeClass("wr-banktitle");
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children(".wr-paypaltitle").addClass("wr-banktitle");
        e.stopPropagation();
    });

    // Dashboard stripe payout
    jQuery("#wr-stripecontent").on("click", function (e) {
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children().removeClass("wr-banktitle");
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children(".wr-stripetilte").addClass("wr-banktitle");
        e.stopPropagation();
    });

    // Dashboard bank acount payout
    jQuery("#wr-bankcontent").on("click", function (e) {
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children().removeClass("wr-banktitle");
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children(".wr-banktransfertitle").addClass("wr-banktitle");
        e.stopPropagation();
    });

    // Dashboard button click
    jQuery(".wr-btnplain").on("click", function (e) {
        let selectedkey = jQuery(this).attr('data-selectedkey');
        if (selectedkey == '' || selectedkey == null || selectedkey == undefined) {
            let key = jQuery(this).attr('data-key');
            jQuery('#payrols-'+key).attr("checked", false);
            jQuery('.wr-li_payouts-'+key).removeClass("wr-radio-checked");

        }
        jQuery(this).closest(".wr-slide").removeClass("wr-slide");
        jQuery(this).closest(".wr-slidecontent").removeClass("wr-slidecontent");
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children().removeClass("wr-banktitle");
        jQuery(this).closest(".wr-asideholder").children(".wr-payoutmethodwrap").children(".wr-bankpayouttitle").addClass("wr-banktitle");
        e.stopPropagation();
    });

    // Package title active class
    jQuery(".wr-sidebartabs__pkgtitle .nav-item").on("click", function (e) {
        jQuery(this).siblings('').removeClass('wr-sideactive');
        jQuery(this).addClass('wr-sideactive');
        e.stopPropagation();
    });

    // Display message
    jQuery('.wr-messagelist__name a').on('click', function (e) {
        jQuery(this).closest('.wr-message').addClass('wr-messageopen');
        e.stopPropagation();
    });

    // Display message
    jQuery('.wr-messageuserabove__back').on('click', function (e) {
        jQuery(this).closest('.wr-message').removeClass('wr-messageopen');
        e.stopPropagation();
    });

    // Prevent collapse range slider
    jQuery('.wr-rangevalue').on('click', function (e) {
        e.stopPropagation();
        jQuery('#wr-rangecollapse').collapse('show');
    });

    // Prevent collapse
    jQuery('#wr-rangecollapse, .wr-distance').on('click', function (e) {
        e.stopPropagation();
    });

    // Prevent collapse
    jQuery(document).on('click', 'body', function (e) {
        jQuery('#wr-rangecollapse').collapse('hide');
        e.stopPropagation();
    });

    // submit search on keyword field submit
    jQuery(document).on('click', '.wr-search-icon', function (e) {
        jQuery('#wr_sort_form').submit();
        e.stopPropagation();
    });

    // Task search range input validation
    jQuery(document).on('click','#workreap_apply_filter',function(e){
        e.preventDefault();
        let min_price = jQuery('input#wr_amount_min').val();
        let max_price = jQuery('input#wr_amount_max').val();

        if (min_price && max_price){
            if (parseInt(min_price) > parseInt(max_price)){
                StickyAlert(scripts_vars.price_min_max_error_title, scripts_vars.price_min_max_error_desc, {classList: 'danger', autoclose: scripts_vars.alertbox_autoclose || 5000});
                return false;
            }
        }

        jQuery('#wr_sort_form').submit();
    });

    jQuery('.wr-togglebtmmenu').on('click', function() {
        jQuery(this).next().slideToggle(500);
    });
});

// Alert the notification
function StickyAlert($title = '', $message = '', data) {
    var $icon = 'wr-icon-check';
    var $class = 'dark';

    if (data.classList === 'success') {
        $icon = 'wr-icon-check';
        $class = 'green';
    } else if (data.classList === 'danger') {
        $icon = 'wr-icon-x';
        $class = 'red';
    }

    jQuery.confirm({
        icon: $icon,
        closeIcon: true,
        theme: 'modern',
        animation: 'scale',
        type: $class, //red, green, dark, orange
        title: false,
        draggable: false,
        content: $message,
        onOpenBefore: function(){
            var self = this;
            self.$body.addClass('wr-confirm-modern-alert');
            self.setContentPrepend(`<h4 class="jconfirm-custom-title">${$title}</h4>`);
        },
        autoClose	: 'close|'+ data.autoclose,
        buttons: {
            close: {btnClass: 'wr-sticky-alert'},
        }
    });

    window.setTimeout(function () {
        // jQuery(".jconfirm-content").linkify();
    }, 500);
}

// Alert the notification
function StickyAlertBtn($title = '', $message = '', data) {
    var $icon = 'wr-icon-check';
    var $class = 'dark';
    var btntext;
    btntext = data.button.btntext;
    if (data.classList === 'success') {
        $icon = 'wr-icon-check';
        $class = 'green';
    } else if (data.classList === 'danger') {
        $icon = 'wr-icon-x';
        $class = 'red';
    }

    jQuery.confirm({
        icon: $icon,
        closeIcon: true,
        theme: 'modern',
        animation: 'scale',
        type: $class, //red, green, dark, orange
        title: false,
        onOpenBefore: function(){
            var self = this;
            self.$body.addClass('wr-confirm-modern-alert');
            self.setContentPrepend(`<h4 class="jconfirm-custom-title">${$title}</h4>`);
        },
        autoClose	: 'close|'+ data.autoclose,
        buttons: {
            close: {btnClass: 'wr-sticky-alert'},
            yes: {
                text: btntext,
                btnClass:data.button.buttonclass,
               action : function() {
                   if(data.button.redirect!=''){
                        window.location.href = data.button.redirect;
                   }
                }
            }
        }
    });

    window.setTimeout(function () {
        jQuery(".jconfirm-content").linkify();
    }, 500);
}
// Confirm before submit
function executeConfirmAjaxRequest(ajax, title = 'Confirm', message = '', loader,icon='') {

    var $icon	= 'wr-icon-check';
    var $class	= 'green';

    if(icon === 'danger'){
        $icon	= 'wr-icon-x';
        $class	= 'red';
    }

    $.confirm({
        title: false,
        content: message,
        icon: $icon,
        class: $class,
        theme: 'modern',
        animation: 'scale',
        onOpenBefore: function(){
            var self = this;
            self.$body.addClass('wr-confirm-modern-alert');
            self.setContentPrepend(`<h4 class="jconfirm-custom-title">${title}</h4>`);
        },
        closeIcon: true,
        'buttons': {
            'Yes': {
                'btnClass': 'btn-dark wr-yesbtn',
                'text': scripts_vars.yes,
                'action': function () {
                    if (loader) {
                        jQuery('body').append(loader_html);
                    }
                    jQuery.ajax(ajax);
                }
            },
            'No': {
                'text': scripts_vars.no,
                'btnClass': 'btn-default wr-nobtn',
                'action': function () {
                    return true;
                }
            },
        }
    });
}

// Handled sort_by drop filtration in search task search filters form
function merge_search_field() {
    // get the selected option of sort by drop-down
    var selectedOption = jQuery('#wr-sort').find(":selected").val();

    // set selected value in hidden field
    jQuery('#wr_sort_by_filter').val(selectedOption)

    // submit search form
    jQuery('#wr_sort_form').submit();
}
function workreap_notification_options(){
    jQuery('.wr-notidropdowns > a').on('click',function(e){
        e.stopPropagation();
        let _this               = jQuery(this).next();
        _this.slideToggle();

    });
    jQuery(window).click(function(e) {
        jQuery('.wr-notidropdowns > a').next().slideUp();
    });
}

function workreap_tippy_options(){
    if(jQuery('.wr_tippy').length > 0){
       //
    }
}

function WorkreapShowMore($key='.wr-description-area'){
    wr_categoriesfilter = {
        collapsedHeight: 150,
        moreLink: '<div class="show-more"><a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_more+'</a></div>',
        lessLink: '<div class="show-more"><a href="javascript:void(0);" class="wr-readmorebtn">'+scripts_vars.show_less+'</a></div>',
    };
    jQuery($key).readmore(wr_categoriesfilter);
}

function tooltipTagsInit( selecter) {
    if (typeof tippy === 'function') {
        console.log('tippy', selecter);
        tippy( selecter, {
            allowHTML: true,
            arrow: true,
            theme: 'light',
            animation: 'scale',
            placement: 'top',
            content(reference) {
                const tippycontent = reference.getAttribute('tippy-content');
                if(tippycontent){
                    return template.innerHTML;
                }
                const id = reference.getAttribute('data-template');
                const template = document.getElementById(id);
                return template.innerHTML;
            }
        });
    }
}


function tooltipInit( selecter) {
    if (typeof tippy === 'function') {
        tippy( selecter, {
            allowHTML: true,
            placement: 'top',
            arrow: true,
            theme: 'workreap-tippy',
            animation: 'scale',
            interactive: true,
            content(reference) {
                const tippycontent = reference.getAttribute('tippy-content');
                if(tippycontent){
                    return tippycontent;
                }
            }
        });
    }
}
//MOBILE MENU
function collapseMenu(){
    jQuery('.wr-navbar ul li.menu-item-has-children, .wr-navbar ul li.page_item_has_children, .wr-navdashboard ul li.menu-item-has-children, .wr-navbar ul li.menu-item-has-mega-menu').prepend('<span class="wr-dropdowarrow"><i class="wr-icon-chevron-right"></i></span>');

    jQuery('.wr-navbar ul li.menu-item-has-children span,.wr-navbar ul li.page_item_has_children span').on('click', function(e) {
        jQuery(this).parent('li').toggleClass('wr-open');
        jQuery(this).next().next().slideToggle(300);
        e.stopPropagation();

    });

    jQuery('.wr-navbar ul li.menu-item-has-children > a, .wr-navbar ul li.page_item_has_children > a').on('click', function(e) {
        if ( location.href.indexOf("#") != -1 ) {
            jQuery(this).parent('li').toggleClass('wr-open');
            jQuery(this).next().slideToggle(300);
            e.stopPropagation();

        } else{
            //do nothing
        }
    });
}
jQuery(".wr-bidbtn > .wr-invite-bidbtn,.wr-invite-sent").on("click", function(){
    jQuery(this).attr("disabled", "disabled");
    jQuery(this).text("Invitation sent");
})
function workreapHideRegModel() {
    jQuery('#wr-login-model').modal('hide');
    jQuery('#wr-signup-model').modal('hide');
    jQuery('#wr-pass-model').modal('hide');
}

// range mater collapse
jQuery("#wr-range-wrapper").on("click",function() {
    jQuery("#rangecollapse").collapse("show");
  });

function workreap_unique_increment(length) {
        let characters      = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let randomString    = characters.length;
        let rad_num         = '';
        for ( var i = 0; i < length;  i++) {
            rad_num += characters.charAt(Math.floor(Math.random() *
            randomString));
        }
        return rad_num;
}

jQuery('.wt-dropdown').on('click', function(event){
    event.preventDefault();
    var _this = jQuery(this);
    _this.parents('.wt-formbanner').find('.wt-radioholder').slideToggle();
});

//DROPDOWN RADIO
jQuery('input:radio[name="searchtype"]').on('change',function(){
        var _this = jQuery(this);
        var _type = _this.data('title');
        var _url  = _this.data('url');

        jQuery('.wt-formbanner').attr('action', _url);
        _this.parents('.wt-formbanner').find('.selected-search-type').html(_type);
        _this.parents('.wt-formbanner').find('.wt-radioholder').slideToggle();

    }
);

//Dropdown outside click
jQuery(document).mouseup(function(e){
    var container = jQuery(".wt-dropdown,.wt-radioholder");
    if(!container.is(e.target) && container.has(e.target).length === 0){
        jQuery('.wt-radioholder').hide();
    }
});

jQuery(".search-form-submit").click(function(){
    jQuery(".search-form").submit();
});
