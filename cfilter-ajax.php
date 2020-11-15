<?php  

/**
* Plugin Name: Category Filter Ajax. a
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
	<div class="cfilter_container woocommerce">
        <div id="nav-holder">
            <div class="cfilter_category_nav" id="cfilter_tabs">
            	<ul>
            		<li><a id="iphone" class="product active" href="#">iPhone</a></li>
               		<li><a id="samsung-smartphones" class="product" href="#" >Samsung</a></li>
              		<li><a id="ipad" class="product" href="#">iPad</a></li>
               	</ul>
            </div>
        </div>
        <div id="cfilter-products" class="product-content"></div>
    </div>
    <?php

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
     ?> <div class="quick-nav"> <?php
     foreach ($all_categories as $cat) {
        if($cat->category_parent == 0) {
            $category_id = $cat->term_id;
            echo '<a href="#" class="label"><div class="'. $icons[strtolower($cat->name)] .'"></div>'. $cat->name .'</a>';

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
            echo '<div class="nav-sub-cat '.  strtolower($cat->name) .' ">';
                foreach($sub_cats as $sub_category) {
                    $thumbnail_id = get_term_meta( $sub_category->term_id, 'thumbnail_id', true );
                    $image_url = wp_get_attachment_url( $thumbnail_id );
                    echo  '<br/><a href="'. get_term_link($sub_category->slug, 'product_cat') .'"><img src="'. $image_url .'"> '. $sub_category->name .'</a>';
                }

            ?> </div>
             <?php
            }
        }
    }
    ?> </div> <?php
}

function cfilter_ajax_load(){
	
	$per_page = 6;

	if (empty( $_POST['paged'])) {   // ajax post request data
		$paged   = 0;
	} else {
		$paged = $per_page*(esc_attr($_POST['paged'])-1);
	}
		if (empty( $_POST['show_cat'] )){
		$show_cat = 'iphone';
	} else {
		$show_cat = esc_attr($_POST['show_cat']);
	}

	$products = wc_get_products( array(  // array of filtered products
		'category'		=> array( $show_cat ),  // iphone , ipad , samsung-smartphones
		'tag'			=> array( 'broken-screen' ),
		'limit'			=> -1,
		'orderby' => 'modified',
    	'order' => 'ASC',
		'return'		=> 'ids'
	));


	if($products) {
			if($paged == 0){
				$prev_page = esc_attr($_POST['paged']);
				$next_page = esc_attr($_POST['paged']) + 1;
			} elseif ($paged>=count($products)-5) {
				$prev_page = esc_attr($_POST['paged']) - 1;
				$next_page = esc_attr($_POST['paged']);
			} else {
				$prev_page = esc_attr($_POST['paged']) - 1;
				$next_page = esc_attr($_POST['paged']) + 1;
			}
		?>
		<nav class="woocommerce-pagination">
			<ul class="page-numbers">
					<li><a class="page-numbers" href="#" onclick="send_page(<?php echo $prev_page; ?>)">Prev</a></li>
					<li><a class="page-numbers" href="#" onclick="send_page(<?php echo $next_page; ?>)">Next</a></li>
			</ul>
		</nav> <?php
		do_action('woocommerce_before_shop_loop');
		woocommerce_product_loop_start();
		for( $i=$paged ; $i < $paged + $per_page && $i <= count($products) ; $i++){
			$post_object = get_post($products[$i]);
			setup_postdata($GLOBALS['post'] =& $post_object);
			wc_get_template_part('content', 'product');
		}

		wp_reset_postdata();
		woocommerce_product_loop_end();
		do_action('woocommerce_after_shop_loop');
		?>
		<nav class="woocommerce-pagination">
			<ul class="page-numbers">
					<li><a class="page-numbers" href="#" onclick="send_page(<?php echo $prev_page; ?>)">Prev</a></li>
					<li><a class="page-numbers" href="#" onclick="send_page(<?php echo $next_page; ?>)">Next</a></li>
			</ul>
		</nav>

               	<?php

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