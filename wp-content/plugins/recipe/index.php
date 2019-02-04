<?php
/*
Plugin Name: Recipe
Description: Simple plugin to llow usr to creste those recipes and rate them
Version: 1.0
Text Domain: recipe
*/

if (!function_exists('add_action')) {
	die('hi there I am just a plugin not much can i do when called directly');
}

function r_activation_plugin() {
	if(version_compare(get_bloginfo('version'),'4.5','<')) {
		wp_die(__('Please update your wordpress in order to use this plugin.','recipe'));
	}
}

register_activation_hook('__FILE__', 'r_activation_plugin');

add_action( 'init', 'recipe_init' );

function recipe_init() {
	$labels = array(
		'name'               => __( 'Recipes', 'recipe' ),
		'singular_name'      => __( 'Recipe', 'recipe' ),
		'menu_name'          => __( 'Recipes', 'recipe' ),
		'name_admin_bar'     => __( 'Book', 'recipe' ),
		'add_new'            => __( 'Add New','recipe' ),
		'add_new_item'       => __( 'Add New Recipe', 'recipe' ),
		'new_item'           => __( 'New Recipe', 'recipe' ),
		'edit_item'          => __( 'Edit Recipe', 'recipe' ),
		'view_item'          => __( 'View Recipe','recipe' ),
		'all_items'          => __( 'All Recipes', 'recipe' ),
		'search_items'       => __( 'Search Recipes', 'recipe' ),
		'parent_item_colon'  => __( 'Parent Recipes:', 'recipe' ),
		'not_found'          => __( 'No recipes found.', 'recipe' ),
		'not_found_in_trash' => __( 'No recipes found in Trash.', 'recipe' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'A custom post type for recipes.', 'recipe' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'recipe' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail'),
		'taxonomies'         => array('category', 'post_tag')
	);

	register_post_type( 'recipe', $args );
	}
?>

