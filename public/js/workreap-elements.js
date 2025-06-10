(function ($, elementor) {
   "use strict";

    let Workreap_Elements = {

        init: function () {

            let widgets = {
                'workreap-mac-book.default': Workreap_Elements.wr_mac_book,
                'workreap-client-logo.default': Workreap_Elements.wr_clients_logo,
                'workreap-image-slider.default': Workreap_Elements.wr_image_slider,
                'workreap-hero-slider.default': Workreap_Elements.wr_hero_slider,
                'workreap-services-grid.default': Workreap_Elements.wr_service_grid,
                'workreap-creative-testimonial.default': Workreap_Elements.wr_testimonial,
                'workreap-pricing.default': Workreap_Elements.wr_pricing,
                'workreap-category-listing.default': Workreap_Elements.wr_category_listing,
                'workreap-category-menu.default': Workreap_Elements.wr_category_menu,
                'workreap-advanced-accordion.default': Workreap_Elements.wr_advanced_accordion,
            }

            $.each(widgets, function (widget, callback) {
                elementorFrontend.hooks.addAction("frontend/element_ready/" + widget, callback);
            });

        },

        wr_mac_book: function($scope){
            var elementSettings = Workreap_Elements.getElementSettings($scope);
            var selector = $scope.find('.wr-macbook-slider');
            if(selector.length){
                var swiper_config = {
                    loop: true,
                    effect: "fade",
                    speed: elementSettings.slide_speed.size * 1000 || 3000,
                    slidesPerView: 1,
                    autoplay: {
                        delay: elementSettings.autoplay_timeout.size * 1000 || 5000,
                    },
                    pagination: {
                        clickable: true,
                        el: $scope.find('.swiper-pagination').get(0),
                    },

                };
                Workreap_Elements.swiperInit(selector.get(0),swiper_config);
            }
        },

        wr_clients_logo: function($scope){
            var selector = $scope.find('.wr-client-logo-layout-3 .swiper');
            if(selector.length){
                let swiper_config = {
                    slidesPerView: "auto",
                    centeredSlides: true,
                    spaceBetween: 60,
                    allowTouchMove: false,
                    loop: true,
                    speed: 2000,
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                    },
                };
                Workreap_Elements.swiperInit(selector.get(0),swiper_config);
            }
        },

        wr_image_slider: function($scope){
            var elementSettings = Workreap_Elements.getElementSettings($scope);
            var selector = $scope.find('.wr-image-slider');
            if(selector.length){
                var swiper_config = {
                    allowTouchMove: false,
                    loop: true,
                    speed: elementSettings.slide_speed.size * 1000 || 3000,
                    direction: elementSettings.orientation,
                    effect: elementSettings.effect,
                    slidesPerView: 1,
                    autoplay: {
                        delay: elementSettings.autoplay_timeout.size * 1000 || 5000,
                    },
                    pagination: {
                        clickable: true,
                        el: $scope.find('.swiper-pagination').get(0),
                    },
                };
                Workreap_Elements.swiperInit(selector.get(0),swiper_config);
            }
        },

        wr_hero_slider: function($scope){
            var elementSettings = Workreap_Elements.getElementSettings($scope);
            var selector = $scope.find('.wr-hero-slider');
            if(selector.length){
                var swiper_config = {
                    allowTouchMove: false,
                    loop: true,
                    autoHeight: true,
                    speed: elementSettings.slide_speed.size * 1000 || 3000,
                    slidesPerView: 1,
                    centeredSlides: true,
                    effect: "creative",
                    creativeEffect: {
                        prev: {
                            shadow: true,
                            translate: [0, 0, -400],
                        },
                        next: {
                            translate: ['100%', 0, 0],
                        },
                    },
                    pagination: {
                        el: $scope.find('.swiper-pagination').get(0),
                        type: "fraction",
                    },
                    navigation: {
                        nextEl: $scope.find('.swiper-button-next').get(0),
                        prevEl: $scope.find('.swiper-button-prev').get(0),
                    },
                };
                Workreap_Elements.swiperInit(selector.get(0),swiper_config);
            }
        },

        wr_service_grid: function($scope){
            var elementSettings = Workreap_Elements.getElementSettings($scope);
            var selector = $scope.find('.wr-services-grid-items');
            if(selector.length && !elementorFrontend.isEditMode()){
                selector.isotope({
                    itemSelector: '.wr-services-grid-item',
                    layoutMode: 'fitRows',
                    filter: '*',
                });

                $('.wr-services-grid-filter-item').on('click',function (e) {
                   e.preventDefault();
                   var filter = $(this).data('filter');
                    $(this).addClass('active').siblings().removeClass('active');
                    selector.isotope({
                        filter: filter
                    });
                });

            }
        },

        wr_testimonial : function($scope){
            var next = $scope.find('.wr-feedback-slider-button .next');
            var prev = $scope.find('.wr-feedback-slider-button .prev');

            if(next && prev) {

                next.on('click',function (e) {
                    e.preventDefault();
                    let items = $scope.find('.wr-feedback-item');
                    $scope.find('.wr-feedback-slide').append(items[0]);
                });

                prev.on('click',function (e) {
                    e.preventDefault();
                    let items = $scope.find('.wr-feedback-item');
                    $scope.find('.wr-feedback-slide').prepend(items[items.length - 1]);
                });

            }

        },

        wr_pricing : function($scope){
            var toggle = $scope.find('.wr-pricing-toggle-btn');
            toggle.on('click',function (e) {
               e.preventDefault();
               var _this = $(this);
               var _id = _this.data('target-id');
               if(!_this.hasClass('active')){
                   $('.wr-pricing-toggle-btn').removeClass('active');
                   _this.addClass('active');
                   $('.wr-package-items').removeClass('active');
                   $('#' + _id).addClass('active');
               }
            });
        },

        wr_category_listing: function($scope){

            let elementSettings = Workreap_Elements.getElementSettings($scope);
            let selector = $scope.find('.wr-category-listing');

            let swiperConfig = {
                loop: elementSettings.loop === 'yes',
                speed: elementSettings.slide_speed.size * 100 || 300,
                allowTouchMove: elementSettings.mouse_drag === 'yes',
                mousewheel: elementSettings.mouse_wheel === 'yes',
                autoplay: elementSettings.autoplay === 'yes' ? { delay: elementSettings.autoplay_timeout.size * 1000 } : false,
                scrollbar: {
                    el: $scope.find('.swiper-scrollbar').get(0),
                    hide: true,
                },
                breakpoints: {
                    [elementorFrontend.config.breakpoints.lg]: {
                        slidesPerView: elementSettings.item_per_row || 3,
                        slidesPerGroup: elementSettings.slides_per_group || 1,
                        spaceBetween: elementSettings.space_between.size || 15
                    },
                    [elementorFrontend.config.breakpoints.md]: {
                        slidesPerView: elementSettings.item_per_row_tablet || 2,
                        slidesPerGroup: elementSettings.slides_per_group || 1,
                        spaceBetween: elementSettings.space_between_tablet.size || 15
                    },
                    0: {
                        slidesPerView: elementSettings.item_per_row_mobile || 1,
                        slidesPerGroup: elementSettings.slides_per_group || 1,
                        spaceBetween: elementSettings.space_between_mobile.size || 15
                    }
                }
            }

            var swiper = Workreap_Elements.swiperInit(selector.get(0),swiperConfig);

        },

        wr_advanced_accordion: function($scope){

            let elementSettings = Workreap_Elements.getElementSettings($scope);
            let selector = $scope.find('.wr-advanced-accordion-header');

            selector.on('click', function (e) {
                e.preventDefault();
                let _this = $(this).parent();
                _this.siblings().find('.wr-advanced-accordion-content').slideUp(200);
                _this.siblings().removeClass('active');
                _this.find('.wr-advanced-accordion-content').slideToggle(200);
                _this.toggleClass('active');
            });

        },

        //Common Settings
        getElementSettings: function(element, setting){
            var elementSettings = {},
                modelCID = element.data('model-cid');
            if (elementorFrontend.isEditMode() && modelCID) {
                var settings = elementorFrontend.config.elements.data[modelCID],
                    type = settings.attributes.widgetType || settings.attributes.elType,
                    settingsKeys = elementorFrontend.config.elements.keys[type];
                if (!settingsKeys) {
                    settingsKeys = elementorFrontend.config.elements.keys[type] = [];
                    $.each(settings.controls, function (name, control) {
                        if (control.frontend_available) {
                            settingsKeys.push(name);
                        }
                    });
                }
                $.each(settings.getActiveControls(), function (controlKey) {
                    if (-1 !== settingsKeys.indexOf(controlKey)) {
                        elementSettings[controlKey] = settings.attributes[controlKey];
                    }
                });
            } else {
                elementSettings = element.data('settings') || {};
            }

            return Workreap_Elements.getItems(elementSettings, setting);

        },
        getItems: function(items, itemKey){
            if (itemKey) {
                var keyStack = itemKey.split('.'),
                    currentKey = keyStack.splice(0, 1);
                if (!keyStack.length) {
                    return items[currentKey];
                }
                if (!items[currentKey]) {
                    return;
                }
                return this.getItems(items[currentKey], keyStack.join('.'));
            }
            return items;
        },
        swiperInit: function (selector, config){
            let swiperInit = false;
            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;
                new asyncSwiper( selector, config ).then( ( swiperInstance ) => {
                    swiperInit = swiperInstance;
                    return swiperInit;
                });
            } else {
                swiperInit = new Swiper( selector, config );
                return swiperInit;
            }
            return swiperInit;
        }

    }

   $(window).on("elementor/frontend/init", Workreap_Elements.init);

}(jQuery, window.elementorFrontend));