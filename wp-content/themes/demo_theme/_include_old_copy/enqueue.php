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

		wp_register_script('dt_plugins', get_template_directory_uri()."/assests/js/plugins.js", array(), false, true);
		wp_register_script('dt_functions', get_template_directory_uri()."/assests/js/functions.js", array(), false, true);
		wp_register_script('dt_load_more', get_template_directory_uri()."/assests/js/load_more.js", array(), false, true);
		wp_register_script('dt_waypoint', get_template_directory_uri()."/assests/waypoint/lib/jquery.waypoints.js", array(), false, true);
		wp_register_script('dt_product_filter', get_template_directory_uri()."/assests/js/product_filter.js", array(), false, true);

		$load_array = array('admin_ajax_url'=>admin_url('admin-ajax.php'));
		$filter_array = array('redirect_url'=> site_url()."/products-filter", 'admin_ajax_url'=>admin_url('admin-ajax.php'));

		wp_localize_script('dt_load_more', 'loadMoreObj', $load_array);
		wp_localize_script('dt_product_filter', 'prodfiltr', $filter_array);

		wp_enqueue_script('jquery');
		wp_enqueue_script('dt_plugins');
		wp_enqueue_script('dt_functions');
		wp_enqueue_script('dt_load_more');
		wp_enqueue_script('dt_waypoint');
		wp_enqueue_script('dt_product_filter');

	}
?>