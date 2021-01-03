var data = {
	action: 'cfilter',
	show_cat : '',
	paged : 1
};
function show_models(cat){
	data.show_cat = cat;
	jQuery.post( myPlugin.ajaxurl, data, function(response) {
		if(jQuery('ul.quick-nav-content').length){
			console.log('ul.quick-nav-content exists');
			jQuery('ul.quick-nav-content').fadeOut(200);
		}
		if(cat==="iPhone" || cat==="iPad" || cat==="Samsung" ){
			jQuery('.quick-nav-content-1').html(response).fadeIn(200);
		} else {
			jQuery('.quick-nav-content-2').html(response).fadeIn(200);
		}
	});
}
jQuery(document).ready(function() {
	jQuery(".quick-nav-category a").click(function (event) {
		event.preventDefault();
		var cat = jQuery(this).text();
		show_models(cat);
	});
});
