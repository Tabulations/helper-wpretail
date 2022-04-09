var product = window.product || ( function( document, window, $ ) {

	var app = {
		init:function () {
			app.addProduct();
			app.toggleCategory();
		},
		addProduct:function(){
			// Add the product
			$(document).on('click','.wpretail_add_product',function(e){
				e.preventDefault();
				var $form = $('#wpretail-add_product')[0];
				var formData = new FormData($form);
				formData.append( 'action', 'wpretail_add_product' );
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
		},
		toggleCategory:function(){
			$(document).on('change','#sub_taxonomy_1',function (event) {
			if ( $( event.target ).is( ':checked' ) ) {
				$( '.parent_id' ).show();
			} else {
				$( '.parent_id' ).hide();
			}
			});
		}
	};
	return app;

}( document, window, jQuery ) );

// Initialize.
product.init();
