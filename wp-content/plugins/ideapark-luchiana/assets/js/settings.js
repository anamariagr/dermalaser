(function ($, root, undefined) {
	$(document).ready(function ($) {

		/***** Colour picker *****/

		$('.color-picker').each(function () {
			$(this).wpColorPicker();
		});

		/***** Uploading images *****/

		var file_frame;

		jQuery.fn.uploadMediaFile = function (button, preview_media) {
			var button_id = button.attr('id');
			var field_id = button_id.replace('_button', '');
			var preview_id = button_id.replace('_button', '_preview');

			// If the media frame already exists, reopen it.
			if (file_frame) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title   : $(this).data('uploader_title'),
				button  : {
					text: $(this).data('uploader_button_text'),
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on('select', function () {
				attachment = file_frame.state().get('selection').first().toJSON();
				$("#" + field_id).val(attachment.id);
				if (preview_media) {
					$("#" + preview_id).attr('src', attachment.sizes.thumbnail.url);
				}
				file_frame = false;
			});

			// Finally, open the modal
			file_frame.open();
		}

		$('.image_upload_button').on('click', function () {
			jQuery.fn.uploadMediaFile($(this), true);
		});

		$('.image_delete_button').on('click', function () {
			$(this).closest('td').find('.image_data_field').val('');
			$(this).closest('td').find('.image_preview').remove();
			return false;
		});
	});

	root.ideaparkSelectWithIcons = function (state) {
		if (!state.id) {
			return state.text;
		}
		var $state = $(
			'<span><i class="' + state.id + '"></i> ' + state.text + '</span>'
		);
		return $state;
	};

	function update() {
		var $this = $( this ),
			options = $this.data( 'options' );

		if (typeof options.templateResult !== 'undefined' && options.templateResult === "ideaparkSelectWithIcons") {
			options.templateResult = ideaparkSelectWithIcons;
			options.templateSelection = ideaparkSelectWithIcons;
			$this.data( 'options', options );
		}
	}

	function price_on_request_checkbox() {
		var $checkbox = $('#price_on_request');
		var $elements = $('.ideapark-luchiana-price,.ideapark-luchiana-condition');
		if ($checkbox.length) {
			if ($checkbox.prop('checked')) {
				$elements.addClass('h-invisible');
			} else {
				$elements.removeClass('h-invisible');
			}
		}
	}
	price_on_request_checkbox();
	
	$('#price_on_request').on('change', price_on_request_checkbox);
	
	$( '.rwmb-select_advanced' ).each( update );
})(jQuery, this);