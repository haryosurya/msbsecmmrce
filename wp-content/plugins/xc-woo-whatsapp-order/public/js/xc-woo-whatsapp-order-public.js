(function( $ ) {
	'use strict';
	 
	 $(function () {
		var $floating_btn_delay = 2500;
		var $button = $('.xc-woo-floating-whatsapp-btn');
		if($button.length > 0){
			setTimeout(function () { $button.addClass('xc-woo-floating-whatsapp-show'); }, $floating_btn_delay);
		}
		
		
		var check_variations = $('.variations_form');
		 if(check_variations.length > 0) {
			 var available_variations = $(check_variations.data('product_variations'));
	    	$(document).on('change', '.variations select', function(e) {
		    	var $selected = [];
				check_variations.find('select').each(function(index, element) {
					if($(this).find('option:selected').val() != ''){
                    	$selected.push($(this).find('option:selected').text());
					}
                });
				
				if($selected.length){
					var $data = $selected.join(', ');
					var $button = $('.xc-woo-order-whatsapp-variable-product');
					var $href = $button.data('href');
					$href = $href+' - '+$data;
					$button.attr({"href":$href});
				}else{
					var $button = $('.xc-woo-order-whatsapp-variable-product');
					var $href = $button.data('href');
					$button.attr({"href":$href});
				}
			});
			
			
		 }
	
		if($(".xc-woo-order-whatsapp-send").length > 0){
			$(document).on('click', 'a.xc-woo-order-whatsapp-send',function(){
				
				$('form.woocommerce-cart-form').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				
				$.post(xc_woo_whatsapp_order_public_params.ajax_url,{'action':"xc_woo_whatsapp_order_send",'nonce':xc_woo_whatsapp_order_public_params.nonce},function(result){
						result = result.data;
						
						//$('<a href="'+result.whatsapp+'" target="blank"></a>')[0].click();
						
						if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
							window.location = result.redirect;
						} else {
							window.location = decodeURI( result.redirect );
						}
				});	
			});	
		}
		
	 });

})( jQuery );
