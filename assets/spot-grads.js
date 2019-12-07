jQuery(function () {
	init_spot_grads_isotope();
});


function init_spot_grads_isotope() {
	let $spot_grads_gallery = jQuery('#spot-grads-gallery');
	if ( !$spot_grads_gallery.length ) return;

	$spot_grads_gallery.isotope({
		itemSelector: '.spot-grads-dog',
		layoutMode: 'fitRows',
		getSortData: {
			name: '.spot-grad-name'
		}
	});

	jQuery('#spot-grads-filtering').on('change', function () {
		$spot_grads_gallery.isotope({ filter: this.value });
	});

	jQuery('#spot-grads-sorting').on('change', function () {
		let order = jQuery(this).find("option:selected").data("ascending");
		$spot_grads_gallery.isotope({
			sortBy: this.value,
			sortAscending: order
		});
	});
}
