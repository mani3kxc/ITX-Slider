<?php

/*
Plugin Name:  ITX Slider Free
Plugin URI:   http://itextreme.pl/plugins/itxsl/
Description:  Simple responsible slider
Version:      1.0
Author:       Mariusz Wiśniowski @ ITextreme.pl
Author URI:   http://itextreme.pl
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  list-builder
Domain Path:  /languages
*/

  
/* !0. TABLE OF CONTENTS */

/*
  
  1. HOOKS

  	1.1 - add_action( 'init', 'itxsl_register_shortcodes' );
	1.2 - add_filter( 'manage_itxsl_slide_posts_columns', 'itxsl_slide_column_headers');
	1.3 - add_filter( 'manage_itxsl_slider_posts_columns', 'itxsl_slider_column_headers');
	1.4 - add_filter( 'manage_itxsl_slider_posts_custom_column', 'itxsl_slider_column_data',1,2);
	1.5 - add_filter( 'acf/fields/post_object/result', 'itxsl_slide_post_object_rows', 10,4);
	1.6 - add_action('admin_head-edit.php', 'itxsl_register_custom_admin_titles');
	1.7 - add_action('wp_enqueue_scripts', 'itxsl_add_scripts_and_styles' );
  
  2. SHORTCODES
    2.1 - itxsl_register_shortcode()
    2.2 - itxsl_shortcode()

  3. FILTERS
  	3.1 - itxsl_slider_column_headers()
  	3.2 - itxsl_slider_column_data()
  	3.3 - itxsl_register_custom_admin_titles()
  	3.4 - itxsl_custom_admin_titles()
  	3.5 - itxsl_slide_column_headers()
  	3.6 - itxsl_slide_post_object_rows()
    
  4. EXTERNAL SCRIPTS

  	4.1 - itxsl_add_scripts_and_styles()
    
  5. ACTIONS

  	5.1 - itxsl_get_slides()
    
  6. HELPERS
    
  7. CUSTOM POST TYPES
  
  8. ADMIN PAGES
  
  9. SETTINGS

*/

include_once( plugin_dir_path( __FILE__ ) . 'lib/advanced-custom-fields/acf.php');
include_once( plugin_dir_path( __FILE__ ) . '/custom_post_types/itxsl_custom_post_types.php');


if(!class_exists('itxsl_Slider')) {

	class itxsl_Slider {

		private $slider_id = 0;
		private $slider_slides = array();


		public function itxsl_Slider()
		{
			$this->itxsl_Slider_Init();			
		}

		public function itxsl_Slider_Init()
		{

			/* !1. HOOKS */

			add_action( 'init', array(&$this, 'itxsl_register_shortcodes' ));

			//1.2
			add_filter( 'manage_itxsl_slide_posts_columns', array(&$this, 'itxsl_slide_column_headers'));

			//1.3
			add_filter( 'manage_itxsl_slider_posts_columns', array(&$this, 'itxsl_slider_column_headers'));

			//1.4
			add_filter( 'manage_itxsl_slider_posts_custom_column', array(&$this, 'itxsl_slider_column_data'),1,2);

			//1.5
			add_filter( 'acf/fields/post_object/result', array(&$this, 'itxsl_page_slider_object_rows'), 10,4);

			//1.6
			add_action('admin_head-edit.php', array(&$this, 'itxsl_register_custom_admin_titles'));

			//1.7
			add_action('wp_enqueue_scripts', array(&$this, 'itxsl_add_scripts_and_styles') );

			//1.8
			add_action('admin_enqueue_scripts', array(&$this, 'itxsl_admin_scripts') );

			//1.9
			add_action('admin_menu', array(&$this, 'itxsl_admin_menus') ); 

			add_action( 'add_meta_boxes', array( &$this, 'itxsl_slider_add_meta_box' ) );

			add_action( 'save_post', array( &$this, 'itxsl_save_post'), 10, 2 );
 

			add_action('itxsl_header_slider', array( &$this, 'itxsl_show_header_slider'));

			add_action('admin_init', array(&$this, 'itxsl_register_settings'));


			add_filter('acf/settings/path', 'itxsl_acf_settings_path');
			add_filter('acf/settings/dir', 'itxsl_acf_settings_dir');
			add_filter('acf/settings/show_admin', 'itxsl_acf_show_admin');
			if(!defined('ACF_LITE')) define('ACF_LITE', true);
		}


 
    /**
     * Adds the meta box container.
     */
    public function itxsl_slider_add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'itxsl_slider' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box('itxsl_sliders_meta_box', __( 'Choose Sliders', 'itxsl_slider' ),
                array( $this, 'itxsl_render_slider_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }

       // wp_nonce_field( "itxsl_nonce_action", "itxsl_nonce" );

    }

  


    function itxsl_render_slider_meta_box_content()
	{
		global $post;

		//$data = get_post_meta($post->ID,"itxsl_sliders_meta_box",true);

		if(get_post_status()!='auto-draft')
		{

		if($data = get_post_custom($post->ID))
		{
			
			if(array_key_exists("itxsl", $data))
				$data = $data['itxsl'];
			else
				$data = 0;
		}
		else
			$data = 0;

		if(!$data)
			$data = 0;

  		$args = array(
  				'post__in'  => $data,
    			'orderby' => 'post__in',
    			'post_type'  => 'itxsl_slide',
    			'meta_key' => '_thumbnail_id');

		$query = new WP_Query($args);

		$output = '<div class="itxsl-admin-sliders-active"><H2>Active slides</H2><div class="itxsl-admin-rows-active itxsl-admin-sliders-group">
        		';

    	if ( $query->have_posts() ) { // you never checked to see if no posts were found
    	while($query->have_posts()) { // alt style syntax doesn't work with most IDEs
        	$query->the_post(); // individual statement should be on individual line
        	 
        	if ( has_post_thumbnail() ) { 
        		$output .= '<div class="itxsl-admin-row">
        						<div class="itxsl-admin-row-title">
        							<span class="itxsl-admin-handle dashicons dashicons-sort"></span>
        							<h2 class="itxsl-admin-row-title-text">
        							<span class="itxsl-admin-order"></span>'. get_the_title() . '</h2><A href="'.get_edit_post_link().'" target=_blank><span class="itxsl-admin-edit dashicons dashicons-edit"></span></A>
        						</div>';
            	$output .=  '	<div class="itxsl-admin-row-background" style=\'background-size:cover; background: url("'.get_the_post_thumbnail_url() . '");\'></div>';
            	$output.='
            					<input class="itxsl-admin-row-input" name="itxsl['.get_the_ID().']" slider-id="'.get_the_ID().'" type="hidden" >
            				</div>';
    
        	}
    	}

	} else {
	    $output .=  '<span class="itxsl-admin-box-info">no slides found</span>';
	} 

	$output .='</div></div>';

	}
	else
	{
		$output ='
			<div class="itxsl-admin-sliders-active">
				<H2>Active slides</H2>
				<div class="itxsl-admin-rows-active itxsl-admin-sliders-group">
				</div>
			</div>';

			$data = 0;
	}

	echo $output;

	$args = array(
				'post__not_in' => $data,
    			'post_type'  => 'itxsl_slide',
    			'meta_key' => '_thumbnail_id',
    			
    		);

	$query = new WP_Query($args);

		$output = '<div class="itxsl-admin-sliders"><H2>All slides</H2><div class="itxsl-admin-rows-all itxsl-admin-sliders-group">
        		';
    	if ( $query->have_posts() ) { // you never checked to see if no posts were found
    	while($query->have_posts()) { // alt style syntax doesn't work with most IDEs
        	$query->the_post(); // individual statement should be on individual line
        	 
                	if ( has_post_thumbnail() ) { 
        		$output .= '<div class="itxsl-admin-row">
        						<div class="itxsl-admin-row-title">
        							<span class="itxsl-admin-handle dashicons dashicons-sort"></span>
        							<h2 class="itxsl-admin-row-title-text">
        							<span class="itxsl-admin-order"></span>'. get_the_title() . '</h2><A href="'.get_edit_post_link().'" target=_blank><span class="itxsl-admin-edit dashicons dashicons-edit"></span></A>
        						</div>';
            	$output .=  '	<div class="itxsl-admin-row-background" style=\'background-size:cover; background: url("'.get_the_post_thumbnail_url() . '");\'></div>';
            	$output.='
            					<input class="itxsl-admin-row-input" name="_itxsl['.get_the_ID().']" slider-id="'.get_the_ID().'" type="hidden" >
            				</div>';
            
        	}
    	}

	} else {
	    $output .=  '<span class="itxsl-admin-box-info">No other slides found</span>';
	} 

	$output .='</div></div>';
	echo $output;

	}

	function itxsl_save_post( $post_id, $post ){

		if(array_key_exists('itxsl', $_POST))
		{
			if(sizeof($_POST['itxsl'])==0)
				delete_post_meta($post_id, 'itxsl');
			else if (!empty($_POST['itxsl']) && is_array($_POST['itxsl'])) {
	    	    delete_post_meta($post_id, 'itxsl');
        		foreach ($_POST['itxsl'] as $itxsl => $val) { 	
	            	add_post_meta($post_id, 'itxsl', $itxsl);
    	    }
    	}
    }

}


		/* !2. SHORTCODES */

		// 2.1
		public function itxsl_register_shortcodes() {
		  add_shortcode( 'itxslider', array(&$this, 'itxsl_shortcode' ));
		}

		// 2.2
		public function itxsl_shortcode( $args, $content="") {
		  
		  // setup our output variable - the form html 
		  $output = '

				<div class="itxsl-slider-wrap">
				<div class="itxsl-slider">
		  		<div class="itxsl-container"><div class="itxsl-overlay"></div><ul>';


		  if(isset($args['id']))
		  	$this->slider_id = (int)$args['id'];
		  else
		  	{
		  	$output .= "<LI class='itxsl-slide'><P>slider ID error - please check your shortcode</P></LI>";
		  	return $output;
		  	}


		  $this->itxsl_get_slides();

		  foreach($this->slider_slides as $slide)
		  {
		  			$image = wp_get_attachment_image_src( get_post_thumbnail_id( (int)$slide->ID ), 'single-post-thumbnail');
		  			
						$output .= "<li class='itxsl-slide' id='itxsl_slide_$slide->ID'/><div class='itxsl-slide-background zoomin'  style=\"background: url('$image[0]');\"></div><div class='itxsl-slide-text'><H1>".$slide->post_title."</H1><HR><P>".$slide->post_content."</P></div></li>";

		  }

		  $output .= "</ul></div></div></div>";
		  
		  return $output;
		  
		}

		/* !3. FILTERS */

		// 3.1
		function itxsl_slider_column_headers($columns)
		{
			
			$columns = array(
				'cb'=>'<input type="checkbox" />',
				'title'=>__( 'Slider Name'),
				'itxsl_slider_timeout'=>__( 'Slider Timeout'),
				'itxsl_slider_shortcode'=>__( 'Slider Shortcode'),
			);	

			return $columns;
		}

		// 3.2
		function itxsl_slider_column_data($column, $post_id) {

			$output = '';

			switch($column) {
				case 'title':
					$output = get_field('itxsl_slider_name', $post_id);
					break;
				case 'itxsl_slider_shortcode':
					$shortcode = "[itxslider id=\"$post_id\"]";			
					$output = $shortcode;
					break;
			}

			echo $output;
		}

		// 3.3
		// Registering custom admin titles fix
		function itxsl_register_custom_admin_titles() {

			add_filter('the_title', array(&$this, 'itxsl_custom_admin_titles'), 99, 2);
			
		}

		// 3.4
		// Custom admin titles fix for custom post types that do not have title field
		function itxsl_custom_admin_titles( $title, $post_id) {

			global $post;

			$output = $title;

			if(isset($post->post_type)):
				switch ($post->post_type) {
					case 'itxsl_slider':
						$output = get_field('itxsl_slider_name', $post_id);
						break;
				}
			endif;

			return $output;

		}

		// 3.5
		function itxsl_slide_column_headers($columns)
		{
			$columns = array(
				'cb'=>'<input type="checkbox"/>',
				'title'=>__( 'Slider Name'),
			);	

			return $columns;
		}

		//3.6

		function itxsl_page_slider_object_rows( $title, $post, $field, $post_id ) {

    	// add post type to each result
    	$title = get_field('itxsl_slider_name', $post->ID);

    	return $title;

		}


		/* !4. EXTERNAL SCRIPTS */

		
		function itxsl_add_scripts_and_styles() {


		    wp_register_style( 'itxsl_styles',  plugins_url('/css/styles.css',__FILE__));
		    wp_register_script( 'itxsl_script', plugins_url('/js/script.js', __FILE__ )  , array( 'jquery' ), '', true );
		    
		   // $arrayOfValues = itxsl_get_settings();

		    $arrayOfValues = array( 'timeout' => 6000);

			wp_localize_script( 'itxsl_script', 'itxsl_js_data', $arrayOfValues );


		    wp_enqueue_style('itxsl_styles');
		    wp_enqueue_script('itxsl_script');
		}

		function itxsl_admin_scripts() {
			wp_register_style( 'itxsl_styles',  plugins_url('/css/styles.css',__FILE__));
			wp_enqueue_style('itxsl_styles');

			wp_register_script( 'itxsl_admin_script', plugins_url('/js/admin/script.js', __FILE__ )  , array( 'jquery', 'jquery-ui-sortable' ), '', true );

			wp_enqueue_script('itxsl_admin_script');

		}


		/* !5. ACTIONS */

		//5.1
		function itxsl_get_slides()
		{
	
			$slides = get_post_meta($this->slider_id, 'itxsl');

			foreach($slides as $slide)
			{

						$this->slider_slides[]=get_post($slide);
		
			}
		}

		function itxsl_show_header_slider(){

			$post = get_post();


			//print_r(get_post_custom(get_the_ID($post)));
			

			$page_slider_ID = get_post_meta(get_the_ID($post), 'itxsl_page_slider');

			$slider_ID = 0;

			if(!empty($page_slider_ID))
				{
					if($page_slider_ID[0]!="null")
						$slider_ID = $page_slider_ID[0];
				}
			else
				$slider_ID = $this->itxsl_get_option('itxsl_header_slider_id');
			
	
			if($slider_ID)
				echo do_shortcode( "[itxslider id=\"$slider_ID\"]", false );			

		}


		/* !6. HELPERS */

		function itxsl_get_slides_select($input_name="", $intput_id="", $parent=-1, $value_field="id", $selected_value="") {

			$sliders = get_posts([
  				'post_type' => 'itxsl_slider',
  				'post_status' => 'publish',
  				'numberposts' => -1,  				
  				// 'order'    => 'ASC'
  				]);

			$output = 
			'<select name="'. $input_name .'" ';

			if(strlen($intput_id)):

				$output .=' id="' . $intput_id .'" ';

			endif;

			$output .= '><option value=""> - No Slider -</option>';


				foreach ($sliders as $slider) 
				{			
					$value = $slider->ID;


					$selected = '';
					if ($selected_value == $value) : $selected = ' selected="selected" '; endif;

					$option = '<option value="' . $value . '" ' . $selected . '>';
					$option .= $slider->itxsl_slider_name;
					$option .= '</option>';
					
					$output .= $option;
				}

			$output .='</select>';

			return $output;

		}

		function itxsl_get_current_options() {

			$options = array ();

			try {

				$options = array (
					'itxsl_header_slider_id' => $this->itxsl_get_option('itxsl_header_slider_id'),
				);
			}
			catch ( Exception $e )
			{

			}

			return $options;

		}

		function itxsl_get_option( $option_name ) {

			$option_value = '';

			try {

				$defaults = $this->itxsl_get_default_options();

				switch ($option_name) {
					case 'itxsl_header_slider_id' :

					$option_value = (get_option('itxsl_header_slider_id')) ? get_option('itxsl_header_slider_id') : $defaults['itxsl_header_slider_id'];
					
					break;
				}
			}

			catch ( Exception $e) {

			}

			return $option_value;

		}

		function itxsl_get_default_options() {

			$defaults = array(
				'itxsl_header_slider_id' => 0
			);

			return $defaults;
		}


		/* !8. ADMIN PAGES */

		function itxsl_dashboard_admin_page() {

			$output = '
			<div class="wrap">

				<h2>ITX Slider Dashboard</h2>
				<p> Here you\'ll find some informations about our slider plugin. </p>

			</div>';

			echo $output;

		}

		function itxsl_settings_admin_page() {

	
			$options = $this->itxsl_get_current_options();

			echo ('
				<div class="wrap">

				<h2>ITX Slider Settings</h2>

				<p> General plugin settings page. </p>

				<form action="options.php" method="post">');

				settings_fields( 'itxsl_plugin_options' );
				@do_settings_fields( 'itxsl_plugin_options' , 'itxsl_plugin_options'  );;


			echo ('<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="itxsl_header_slider_id">Select default header slider</label></th>
							<td>'.
							 $this->itxsl_get_slides_select('itxsl_header_slider_id', 'itxsl_header_slider_id', 0, 'id', $options['itxsl_header_slider_id']) .
							 '<p class="description" id="itxsl_header_slider_id-description">Please select slider that apears on the page header</p>
							 </td>
						</tr>
					</tbody>
				</table>
				');
				
				@submit_button();
				

				echo ('</form></div>');


		}


		function itxsl_admin_menus() {

			$top_menu_item = 'itxsl_dashboard_admin_page';

			add_menu_page( '', "ITX Slider Admin", 'manage_options', 'itxsl_dashboard_admin_page', array(&$this,'itxsl_dashboard_admin_page'), 'dashicons-images-alt2');


			add_submenu_page( $top_menu_item, "Dashboard", "Dashboard", 'manage_options', 'itxsl_dashboard_admin_page', array(&$this,'itxsl_dashboard_admin_page') );

			add_submenu_page( $top_menu_item, "Slides", "Slides", 'manage_options', 'edit.php?post_type=itxsl_slide' );
			
			add_submenu_page( $top_menu_item, "Slider List", "Slider List", 'manage_options', 'edit.php?post_type=itxsl_slider' );

			add_submenu_page( $top_menu_item, "Settings", "Settings", 'manage_options', 'itxsl_settings_admin_page', array(&$this,'itxsl_settings_admin_page') );

		}


		/* !9. SETTINGS */

		function itxsl_register_settings() {

			register_setting('itxsl_plugin_options', 'itxsl_header_slider_id');

			}

	}

}

if(class_exists('itxsl_Slider'))
{
	$itxsl_slider = new itxsl_Slider();
}






/*function my_acf_load_field( $value, $post_id, $field ) {
		
     //$shortcode = get_field('itxsl_slider_shortcode', $post_id);
		
   //  $shortcode['default_value'] = "TEST";
   //  return $shortcode;
	return "test";
		 
}*/

//add_filter('acf/load_field/name=itxsl_slider', 'my_acf_load_field', 10 ,3);





/* !10. FIELDS AND POST TYPES */




