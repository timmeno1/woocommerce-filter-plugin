var data = {
	action: 'cfilter',
	show_cat : 'iphone',
	paged : 1
};
function send_page(page){
	data.paged = page;
	jQuery.post( myPlugin.ajaxurl, data, function(response) {
		jQuery('#cfilter-products').fadeOut(100);
		jQuery('#cfilter-products').html(response).fadeIn(200);
		// jQuery(".woocommerce-pagination a").removeClass('current');
		// jQuery(".woocommerce-pagination a").eq(page-1).addClass('current');
		// jQuery(".woocommerce-pagination a").eq(page-1).removeAttr('onclick');
		jQuery(".woocommerce-pagination a").click(function (event) {
			event.preventDefault();
		});
	});
}
jQuery(document).ready(function() {
	jQuery.post( myPlugin.ajaxurl, data, function(response) {
		jQuery('#cfilter-products').html(response);
		jQuery(".woocommerce-pagination a").click(function (event) {
			event.preventDefault();
		});             
		event.preventDefault();
		jQuery("#cfilter_tabs a").click(function(event) {
			event.preventDefault();
			data.show_cat = jQuery(this).attr('id');
			data.paged = 1;	
			jQuery.post( myPlugin.ajaxurl, data, function(response) {
				jQuery('#cfilter-products').fadeOut(100);
				jQuery(".woocommerce-pagination").remove();
				jQuery('#cfilter-products').html(response).fadeIn(200);
				jQuery("#cfilter_tabs a.active").removeClass('active');
				jQuery("#"+data.show_cat).addClass('active');
				jQuery(".woocommerce-pagination a").click(function (event) {
					event.preventDefault();
				});             
			});
		});

		
	});
});
