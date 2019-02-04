<?php

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

if(is_search()){ 
	$search_term = $_GET['s'];
	$ids = array();
	$q1 = "";
	$q2 = "";
	$arr1 = array();
	$arr2 = array();

	// get for title
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => 10,
		's' => $search_term
	);	

	$q1 = get_posts($args);

	for($i=0; $i < count($q1); $i++){
		array_push($arr1, $q1[$i]->ID);
	}

	//sku
	$args2 = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => 10,
		'meta_query' => array(
			array(
				'key' => '_sku',
				'value' => $search_term,
				'compare'=> 'LIKE'
			)
		)
	);

	$q2 = get_posts($args2);

	for($i=0; $i < count($q2); $i++){
		array_push($arr2, $q2[$i]->ID);
	}

	$arr3 = array_merge($arr1, $arr2);
	$arr3 = array_unique($arr3);

	$args3 = array(
		'post_type' => 'product',
		'post__in'  => $arr3
	);

	$query = new WP_Query($args3);

?>
	<h3 style="text-align:center;">Search for: '<?php echo $search_term; ?>'</h3>
<?php



if ( $query->have_posts() ) {


	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	while ( $query->have_posts() ) {
		$query->the_post();
		do_action( 'woocommerce_shop_loop' );
		wc_get_template_part( 'content', 'product' );
	}
	

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );
} else {

	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );

} else { // archive or shop
$obj = get_queried_object();
$taxonomy = "no_taxonomy";
$term = "no_term";
$args = array(
	'paged' => 1,
	'post_type' => 'product',
	'orderby' => 'title',
	'order' => 'ASC',
	'post_status' => 'publish',
	'tax_query'   => array()
);

if(isset($obj->taxonomy) && !(empty($obj->taxonomy)) && isset($obj->term_id) && !(empty($obj->term_id))) {
	$taxonomy = $obj->taxonomy;
	$term = $obj->term_id;
	$tax = array(
			'taxonomy' => $taxonomy,
			'terms'    => $term 
	);
	array_push($args['tax_query'], $tax);
}

$query = new WP_Query($args); 



do_action( 'woocommerce_before_main_content' );



?>
<div class="col-sm-3">
	<?php

	do_action( 'woocommerce_sidebar' );
	?>
</div>

<div class="col-sm-9">
<?php
if ( $query->have_posts() ) {


	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	while ( $query->have_posts() ) {
		$query->the_post();
		do_action( 'woocommerce_shop_loop' );
		wc_get_template_part( 'content', 'product' );
	}
	

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );
} else {

	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );
?>

</div>

<input type="hidden" name="clean_div" id="clean_div" value="0"/>
<input type="hidden" name="taxonomy" id="taxonomy" value="<?php echo $taxonomy; ?>"/>
<input type="hidden" name="term" id="term" value="<?php echo $term; ?>"/>
<input type="hidden" name="is_shop" id="is_shop" value="<?php echo (is_shop())?1:0 ?>"/>
<?php
}
get_footer( 'shop' );
