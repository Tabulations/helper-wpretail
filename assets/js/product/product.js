var product = window.product || ( function( document, window, $ ) {

	var app = {
		init:function () {
			app.addProduct();
		},
		addProduct:function(){

			// Add the product
			$(document).on('click','.wp-retail-add-product-button',function(e){
				e.preventDefault();
				var $form = $('#wp-retail-add-product')[0];
				var formData = new FormData($form);
				formData.append( 'action', 'add_product' );
				formData.append('security',productAjax.product_nonce)

				$.ajax({
					url:productAjax.ajaxurl,
					type:'POST',
					data:formData,
					cache      : false,
					contentType: false,
					processData: false,
					success:function (response) {
						console.log(response);
					},
					error:function(error){
						console.log(error);
					}
				})
			});
		}
	};
	return app;

}( document, window, jQuery ) );

// Initialize.
product.init();
