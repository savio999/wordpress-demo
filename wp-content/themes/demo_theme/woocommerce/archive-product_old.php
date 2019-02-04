<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if( is_shop() ){
	$query = new WP_Query(array(
		'paged' => 1,
		'post_type' => 'product',
		'orderby' => 'title',
		'order' => 'ASC'
	));

}

do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php
	$obj = get_queried_object();
	$taxonomy = "no_taxonomy";
	$term = "no_term";
	if(isset($obj->taxonomy) && !(empty($obj->taxonomy))) {
			$taxonomy = $obj->taxonomy;
			$term = $obj->term_id;
	}
	?>

	<input type="hidden" name="taxonomy" id="taxonomy" value="<?php echo $taxonomy; ?>"/>
	<input type="hidden" name="term" id="term" value="<?php echo $term; ?>"/>
	<input type="hidden" name="clean_div" id="clean_div" value="0"/>

	<?php


	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div class="col-sm-3">
	<?php

	do_action( 'woocommerce_sidebar' );
	?>
</div>
<div class="col-sm-9" id="products_div">
<?php
if ( woocommerce_product_loop() ) {

	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );
} else {

	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );
?>
</div>
<?php
get_footer( 'shop' );

