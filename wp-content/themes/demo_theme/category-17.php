
<?php get_header(); ?>
    <section id="page-title">

        <div class="container clearfix">
            <h1><?php the_archive_title() ?></h1>
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
							if (have_posts()) {
								while (have_posts()) {
									the_post();
									?>
										<div class="entry clearfix">
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

											<div class="entry-title">
												<h2><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>
											</div>
											<ul class="entry-meta clearfix">
												<li><i class="icon-calendar3"></i><?php the_date(); ?></li>
												<li><a href="<?php echo get_author_posts_url(get_the_author_meta('ID'))?>"><i class="icon-user"></i> admin</a></li>
												<li><i class="icon-folder-open"></i><?php the_category(' '); ?></li>
												<li><a href="<?php the_permalink(); ?>#comments"><i class="icon-comments"></i> <?php comments_number('0'); ?></a></li>
											</ul>
											<div class="entry-content">
												<?php the_excerpt(); ?>
												<a href="<?php the_permalink(); ?>" class="more-link">Read More</a>
											</div>
										</div>
							<?php
								}
							}							
						?>

						<div>
							<div id="appended_div_posts"></div>							
							<input type="hidden" id="current_page" value="1"/>
							<div class="text-center">
								<button type="button" id="btnLoadMore" class="btn btn-danger" onclick="loadMorePosts();" style="display:none;">Load More</button>
								<span class='fa fa-spinner fa-spin fa-3x' style="display:none" id="spinner"></span>
							</div>							
							<div id="displayMore"></div>
						</div>



					</div><!-- #posts end -->

					
				</div><!-- .postcontent end -->

				<?php get_sidebar(); ?>

			</div>

		</div>

	</section><!-- #content end -->

<?php get_footer(); ?>