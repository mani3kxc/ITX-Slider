<?php 




add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {

	/**
	 * Post Type: Slides.
	 */

	$labels = array(
		"name" => __( "Slides", "itxsl" ),
		"singular_name" => __( "Slide", "itxsl" ),
	);

	$args = array(
		"label" => __( "Slides", "itxsl" ),
		"labels" => $labels,
		"description" => "ITX Slider Slide",
		"public" => false,
		"supports" => array( "title","editor", "thumbnail" ),
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
		"rewrite" => array( "slug" => "itxsl_slide", "with_front" => false ),
		"query_var" => true,
		
	);

	register_post_type( "itxsl_slide", $args );

	/**
	 * Post Type: Sliders.
	 */

	$labels = array(
		"name" => __( "Sliders", "itxsl" ),
		"singular_name" => __( "Slider", "itxsl" ),
	);

	$args = array(
		"label" => __( "Sliders", "itxsl" ),
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
		"rewrite" => array( "slug" => "itxsl_slider", "with_front" => false ),
		"query_var" => true,
		"supports" => false,
	);

	register_post_type( "itxsl_slider", $args );
}

if(function_exists("register_field_group"))
{

	if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_slide',
		'title' => 'Slide',
		'fields' => array (
			array (
				'key' => 'field_5a0233413ea11',
				'label' => 'Show Title',
				'name' => 'itxsl_slide_title_show',
				'type' => 'checkbox',
				'choices' => array (
					1 => 'Check if you want to show customized text on this slide.',
				),
				'default_value' => 1,
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_5a023168d5dbd',
				'label' => 'Title Color',
				'name' => 'itxsl_slide_title_color',
				'type' => 'color_picker',
				'instructions' => 'Choose color for Slide title',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5a0233413ea11',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
			),
			array (
				'key' => 'field_5a0235531dcb1',
				'label' => 'Title Font Size',
				'name' => 'itxsl_slide_title_font_size',
				'type' => 'text',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5a0233413ea11',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '72px',
				'placeholder' => '72px',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => 5,
			),
			array (
				'key' => 'field_5a02345159a18',
				'label' => 'Show Description',
				'name' => 'itxsl_slide_description_show',
				'type' => 'checkbox',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5a0233413ea11',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					1 => 'Check if you want to show slide description text under the title.',
				),
				'default_value' => 1,
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_5a02350dbbfea',
				'label' => 'Description Color',
				'name' => 'itxsl_slide_description_color',
				'type' => 'color_picker',
				'instructions' => 'Choose color for Slide description',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5a02345159a18',
							'operator' => '==',
							'value' => '1',
						),
						array (
							'field' => 'field_5a0233413ea11',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
			),
			array (
				'key' => 'field_5a0235bca9f0a',
				'label' => 'Description Font Size',
				'name' => 'itxsl_slide_description_font_size',
				'type' => 'text',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_5a02345159a18',
							'operator' => '==',
							'value' => '1',
						),
						array (
							'field' => 'field_5a0233413ea11',
							'operator' => '==',
							'value' => '1',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '20px',
				'placeholder' => '20px',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => 5,
			),
			array (
				'key' => 'field_5a0231e60bf1f',
				'label' => 'Text Position',
				'name' => 'itxsl_slide_text_position',
				'type' => 'select',
				'choices' => array (
					'top-center' => 'top-center',
					'top-left' => 'top-left',
					'top-right' => 'top-right',
					'middle-center' => 'middle-center',
					'middle-left' => 'middle-left',
					'middle-right' => 'middle-right',
					'bottom-center' => 'bottom-center',
					'bottom-left' => 'bottom-left',
					'bottom-right' => 'bottom-right',
				),
				'default_value' => 'middle-center',
				'allow_null' => 0,
				'multiple' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'itxsl_slide',
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

	
	register_field_group(array (
		'id' => 'acf_itxsl-slider-details',
		'title' => 'ITX Slider Details',
		'fields' => array (
			array (
				'key' => 'field_59de2ed6a176a',
				'label' => 'Slider Name',
				'name' => 'itxsl_slider_name',
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
				'name' => 'itxsl_slider_timeout',
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
				'name' => 'itxsl_slider_width',
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
				'name' => 'itxsl_slider_height',
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
					'value' => 'itxsl_slider',
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

	register_field_group(array (
		'id' => 'acf_itxsl-page-slider',
		'title' => 'ITX Page Slider',
		'fields' => array (
			array (
				'key' => 'field_5a00ceec9067a',
				'label' => 'ITX Slider',
				'name' => 'itxsl_page_slider',
				'type' => 'post_object',
				'instructions' => 'Select slider for this page',
				'post_type' => array (
					0 => 'itxsl_slider',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 1,
				'multiple' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
