// JavaScript Document

jQuery(document).ready(function(){
	
	
	jQuery(document).on('found_variation', 'form.variations_form', function(event, variation) {
		
		console.log("🔍 variation trouvée :", variation);

        if (variation.image && variation.image.src) {
			var newImageSrc = variation.image.src;
			var newImageSrcset = variation.image.srcset;

			var $activeSlideImg = jQuery('.woocommerce-product-gallery__wrapper .swiper-slide-active img');

			if ($activeSlideImg.length) {
				$activeSlideImg.attr('src', newImageSrc);
				if (newImageSrcset) $activeSlideImg.attr('srcset', newImageSrcset);
			}
		}
    });

    // Remet l’image et le prix par défaut si aucune variation
   jQuery(document).on('reset_data', 'form.variations_form', function() {
        location.reload(); // ou remets manuellement le prix/image d'origine si besoin
    });
	
	
	
	// + - add cart
	jQuery(document).on('click', '.qty-button', function () {

		var $button = jQuery(this);
		var $input = $button.closest('.quantity-wrapper').find('input.qty');

		var currentVal = parseInt($input.val(), 10);
		var min = parseInt($input.attr('min')) || 1;
		var max = parseInt($input.attr('max')) || 9999;

		if (isNaN(currentVal)) currentVal = min;

		if ($button.hasClass('plus')) {
			if (currentVal < max) {
				$input.val(currentVal + 1).trigger('change');
			}
		} else if ($button.hasClass('minus')) {
			if (currentVal > min) {
				$input.val(currentVal - 1).trigger('change');
			}
		}
	});
});