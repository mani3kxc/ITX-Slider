<?php 




add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {

	/**
	 * Post Type: Slides.
	 */

	$labels = array(
		"name" => __( "Slides", "sfs" ),
		"singular_name" => __( "Slide", "sfs" ),
	);

	$args = array(
		"label" => __( "Slides", "sfs" ),
		"labels" => $labels,
		"description" => "Simple FreeSlider Slide",
		"public" => false,
		"supports" => array( "title","excerpt", "editor", "thumbnail" ),
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "sfs_slide", "with_front" => false ),
		"query_var" => true,
		
	);

	register_post_type( "sfs_slide", $args );

	/**
	 * Post Type: Sliders.
	 */

	$labels = array(
		"name" => __( "Sliders", "sfs" ),
		"singular_name" => __( "Slider", "sfs" ),
	);

	$args = array(
		"label" => __( "Sliders", "sfs" ),
		"labels" => $labels,
		"description" => "Slider",
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "sfs_slider", "with_front" => false ),
		"query_var" => true,
		"supports" => false,
	);

	register_post_type( "sfs_slider", $args );
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_freeslider-slider-details',
		'title' => 'FreeSlider Slider Details',
		'fields' => array (
			array (
				'key' => 'field_59de2ed6a176a',
				'label' => 'Slider Name',
				'name' => 'sfs_slider_name',
				'type' => 'text',
				'instructions' => 'Friendly Name of a slider',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_59e50997ba330',
				'label' => 'Slider Timeout',
				'name' => 'sfs_slider_timeout',
				'type' => 'number',
				'required' => 1,
				'default_value' => 10000,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_59e85f2a19bdb',
				'label' => 'Slider Width',
				'name' => 'sfs_slider_width',
				'type' => 'text',
				'default_value' => '100%',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_59e85f5919bdc',
				'label' => 'Slider Height',
				'name' => 'sfs_slider_height',
				'type' => 'text',
				'default_value' => '300px',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sfs_slider',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
