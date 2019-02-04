<?php

/*
  Template name: Page without sidebar
  Template post type: page
*/

 get_header(); ?>

 <!--page-title-->
 <?php if (have_posts()) { 
    the_post();
?>
       <section id="page-title">

        <div class="container clearfix">
            <h1><?php _e('Privacy policy','demo_theme'); ?></h1>
        </div>

    </section><!-- #page-title end -->
<?php
 }
 rewind_posts();
 ?>
    <!-- Content
    ============================================= -->
    <section id="content">

        <div class="content-wrap" style="padding-top:33px;">
            <div class="container clearfix">

                <!-- Post Content
                ============================================= -->
                <div class="nobottommargin clearfix">

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

                                            <div class="entry-content" style="margin-top:0px;text-align:justify;">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                            <?php
                                }
                            }
                        ?>

                   </div><!-- #posts end -->
                </div><!-- .postcontent end -->

            </div>

        </div>

    </section><!-- #content end -->

<?php get_footer(); ?>