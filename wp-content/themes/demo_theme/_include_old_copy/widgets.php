<?php

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

?>