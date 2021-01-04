var data = {
	action: 'cfilter',
	show_cat : '',
	paged : 1
};

function closeQuickNavContent () {
	if(jQuery('ul.quick-nav-content').length){
		jQuery('ul.quick-nav-content').fadeOut(50);
		jQuery('.quick-nav-row.qn-content.active').removeClass('active');
	}
	jQuery(".quick-nav-category a.active").removeClass("active");
}

function show_models(cat){
	data.show_cat = cat;
	jQuery.post( myPlugin.ajaxurl, data, function(response) {
		if(cat==="iPhone" || cat==="iPad" || cat==="Samsung" ){
			jQuery('.quick-nav-content-1').addClass('active').html(response).fadeIn(100);
		} else {
			jQuery('.quick-nav-content-2').addClass('active').html(response).fadeIn(100);
		}
	});
}

jQuery(document).ready(function() {
	jQuery(".quick-nav-category a").click(function (event) {
		event.preventDefault();
		if(jQuery(this).hasClass('active')){
			closeQuickNavContent();
		} else {
			closeQuickNavContent();
			jQuery(this).addClass("active");
			var cat = jQuery(this).text();
			show_models(cat);
			setTimeout(()=>{
				jQuery([document.documentElement, document.body]).animate({
					scrollTop: jQuery(this).parent().offset().top - 50
				}, 100);
			}, 200)
		}
	});
});
