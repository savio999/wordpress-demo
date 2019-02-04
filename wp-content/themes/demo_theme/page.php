
<?php get_header(); ?>

 <!--page-title-->
 <?php if (have_posts()) { 
    the_post();

    if(function_exists(is_cart())) {
    
    if( !is_cart()) {
?>
       <section id="page-title">

            <div class="container clearfix">
                <h1><?php the_title() ?></h1>
            </div>

       </section><!-- #page-title end -->
<?php
     }
    }
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
                <div class="<?php                 
                        if(function_exists(is_cart())) {
                            if(! is_cart()) {
                                echo "postcontent"; 
                            }}?>nobottommargin clearfix">

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

                                            <div class="entry-content" style="margin-top:0px;">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                            <?php
                                }
                            }
                        ?>

                   </div><!-- #posts end -->
                </div><!-- .postcontent end -->

                <?php 
                    get_sidebar(); 
                ?>
            </div>

        </div>

    </section><!-- #content end -->

<?php get_footer(); ?>