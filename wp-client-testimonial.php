<?php
/**
 * Plugin Name: WP Client Testimonial
 * Plugin URI: https://aftabhusain.wordpress.com/
 * Description: Display client testimonial with slide previous and next features. And having different color settings from admin .
 * Version: 2.0
 * Author: Aftab Husain
 * Author URI: https://aftabhusain.wordpress.com/
 * License: GPLv2
 */

if(!defined('ABSPATH')) exit; // Prevent Direct Browsing

define('AWTS_INCLUDE_DIR', plugin_dir_path(__FILE__).'include/');


// CSS and JS include
function aft_incl_script_style() {
    wp_enqueue_script('jquery' );
    wp_enqueue_style( 'aft-owl-style', plugins_url('/include/styles.css', __FILE__) );
    wp_enqueue_script( 'aft-main-js', plugins_url('/include/carousels.js', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'aft_incl_script_style' );

// Testimonial Custom Post Type
function aft_custom_post_type(){

    $labels = array(
        'name'                => _x( 'Client Testimonials', 'aft' ),
        'singular_name'       => _x( 'Client Testimonial', 'aft' ),
        'menu_name'           => __( 'Client Testimonials', 'aft' ),
        'parent_item_colon'   => __( 'Parent Client Testimonials:', 'aft' ),
        'all_items'           => __( 'All Testimonials', 'aft' ),
        'view_item'           => __( 'View Testimonial', 'aft' ),
        'add_new_item'        => __( 'Add New Testimonial', 'aft' ),
        'add_new'             => __( 'New Testimonial', 'aft' ),
        'edit_item'           => __( 'Edit Testimonial', 'aft' ),
        'update_item'         => __( 'Update Testimonial', 'aft' ),
        'search_items'        => __( 'Search Testimonials', 'aft' ),
        'not_found'           => __( 'No Testimonials found', 'aft' ),
        'not_found_in_trash'  => __( 'No Testimonials found in Trash', 'aft' ),
    );
    $args = array(

        'labels'              => $labels,
        'description'         => __( 'Testimonials Post Type', 'aft' ),
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => '',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type( 'testimonials', $args );


}

add_action('init' , 'aft_custom_post_type');


// admin menu
function aft_menus() {
	add_submenu_page("edit.php?post_type=testimonials", "Settings", "Settings", "administrator", "testimonial-settings", "aft_pages");
}
add_action("admin_menu", "aft_menus");


//function menu pages
function aft_pages() {

   $setting = AWTS_INCLUDE_DIR.$_GET["page"].'.php';
   include($setting);

}

add_action( 'admin_enqueue_scripts', 'aft_wp_enqueue_color_picker' );
function aft_wp_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', plugins_url('include/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


// Testimonial Meta Box
function aft_testimonial_add_meta_box(){

// add meta Box
    add_meta_box(
        'aft_testimonial_meta_id', 					// ID
        __( 'Client Information', 'aft' ), 			// Testimonial Meta Box Title
        'aft_meta_callback', 						// Call Back Funtion
        'testimonials',								//Post Type
        'normal'

    );

}
add_action('add_meta_boxes' , 'aft_testimonial_add_meta_box');

// Testimonial Meta Box Call Back Funtion
function aft_meta_callback($post){

    wp_nonce_field( basename( __FILE__ ), 'aft_nonce' );
    $aft_stored_meta = get_post_meta( $post->ID );
    ?>

    <p>
        <label for="aft_testimonial_meta_name" class="aft_testimonial_meta_name"><?php _e( 'Name', 'aft' )?></label>
        <input class="widefat" type="text" name="aft_testimonial_meta_name" id="aft_testimonial_meta_name" value="<?php if ( isset ( $aft_stored_meta['aft_testimonial_meta_name'] ) ) echo $aft_stored_meta['aft_testimonial_meta_name'][0]; ?>" />
    </p>

    <p>
        <label for="aft_testimonial_meta_destignation" class="aft_testimonial_meta_destignation"><?php _e( 'Designation', 'aft' )?></label>
        <input class="widefat" type="text" name="aft_testimonial_meta_destignation" id="aft_testimonial_meta_destignation" value="<?php if ( isset ( $aft_stored_meta['aft_testimonial_meta_destignation'] ) ) echo $aft_stored_meta['aft_testimonial_meta_destignation'][0]; ?>" />
    </p>

<?php

}


//Testimonial Save Meta Box 

function aft_testimonial_meta_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'aft_nonce' ] ) && wp_verify_nonce( $_POST[ 'aft_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'aft_testimonial_meta_name' ] ) ) {
        update_post_meta( $post_id, 'aft_testimonial_meta_name', sanitize_text_field( $_POST[ 'aft_testimonial_meta_name' ] ) );
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'aft_testimonial_meta_destignation' ] ) ) {
        update_post_meta( $post_id, 'aft_testimonial_meta_destignation', sanitize_text_field( $_POST[ 'aft_testimonial_meta_destignation' ] ) );
    }

}
add_action( 'save_post', 'aft_testimonial_meta_save' );



//Testimonials Dashboard Icons
function aft_testimonials_dashboard_icon(){
?>
 <style>
#adminmenu .menu-icon-testimonials div.wp-menu-image:before {
  content: "\f205";
}
</style>
<?php
}
add_action( 'admin_head', 'aft_testimonials_dashboard_icon' );

//Client Testimonial Shortcode 

add_shortcode( 'wp-client-testimonial', 'aft_client_testimonial_shortcode' );
function aft_client_testimonial_shortcode( $atts ) {
    ob_start();
	
	//get saved settings
    $main_box_bg_color= get_option('main_box_bg_color'); 
    $all_text_color= get_option('all_text_color'); 
    $client_content_box_color= get_option('client_content_box_color');
    $testimonial_heading_text= get_option('testimonial_heading_text'); 
	
	//default color settings
	if($main_box_bg_color == ''){ $main_box_bg_color='#405448'; }
	if($all_text_color == ''){ $all_text_color='#fff'; }
	if($client_content_box_color == ''){ $client_content_box_color='#2a4126'; }
	if($testimonial_heading_text == ''){ $testimonial_heading_text='Client Testimonials'; }
	?>
	<style>
	#wp-client-testimonials { background: <?php echo $main_box_bg_color;?> none repeat scroll 0 0; }
	#wp-client-testimonials .carousel-wrap .context { background:<?php echo $client_content_box_color;?>; }
	#wp-client-testimonials .carousel-wrap .context::after {
		border-color: <?php echo $client_content_box_color;?> transparent transparent;
		border-style: solid;
		border-width: 20px 18px 0;
		content: "";
		height: 0;
		left: 20px;
		position: relative;
		top: 44px;
		width: 0;
    }
	#wp-client-testimonials h2 { color: <?php echo $all_text_color;?>; }
	#wp-client-testimonials .prevbtn, #wp-client-testimonials .nextbtn { color: <?php echo $all_text_color;?>; }
	#wp-client-testimonials .student p { color: <?php echo $all_text_color;?> !important; }
	#wp-client-testimonials .context > p { color: <?php echo $all_text_color;?> !important; }
	</style>
	<?php
	
    extract( shortcode_atts( array (
        'type' => 'testimonials',
        'order' => 'date',
        'orderby' => 'title',
        'posts' => -1,
    
    ), $atts ) );
    $options = array(
        'post_type' => $type,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $posts,
  		
    );
    $query = new WP_Query( $options );?>
    <?php if ( $query->have_posts() ) { ?>
	
	
	 <div id="wp-client-testimonials">
	   <h2><?php echo $testimonial_heading_text;?></h2>
        <div class="carousel-nav clearfix">
		 <a id="prv-testimonial" class="prevbtn" href="javascript:void(0)"> << Prev </a>
		 <a id="nxt-testimonial" class="nextbtn" href="javascript:void(0)">Next >></a>
          
        </div>
        <div class="carousel-wrap">
          <ul id="testimonial-list" class="clearfix">
          <?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<li>
			
              <div class="context">  
			  <?php the_content();?></div>
			  <div class="student">
				<div class="photo"><?php the_post_thumbnail('thumbnail'); ?> </div>
				<p><?php echo  get_post_meta( get_the_ID(), 'aft_testimonial_meta_name', true );?></p>
				<p><?php echo  get_post_meta( get_the_ID(), 'aft_testimonial_meta_destignation', true );?></p>
			</div>
            </li>   
            <?php endwhile;
            wp_reset_postdata(); ?>
			<?php $myvariable = ob_get_clean();
			return $myvariable;
			?>
	     </ul>
       </div>
    </div>
	<div style="clear:both"></div>
	<?php
    } else{
		
		echo 'No Client Testimonials!';
	}  
}




