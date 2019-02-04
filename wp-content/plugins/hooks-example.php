<?php
/*
Plugin Name: Hooks Example
*/

add_filter('the_title', 'dt_title');

function dt_title($title) {
	return  "Hooked:" . $title;
}
?>