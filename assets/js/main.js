document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll("form").forEach((form) => {
		form.addEventListener("submit", (event) => {
			if (!form.matches(".project-contact__form")) return;

			event.preventDefault();

			const name = form.elements.name.value.trim();
			const phone = form.elements.phone.value.trim();
			const whatsapp = form.dataset.whatsapp;
			const project = form.dataset.project;
			const message = `مرحباً، أنا ${name}. أرغب في الاستفسار عن مشروع ${project}. رقم هاتفي: ${phone}`;
			const url = `https://wa.me/${whatsapp}?text=${encodeURIComponent(message)}`;

			window.location.assign(url);
		});
	});
});
