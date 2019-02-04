<?php

/*Template name: Product listing*/

$products_qry = get_terms(array(
				'hide_empty' => true,
				'taxonomy'	 => 'product'
			));

$products = array();

foreach ($products_qry as $p) {
	array_push($products, $p->term_id);
}


$page = 1;


 get_header(); 
 ?>

    <section id="page-title">

        <div class="container clearfix">
            <h1>Products</h1>
        </div>

    </section>
	<!-- Content
    ============================================= -->
	<section id="content">

		<div class="content-wrap">

			<div class="container clearfix">

				<!-- Post Content
                ============================================= -->
				<div class="postcontent nobottommargin clearfix">

					<!-- Posts
                    ============================================= -->
					<div id="posts">
						<?php
							$query = new WP_Query(
								array(
									'paged'			 => $page,
									'tax_query'      => array( 
										array(
											'taxonomy' => 'product',
											'terms'    => $products,
											'operator' => 'EXISTS'
										)
									)
								)
							);
								if ($query->have_posts()) { 
									while ($query->have_posts()) {
										$query->the_post();
								?>
											<div class="entry clearfix" style="margin-bottom:0px; padding-bottom:0px; margin-top:25px;">
												<?php
													if (has_post_thumbnail()) {
												?>
													<div class="entry-image">
														<a href="<?php the_permalink(); ?>" data-lightbox="image">
															<?php the_post_thumbnail('full'); ?>
														</a>
													</div>
												<?php
													}
												?>
													<h3><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
												</div>
												<ul class="entry-meta clearfix">
													<?php
													  $terms = get_the_terms($post->ID,'product');
													  $brand = "";
													  foreach($terms as $term) {
													  	$brand .= $term->name." ";	
													  }
													?>
													<li><?php echo $brand ?></li>
												</ul>
												<div class="entry-content">
													<?php the_excerpt(); ?>
													<a href="<?php the_permalink(); ?>" class="more-link">Read More</a>
												</div>
										<?php
										}
										?>


									<div>
										<div id="appended_div_posts"></div>							
										<input type="hidden" id="current_page" value="1"/>
											<div class="text-center">
												<!--<button type="button" id="btnLoadMore" class="btn btn-danger" onclick="loadPostsFilter();">Load More</button>-->
												<span class='fa fa-spinner fa-spin fa-3x' style="display:none" id="spinner"></span>
											</div>							
											<div id="displayMoreFilter"></div>
									</div>
								<?php
								
								}
								?>

					</div><!-- #posts end -->

					
				</div><!-- .postcontent end -->

				<?php get_sidebar(); ?>

			</div>

		</div>

	</section><!-- #content end -->

<?php get_footer(); ?>