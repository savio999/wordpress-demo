<?php

function dt_aftr_theme_loaded() {
	register_nav_menu('primary', __('primary', 'demo_theme'));
	register_nav_menu('secondary', __('secondary', 'demo_theme'));
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
}
?>