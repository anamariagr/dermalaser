(function ($) {
	"use strict";
	$(window).on('elementor/frontend/init', function () {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-slider.default', function ($scope) {
			ideapark_init_slider_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-woocommerce-carousel.default', function ($scope) {
			ideapark_init_woocommerce_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-product-tabs.default', function ($scope) {
			ideapark_init_woocommerce_widget_carousel();
			ideapark_init_tabs();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-team.default', function ($scope) {
			ideapark_init_team_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-gift.default', function ($scope) {
			ideapark_init_gift_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-news-carousel.default', function ($scope) {
			ideapark_init_news_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-video-carousel.default', function ($scope) {
			ideapark_init_video_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-reviews.default', function ($scope) {
			ideapark_init_reviews_widget_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-tabs.default', function ($scope) {
			ideapark_init_tabs();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-image-list-1.default', function ($scope) {
			ideapark_init_image_list_1_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-image-list-2.default', function ($scope) {
			ideapark_init_image_list_2_carousel();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-countdown.default', function ($scope) {
			ideapark_init_countdown();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-accordion.default', function ($scope) {
			ideapark_init_accordion();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-banners.default', function ($scope) {
			ideapark_init_banners();
		});
		window.elementorFrontend.hooks.addAction('frontend/element_ready/ideapark-running-line.default', function ($scope) {
			ideapark_init_running_line();
		});
		
		if (window.elementorFrontend.isEditMode()) {
			var debounce_function = null;
			elementor.channels.editor.on('change', function (view) {
				var changed = view.container.settings.changed;
				var widget = view.container.settings.attributes.widgetType;
				var id = view.container.id;
				var $container = $('[data-id="' + id + '"]');
				
				if (widget == 'ideapark-image-list-1' && changed.space && $container.length) {
					debounce_function = debounce_function ? debounce_function : function () {
						var $list = $('.c-ip-image-list-1__list', $container);
						if ($list.hasClass('owl-carousel')) {
							$list.trigger('destroy.owl.carousel').removeClass('owl-carousel');
							ideapark_init_image_list_1_carousel();
						}
					};
					ideapark_debounce_call();
				}
				
				if (widget == 'ideapark-image-list-2' && changed.space && $container.length) {
					debounce_function = debounce_function ? debounce_function : function () {
						var $list = $('.c-ip-image-list-2__list', $container);
						if ($list.hasClass('owl-carousel')) {
							$list.trigger('destroy.owl.carousel').removeClass('owl-carousel');
						}
							ideapark_init_image_list_2_carousel();
					};
					ideapark_debounce_call();
				}
			});
			var ideapark_debounce_call = ideapark_debounce(function () {
				if (debounce_function) {
					debounce_function();
					debounce_function = null;
				}
			}, 500);
		}
	});
	
})(jQuery);
