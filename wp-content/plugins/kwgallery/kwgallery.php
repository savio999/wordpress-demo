<?php
   /*
   Plugin Name: kw gallery
    */

   //create gallery otype
   function kwg_create_gallery_postype() {
      register_post_type('gallery_post',
         array(
            'labels' => array(
                'name'          => __('Gallery Posts') ,
                'singular_name' => __('Gallery Post')
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'gallery'),
            'supports'    => array('title', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
            'rest_base'          => 'gallery_api'
         )
      );
   }

   add_action('init', 'kwg_create_gallery_postype');

   function kwg_gallery_plugin_install() {
      kwg_create_gallery_postype();
      flush_rewrite_rules();
   }

   register_activation_hook(__FILE__, 'kwg_gallery_plugin_install');

   //uninstall
   function kwg_gallery_plugin_uninstall() {
      unregister_post_type('gallery_post');
      flush_rewrite_rules();
   }

   register_deactivation_hook(__FILE__,'kwg_gallery_plugin_uninstall');

   //add subtitle field
   function kwg_custom_box_html($post) {
      $value = get_post_meta($post->ID, 'kwg_subtitle', true);
      ?>
      <input type="text" name="kwg_subtitle" id="kwg_subtitle" value="<?php echo $value ?>" placeholder="Enter subtitle here"/>
      <?php
   }

    //placing metabox
   function kwg_subtitle( $post_type ) {
      add_meta_box(
         'subtitle_meta',
         'Subtitle',
        'kwg_custom_box_html',
         array('gallery_post'),
            'test', 
           'high'
         );
   }

add_action('add_meta_boxes', 'kwg_subtitle');

function move_subtitle_metabox() {
   //Get the globals:
   global $post, $wp_meta_boxes;

   //Output the "advanced" meta boxes:
   do_meta_boxes( get_current_screen(), 'test', $post );

   //Remove the initial "advanced" meta boxes:
   unset($wp_meta_boxes['post']['test']);
}

add_action('edit_form_after_title', 'move_subtitle_metabox');


//adding styles for metabox
function metabox_styles() {
   wp_enqueue_style('admin_styles',plugin_dir_url(__FILE__) . 'metabox.css');   
}

add_action('admin_enqueue_scripts','metabox_styles');


   //saving subtitle field
   function kwg_save_subtitle_field($post_id) {
      if(array_key_exists('kwg_subtitle', $_POST)) {
         update_post_meta(
            $post_id,
            'kwg_subtitle',
            $_POST['kwg_subtitle']
         );
      }

   }

   add_action('save_post', 'kwg_save_subtitle_field');

   // add shortcode
   function kwg_shortcodes_init() {
      function kwg_shortcode($atts = [], $content = null) {
         $atts = shortcode_atts(array(
            'subtitle'    => true,
            'description' => true
         ), $atts);

         $atts = array_change_key_case((array)$atts, CASE_LOWER);        
         $retSlider = kwg_make_slider($atts);

         return $retSlider;
      }
      add_shortcode('kwgallery', 'kwg_shortcode');      
   } 

   add_action('init', 'kwg_shortcodes_init');

   //include scripts and styles required for slider
   function kwg_load_scripts_styles() {
      wp_enqueue_style('kwg_slider_style', plugin_dir_url(__FILE__)."slider_style.css");
      wp_enqueue_script('kwg_slider_script',plugin_dir_url(__FILE__)."slider_script.js", array(),false,true);
   }

   add_action('wp_enqueue_scripts', 'kwg_load_scripts_styles');

   //build slider
   function kwg_make_slider($atts=[]) { 
      $count = 0;
      $query = new WP_Query(
         array(
            'post_type' => 'gallery_post',
            'posts'     => -1,
            'orderby'   => 'date',
            'order'     => 'asc'
         )
      );

      if ($query->have_posts()) { 
         ?>
            <!-- Slideshow container -->
             <div class="slideshow-container"> 
             <?php      
              while($query->have_posts()) {                  
                  $query->the_post();
               ?> 

                 <!-- Full-width images with number and caption text -->
                 <div class="mySlides fades" <?php echo ($count == 1)? "style='display:block;'":''?> onclick="window.location.href='<?php the_permalink() ?>'">
                  <?php

                     if (has_post_thumbnail()) {
                        the_post_thumbnail('large');
                     }
                  ?>
               
                     <div class="caption_text">
                        <h1 class="color_css"><?php the_title() ?></h1>
                        <?php
                           if($atts['subtitle'] == 1) {
                              $custom_fields = get_post_custom($query->post->ID);
                              
                              if (!empty($custom_fields)) {
                                 echo "<h4 class='color_css'>" . $custom_fields['kwg_subtitle'][0] . "</h4>";
                              }
                           }
                           if($atts['description'] == 1) {
                        ?>
                           <p class="desc"><?php echo stripslashes(get_the_excerpt()); ?></p>
                     <?php } ?>
                     </div>
                  </div>
             <?php
               
              }
             ?>
             <!-- Next and previous buttons -->
              <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
              <a class="next" onclick="plusSlides(1)">&#10095;</a>

              <?php             
                  wp_reset_postdata();   
               ?>
            <br> 

             <!-- The dots/circles -->
              <div style="text-align:center">
                 <?php
                    for($i = 1; $i <= $count; $count++) {
                 ?>
                       <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span> 
                 <?php
                    }
                 ?>
              </div>
         </div>
      <?php
      }

      $res = ob_get_clean();  
       return $res;
   }

   add_action( 'rest_api_init', 'dt_subtitle_register' );
    function dt_subtitle_register() {
        global $wpdb;
        $meta_keys = $wpdb->get_results("SELECT distinct(meta_key) FROM wp_postmeta pm JOIN wp_posts p ON p.ID = pm.post_id WHERE p.post_status = 'publish' AND p.post_type = 'gallery_post'");
        foreach($meta_keys as $obj) {
            register_rest_field( 'gallery_post',
            $obj->meta_key,
            array(
                'get_callback'    => 'subtitle_get_call',
                'update_callback' => null,
                'schema'          => null,
            )
        );
        }
    }

function subtitle_get_call( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], $field_name, true );
}
?>