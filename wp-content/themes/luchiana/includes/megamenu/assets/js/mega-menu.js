(function ($, root, undefined) {
	$(function () {
		"use strict";
		
		var ideapark_check_megamenu = function () {
			$('.ip-field-custom--active').removeClass('ip-field-custom--active');
			$('.menu-item-depth-0 .ip-field-custom--depth-0').addClass('ip-field-custom--active');
			var is_primary = $("input[id^=locations-primary]:checked").length;
			var is_primary_mobile = $("input[id^=locations-mobile]:checked").length;
			if (is_primary || is_primary_mobile) {
				$('.ip-field-custom__option-html-block').prop('disabled', false);
			} else {
				$('.ip-field-custom__option-html-block').prop('disabled', true);
			}
			if (is_primary || is_primary_mobile) {
				$('.menu-item-depth-0 .ip-field-custom--primary-0').addClass('ip-field-custom--active');
			}
			if (is_primary || is_primary_mobile) {
				$('.menu-item:not(.menu-item-depth-0) .ip-field-custom--primary-not-0').addClass('ip-field-custom--active');
			}
			$('.ip-field-custom--badge_color.ip-field-custom--active:not(.ip-field-custom--hidden) .ip-field-custom__badge_color:not(.init)').addClass('init').wpColorPicker();
			if (is_primary_mobile && !is_primary) {
				$('.ip-field-custom--columns').removeClass('ip-field-custom--active');
			}
		}
		
		ideapark_check_megamenu();
		$('.menu-settings [name^="menu-locations"]').on('click', ideapark_check_megamenu);
		
		var ideapark_select_content = function () {
			$('.menu-item-depth-0 .ip-field-custom__content').each(
				function () {
					var $this = $(this);
					var content_type = $this.val();
					var $menu_item = $this.closest('.menu-item');
					$('.ip-field-custom--html_block,.ip-field-custom--product_category,.ip-field-custom--product_attr', $menu_item).removeClass('active');
					$('.ip-field-custom--' + content_type, $menu_item).addClass('active');
				}
			)
		}
		
		ideapark_select_content();
		
		$(document)
			.on('sortstop menu-item-added', function () {
				setTimeout(ideapark_check_megamenu, 100);
			})
			.on('change', '.ip-field-custom__content', ideapark_select_content)
			.on('click', '.ip-field-custom__badge_add', function (e) {
				e.preventDefault();
				var $this = $(this);
				var $parent = $this.closest('.menu-item-settings');
				$parent.find('.ip-field-custom--badge_text, .ip-field-custom--badge_color').removeClass('ip-field-custom--hidden');
				$('.ip-field-custom__badge_color:not(.init)', $parent).addClass('init').wpColorPicker();
				$this.remove();
			})
			.on('click', '.ip-field-custom__loader', function (e) {
				e.preventDefault();
				var $this = $(this);
				var $parent = $this.closest('.ip-field-custom');
				var $spinner = $parent.find('.spinner');
				
				if ($parent.hasClass('loaded')) {
					return false;
				}
				
				if ($spinner.hasClass('is-active')) {
					return false;
				}
				
				$spinner.addClass('is-active');
				
				var $value = $parent.find('.ip-field-custom__val');
				var value = $value.val();
				var type = $parent.data('type');
				var item_id = $parent.data('item-id');
				var $container = $parent.find('.ip-field-custom__container');
				var $selected = $parent.find('.ip-field-custom__loader');
				
				$.ajax({
					url    : ajaxurl,
					type   : 'POST',
					data   : {
						action : 'ideapark_load_mega_menu',
						value  : value,
						type   : type,
						item_id: item_id,
					},
					success: function (html) {
						if (type == 'icon') {
							$container.html(html);
							$container.find('.ip-field-custom__icon-select')
								.select2({
									placeholder   : ideapark_wp_vars_mega_menu.placeholderIcon,
									allowClear    : true,
									templateResult: function (state) {
										if (!state.id) {
											return state.text;
										}
										var $state = $(
											'<span><span class="ip-field-custom__option-ico ' + state.id + '"></span> ' + state.text + '</span>'
										);
										return $state;
									}
								})
								.on('select2:select select2:clear select2:unselect', function (e) {
									var data = e.params.data;
									if (e.type === 'select2:unselect' || e.type === 'select2:unselect') {
										$selected.html('');
										$value.val('');
									} else {
										$selected.html(data.id ? '<span class="ip-field-custom__icon ' + data.id + '"></span>' : '');
										$value.val(data.id);
									}
								});
						} else {
							$selected.replaceWith(html);
						}
						$parent.addClass('loaded');
					}
				}).always(function () {
					$spinner.removeClass('is-active');
				});
				
				return false;
			});
	});
	
})(jQuery, this);
