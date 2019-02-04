<?php

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
		'priority' =>  30
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
}

?>