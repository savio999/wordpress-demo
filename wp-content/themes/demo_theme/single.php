
<?php get_header() ?>

<!-- Content
============================================= -->
  <section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="single-post nobottommargin">

                <!-- Single Post
                ============================================= -->
                <div class="entry clearfix">
                <?php 
                    $current_post_id = 0;
	                if (have_posts()) {
	                	while (have_posts()) {
	                		the_post();
                                        echo '<h3>' . get_the_title() .'</h3>';  

    echo url_to_postid("http://localhost/wp_demo/this-is-title-via-rest-2/");
    exit;
                                        the_content(); 
                                }
                        }
                        $no = get_option('posts_per_page'); 
                        echo $no;
                ?>
                </div><!-- .entry end -->

                </div><!-- .postcontent end -->

                <!-- Sidebar
                    ============================================= -->
                <?php get_sidebar(); ?>

            </div>

    </section><!-- #content end -->

<?php get_footer() ?>