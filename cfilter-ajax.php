<?php  

/**
* Plugin Name: Category Filter Ajax. 2.0.1
*/
add_action('wp_ajax_cfilter', 'cfilter_ajax_load', 99);
add_action('wp_ajax_nopriv_cfilter', 'cfilter_ajax_load', 99);	

add_action( 'init', function() { 
  reg_cfilter_ajax_load( 'cfilter_ajax_load', 'cfilter_ajax_load' ); 
} );
add_shortcode('cfilter-product-cat', 'cfilter_load_products');

function reg_cfilter_ajax_load( $callable, $action ) {

  if ( empty( $_POST['action'] ) || $_POST['action'] != $action )
    return;

  call_user_func( $callable );
}

function cfilter_load_products(){
	




	$products = wc_get_products( array(  // array of filtered products
		'category'		=> array( 'iphone' ),  // iphone , ipad , samsung-smartphones
		'tag'			=> array( 'broken-screen' ),
		'limit'			=> -1,
		'return'		=> 'ids'
	));
	?>

	<div class="quick-nav"> 
     	<div class="quick-nav-row quick-nav-category">
	     	<a href="#" class="label"><div class="icon2-iphoneg"></div>iPhone</a>
	     	<a href="#" class="label"><div class="icon2-ipadg"></div>iPad</a>
	     	<a href="#" class="label"><div class="icon2-galaxys10"></div>Samsung</a>
     	</div>
     	<div class="quick-nav-row quick-nav-content-1"></div>
     	<div class="quick-nav-row quick-nav-category">
	     	<a href="#" class="label"><div class="icon2-macg"></div>Computer</a>
	     	<a href="#" class="label"><div class="icon2-macg"></div>Other</a>
     	</div>
     	<div class="quick-nav-row quick-nav-content-2"></div>
     </div>

     	<?php
}

function cfilter_ajax_load(){

	$show_cat = esc_attr($_POST['show_cat']);

	$taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no
    $title        = '';
    $empty        = 1;

	$icons = array(
        'iphone' => 'icon2-iphoneg',
        'ipad' => 'icon2-ipadg',
        'samsung' => 'icon2-galaxys10',
        'computer' => 'icon2-macg',
        'other' => 'icon2-ps4',
    );

    $args = array(
             'taxonomy'     => $taxonomy,
             'orderby'      => $orderby,
             'show_count'   => $show_count,
             'pad_counts'   => $pad_counts,
             'hierarchical' => $hierarchical,
             'title_li'     => $title,
             'hide_empty'   => $empty
    );
    $all_categories = get_categories( $args );

    foreach ($all_categories as $cat) {
	        if( $cat->name == $show_cat ) {
	            $category_id = $cat->term_id;
	            
	            $args2 = array(
	                'taxonomy'     => $taxonomy,
	                'child_of'     => 0,
	                'parent'       => $category_id,
	                'orderby'      => $orderby,
	                'show_count'   => $show_count,
	                'pad_counts'   => $pad_counts,
	                'hierarchical' => $hierarchical,
	                'title_li'     => $title,
	                'hide_empty'   => $empty
	        	);
	
		        $sub_cats = get_categories( $args2 );
		        if($sub_cats) {
		          	echo '<ul class="quick-nav-content">';
		            foreach($sub_cats as $sub_category) {
		                $thumbnail_id = get_term_meta( $sub_category->term_id, 'thumbnail_id', true );
		                echo  '<li class="quick-nav-content-item"><a href="'. get_term_link($sub_category->slug, 'product_cat') .'"><span class="icon2-iphoneg"></span> '. $sub_category->name .'</a>';
		            }
	            }
	        }
    }

	wp_die();
}

add_action('wp_enqueue_scripts', 'my_assets');
function my_assets() {
	wp_enqueue_script('ajax-test', plugins_url('cfilter-script.js', __FILE__), array('jquery'));
	wp_localize_script('ajax-test', 'myPlugin',array(
		'ajaxurl' => admin_url('admin-ajax.php')
	));
	wp_register_style('cfilter_plugin_style', plugin_dir_url(__FILE__) . 'cfilter-styles.css');
    wp_enqueue_style('cfilter_plugin_style');
}
?>