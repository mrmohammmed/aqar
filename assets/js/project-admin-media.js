jQuery(function ($) {
	const $gallery = $("#aqar_project_gallery");
	const $galleryPreview = $(".aqar-project-gallery-preview");

	$(".aqar-project-gallery-select").on("click", function (event) {
		event.preventDefault();
		const frame = wp.media({
			title: "اختيار صور المعرض",
			button: { text: "إضافة الصور المحددة" },
			library: { type: "image" },
			multiple: true
		});

		frame.on("select", function () {
			const selected = frame.state().get("selection");
			const lines = $gallery.val().split(/\r?\n/).map((line) => line.trim()).filter(Boolean);
			const ids = new Set(lines.filter((line) => /^\d+$/.test(line)));

			selected.each(function (attachment) {
				const image = attachment.toJSON();
				if (ids.has(String(image.id))) return;
				ids.add(String(image.id));
				lines.push(String(image.id));
				const thumbnail = image.sizes && image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url;
				$("<img>", {
					src: thumbnail,
					alt: "",
					css: { width: "72px", height: "60px", objectFit: "cover" }
				}).appendTo($galleryPreview);
			});

			$gallery.val(lines.join("\n"));
		});
		frame.open();
	});

	$(".aqar-project-gallery-clear").on("click", function (event) {
		event.preventDefault();
		$gallery.val("");
		$galleryPreview.empty();
	});
});
