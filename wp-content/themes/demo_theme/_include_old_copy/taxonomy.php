<?php
	function add_products_brand_taxonomy() {
		register_taxonomy('Brand','post',array(
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
		register_taxonomy('Sizes','post',array(
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

	class dt_filter_widget extends WP_Widget {

		public function __construct() {
			$widget_options = array(
				'classname'   => 'filter_widget',
				'description' => 'Widget to filter products based on color, brand and size'
			);
			parent::__construct('filter_widget', 'Filter Widget', $widget_options);
		}

		public function widget($args, $instance) {
			if (is_archive()) {
				$widget_title = apply_filters('widget_title', $instance['title']);
				echo $args['before_widget'] . $args['before_title'] . $widget_title . $args['after_title'];
				if ($instance['taxonomy'] != 'product') {
					$products = get_terms(array(
						'hide_empty' => true,
						'taxonomy'	 => 'product'
					));
					
						if ( !empty($products)) {
							$url = admin_url('admin-ajax.php');
							?>
							<form method="post" action="<?php echo $url ?>" id="product_filter">
								<?php foreach($products as $product) {
								?> 
									 <p style="font-weight:bold;margin-bottom:2px;"><input type="checkbox" name="products[]" value="<?php echo $product->term_id ?>"> <?php echo $product->name ?></p>
								<?php						
								}
								?>
									<input type="hidden" name="action" value="pro_filter"/>						
							</form>
						<?php
						}
				}else if($instance['taxonomy'] != 'size'){
					$sizes = get_terms(array(
						'hide_empty' => true,
						'taxonomy'	 => 'size'
					));
					
						if ( !empty($sizes)) {
							$url = admin_url('admin-ajax.php');
							?>
							<form method="post" action="<?php echo $url ?>" id="size_filter">
								<?php foreach($sizes as $size) {
								?> 
									 <p style="font-weight:bold;margin-bottom:2px;"><input type="checkbox" name="sizes[]" value="<?php echo $sizes->term_id ?>"> <?php echo $size->name ?></p>
								<?php						
								}
								?>
									<input type="hidden" name="action" value="pro_filter"/>						
							</form>
						<?php
						}	
				}
				echo $args['after_widget'];
			}
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

	function dt_product_filter() {
	$products = $_POST['products'];

	$page = 1;

	if (isset($_POST['page']) && (!empty($_POST['page']))){
		$page = $_POST['page'];
	}

	if(empty($products)) {;
		$taxonomy = $_POST['taxonomy'];
		$term_id = $_POST['term_id'];

		$trmarr = array();
		array_push($trmarr, $term_id);

		$query = new WP_Query(
			array(
				'paged'			 => $page,
				'tax_query'      => array( 
					array(
						'taxonomy' => $taxonomy,
						'terms'    => $trmarr
					)
				)
			)
		);
	} else {
			$query = new WP_Query(
		array(
			'paged'			 => $page,
			'tax_query'      => array( 
				array(
					'taxonomy' => 'product',
					'terms'    => $products,
					'operator' => 'AND'
				)
			)
		)
	);		
	}



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
	exit();
	}


?>