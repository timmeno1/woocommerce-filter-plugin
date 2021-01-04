var data = {
	action: 'cfilter',
	show_cat : '',
	paged : 1
};
function show_models(cat){
	data.show_cat = cat;
	jQuery.post( myPlugin.ajaxurl, data, function(response) {
		if(cat==="iPhone" || cat==="iPad" || cat==="Samsung" ){
			jQuery('.quick-nav-content-1').html(response).fadeIn(100);
		} else {
			jQuery('.quick-nav-content-2').html(response).fadeIn(100);
		}
	});
}
jQuery(document).ready(function() {
	jQuery(".quick-nav-category a").click(function (event) {
		if(jQuery('ul.quick-nav-content').length){
			jQuery('ul.quick-nav-content').fadeOut(50);
		}
		event.preventDefault();
		jQuery(".quick-nav-category a.active").removeClass("active");
		jQuery(this).addClass("active");
		var cat = jQuery(this).text();
		show_models(cat);
		setTimeout(()=>{
			jQuery([document.documentElement, document.body]).animate({
				scrollTop: jQuery(this).parent().offset().top - 50
			}, 100);
		}, 200)
	});
});
