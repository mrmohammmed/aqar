jQuery(function ($) {
	let imageFrame;

	$('.aqar-city-image-select').on('click', function (event) {
		event.preventDefault();

		if (!imageFrame) {
			imageFrame = wp.media({
				title: aqarCityImagePicker.title,
				button: { text: aqarCityImagePicker.button },
				multiple: false,
				library: { type: 'image' },
			});

			imageFrame.on('select', function () {
				const attachment = imageFrame.state().get('selection').first().toJSON();
				const imageUrl = attachment.sizes && attachment.sizes.thumbnail
					? attachment.sizes.thumbnail.url
					: attachment.url;

				$('.aqar-city-image-field input[name="aqar_city_image"]').val(attachment.id);
				$('.aqar-city-image-preview').html($('<img>', {
					src: imageUrl,
					alt: '',
				}));
				$('.aqar-city-image-remove').prop('disabled', false);
			});
		}

		imageFrame.open();
	});

	$('.aqar-city-image-remove').on('click', function (event) {
		event.preventDefault();
		$('.aqar-city-image-field input[name="aqar_city_image"]').val('');
		$('.aqar-city-image-preview').empty();
		$(this).prop('disabled', true);
	});
});
