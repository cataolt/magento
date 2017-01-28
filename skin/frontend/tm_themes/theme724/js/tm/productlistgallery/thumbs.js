
(function($){
	$.fn.productListGalleryThumbs = function(){
		return this.each(function(){

			var thumbs 			= $(this).find('.product-thumbs');
			var thumb_link 		= $(this).find('.product-thumb a');

			thumbs.find('.product-thumb:first-child a').addClass('active');

			thumb_link.click(function(e){
				e.preventDefault();

				var url 			= $(this).find('img').attr('src');
				var container 		= $(this).closest('.product-image-container');
				var product_image 	= container.find('.product-image img');

				product_image.stop().fadeOut(200, function(){
					$(this).attr('src', url);
					$(this).fadeIn(200);
				});

				$(this).addClass('active');
				$(this).parent().siblings().find('a').removeClass('active');
			})

		})
	}
})(jQuery);

jQuery(document).ready(function() {
	jQuery('.products-grid').productListGalleryThumbs();
	jQuery('.products-list').productListGalleryThumbs();
});