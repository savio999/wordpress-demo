<?php

$post_type = get_post_type();

if (empty($post_type)) {
 $post_type = 'post';
}

$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$utx= get_query_var('taxonomy');
$term_id_array = array();
if( !empty($term)) {
	array_push($term_id_array, $term->term_id);	
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
							if (!empty($term)) {
								$query = new WP_Query(
									array(
										'paged'			 => $page,
										'tax_query'      => array( 
											array(
												'taxonomy' => $term->taxonomy,
												'terms'    => $term_id_array,									
											)
										)
									)
								);
							} else{
								$query = new WP_Query(
									array(
										'paged'     => $page,
										'post_type' => $post_type
									)
								);
							}
								$all_size_terms = array();
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
													  $brand_terms = get_the_terms($post->ID,'brands');				  
													  $brand = "Brands: ";
													  foreach($brand_terms as $t) {
													  	$brand .= $t->name." ";	
													  }

													?>
													<li><?php echo $brand ?></li>
													<?php
													  $size_terms = get_the_terms($post->ID,'sizes');			  
													  $size = "Sizes: ";
													  foreach($size_terms as $t) {
													  	$size .= $t->name." ";	
													  	array_push($all_size_terms, $t);
													  }
													?>

													<li><?php echo $size ?></li>
												</ul>
												<div class="entry-content">
													<?php the_excerpt(); ?>
													<a href="<?php the_permalink(); ?>" class="more-link">Read More</a>
												</div>
											<?php
											}
											$uniq_all_size_terms = array_unique($all_size_terms, SORT_REGULAR);
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
					<?php
					if (!empty($term)) {
					?>
						<input type="hidden" id="term_id" value="<?php echo $term->term_id ?>"/>
						<input type="hidden" id="term_arr" value = "<?php echo serialize($term_id_array) ?>"/>
					<?php
					}else{
					?>					
						<input type="hidden" id="term_id" value=""/>
						<input type="hidden" id= "url_tax" value=""/>
					<?php
					}					
					?>
					<input type="hidden" id= "post_type" value="<?php echo $post_type ?>"/>
					<input type="hidden" id= "url_tax" value=" <?php echo $utx ?>"/>
					
				</div><!-- .postcontent end -->

				<?php get_sidebar(); ?>

			</div>

		</div>

	</section><!-- #content end -->

<?php get_footer(); ?>