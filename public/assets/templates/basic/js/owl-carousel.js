function initImgSlider() {

	$('.product-images').owlCarousel({
		loop: false,
		margin: 8,
		items: 1,
		dots: false,
	});

	const owl = $(document)
		.find('.quick-view-slider')
		.owlCarousel({
			loop: false,
			margin: 8,
			responsiveClass: true,
			items: 1,
			dots: false,
			nav: true,
			autoplay: false,
			responsive: {
				320: {
					items: 4,
				},
				400: {
					items: 5,
				},
				576: {
					items: 5,
				},
				768: {
					items: 4,
				},
				992: {
					items: 5,
				},
				1200: {
					items: 5,
				},
			}
		});

	return owl;
}

$(document).ready(function () {
	let slider = initImgSlider();

	$('.media-item-nav').on('click', function () {
		var targetIndex = $(this).data('media_id');
		slider.trigger('to.owl.carousel', [targetIndex, 300]);
	});
});

function toggleOwlCarousel() {
	if ($(window).width() >= 576) {
		$(".product-with-banner").owlCarousel({
			loop: false,
			margin: 16,
			responsiveClass: true,
			items: 2,
			dots: false,
			nav: true,
			autoplay: false,
			responsive: {
				576: {
					items: 2
				},
				768: {
					items: 3,
				},
				992: {
					items: 3,
				},
				1400: {
					items: 4,
				}
			}
		});
	}
}

toggleOwlCarousel();

$(window).resize(toggleOwlCarousel);

