<?php

function dt_enqueue() {
		wp_register_style('dt_google_fonts', "http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic");
		wp_register_style('dt_font_awesome',"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css");
		wp_register_style('dt_bootstrap', get_template_directory_uri()."/assests/css/bootstrap.css");
		wp_register_style('dt_style', get_template_directory_uri()."/style.css");
		wp_register_style('dt_dark', get_template_directory_uri()."/assests/css/dark.css");
		wp_register_style('dt_fonts', get_template_directory_uri()."/assests/css/font-icons.css");
		wp_register_style('dt_animate', get_template_directory_uri()."/assests/css/animate.css");
		wp_register_style('dt_popup', get_template_directory_uri()."/assests/css/magnific-popup.css");
		wp_register_style('dt_responsive', get_template_directory_uri()."/assests/css/responsive.css");
		wp_register_style('dt_custom', get_template_directory_uri()."/assests/css/custom.css");
		wp_register_style('dt_woocommerce', get_template_directory_uri()."/assests/css/woocommerce.css");
		wp_register_style('dt_cart_css', get_template_directory_uri()."/assests/css/cart.css");

		wp_enqueue_style('dt_google_fonts');
		wp_enqueue_style('dt_font_awesome');
		wp_enqueue_style('dt_bootstrap');
		wp_enqueue_style('dt_style');
		wp_enqueue_style('dt_dark');
		wp_enqueue_style('dt_fonts');
		wp_enqueue_style('dt_animate');
		wp_enqueue_style('dt_popup');
		wp_enqueue_style('dt_resposive');
		wp_enqueue_style('dt_custom');
		wp_enqueue_style('dt_woocommerce');		
		
		//load only cart css
		if ( function_exists( 'is_woocommerce' ) ) {
	        if( is_page(array( 'cart' ) )) {
	            wp_enqueue_style('dt_cart_css');
	        }
	    }		

		wp_register_script('dt_plugins', get_template_directory_uri()."/assests/js/plugins.js", array(), false, true);
		wp_register_script('dt_functions', get_template_directory_uri()."/assests/js/functions.js", array(), false, true);
		wp_register_script('dt_load_more', get_template_directory_uri()."/assests/js/load_more.js", array(), false, true);
		wp_register_script('dt_waypoint', get_template_directory_uri()."/assests/waypoint/lib/jquery.waypoints.js", array(), false, true);
		wp_register_script('dt_product_filter', get_template_directory_uri()."/assests/js/product_filter.js", array(), false, true);
		wp_register_script('dt_woocommrce_js', get_template_directory_uri()."/assests/js/woocommerce.js", array(), false, true);
		wp_register_script('dt_search_products', get_template_directory_uri()."/assests/js/product_search.js", array(), false,true);
                

		$load_array = array('admin_ajax_url'=>admin_url('admin-ajax.php'));
		$filter_array = array('redirect_url'=> site_url()."/products-filter", 'admin_ajax_url'=>admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wp_rest'));
		$wc_array = array('admin_ajax_url'=>admin_url('admin-ajax.php'));	

		wp_localize_script('dt_load_more', 'loadMoreObj', $load_array);
		wp_localize_script('dt_product_filter', 'prodfiltr', $filter_array);
		wp_localize_script('dt_woocommrce_js', 'varObj', $wc_array);
		wp_localize_script('dt_search_products','varObj',$wc_array);
                

		wp_enqueue_script('jquery');
		wp_enqueue_script('dt_plugins');
		wp_enqueue_script('dt_functions');
		wp_enqueue_script('dt_load_more');
		wp_enqueue_script('dt_waypoint');
		wp_enqueue_script('dt_product_filter');
		wp_enqueue_script('dt_woocommrce_js');
		wp_enqueue_script('dt_search_products');
                
               
	}

add_action('wp_enqueue_scripts', 'dt_enqueue');

function dt_admin_enqueue_scripts(){
    $site_url_array = array('site_url'=> get_option('siteurl'),'admin_ajax_url'=>admin_url('admin-ajax.php'));
    wp_register_script('dt_admin', get_template_directory_uri()."/assests/js/admin.js", array(), false,true);
    wp_localize_script('dt_admin','url_link',$site_url_array);
    wp_enqueue_script('dt_admin');
}

add_action('admin_enqueue_scripts', 'dt_admin_enqueue_scripts');


/*add menus, post thumbnails and title tag*/
function dt_aftr_theme_loaded() {
	register_nav_menu('primary', __('primary', 'demo_theme'));
	register_nav_menu('secondary', __('secondary', 'demo_theme'));
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
	add_theme_support('automatic-feed-links');
	add_theme_support('woocommerce');
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
}
add_action('after_setup_theme', 'dt_aftr_theme_loaded');

//sidebar widget
function dt_widgets() {
	register_sidebar(
		array(
			'name'          => __('Sidebar', 'demo_theme'),
			'id'   		    => 'demo_siebar',
			'description'   => __('This is demo sidebar', 'demo_theme'),
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 style="margin:30px 0px 10px">',
			'after_title'   => '</h3>'
		));
}
add_action('widgets_init', 'dt_widgets');

/*theme customizer*/

function dt_customize_register( $wp_customize ) {
	$wp_customize->add_setting('dt_facebook_handle', array(
		'default'   => ''
	));

	$wp_customize->add_setting('dt_twitter_handle', array(
		'default' => ''
	));

	$wp_customize->add_setting('dt_instagram_handle', array(
		'default' => ''
	));

	$wp_customize->add_setting('dt_phone_handle', array(
		'default' => ''
	));

	$wp_customize->add_setting('dt_email_handle', array(
		'default' => ''
	));

	$wp_customize->add_section('dt_social_section', array(
		'title'    => __('Social Settings', 'dt_theme'),
		'priority' =>  30,
		'panel'     => 'demo_panel'
	));

	$wp_customize->add_control( new WP_Customize_Control( 
		$wp_customize, 
		'dt_social_facebook_input', 
		array(
			'label'      => __( 'Facebook', 'dt_theme' ),
			'section'    => 'dt_social_section',
			'settings'   => 'dt_facebook_handle',
		)
	));

	$wp_customize->add_control( new WP_Customize_Control( 
		$wp_customize, 
		'dt_social_twitter_input', 
		array(
			'label'      => __( 'Twitter', 'dt_theme' ),
			'section'    => 'dt_social_section',
			'settings'   => 'dt_twitter_handle',
		)
	));

	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_social_instagram_input', 
		array(
			'label'      => __( 'Intagram', 'dt_theme' ),
			'section'    => 'dt_social_section',
			'settings'   => 'dt_instagram_handle',
		)
	));

	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_social_phone_input', 
		array(
			'label'      => __( 'Phone', 'dt_theme' ),
			'section'    => 'dt_social_section',
			'settings'   => 'dt_phone_handle',
		)
	));

	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_social_email_input', 
		array(
			'label'      => __( 'Email', 'dt_theme' ),
			'section'    => 'dt_social_section',
			'settings'   => 'dt_email_handle',
		)
	));

	$wp_customize->add_setting('dt_header_show_search', array(
		'default'    => 'yes',
		'transport'  => 'postMessage'
	));

	$wp_customize->add_setting('dt_footer_copyright_text', array(
		'default' => 'Copyrights &copy; 2018 All Rights Reserved'
	));

	$wp_customize->add_setting('dt_footer_tos_page', array(
		'default' => 0
	));

	$wp_customize->add_setting('dt_footer_privacy_page', array(
		'default' => 0
	));

	$wp_customize->add_setting('dt_header_show_cart', array(
		'default'    => 0,
		'transport'  => 'postMessage'
	));

	$wp_customize->add_section('dt_misc_section', array(
		'title'    => __('Misc Settings', 'dt_theme'),
		'priority' =>  30,
		'panel'    => 'demo_panel'
	));

	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		'dt_header_show_search_input',
		array(
			'label'     => __('Show Search Button in Header', 'dt_theme'),
			'section'   => 'dt_misc_section',
			'settings'  => 'dt_header_show_search',
			'type'      => 'checkbox',
			'choices'   => array(
				'Yes' => __('Yes', 'dt_theme')
			)
		)

	));

	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize,
		'dt_header_show_cart_input',
		array(
			'label'     => __('Show Cart Button in Header', 'dt_theme'),
			'section'   => 'dt_misc_section',
			'settings'  => 'dt_header_show_cart',
			'type'      => 'checkbox',
			'choices'   => array(
				'Yes' => __('Yes', 'dt_theme')
			)
		)

	));

	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_footer_copy_text_input', 
		array(
			'label'      => __( 'Copyright Text', 'dt_theme' ),
			'section'    => 'dt_misc_section',
			'settings'   => 'dt_footer_copyright_text'
		)
	));


	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_footer_tos_text_input', 
		array(
			'label'      => __( 'TOS page', 'dt_theme' ),
			'section'    => 'dt_misc_section',
			'settings'   => 'dt_footer_tos_page',
			'type'       => 'dropdown-pages'
		)
	));

	$wp_customize->add_control( new WP_Customize_Control( 
	$wp_customize, 
	'dt_footer_privacy_text_input', 
		array(
			'label'      => __( 'Privacy page', 'dt_theme' ),
			'section'    => 'dt_misc_section',
			'settings'   => 'dt_footer_privacy_page',
			'type'       => 'dropdown-pages'
		)
	));

	$wp_customize->add_panel(
		'demo_panel', array(
			'title'       => __('Demo Panel', 'demo_theme'),
			'description' => __('Theme Settings', 'demo_theme'),
			'priority'    => 160
		)
	);

    $wp_customize->get_section('title_tagline')->title = 'General';

}

function mytheme_customizer_live_preview()
{
	wp_enqueue_script( 
		  'mytheme-themecustomizer',			//Give the script an ID
		  get_template_directory_uri().'/assests/js/theme-customizer.js',//Point to file
		  array( 'jquery','customize-preview' ),	//Define dependencies
		  '',						//Define a version (optional) 
		  true						//Put script in footer?
	);
}

add_action( 'customize_preview_init', 'mytheme_customizer_live_preview' );
add_action('customize_register', 'dt_customize_register');


//ajax
add_action('wp_ajax_fetch','fetch_posts');
add_action('wp_ajax_nopriv_fetch','fetch_posts');


//custom post type: products
function create_post_type() {
  register_post_type( 'products',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'products'),
      'show_in_rest' => true,
      'rest_base' => 'products',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
    )
  );
}
add_action( 'init', 'create_post_type' );


/*register new taxonomies for products*/
function add_products_brand_taxonomy() {
		register_taxonomy('brands','products',array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x('Brands', 'Taxonomy general name'),
				'singular_name'     => _x('Brand', 'Taxonomy single name'),
				'search_items'      => __('Search Brands'),
				'all_items'         => __('All Brands'),
				'parent_item'       => __('Parent Brand'),
				'parent_item_colon' => __('Parent Brand: '),
				'edit_item'         => __('Edit Brand'),
				'update_item'       => __('Update Brand'),
				'add_new_item'      => __('Add new Brand'),
				'new_item_name'     => __('New brand name'),
				'menu_name'         => __('Brands')
			),
			'rewrite'      => array(
				'slug'         => 'brands',
				'with_front'   => true,
				'hierarchical' => true
			),
		));
	}

	function add_products_size_taxonomy() {
		register_taxonomy('sizes','products',array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x('Sizes', 'Taxonomy general name'),
				'singular_name'     => _x('Size', 'Taxonomy single name'),
				'search_items'      => __('Search Size'),
				'all_items'         => __('All Sizes'),
				'parent_item'       => __('Parent Size'),
				'parent_item_colon' => __('Parent Size: '),
				'edit_item'         => __('Edit Size'),
				'update_item'       => __('Update Size'),
				'add_new_item'      => __('Add new Size'),
				'new_item_name'     => __('New Size name'),
				'menu_name'         => __('Sizes')
			),
			'rewrite'      => array(
				'slug'         => 'size',
				'with_front'   => true,
				'hierarchical' => true
			),
		));
	}
add_action('init', 'add_products_brand_taxonomy');
add_action('init','add_products_size_taxonomy');

/*filter widget*/
	function dt_product_filter() { 	
		//$obj = parse_str($_POST['data']); var_dump($_POST); echo json_last_error();exit;
		$products = $_POST['products']; 
		$post_type = "products";
		$taxonomy = $_POST['tax_active']; 
		$term_id = $_POST['term_id']; 
		$url_tax = get_term($term_id)->taxonomy;

	$page = 1;

	if (isset($_POST['page']) && (!empty($_POST['page']))){
		$page = $_POST['page'];
	}

	if(empty($products)) {
			if (!empty($url_tax)) { 
					$query = new WP_Query(
					array(
						'paged'			 => $page,
						'post_type'      => $post_type,
						'tax_query'      => array( 
							array(
								'taxonomy' => $url_tax,
								'terms'    => array($term_id),
								'operator' => 'IN'
	 						)
						)
					)
				);


			} else {
				$query = new WP_Query(
					array(
						'paged'     => $page,
						'post_type' => $post_type
					)
					);
			}
	} else { 
			$query = new WP_Query(
				array(
					'paged'			 => $page,
					'post_type'      => $post_type,
					'tax_query'      => array( 
					array(
						'taxonomy' => $taxonomy,
						'terms'    => $products					
					),
					array(
						'taxonomy' => $url_tax,
						'terms'    => array($term_id),
					)
					)
				)
		);

	}


    ob_start();
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
					  $ter = get_the_terms($post->ID,'brands');
					  $brand = "Brands: ";
					  foreach($ter as $t) {
					  	$brand .= $t->name." ";	
					  }
					?>
					<li><?php echo $brand ?></li>
					<?php
					  $ter = get_the_terms($post->ID,'sizes');
					  $size = "Sizes: ";
					  foreach($ter as $t) {
					  	$size .= $t->name." ";	
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

		if(!isset($_POST['repeat'])) {
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
			<script>
				$(document).ready(function(){
									$("#displayMoreFilter").waypoint(function(direction){
		if(direction == 'down') {
			loadPostsFilter();
		}		
	},{
		offset:'bottom-in-view' 
	});
				});

</script>
		<?php
		}
	}
	?>
	<?php 
	
	wp_reset_postdata();
	$myJason = array();
	$myJason['result'] = ob_get_clean();
	echo json_encode($myJason);
	exit;
	}
//ajax
add_action('wp_ajax_pro_filter', 'dt_product_filter');
add_action('wp_ajax_nopriv_pro_filter', 'dt_product_filter');

//filter widget
	class dt_filter_widget extends WP_Widget {

		public function __construct() {
			$widget_options = array(
				'classname'   => 'filter_widget',
				'description' => 'Widget to filter products based on its traits'
			);
			parent::__construct('filter_widget', 'Filter Widget', $widget_options);
		}

		public function widget($args, $instance) {
			?>
			<div id="filter_div">
			<?php
			if (is_archive() && get_post_type() == 'products') {
			$utax = get_query_var('taxonomy');	
			if(!empty($utax)) {
				$term = get_term_by('slug', get_query_var('term'), $utax);	
						
					$term_taxonomy = $term->taxonomy;

					$post_type = get_post_type();

					if (empty($post_type)) {
						 $post_type = 'post';
					}

					$term_id_array = array();
					if( !empty($term)) {
						array_push($term_id_array, $term->term_id);	
					}

					$page = 1; 

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
						} 

						$all_size_terms = array();
						$all_brand_terms = array();

						if ($query->have_posts()) { 
							while ($query->have_posts()) {
								$query->the_post();
								
								$size_terms = get_the_terms(get_the_ID(),'sizes');			  
							    foreach($size_terms as $t) {
								  	array_push($all_size_terms, $t);
							   }

							   $brand_terms = get_the_terms(get_the_ID(),'brands');			  
							    foreach($brand_terms as $t) {
								  	array_push($all_brand_terms, $t);
							   }
							}
							wp_reset_postdata();
						}

						$uniq_all_size_terms = array_unique($all_size_terms, SORT_REGULAR);
						$uniq_all_brand_terms = array_unique($all_brand_terms, SORT_REGULAR);
					
						//brands
						$widget_title = apply_filters('widget_title', $instance['title']);
						?>

                         <input type="hidden" id="term" value="<?php echo $term_taxonomy ?>"/>		
                        <?php		
						if ($instance['taxonomy'] == 'brands' && $term_taxonomy != 'brands') {
						?>
							<input type="hidden" name="taxonomy_active" value="brands"/>
						<?php
							echo $args['before_widget'] . $args['before_title'] . $widget_title . $args['after_title'];
							$products = get_terms(array(
								'hide_empty' => true,
								'taxonomy'	 => 'brands'
							));

							//compare $uniq_all_brand_terms and brnds array to return all matching entries
							$unique_brands = array_map(
		    					'unserialize',
								    array_intersect(
								        array_map(
								            'serialize',
								            $products
								        ), 
								        array_map(
								            'serialize', 
								            $uniq_all_brand_terms
								        )
								    )
								);


							
								if ( !empty($unique_brands)) {
									$url = admin_url('admin-ajax.php');
									?>
									<form method="post" action="<?php echo $url ?>" id="product_filter">
										<div id="checkboxes_to_replace">
										<?php foreach($unique_brands as $product) {
										?> 
											 <p style="font-weight:bold;margin-bottom:2px;"><input type="checkbox" name="products[]" value="<?php echo $product->term_id ?>"  onclick="check_click();"> <?php echo $product->name ?></p>
										<?php						
										}
										?>
										</div>
											<input type="hidden" name="action" value="pro_filter"/>	
											<input type="hidden" name="taxonomy" id="taxonomy" value="brands"/>			
									</form>
								<?php
								}
						} else if($instance['taxonomy'] == 'sizes' && $term_taxonomy != 'sizes'){ //sizes
							?>
							<input type="hidden" name="taxonomy_active" value="sizes"/>
							<?php
							echo $args['before_widget'] . $args['before_title'] . $widget_title . $args['after_title'];
							//all the terms
							$products = get_terms(array(
								'hide_empty' => true,
								'taxonomy'	 => 'sizes'
							));

				

							//compare $uniq_all_size_terms and sizes array to return all matching entries
							$unique_sizes = array_map(
		    					'unserialize',
								    array_intersect(
								        array_map(
								            'serialize',
								            $products
								        ), 
								        array_map(
								            'serialize', 
								            $uniq_all_size_terms
								        )
								    )
								);
							
							
								if ( !empty($unique_sizes)) {
									$url = admin_url('admin-ajax.php');
									?>
									<form method="post" action="<?php echo $url ?>" id="product_filter">
										<div id="checkboxes_to_replace">
											<?php foreach($unique_sizes as $product) {
											?> 
												 <p style="font-weight:bold;margin-bottom:2px;"><input type="checkbox" name="products[]" value="<?php echo $product->term_id ?>"  onclick="check_click();"> <?php echo $product->name ?></p>
											<?php						
											}
											?>
										</div>
											<input type="hidden" name="action" value="pro_filter"/>
											<input type="hidden" name="taxonomy" id="taxonomy" value="sizes"/>						
									</form>
								<?php
								}	
						}
						echo $args['after_widget'];
					}
				}?>
			</div>
				<?php
		}

		public function form($instance) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$taxonomy = $instance['taxonomy'];
			$args = array(
				'_builtin' => false,
			);
			$taxonomies = get_taxonomies($args, 'names');
		?>
		<p>
			<label style="font-weight:bold;" for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'demo_theme' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label style="font-weight:bold;" for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php esc_attr_e( 'Taxonomy:', 'demo_theme' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('taxonomy'))?>" name="<?php echo esc_attr($this->get_field_name('taxonomy'))?>">
				<?php
					foreach($taxonomies as $keys=>$values) {
				?>
						<option value="<?= $values ?>" <?php echo ($taxonomy == $values)? 'selected':''; ?>><?php echo ucfirst($values) ?></option>
				<?php		
				}	
				?>
			</select>
		</p>
		<?php 
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['taxonomy'] = $new_instance['taxonomy'];
			return $instance;
	}
	}

	function register_filter_widget() {
		register_widget('dt_filter_widget');	
	}

add_action('widgets_init', 'register_filter_widget');

//function to compare
function returnMatchingEntries($a, $b) {
	array_uintersect($a, $b, function($a, $b) {
	        return strcmp(spl_object_hash($a), spl_object_hash($b));
	 });
}

//update filter
function updateFilter(){ 
 	$page = $_POST['page'];
 	$selected_ids = $_POST['selected']; 
 	$term = $_POST['term']; 
	$post_type = 'products';
	$taxonomy_active = $_POST['taxonomy_active'];
	$term_arr = $_POST['term_arr'];
	$term_arr = unserialize($term_arr); 
	$all_size_terms = array();
	$all_brand_terms = array(); 

	//all terms from previous pges
	for($i=1; $i<=$page; $i++) {
			if (!empty($term)) {
				if( !empty($selected_ids) ) {//options are selected
					$query = new WP_Query(
						array(
							'paged'			 => $i,
							'tax_query'      => array( 
								array(
										'taxonomy' => $term,
										'terms'    => $term_arr,									
									),
								array(
									'taxonomy' => $taxonomy_active,
									'terms'    => $selected_ids
								)
							)
						)
					);

				}else{
					$query = new WP_Query(
						array(
							'paged'			 => $i,
							'tax_query'      => array( 
							array(
									'taxonomy' => $term,
									'terms'    => $term_arr,									
								)
							)
						)
					);
				}


			} 

			if ($query->have_posts()) { 
				while ($query->have_posts()) {
					$query->the_post();
								
					$size_terms = get_the_terms(get_the_ID(),'sizes');		  
				    foreach($size_terms as $t) {
					  	array_push($all_size_terms, $t);
				   }

				   $brand_terms = get_the_terms(get_the_ID(),'brands');			  
		     	    foreach($brand_terms as $t) {
					  	array_push($all_brand_terms, $t);
					}
				} 
				
			}

		} 

			wp_reset_postdata();

			$uniq_all_size_terms = array_unique($all_size_terms, SORT_REGULAR);
			$uniq_all_brand_terms = array_unique($all_brand_terms, SORT_REGULAR);


			//default terms
			$products = get_terms(array(
								'hide_empty' => true,
								'taxonomy'	 => $taxonomy_active
							));

			if ($taxonomy_active == 'brands') {
				$matching = array_map(
		    			'unserialize',
							array_intersect(
							    array_map(
							        'serialize',
							         $products
							    ), 
							    array_map(
							       'serialize', 
							        $uniq_all_brand_terms
							       )
							   )
							);

			}else if ($taxonomy_active == 'sizes'){
				$matching = array_map(
		    			'unserialize',
							array_intersect(
							    array_map(
							        'serialize',
							         $products
							    ), 
							    array_map(
							       'serialize', 
							        $uniq_all_size_terms
							       )
							   )
							);

				} 
			?>
			<?php
				foreach($matching as $ids) {
			?>	
					
						<p style="font-weight:bold;margin-bottom:2px;">
							<input type="checkbox" name="products[]" value="<?php echo $ids->term_id ?>" <?php echo (in_array($ids->term_id, $selected_ids))? 'checked':''; ?> onclick="check_click();"/> <?php echo $ids->name ?>
						</p>
						

			<?php
				}

			?>
			<?php
			exit;
 }

add_action('wp_ajax_update_filter', 'updateFilter');
add_action('wp_ajax_nopriv_update_filter', 'updateFilter');


/*******content-single-product_1 ***********/
/*
remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

function woo_remove_tabs( $tabs ) {
    unset( $tabs['additional_information'] );  	
    unset($tabs['description']);
    return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_tabs', 98 );

function display_product_description() {
	wc_get_template('single-product/tabs/description.php');	
}

add_action('woocommerce_after_single_product_summary','display_product_description', 68);

function display_product_title() {
	add_filter( 'woocommerce_show_page_title', '__return_true', 1 );
	add_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 6);
}

add_action( 'init', 'display_product_title' );*/

/********************************************/

/***************content-ingle-product-2*********/
/*remove sale sticker, rating, related products and price range */
remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//move price range below product excerpt
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);//group price
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 22);

/*remove tabs and display only product information*/
function woo_remove_tabs( $tabs ) {
    unset( $tabs['additional_information'] );  	
    unset($tabs['description']);
    return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_tabs', 98 );

function display_product_description() {
	wc_get_template('single-product/tabs/description.php');	
}

add_action('woocommerce_after_single_product_summary','display_product_description', 68);

/*display product title*/
/*function display_product_title() {
	add_filter( 'woocommerce_show_page_title', '__return_true', 1 );
	add_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 6);
}

add_action( 'init', 'display_product_title' );*/

//format variation product price
function ss_format_sale_price( $price, $regular_price, $sale_price ) {
    $output_ss_price = '<div>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</div><div><del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del></div>';
    return $output_ss_price;
}
add_filter('woocommerce_format_sale_price', 'ss_format_sale_price', 10, 3);

/*div to hide store default price*/
function add_default_store_price() {
	echo "<div id='store_def_price' style='display:none'></div>";
}
add_action('woocommerce_single_product_summary','add_default_store_price', 9);

/*register brands*/
function add_wc_product_brand_taxonomy() {
		register_taxonomy('brandss','product',array(
			'hierarchical' => true,
			'labels'       => array(
				'name'              => _x('Brandss', 'Taxonomy general name'),
				'singular_name'     => _x('Brandss', 'Taxonomy single name'),
				'search_items'      => __('Search Brands'),
				'all_items'         => __('All Brands'),
				'parent_item'       => __('Parent Brand'),
				'parent_item_colon' => __('Parent Brand: '),
				'edit_item'         => __('Edit Brand'),
				'update_item'       => __('Update Brand'),
				'add_new_item'      => __('Add new Brand'),
				'new_item_name'     => __('New brand name'),
				'menu_name'         => __('Brands')
			),
			'rewrite'      => array(
				'slug'         => 'brandss',
				'with_front'   => true,
				'hierarchical' => true
			),
		));
	}

add_action( 'init', 'add_wc_product_brand_taxonomy' );
register_taxonomy_for_object_type( 'brandss', 'product' );

function get_brand_image() {
	global $post;
	$brand_info = get_the_terms($post->ID,'brandss');
	if (!empty($brand_info)) {
		$image = get_field('upload_image',$brand_info[0]);
		?>
			<p style="margin-bottom:10px;"><img src="<?php echo $image['url']; ?>" style="width:60px;"/></p>
		<?php		
	}
}
add_filter( 'woocommerce_single_product_summary', 'get_brand_image', 3);

//add social icons
function add_social_icons( $price, $product ){
	ob_start();		
	if(is_product()) { // is product page?>
			<span id="left_div">
		<?php echo $price; ?>
		<span class="stock_field"></span>
	</span>
	<span id="right_div">    
		<button class="woo_buttons" id="woo_wishlist_btn"><i class="fa fa-heart" aria-hidden="true"></i> Add to wishlist</button>
			<span id="woo_wishlist_div" style="display:none;" class="wl-list-pop">
				<span style="display:block;font-weight:bold">Wishlists</span>
				<button class="woo_buttons">Create new Wishlists</button>
				<input type="hidden" id="hidden_wishlist_no" value="0"/>
			</span>    
		<button class="woo_buttons" id="woo_social_btn"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</button>
		<span id="woo_social_div">
			<a href="javascript::void(0);" style="color:black;"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			<a href="javascript::void(0);" style="color:black;"><i class="fa fa-envelope" aria-hidden="true"></i></a>
			<input type="hidden" id="hidden_social_no" value="0"/>
		</span>
	</span>		
<?php	} else if(is_shop()) { // is shop page
		echo $price;
	}

	$ret = ob_get_clean();
	return $ret;
}
//add_filter( 'woocommerce_get_price_html', 'add_social_icons', 8, 2 );

//move sku up
remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);
add_action('woocommerce_single_product_summary','woocommerce_template_single_meta',9);

/***********************************************/

//add checkout page twice
add_action('woocommerce_before_cart','display_checkout_to_top');

function display_checkout_to_top() {
	echo "<div class='div_top'><h2>Cart</h2></div>
		 <div class='top_checkout_div'>
				<div  class='wc-proceed-to-checkout'>";
					wc_get_template('cart/proceed-to-checkout-button.php');
				echo "</div>
					</div>
					<div style='clear:both;'></div>";
}

//remove cart cross sales
remove_action('woocommerce_cart_collaterals','woocommerce_cross_sell_display',8);

//remove search label from shop page
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

//remove breadcrumbs
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

//remove reviews
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );    
function wcs_woo_remove_reviews_tab($tabs) {    
	unset($tabs['reviews']);    
	return $tabs;
} 

//nummber of columns per shop page
/*add_filter('loop_shop_columns', 'loop_columns',20);
	function loop_columns() {
		return 4; // 4 products per row
	}

//Number of woocommerce products per page
add_filter( 'loop_shop_per_page', 'products_per_page', 21);

function products_per_page($cols){
	$cols=4;
	return $cols;
}*/

//display only results from page 1
/*add_action('pre_get_posts', 'display_page_one_results');

function display_page_one_results($query) {
	if(is_post_type_archive('product') && $query->is_main_query()) {
		$query->set('paged', 1);
	}
}*/


//append waypoint div in shop page
function append_waypoint_div_shop(){
		if(!is_search()) {
	?>
			<div>
				<div id="appended_div_products"></div>							
				<input type="hidden" id="current_page" value="1"/>
				<div class="text-center">
				<span class='fa fa-spinner fa-spin fa-3x' style="display:none" id="spinner"></span>
				</div>							
				<div id="displayMoreShopProd"></div>
			</div>
	<?php
		}
}

add_action('woocommerce_after_shop_loop', 'append_waypoint_div_shop');

//ajax to fetch products dyamically
add_action('wp_ajax_get_pro','get_shop_products');
add_action('wp_ajax_nopriv_get_pro','get_shop_products');

function get_shop_products(){	
	ob_start();
	$json_obj = $_POST['options'];
	$tmpData = str_replace("\\","",$json_obj);
	$obj = json_decode($tmpData);

	$all_product_sizes = array();
	$product_sizes = array();
	$product_brands = array();
	$page = $obj->page; 
	$selected_brands = $obj->filterOptions->brandss;
	$selected_categories = $obj->filterOptions->product_cat;
	$product_categories = array();
	$is_shop = $obj->filterOptions->is_shop;

	$args = array(
	'paged' => $page,
	'post_type' => 'product',
	'post_status' => 'publish',
	);

	if($obj->sort == 'name_asc'){
		 $args['orderby'] = 'title';
		 $args['order'] = 'ASC';
		 $args['meta_key'] = '';
	} else if($obj->sort == 'name_desc'){
		 $args['orderby'] = 'title';
		 $args['order'] = 'DESC';
		 $args['meta_key'] = '';
	}else if($obj->sort == 'new'){
		 $args['orderby'] = 'date';
		 $args['order'] = 'DESC';
		 $args['meta_key'] = '';
	}else if($obj->sort == 'price_desc'){
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
        $args['meta_key'] = '_price'; 
	}else if($obj->sort == 'price_asc'){
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
        $args['meta_key'] = '_price'; 
	}else{//default
		 $args['orderby'] = 'title';
		 $args['order'] = 'ASC';
		 $args['meta_key'] = '';
	}

	$tax_array = array();
	if(isset($obj->taxonomy) && $obj->taxonomy != 'no_taxonomy' && isset($obj->term) && $obj->term != 'no_term') {
		$tax = array(
			'taxonomy' => $obj->taxonomy,
			'terms'    => $obj->term
		);
		$args['tax_query'] = array($tax);
	}

	foreach($obj->filterOptions as $tax=>$terms) {
		if(!empty($terms)){
			if(!isset($args['tax_query'])) {
				$args['tax_query'] = array(array(
					'taxonomy'=>$tax,
					'field' => 'slug',
					'terms'=>$terms
				));
			} else {
				array_push($args['tax_query'],array(
					'taxonomy'=>$tax,
					'field' => 'slug',
					'terms'=>$terms
				));
			}
		}
	}


	$query = new WP_Query($args); 
	$posts_per_page = get_option('posts_per_page');
    $iteration_posts = $query->post_count;
    $total_posts = $query->found_posts;

	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_query();
	}

	$output = array();
	$res = ob_get_clean();
    $output['result'] = $res;
    

	//for getting terms
	ob_start();
	for($i=1; $i<=$page; $i++){
		$args['paged'] = $i;
		$query = new WP_Query($args);
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				global $product;
				$id = $product->get_id();
				$brands = get_the_terms($id, 'brandss');
				foreach($brands as $brand) {
					array_push($product_brands, $brand->slug);
				}

				$categories = get_the_terms($id, 'product_cat');
				foreach($categories as $category) {
					array_push($product_categories, $category->slug);
				}
			}
			wp_reset_query();
		}
	} 

	$uniq_all_brand_terms=array();

	if(!empty($product_brands)){
		$uniq_all_brand_terms = array_unique($product_brands, SORT_REGULAR);
	}	

	$uniq_all_cat_terms=array();
	if(!empty($categories)){
		$uniq_all_cat_terms = array_unique($product_categories, SORT_REGULAR);
	}

	
	$brands_terms_obj = get_terms('brandss'); 
	$brands_terms = array();
	foreach($brands_terms_obj as $brand) { 
		array_push($brands_terms, $brand->slug);
	}

	$cat_terms_obj = get_terms('product_cat');
	$cat_terms = array();
	foreach($cat_terms_obj as $cat) {
		array_push($cat_terms, $cat->slug);
	}

		//return all matching entries
		$unique_brands = array_map(
			'unserialize',
		    array_intersect(
		        array_map(
		            'serialize',
					$brands_terms
				), 
				array_map(
				   'serialize', 
					$uniq_all_brand_terms
				)
			)
		);

		$unique_categories = array_map(
		'unserialize',
	    array_intersect(
	        array_map(
	            'serialize',
				$cat_terms
			), 
			array_map(
			   'serialize', 
				$uniq_all_cat_terms
			)
		)
	);


		?>

		<div id="filters">
			<div id="shop_brand_filter">
				<?php 
					if(!empty($unique_brands)) {
				?>
				<h4 style="margin-top:8%;margin-bottom:2%;">Brands</h4>
				<?php
				}
					foreach($unique_brands as $brand) {
					?>
						<p style="margin-bottom:5px;"><input type="checkbox" name="brands[]" value="<?php echo $brand; ?>" onclick="filterProducts();" <?php echo (in_array($brand, $selected_brands))?"checked" : ''; ?>/> <?php echo ucfirst($brand); ?>
						</p>
						<?php
					}
					?>
			</div>

					<div id="shop_category_filter">
					<?php 
						if(!empty($unique_categories)) {
					?>
					<h4 style="margin-top:8%;margin-bottom:2%;">Category</h4>
					<?php
					}
						foreach($unique_categories as $category) {
						?>
							<p style="margin-bottom:5px;"><input type="checkbox" name="categories[]" value="<?php echo $category; ?>" onclick="filterProducts();" <?php echo (in_array($category, $selected_categories ))?"checked":""?>/> <?php echo ucfirst($category); ?>
							</p>
							<?php
						}
						?>
					</div>

		</div>
	<?php
		$output['filters'] = ob_get_clean();
	
 
    //$query->post_count number of posts returned per query using offset
    //$query->found_posts total number of posts returned without considering offet
    $output['last'] = 0;
   
  	if(((($page-1) * $posts_per_page) + $iteration_posts) >= $total_posts) {
  		$output['last'] = 1;
  	}
 
    echo json_encode($output);
	exit;
}

// remove default sorting dropdown 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

function sort_product_widget() {
	if(is_shop() || is_archive()) {
		ob_start();
		?>
		<div>
			<div id="shop_sort">
				<h4 style="margin-top:8%;margin-bottom:2%;">Sort By</h4>
				<select name="sort_by" onchange="filterProducts();">
					<option value="name_asc">Name (A-Z)</option>
					<option value="name_desc"> Name (Z-A)</option>
					<option value="price_asc">Price: Low to High</option>
					<option value="price_desc">Price: High to Low</option>
				</select>
			</div>
		</div>
		<?php
		$filter = ob_get_clean();
		echo $filter;
	}
}

add_action('woocommerce_sidebar','sort_product_widget', 12);


function variations_filter_widget(){
$all_product_sizes = array();
$product_sizes = array();
$product_brands = array();
$all_product_brands = array();
$product_categories = array();
$all_product_categories = array();
$obj2 = get_queried_object();
$taxonomy = "no_taxonomy";
$term = "no_term";
$tax_query = array();
$args = array(
	'post_type' => 'product',
	'orderby' => 'title',
	'order' => 'ASC',
	'post_status' => 'publish',
);

if(isset($obj2->taxonomy) && !(empty($obj2->taxonomy)) && isset($obj2->term_id) && !(empty($obj2->term_id))) {
	$taxonomy = $obj2->taxonomy;
	$term = $obj2->term_id;
	$tax = array(
			'taxonomy' => $taxonomy,
			'terms'    => $term 
	);
	array_push($tax_query, $tax);
}
if($tax_query) {
	$args['tax_query'] = $tax_query;
}
$query = new WP_Query($args); 

if($query->have_posts()){
	while($query->have_posts()) {
		$query->the_post();
		global $product;
		$id = $product->get_id();
		$brands = get_the_terms($id, 'brandss');
		foreach($brands as $brand) {
			array_push($product_brands, $brand->slug);
		}

		$categories = get_the_terms($id, 'product_cat');
		foreach($categories as $category) {
			array_push($product_categories, $category->slug);
		}
	}
	wp_reset_query();
}



$uniq_all_brands_terms = array_unique($product_brands, SORT_REGULAR);
$uniq_all_cat_terms = array_unique($product_categories, SORT_REGULAR);



$brands_terms_obj = get_terms('brandss');
$brands_terms = array();
foreach($brands_terms_obj as $brand) {
	array_push($brands_terms, $brand->slug);
}

$cat_terms_obj = get_terms('product_cat');
$cat_terms = array();
foreach($cat_terms_obj as $cat) {
	array_push($cat_terms, $cat->slug);
}

//return all matching entries
$unique_brands = array_map(
	'unserialize',
    array_intersect(
        array_map(
            'serialize',
			$brands_terms
		), 
		array_map(
		   'serialize', 
			$uniq_all_brands_terms
		)
	)
);

$unique_categories = array_map(
	'unserialize',
    array_intersect(
        array_map(
            'serialize',
			$cat_terms
		), 
		array_map(
		   'serialize', 
			$uniq_all_cat_terms
		)
	)
);

if(is_shop() || is_archive()) {

		?>
		<div id="filters">
			<div id="shop_brand_filter">
				<?php 
					if(!empty($unique_brands)) {
				?>
				<h4 style="margin-top:8%;margin-bottom:2%;">Brands</h4>
				<?php
				}
					foreach($unique_brands as $brand) {
					?>
						<p style="margin-bottom:5px;"><input type="checkbox" name="brands[]" value="<?php echo $brand; ?>" onclick="filterProducts();"/> <?php echo ucfirst($brand); ?>
						</p>
						<?php
					}
					?>
			</div>
			<?php
				if(is_shop()){
			?>
				<div id="shop_category_filter">
				<?php 
					if(!empty($unique_categories)) {
				?>
				<h4 style="margin-top:8%;margin-bottom:2%;">Category</h4>
				<?php
				}
					foreach($unique_categories as $category) {
					?>
						<p style="margin-bottom:5px;"><input type="checkbox" name="categories[]" value="<?php echo $category; ?>" onclick="filterProducts();"/> <?php echo ucfirst($category); ?>
						</p>
						<?php
					}
					?>
			</div>

			<?php
				}
			?>
		</div>
		<?php
	}
}


add_action('woocommerce_sidebar','variations_filter_widget', 14);

//demo shipping method
function express_shipping_init() {
	if ( ! class_exists( 'ExpressShippingInit' ) ) {
		class ExpressShippingInit extends WC_Shipping_Method {

	    public function __construct() {
	            $this->id                 = 'express_method';
	            $this->method_title       = __( 'Express Shipping', 'express_method' );
	            $this->method_description = __( 'Express Shipping Method','express_method' ); 
	            $this->enabled            = $this->get_option('enabled'); 
	            $this->title              =  $this->get_option('title');
	            $this->init();
	    }

	    function init() {
	            $this->init_form_fields(); 
	            $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

	            // Save settings in admin if you have any defined
	            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	        }

	        public function calculate_shipping($package = array()) {
				$cart_subtotal = WC()->cart->subtotal;
	        	$percentage = $this->get_option('percentage');
	        	$shipTotal = ($cart_subtotal * $percentage)/100;
	        	$shipTotal = sprintf("%0.2f", $shipTotal);

	            $rate = array(
	            	'id'    => $this->id,
	            	'label' => $this->title,
	            	'cost'  => $shipTotal
	            );
	            $this->add_rate($rate);
	        }

	        function init_form_fields() { 
	 
	        $this->form_fields = array(	 
	         'enabled' => array(
	              'title' => __( 'Enable', 'express_method' ),
	              'type' => 'checkbox',
	              'description' => __( 'Enable this shipping.', 'express_method' ),
	              'default' => 'yes'
	              ),
	 
	         'title' => array(
	            'title' => __( 'Title', 'express_method' ),
	              'type' => 'text',
	              'description' => __( 'Title to be displayed on site', 'demo' ),
	              'default' => __( 'Express Shipping', 'express_method' )
	              ),	

	         'percentage' => array(
	            'title' => __( 'Percentage', 'express_method' ),
	              'type' => 'number',
	              'description' => __( 'Shipping percenatge', 'express_method' ),
	              'default' => 0
	              ),
	         );
	 
	    }
	    }
	}

}



add_action( 'woocommerce_shipping_init', 'express_shipping_init' );

function add_shipping_method( $methods ) {
    $methods['express_shipping']= 'ExpressShippingInit'; 
    return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_shipping_method' );

//product search
add_action('wp_ajax_searchpro', 'search_products');
add_action('wp_ajax_nopriv_searchpro', 'search_products');

function search_products(){
	$search_term = $_POST['term'];
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


	if($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
			?>
				<a href="<?= the_permalink() ?>" target="_blank"><?= the_title() ?></a>
			<?php
		}
	}

	exit;
}

function title_filter($where, $wp_query) {
	global $wpdb;
	if($search_term = $wp_query->get('search_prod_title')){
		$where .= " AND ((". $wpdb->posts. ".post_title like '%". esc_sql($wpdb->esc_like($search_term)) ."%') OR 
		(".$wpdb->postmeta.".meta_key = '_sku' AND ".$wpdb->postmeta.".meta_value like '%".esc_sql($wpdb->esc_like($search_term))."%'))";
	}
	return $where;

}

function add_filter_for_search_prod() {
	if(is_search()) {
		add_filter('posts_join','product_search_join', 10, 2);
		add_filter('posts_where', 'title_filter', 10, 2);
	}	
}


function rem_filter_for_search_prod() {
	if(is_search()) {
		remove_filter('posts_where', 'title_filter', 10, 2);
		remove_filter('posts_join','product_search_join', 10, 2);

	}	
}

function product_search_join($join, $query){
	global $wpdb;
	$join .= " LEFT join " .$wpdb->postmeta ." ON ". $wpdb->posts.".ID" ."=". $wpdb->postmeta.".post_id";
	return $join;
}


/*
	==================================================
	custom menu
	==================================================

*/
class Image_Custom_Menu{

	function __construct(){
		add_post_type_support('nav_menu_item', array('thumbnail'));
		add_filter('wp_setup_nav_menu_item', array($this, 'add_nav_fields'));
		add_action('wp_update_nav_menu_item', array($this, 'update_nav_fields'), 10,3);
		add_filter('wp_edit_nav_menu_walker', array($this, 'edit_walker'), 10, 2);
	}

	function add_nav_fields($menu_item){
		$menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
		return $menu_item;
	}

	function update_nav_fields( $menu_id, $menu_item_db_id, $args ) {

    // Check if element is properly sent
    if ( is_array( $_REQUEST['menu-item-subtitle']) ) {
        $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );
    }

}

	function edit_walker($walker, $menu_id) {
		return 'Walker_Nav_Menu_Edit_Custom';
	}

}

$GLOBALS['image_custom_menu'] = new Image_custom_Menu();

class rc_scm_walker extends Walker_Nav_Menu
{
	   /*function start_lvl( &$output, $depth = 0, $args = array()) {
	   		$indent = ($depth) ? str_repeat("\t", $depth) : '';
	   		$submenu = ($depth > 0)? ' sub-menu' : '';
	   		$output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
	   }*/

	  function start_el(&$output, $item, $depth=0, $args=array(), $id=0) {		
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           //$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           //$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $prepend = '<strong>';
           $append = '</strong>';
           //$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
	           $description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'><i class="icon-email3"></i>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            //$item_output .= $description.$args->link_after;
            $item_output .= $args->link_after."</a>";
            //$item_output .= ' '.$item->subtitle.'</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}

class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {

	function start_el(&$output, $item, $depth=0, $args=array(), $id=0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <div class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item' ); ?></a>
	                </span>
	            </dt>
	        </div>
	
	        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Title Attribute' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>      
	            <p class="field-custom description description-wide">
	                <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
	                    <?php _e( 'Subtitle' ); ?><br />
	                    <input type="text" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-subtitle[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->subtitle ); ?>" />
	                </label>
	            </p>
	            <?php
	            /* New fields insertion ends here */
	            ?>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	}
}
/*******************************************
API
*******************************************/
/*add_action( 'init', 'my_movies_cpt' );
function my_movies_cpt() {
  $labels = array(
    'name'               => __( 'Movies', 'demo_theme'),
    'singular_name'      => __( 'Movie', 'demo_theme'),
    'menu_name'          =>__( 'Movies', 'demo_theme'),
    'name_admin_bar'     =>__( 'Movies', 'demo_theme'),
    'add_new'            => __( 'Add New', 'demo_theme'),
    'add_new_item'       => __( 'Add New Movie', 'demo_theme'),
    'new_item'           => __( 'New Movie', 'demo_theme'),
    'edit_item'          => __( 'Edit Movie', 'demo_theme'),
    'view_item'          => __( 'View Movie', 'demo_theme'),
    'all_items'          => __( 'All Movies', 'demo_theme'),
    'search_items'       => __( 'Search movies', 'demo_theme'),
    'parent_item_colon'  => __( 'Parent Movies:', 'demo_theme'),
    'not_found'          => __( 'No movies found.', 'demo_theme'),
    'not_found_in_trash' => __( 'No movies found in Trash.', 'demo_theme')
   );
 
  $args = array(
    'labels'             => $labels,
    'description'        => __( 'Description.', 'demo_theme' ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'book' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'show_in_rest'       => true,
    'rest_base'          => 'movies',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  );
 
  register_post_type( 'movie', $args );
}*/

/*class My_REST_Movies_Controller{

	// Here initialize our namespace and resource name.
    public function __construct() {
        $this->namespace     = '/wp/v1';
        $this->resource_name = 'movies';
    }
 
    // Register our routes.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_item_schema' ),
        ) );
        register_rest_route( $this->namespace, '/' . $this->resource_name . '/(?P<id>[\d]+)', array(
            // Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_item_schema' ),
        ) );
    }
 

    public function get_items_permissions_check( $request ) {
        if ( ! current_user_can( 'read' ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), array( 'status' => $this->authorization_status_code() ) );
        }
        return true;
    }
 

    public function get_items( $request ) {
        $args = array(
            'post_per_page' => 5,
            'post_type' => 'movie'
        );
        $posts = get_posts( $args );
 
        $data = array();
 
        if ( empty( $posts ) ) {
            return rest_ensure_response( $data );
        }
 
        foreach ( $posts as $post ) {
            $response = $this->prepare_item_for_response( $post, $request );
            $data[] = $this->prepare_response_for_collection( $response );
        }
 
        return rest_ensure_response( $data );
    }
 

    public function get_item_permissions_check( $request ) {
        if ( ! current_user_can( 'read' ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), array( 'status' => $this->authorization_status_code() ) );
        }
        return true;
    }
 

    public function get_item( $request ) {
        $id = (int) $request['id'];
        $post = get_post( $id );
 
        if ( empty( $post ) ) {
            return rest_ensure_response( array() );
        }
 
        $response = $this->prepare_item_for_response( $post, $request );
 
        return $response;
    }
 

    public function prepare_item_for_response( $post, $request ) {
        $post_data = array();
 
        $schema = $this->get_item_schema( $request );

        if ( isset( $schema['properties']['id'] ) ) {
            $post_data['id'] = (int) $post->ID;
        }
 
        if ( isset( $schema['properties']['content'] ) ) {
            $post_data['content'] = apply_filters( 'the_content', $post->post_content, $post );
        }
 
        return rest_ensure_response( $post_data );
    }
 

    public function prepare_response_for_collection( $response ) {
        if ( ! ( $response instanceof WP_REST_Response ) ) {
            return $response;
        }
 
        $data = (array) $response->get_data();
        $server = rest_get_server();
 
        if ( method_exists( $server, 'get_compact_response_links' ) ) {
            $links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
        } else {
            $links = call_user_func( array( $server, 'get_response_links' ), $response );
        }
 
        if ( ! empty( $links ) ) {
            $data['_links'] = $links;
        }
 
        return $data;
    }
 

    public function get_item_schema( $request ) {
        $schema = array(
            '$schema'              => 'http://json-schema.org/draft-04/schema#',

            'title'                => 'post',
            'type'                 => 'object',

            'properties'           => array(
                'id' => array(
                    'description'  => esc_html__( 'Unique identifier for the object.', 'demo_theme' ),
                    'type'         => 'integer',
                    'context'      => array( 'view', 'edit', 'embed' ),
                    'readonly'     => true,
                ),
                'content' => array(
                    'description'  => esc_html__( 'The content for the object.', 'demo_theme' ),
                    'type'         => 'string',
                ),
            ),
        );
 
        return $schema;
    }
 
    public function authorization_status_code() {
 
        $status = 401;
 
        if ( is_user_logged_in() ) {
            $status = 403;
        }
 
        return $status;
    }
}

function prefix_register_my_rest_routes() {
    $controller = new My_REST_Movies_Controller();
    $controller->register_routes();
}
 
add_action( 'rest_api_init', 'prefix_register_my_rest_routes' );*/

add_action( 'rest_api_init', 'slug_register_spaceship' );
function slug_register_spaceship() {
    register_rest_field( 'movie',
        'description',
        array(
            'get_callback'    => 'slug_get_spaceship',
            'update_callback' => 'slug_update_spaceship',
            'schema'          => null,
        )
    );
}

function slug_get_spaceship( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], $field_name );
}


function slug_update_spaceship( $value, $object, $field_name ) {
    if ( ! $value || ! is_string( $value ) ) {
        return;
    }

    return update_post_meta( $object->ID, $field_name, strip_tags( $value ) );

}
//disply all users in api
class UserFields {

 function __construct() {
  add_filter('rest_user_query',           [$this, 'show_all_users']);
 }

function show_all_users($prepared_args) {
    unset($prepared_args['has_published_posts']);
	unset($prepared_args['has_published_posts']);

    return $prepared_args;
  }
}

new UserFields();

//display users metadata
add_action( 'rest_api_init', 'adding_user_meta_rest' );

    function adding_user_meta_rest() {
    	global $wpdb;
        $meta_keys = $wpdb->get_results("SELECT distinct(meta_key) FROM wp_usermeta um JOIN wp_users u ON u.ID = um.user_id");

       foreach($meta_keys as $obj) {
       		register_rest_field( 'user',
                $obj->meta_key,
                array(
                    'get_callback'      => 'dt_user_get_meta',
                    'update_callback'   => 'dt_user_update_meta',
                    'schema'            => null,
                   )
            );
       } 
    }
	
	 function dt_user_get_meta( $user, $field_name, $request) {
       return get_user_meta( $user[ 'id' ], $field_name, true );
   	}

   	function dt_user_update_meta( $value, $user, $field_name) {
       return update_user_meta( $user[ 'id' ], $field_name, strip_tags($value) );
   	}


 /*custom class */

 class Custom_Products_Controller{
 	public function __construct(){
 		$this->namespace = "wp/v2/";
 		$this->resource_name = 'products';
 	}

 	public function register_routes(){
 		register_rest_route( $this->namespace,'/'.$this->resource_name, array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_items' ),
                'permission_callback' => null
            ),
            'schema' => null
        ) );
        register_rest_route( $this->namespace, '/' . $this->resource_name . '/(?P<id>[\d]+)', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_item' ),
                'permission_callback' => null
            ),
            'schema' => null,
        ) );
 	}

 	public function get_items( $request ) {
        $args = array(
            'post_per_page' => 5,
            'post_type' => 'products'
        );
        $posts = get_posts( $args );
 
        $data = array();
 
        if ( empty( $posts ) ) {
            return rest_ensure_response( $data );
        }
 
        foreach ( $posts as $post ) {
            $response = $this->prepare_item_for_response( $post, $request );
            $data[] = $this->prepare_response_for_collection( $response );
        }
 
        return rest_ensure_response( $data );
    }

    public function prepare_item_for_response( $post, $request ) {
        return rest_ensure_response( $post );
    }

    public function prepare_response_for_collection( $response ) {
        if ( ! ( $response instanceof WP_REST_Response ) ) {
            return $response;
        }
 
        $data = (array) $response->get_data();
        $server = rest_get_server();
 
        if ( method_exists( $server, 'get_compact_response_links' ) ) {
            $links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
        } else {
            $links = call_user_func( array( $server, 'get_response_links' ), $response );
        }
 
        if ( ! empty( $links ) ) {
            $data['_links'] = $links;
        }
 
        return $data;
    }

    public function get_items_permissions_check( $request ) {
        if ( ! current_user_can( 'read' ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), array( 'status' => $this->authorization_status_code() ) );
        }
        return true;
    }

    public function authorization_status_code() {
 
        $status = 401;
 
        if ( is_user_logged_in() ) {
            $status = 403;
        }
 
        return $status;
    }
 }

 function dt_register_my_routes() {
    $controller = new Custom_Products_Controller();
    $controller->register_routes();
}
 
add_action( 'rest_api_init', 'dt_register_my_routes' );

/*to handle bsic authentiction*/
function json_basic_auth_handler( $user ) {
	if ( ! empty( $user ) ) {
		return $user;
	}
	if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		return $user;
	}
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
	remove_filter( 'determine_current_user', 'json_basic_auth_handler', 200 );
	$user = wp_authenticate( $username, $password );
	add_filter( 'determine_current_user', 'json_basic_auth_handler', 200 );
	if ( is_wp_error( $user ) ) {
		return null;
	}
	return $user->ID;
}
add_filter( 'determine_current_user', 'json_basic_auth_handler', 200, 1 );


	/**
	 * Rest endpoint filter to change permission callback of item_permission_check.
	 *
	 * @param type $endpoints return endpoints.
	 */
	function replace_rest_endpoints_permission_callback( $endpoints ) {
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
			foreach ( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] as $route => &$handlers ) {
				if ( isset( $handlers['permission_callback'] ) ) {
					foreach ( $handlers['permission_callback'] as $key => &$handler ) {
						if ( 'get_item_permissions_check' === $handler ) {
							$handlers['permission_callback'] = 'cached_get_item_permissions_check';
						}
					}
				}
			}
		}
		return $endpoints;
	}
  add_filter( 'rest_endpoints', 'replace_rest_endpoints_permission_callback', 10 );
	/**
	 * Permission check with cached count users.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access for the item, otherwise WP_Error object.
	 */
  function cached_get_item_permissions_check( $request ) {
		$error = new WP_Error( 'rest_user_invalid_id', __( 'Invalid user ID.' ), array( 'status' => 404 ) );
		if ( (int) $request['id'] <= 0 ) {
			return $error;
		}
		$user = get_userdata( (int) $request['id'] );
		if ( empty( $user ) || ! $user->exists() ) {
			return $error;
		}
		if ( is_multisite() && ! is_user_member_of_blog( $user->ID ) ) {
			return $error;
		}
		if ( is_wp_error( $user ) ) {
			return $user;
		}
		$types = get_post_types( array( 'show_in_rest' => true ), 'names' );
		if ( get_current_user_id() === $user->ID ) {
			return true;
		}
		if ( 'edit' === $request['context'] && ! current_user_can( 'list_users' ) ) {
			return new WP_Error( 'rest_user_cannot_view', __( 'Sorry, you are not allowed to list users.' ), array( 'status' => rest_authorization_required_code() ) );
		} elseif ( ! cached_count_user_posts( $user->ID, $types ) && ! current_user_can( 'edit_user', $user->ID ) && ! current_user_can( 'list_users' ) ) {
			return new WP_Error( 'rest_user_cannot_view', __( 'Sorry, you are not allowed to list users.' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}
	/**
	 * Cached version of count_user_posts, which is uncached but doesn't always need to hit the db
	 *
	 * Count_user_posts is generally fast, but it can be easy to end up with many redundant queries.
	 * if it's called several times per request. This allows bypassing the db queries in favor of
	 * the cache
	 *
	 * @param int       $user_id user id to get count.
	 * @param int|array $types post type array or string.
	 * @return string Count of users.
	 */
  function cached_count_user_posts( $user_id, $types = 'post' ) {
		if ( ! is_numeric( $user_id ) ) {
			return 0;
		}
		$cache_key   = 'cached_' . (int) $user_id;
		$cache_group = 'user_posts_count';
		$count = wp_cache_get( $cache_key, $cache_group );
		if ( false === $count ) {
			// @codingStandardsIgnoreLine.
			$count = count_user_posts( $user_id, $types );
      
      // 5 Mins, We don't want to handle cache invalidation.
			wp_cache_set( $cache_key, $count, $cache_group, 5 * MINUTE_IN_SECONDS );
		}
		return $count;
	}
        
 /*csv download*/   
function pcsv_add_column( $columns ) {
    $columns['csv_column'] = 'CSV';
    return $columns;
}

add_filter( 'manage_edit-shop_order_columns', 'pcsv_add_column' );

/*for each row*/
add_action( 'manage_shop_order_posts_custom_column' , 'pcsv_custom_orders_list_column_content');
function pcsv_custom_orders_list_column_content( $column )
{
    switch ( $column )
    {
        case 'csv_column':
            global $the_order;
            $order_id = $the_order->get_id();
            echo "<button id='".$order_id."_csv' class='csv_download'>Download CSV</button>";
            break;
        
    }
} 

/*mobile custom img type*/
add_action( 'init', 'mobiles_cpt' );
function mobiles_cpt() {
  $labels = array(
    'name'               => __( 'Mobiles', 'demo_theme'),
    'singular_name'      => __( 'Mobile', 'demo_theme'),
    'menu_name'          =>__( 'Mobiles', 'demo_theme'),
    'name_admin_bar'     =>__( 'Mobiles', 'demo_theme'),
    'add_new'            => __( 'Add New Mobile', 'demo_theme'),
    'add_new_item'       => __( 'Add New Mobile', 'demo_theme'),
    'new_item'           => __( 'New Mobile', 'demo_theme'),
    'edit_item'          => __( 'Edit Mobile', 'demo_theme'),
    'view_item'          => __( 'View Mobile', 'demo_theme'),
    'all_items'          => __( 'All Mobiles', 'demo_theme'),
    'search_items'       => __( 'Search mobile', 'demo_theme'),
    'parent_item_colon'  => __( 'Parent Mobiles:', 'demo_theme'),
    'not_found'          => __( 'No mobiles founds.', 'demo_theme'),
    'not_found_in_trash' => __( 'No mobiles found in Trash.', 'demo_theme')
   );
 
  $args = array(
    'labels'             => $labels,
    'description'        => __( 'Description.', 'demo_theme' ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'mobile' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'show_in_rest'       => true,
    'rest_base'          => 'mobiles',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'supports'           => array( 'title','thumbnail')
  );
 
  register_post_type( 'mobile', $args );
}

/*add admin menu*/
add_action('admin_menu','csv_upload_menu');

function csv_upload_menu(){
    add_submenu_page('upload.php', 'Upload Mobile data',  'Upload Mobile', 'manage_options', 'upload_mobile_csv','render_mobile_csv');
}

function render_mobile_csv($title){
    global $title;
    ?>
    <h2><?= $title ?></h2>
    <form method="post" id="csv_upload_form" name="csv_upload_form" enctype="multipart/form-data">
        <input type="file" name="csv_upload" id="csv_upload" style="display:none;">
        <input type="button" value="upload" id="upload_btn"/>
    </form>
    <p style="margin-top: 2%" id="output"></p>
    <?php
    
}

/*add csv support in media*/
function add_csv_mime($mimes){
    $mimes['csv'] = 'text/csv';
    return $mimes;
}

add_filter('upload_mimes','add_csv_mime');

/*procss csv file*/
add_action('add_attachment','process_csv_file');
function process_csv_file($attachment_ID){
    global $wpdb;
   $type = get_post_mime_type($attachment_ID);
   $filename = get_attached_file($attachment_ID);
   $first_row = true;
   $added = 0;
   $skipped =0;
           
   if(strpos($type, 'text/csv') === 0){ 
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                if($first_row){
                    $first_row = false;
                    continue;
                }
                print_r($data);
                $title = $data[0];
                $titlelower = strtolower($title);
                $brand = $data[1];
                $price = $data[2];
                $os = $data[3];
                
                /*skip insertion if title already exits*/
                $id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE lower(post_title) = '" . $titlelower . "'");
                if(empty($id)){
                    $post_id = wp_insert_post(array(
                        'post_type'  => 'mobile',
                        'post_title' => $title,
                        'post_status' => 'publish'
                    ));
                    
                    if(!empty($post_id)){
                        add_post_meta($post_id, 'brand', $brand);
                        add_post_meta($post_id,'price',$price);
                        add_post_meta($post_id,'os',$os);                        
                    }
                    $added++;
                }else{
                    $skipped++;
                }
            }
            fclose($handle);
            echo "Added: ".$added."<br/>";
            echo "Skipped: ".$skipped;
        }
    }
}

/*to handle csv upload*/
add_action('wp_ajax_upload_csv','handle_csv_upload');
add_action('wp_ajax_nopriv_upload_csv','handle_csv_upload');

function handle_csv_upload(){
    if(!empty($_POST)){  
        global $wpdb;
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        require_once (ABSPATH . '/wp-admin/includes/image.php');
     
        $file = $_FILES['file'];
        
        if(!empty($file)){
            $csv_folder = ABSPATH."wp-content/uploads/csv_files";
            if(!file_exists($csv_folder)){
                mkdir($csv_folder,0777,true);
            }
            
            $excel_folder = ABSPATH."wp-content/uploads/excel_files";
            if(!file_exists($excel_folder)){
                mkdir($excel_folder,0777,true);
            }
            
            $file_format = end(explode('.', $_FILES['file']['name']));
            $file_format = strtolower($file_format); 
            if($file_format == 'csv'){
                $new_file = ABSPATH."wp-content/uploads/csv_files/".time().".csv"; 
                if(move_uploaded_file($_FILES['file']['tmp_name'], $new_file)){
                    $first_row = true;
                    $added = 0;
                    $total = 0;
                    if (($handle = fopen($new_file, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                            if($first_row){
                                $first_row = false;
                                continue;
                            }
                            
                            $total++;

                            $title = $data[0];
                            $titlelower = strtolower($title);
                            $brand = $data[1];
                            $price = $data[2];
                            $os = $data[3];

                            /*skip insertion if title already exits*/
                            $id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE lower(post_title) = '" . $titlelower . "'");
                            if(empty($id)  && !empty($title)){
                                $post_id = wp_insert_post(array(
                                    'post_type'  => 'mobile',
                                    'post_title' => $title,
                                    'post_status' => 'publish'
                                ));

                                if(!empty($post_id)){
                                    add_post_meta($post_id, 'brand', $brand);
                                    add_post_meta($post_id,'price',$price);
                                    add_post_meta($post_id,'os',$os);                        
                                }
                                $added++;
                            }
                        }
                        fclose($handle);
                        echo 'added = '.$added.'<br/> skipped = '. ($total - $added);
                    }
                }
            } elseif($file_format == 'xlsx' || $file_format == 'xls'){
                $new_file = ABSPATH."wp-content/uploads/excel_files/".time().".".$file_format; 
                if(move_uploaded_file($_FILES['file']['tmp_name'], $new_file)){
                    $added = 0;
                    $total = 0;
                    require get_template_directory().'/assests/phpexcel/Classes/PHPExcel/IOFactory.php';
                    try {
                        $inputFileType = PHPExcel_IOFactory::identify($new_file);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($new_file);
                    } catch(Exception $e) {
                        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                    }

                    //  Get worksheet dimensions
                    $sheet = $objPHPExcel->getSheet(0); 
                    $highestRow = $sheet->getHighestRow(); 
                    $highestColumn = $sheet->getHighestColumn();

                    for ($row = 2; $row <= $highestRow; $row++){  
                        $total++;
                        $title = $sheet->getCellByColumnAndRow(0,$row)->getValue(); 
                        $titlelower = strtolower($title);
                        $brand = $sheet->getCellByColumnAndRow(1,$row)->getValue();
                        $price = $sheet->getCellByColumnAndRow(2,$row)->getValue();
                        $os = $sheet->getCellByColumnAndRow(3,$row)->getValue();
                        
                        /*skip insertion if title already exits*/
                        $id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE lower(post_title) = '" . $titlelower . "'");
                        if(empty($id) && !empty($title)){
                            $post_id = wp_insert_post(array(
                                'post_type'  => 'mobile',
                                'post_title' => $title,
                                'post_status' => 'publish'
                            ));

                            if(!empty($post_id)){
                                add_post_meta($post_id, 'brand', $brand);
                                add_post_meta($post_id,'price',$price);
                                add_post_meta($post_id,'os',$os);                        
                           }
                           $added++;
                        }
                    }
                }
                echo 'added = '.$added.'<br/> skipped = '. ($total - $added);
            }else {
                echo 'Upload file should be in csv, xls or xlsx format.';
            }
        }
    }    
    exit;
    
}

/*load text domain to trnslate*/
add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain('demo_theme', get_template_directory() . '/languages');
}

/*automatically update cart on cart page*/
add_action( 'wp_footer', 'cart_update_qty_script' );
function cart_update_qty_script() {
    if (is_cart()) :
        ?>
        <script type="text/javascript">
            (function($){
                $(function(){
                    jQuery('div.woocommerce').on('change', '.qty', function(){
                    jQuery("[name='update_cart']").prop("disabled", false);
                        jQuery(".woocommerce-cart input[name='update_cart']").trigger("click"); 
                    });
                    
                    var upd_cart_btn = $(".woocommerce-cart input[name="update_cart"]"); upd_cart_btn.hide(); $(".cart_item").find(".qty").on("change", function(){ upd_cart_btn.trigger("click"); }); }); </script>
                });
            })(jQuery);
        </script>
        <?php
    endif;
}
/*widget to display posts*/
add_action('widgets_init', 'display_posts');
function display_posts(){
    register_widget('Posts_Widget');
}

class Posts_Widget extends WP_Widget{
    public function __construct(){
        $widget_details = array(
            'classname'   => 'posts_widget',
            'description' => 'Widget to display posts'
        );
        parent::construct('posts_widget', 'Posts', $widget_details);
    }
    
    public function widget($args, $instance){
        echo $args['before_widget'];
        if((!empty($instance['title']))){
            echo $args['before_title'].apply_filters('widget_title',$instance['title']).$args['after_title'];
        }
        
        if(!empty($instance['selected_posts']) && is_array($instance['selected_posts'])){
            $selected_posts = get_posts();
        }
        echo $args['after_widget'];
    }
    
    public function form($instance){        
        $posts = get_posts(
                array(
                  'posts_per_page' => 20,
                  'offset' => 0  
                ));
        $selected_posts = !empty($instance['selected_posts'])? $instance['selected_posts']: array();
        ?>
        <ul>
                <?php
                    foreach($posts as $post){
                        ?>
                        <li><input type="checkbox" 
                            name="<?php esc_attr_e($this->get_field_name($selected_posts)); ?>[]"
                            value="<?php echo $post->ID ?>"
                            <?php checked((in_array($post->ID,$selected_posts))? $post->ID : '', $post->ID) ?>
                        /><?php echo get_the_title($post->ID); ?></li>
                        <?php       
                    }
                ?>
        </ul>
        <?php
    }
    
    public function update($new_instance, $old_instance){
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']))? strip_tags($new_instance['title']):'';
        $selected_posts = (!empty($new_instance['selected_posts']))?(array)$new_instance['selected_posts']:array();
        $instance['selected_posts'] = array_map('sanitize_text_field',$selected_posts);
        return $instance;
    }
}
?>