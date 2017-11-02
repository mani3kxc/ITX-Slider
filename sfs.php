<?php

/*
Plugin Name:  Simple FreeSlider 
Plugin URI:   http://itextreme.pl/plugins/sfs/
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

  	1.1 - add_action( 'init', 'sfs_register_shortcodes' );
	1.2 - add_filter( 'manage_sfs_slide_posts_columns', 'sfs_slide_column_headers');
	1.3 - add_filter( 'manage_sfs_slider_posts_columns', 'sfs_slider_column_headers');
	1.4 - add_filter( 'manage_sfs_slider_posts_custom_column', 'sfs_slider_column_data',1,2);
	1.5 - add_filter( 'acf/fields/post_object/result', 'sfs_slide_post_object_rows', 10,4);
	1.6 - add_action('admin_head-edit.php', 'sfs_register_custom_admin_titles');
	1.7 - add_action('wp_enqueue_scripts', 'sfs_add_scripts_and_styles' );
  
  2. SHORTCODES
    2.1 - sfs_register_shortcode()
    2.2 - sfs_shortcode()

  3. FILTERS
  	3.1 - sfs_slider_column_headers()
  	3.2 - sfs_slider_column_data()
  	3.3 - sfs_register_custom_admin_titles()
  	3.4 - sfs_custom_admin_titles()
  	3.5 - sfs_slide_column_headers()
  	3.6 - sfs_slide_post_object_rows()
    
  4. EXTERNAL SCRIPTS

  	4.1 - sfs_add_scripts_and_styles()
    
  5. ACTIONS

  	5.1 - sfs_get_slides()
    
  6. HELPERS
    
  7. CUSTOM POST TYPES
  
  8. ADMIN PAGES
  
  9. SETTINGS

*/

include_once( plugin_dir_path( __FILE__ ) . 'lib/advanced-custom-fields/acf.php');
include_once( plugin_dir_path( __FILE__ ) . '/custom_post_types/sfs_custom_post_types.php');


if(!class_exists('SFS_Slider')) {

	class SFS_Slider {

		private $slider_id = 0;
		private $slider_slides = array();


		public function SFS_Slider()
		{
			$this->SFS_Slider_Init();			
		}

		public function SFS_Slider_Init()
		{

			/* !1. HOOKS */

			add_action( 'init', array(&$this, 'sfs_register_shortcodes' ));

			//1.2
			add_filter( 'manage_sfs_slide_posts_columns', array(&$this, 'sfs_slide_column_headers'));

			//1.3
			add_filter( 'manage_sfs_slider_posts_columns', array(&$this, 'sfs_slider_column_headers'));

			//1.4
			add_filter( 'manage_sfs_slider_posts_custom_column', array(&$this, 'sfs_slider_column_data'),1,2);

			//1.5
			add_filter( 'acf/fields/post_object/result', array(&$this, 'sfs_slide_post_object_rows'), 10,4);

			//1.6
			add_action('admin_head-edit.php', array(&$this, 'sfs_register_custom_admin_titles'));

			//1.7
			add_action('wp_enqueue_scripts', array(&$this, 'sfs_add_scripts_and_styles') );

			//1.8
			add_action('admin_enqueue_scripts', array(&$this, 'sfs_admin_scripts') );

			//1.9
			add_action('admin_menu', array(&$this, 'sfs_admin_menus') ); 

			add_action( 'add_meta_boxes', array( &$this, 'sfs_slider_add_meta_box' ) );

			add_action( 'save_post', array( &$this, 'sfs_save_post'), 10, 2 );
 

			add_action('sfs_header_slider', array( &$this, 'sfs_show_header_slider'));

			add_action('admin_init', array(&$this, 'sfs_register_settings'));


			add_filter('acf/settings/path', 'sfs_acf_settings_path');
			add_filter('acf/settings/dir', 'sfs_acf_settings_dir');
			add_filter('acf/settings/show_admin', 'sfs_acf_show_admin');
			if(!defined('ACF_LITE')) define('ACF_LITE', true);
		}


 
    /**
     * Adds the meta box container.
     */
    public function sfs_slider_add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'sfs_slider' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box('sfs_sliders_meta_box', __( 'Choose Sliders', 'sfs_slider' ),
                array( $this, 'sfs_render_slider_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }

       // wp_nonce_field( "sfs_nonce_action", "sfs_nonce" );

    }

  


    function sfs_render_slider_meta_box_content()
	{
		global $post;

		//$data = get_post_meta($post->ID,"sfs_sliders_meta_box",true);

		if(get_post_status()!='auto-draft')
		{

		$data = get_post_custom($post->ID);
		$data = $data['sfs'];

  		$args = array(
  				'post__in'  => $data,
    			'orderby' => 'post__in',
    			'post_type'  => 'sfs_slide',
    			'meta_key' => '_thumbnail_id');

		$query = new WP_Query($args);

		$output = '<div class="sfs-admin-sliders-active"><H2>Active slides</H2><div class="sfs-admin-rows-active sfs-admin-sliders-group">
        		';

    	if ( $query->have_posts() ) { // you never checked to see if no posts were found
    	while($query->have_posts()) { // alt style syntax doesn't work with most IDEs
        	$query->the_post(); // individual statement should be on individual line
        	 
        	if ( has_post_thumbnail() ) { 
        		$output .= '<div class="sfs-admin-row">
        						<div class="sfs-admin-row-title">
        							<span class="sfs-admin-handle dashicons dashicons-sort"></span>
        							<h2 class="sfs-admin-row-title-text">
        							<span class="sfs-admin-order"></span>'. get_the_title() . '</h2><A href="'.get_edit_post_link().'" target=_blank><span class="sfs-admin-edit dashicons dashicons-edit"></span></A>
        						</div>';
            	$output .=  '	<div class="sfs-admin-row-background" style=\'background-size:cover; background: url("'.get_the_post_thumbnail_url() . '");\'></div>';
            	$output.='
            					<input class="sfs-admin-row-input" name="sfs['.get_the_ID().']" slider-id="'.get_the_ID().'" type="hidden" >
            				</div>';
    
        	}
    	}

	} else {
	    $output .=  '<p>no slides found</p>';
	} 

	$output .='</div></div>';

	}
	else
	{
		$output ='
			<div class="sfs-admin-sliders-active">
				<H2>Active slides</H2>
				<div class="sfs-admin-rows-active sfs-admin-sliders-group">
				</div>
			</div>';

			$data = 0;
	}

	echo $output;

	$args = array(
				'post__not_in' => $data,
    			'post_type'  => 'sfs_slide',
    			'meta_key' => '_thumbnail_id',
    			
    		);

	$query = new WP_Query($args);

		$output = '<div class="sfs-admin-sliders"><H2>All slides</H2><div class="sfs-admin-rows-all sfs-admin-sliders-group">
        		';
    	if ( $query->have_posts() ) { // you never checked to see if no posts were found
    	while($query->have_posts()) { // alt style syntax doesn't work with most IDEs
        	$query->the_post(); // individual statement should be on individual line
        	 
                	if ( has_post_thumbnail() ) { 
        		$output .= '<div class="sfs-admin-row">
        						<div class="sfs-admin-row-title">
        							<span class="sfs-admin-handle dashicons dashicons-sort"></span>
        							<h2 class="sfs-admin-row-title-text">
        							<span class="sfs-admin-order"></span>'. get_the_title() . '</h2><A href="'.get_edit_post_link().'" target=_blank><span class="sfs-admin-edit dashicons dashicons-edit"></span></A>
        						</div>';
            	$output .=  '	<div class="sfs-admin-row-background" style=\'background-size:cover; background: url("'.get_the_post_thumbnail_url() . '");\'></div>';
            	$output.='
            					<input class="sfs-admin-row-input" name="_sfs['.get_the_ID().']" slider-id="'.get_the_ID().'" type="hidden" >
            				</div>';
            
        	}
    	}

	} else {
	    $output .=  '<p>No other slides found</p>';
	} 

	$output .='</div></div>';
	echo $output;

	}

		function sfs_save_post( $post_id, $post ){

	    if (!empty($_POST['sfs']) && is_array($_POST['sfs'])) {
    	    delete_post_meta($post_id, 'sfs');
        	foreach ($_POST['sfs'] as $sfs => $val) { 	
            	add_post_meta($post_id, 'sfs', $sfs);
        }
    }


}


		/* !2. SHORTCODES */

		// 2.1
		public function sfs_register_shortcodes() {
		  add_shortcode( 'sfslider', array(&$this, 'sfs_shortcode' ));
		}

		// 2.2
		public function sfs_shortcode( $args, $content="") {
		  
		  // setup our output variable - the form html 
		  $output = '

				<div class="itx-slider-wrap">
				<div class="itx-slider">
		  		<div class="sfs-container"><div class="sfs-overlay"></div><ul>';


		  if(isset($args['id']))
		  	$this->slider_id = (int)$args['id'];
		  else
		  	{
		  	$output .= "<LI class='sfs-slide'><P>slider ID error - please check your shortcode</P></LI>";
		  	return $output;
		  	}


		  $this->sfs_get_slides();

		  foreach($this->slider_slides as $slide)
		  {
		  			$image = wp_get_attachment_image_src( get_post_thumbnail_id( (int)$slide->ID ), 'single-post-thumbnail');
		  			
						$output .= "<li class='sfs-slide' id='sfs_slide_$slide->ID'/><div class='sfs-slide-background zoomin'  style=\"background: url('$image[0]');\"></div><div class='sfs-slide-text'><H1>".$slide->post_title."</H1><HR><P>".$slide->post_content."</P></div></li>";

		  }

		  $output .= "</ul></div></div></div>";
		  
		  return $output;
		  
		}

		/* !3. FILTERS */

		// 3.1
		function sfs_slider_column_headers($columns)
		{
			
			$columns = array(
				'cb'=>'<input type="checkbox" />',
				'title'=>__( 'Slider Name'),
				'sfs_slider_timeout'=>__( 'Slider Timeout'),
				'sfs_slider_shortcode'=>__( 'Slider Shortcode'),
			);	

			return $columns;
		}

		// 3.2
		function sfs_slider_column_data($column, $post_id) {

			$output = '';

			switch($column) {
				case 'title':
					$output = get_field('sfs_slider_name', $post_id);
					break;
				case 'sfs_slider_shortcode':
					$shortcode = "[sfslider id=\"$post_id\"]";			
					$output = $shortcode;
					break;
			}

			echo $output;
		}

		// 3.3
		// Registering custom admin titles fix
		function sfs_register_custom_admin_titles() {

			add_filter('the_title', array(&$this, 'sfs_custom_admin_titles'), 99, 2);
			
		}

		// 3.4
		// Custom admin titles fix for custom post types that do not have title field
		function sfs_custom_admin_titles( $title, $post_id) {

			global $post;

			$output = $title;

			if(isset($post->post_type)):
				switch ($post->post_type) {
					case 'sfs_slider':
						$output = get_field('sfs_slider_name', $post_id);
						break;
				}
			endif;

			return $output;

		}

		// 3.5
		function sfs_slide_column_headers($columns)
		{
			$columns = array(
				'cb'=>'<input type="checkbox"/>',
				'title'=>__( 'Slider Name'),
			);	

			return $columns;
		}

		//3.6


		/* !4. EXTERNAL SCRIPTS */

		
		function sfs_add_scripts_and_styles() {


		    wp_register_style( 'sfs_styles',  plugins_url('/css/styles.css',__FILE__));
		    wp_register_script( 'sfs_script', plugins_url('/js/script.js', __FILE__ )  , array( 'jquery' ), '', true );
		    
		   // $arrayOfValues = sfs_get_settings();

		    $arrayOfValues = array( 'timeout' => 6000);

			wp_localize_script( 'sfs_script', 'sfs_js_data', $arrayOfValues );


		    wp_enqueue_style('sfs_styles');
		    wp_enqueue_script('sfs_script');
		}

		function sfs_admin_scripts() {
			wp_register_style( 'sfs_styles',  plugins_url('/css/styles.css',__FILE__));
			wp_enqueue_style('sfs_styles');

			wp_register_script( 'sfs_admin_script', plugins_url('/js/admin/script.js', __FILE__ )  , array( 'jquery', 'jquery-ui-sortable' ), '', true );

			wp_enqueue_script('sfs_admin_script');

		}


		/* !5. ACTIONS */

		//5.1
		function sfs_get_slides()
		{
	
			$slides = get_post_meta($this->slider_id, 'sfs');

			foreach($slides as $slide)
			{

						$this->slider_slides[]=get_post($slide);
		
			}
		}

		function sfs_show_header_slider(){
			$header_slider_ID = $this->sfs_get_option('sfs_header_slider_id');

			if($header_slider_ID)
				echo do_shortcode( "[sfslider id=\"$header_slider_ID\"]", false );			
			else 
				echo "NO SLIDER TODAY";
		}


		/* !6. HELPERS */

		function sfs_get_slides_select($input_name="", $intput_id="", $parent=-1, $value_field="id", $selected_value="") {

			$sliders = get_posts([
  				'post_type' => 'sfs_slider',
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
					$option .= $slider->sfs_slider_name;
					$option .= '</option>';
					
					$output .= $option;
				}

			$output .='</select>';

			return $output;

		}

		function sfs_get_current_options() {

			$options = array ();

			try {

				$options = array (
					'sfs_header_slider_id' => $this->sfs_get_option('sfs_header_slider_id'),
				);
			}
			catch ( Exception $e )
			{

			}

			return $options;

		}

		function sfs_get_option( $option_name ) {

			$option_value = '';

			try {

				//$defaults = $this->sfs_get_default_options();

				switch ($option_name) {
					case 'sfs_header_slider_id' :

					$option_value = (get_option('sfs_header_slider_id')) ? get_option('sfs_header_slider_id') : $defaults['sfs_header_slider_id'];
					
					break;
				}
			}

			catch ( Exception $e) {

			}

			return $option_value;

		}

		function slb_get_default_options() {

			$defaults = array(
				'sfs_header_slider_id' => 0
			);

			return $defaults;
		}


		/* !8. ADMIN PAGES */

		function sfs_dashboard_admin_page() {

			$output = '
			<div class="wrap">

				<h2>SFS Slider Dashboard</h2>
				<p> Here you\'ll find some informations about our slider plugin. </p>

			</div>';

			echo $output;

		}

		function sfs_settings_admin_page() {

	
			$options = $this->sfs_get_current_options();

			echo ('
				<div class="wrap">

				<h2>SFS Slider Settings</h2>

				<p> General plugin settings page. </p>

				<form action="options.php" method="post">');

				settings_fields( 'sfs_plugin_options' );
				@do_settings_fields( 'sfs_plugin_options' , 'sfs_plugin_options'  );;


			echo ('<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="sfs_header_slider_id">Select header slider</label></th>
							<td>'.
							 $this->sfs_get_slides_select('sfs_header_slider_id', 'sfs_header_slider_id', 0, 'id', $options['sfs_header_slider_id']) .
							 '<p class="description" id="sfs_header_slider_id-description">Please select slider that apears on the page header</p>
							 </td>
						</tr>
					</tbody>
				</table>
				');
				
				@submit_button();
				

				echo ('</form></div>');


		}


		function sfs_admin_menus() {

			$top_menu_item = 'sfs_dashboard_admin_page';

			add_menu_page( '', "SFS Slider Admin", 'manage_options', 'sfs_dashboard_admin_page', array(&$this,'sfs_dashboard_admin_page'), 'dashicons-images-alt2');


			add_submenu_page( $top_menu_item, "Dashboard", "Dashboard", 'manage_options', 'sfs_dashboard_admin_page', array(&$this,'sfs_dashboard_admin_page') );

			add_submenu_page( $top_menu_item, "Slides", "Slides", 'manage_options', 'edit.php?post_type=sfs_slide' );
			
			add_submenu_page( $top_menu_item, "Slider List", "Slider List", 'manage_options', 'edit.php?post_type=sfs_slider' );

			add_submenu_page( $top_menu_item, "Settings", "Settings", 'manage_options', 'sfs_settings_admin_page', array(&$this,'sfs_settings_admin_page') );

		}


		/* !9. SETTINGS */

		function sfs_register_settings() {

			register_setting('sfs_plugin_options', 'sfs_header_slider_id');

			}

	}

}

if(class_exists('SFS_Slider'))
{
	$sfs_slider = new SFS_Slider();
}






/*function my_acf_load_field( $value, $post_id, $field ) {
		
     //$shortcode = get_field('sfs_slider_shortcode', $post_id);
		
   //  $shortcode['default_value'] = "TEST";
   //  return $shortcode;
	return "test";
		 
}*/

//add_filter('acf/load_field/name=sfs_slider', 'my_acf_load_field', 10 ,3);





/* !10. FIELDS AND POST TYPES */




