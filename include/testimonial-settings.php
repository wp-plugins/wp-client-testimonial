<?php
global $wpdb;
//sanitize all post values
$testmonial_setting_submit= sanitize_text_field( $_POST['testmonial_setting_submit'] );
$main_box_bg_color= sanitize_text_field( $_POST['main_box_bg_color'] );
$all_text_color= sanitize_text_field( $_POST['all_text_color'] );
$client_content_box_color= sanitize_text_field( $_POST['client_content_box_color'] );
$testimonial_heading_text= sanitize_text_field( $_POST['testimonial_heading_text'] );
$saved= sanitize_text_field( $_POST['saved'] );

if($testmonial_setting_submit!='') { 
    if(isset($main_box_bg_color) ) {
		update_option('main_box_bg_color', $main_box_bg_color);
    }
    if(isset($all_text_color) ) {
		update_option('all_text_color', $all_text_color);
    }
    if(isset($client_content_box_color) ) {
		update_option('client_content_box_color', $client_content_box_color);
    }
	if(isset($testimonial_heading_text) ) {
		update_option('testimonial_heading_text', $testimonial_heading_text);
    }
	
	if($saved==true) {
		$message='saved';
	} 
}
?>
  <?php
        if ( $message == 'saved' ) {
		echo ' <div class="updated settings-error"><p><strong>Settings Saved.</strong></p></div>';
		}
   ?>
   
<div class="wrap netgo-whois-post-setting">
    <form method="post" id="whoisSettingForm" action="">
	<h2><?php _e('Client Testimonials Setting','');?></h2>
		<table class="form-table">
		   <tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="testimonial_heading_text"><?php _e('Testimonial Heading','');?></label>
				</th>
				<td>
				<input name="testimonial_heading_text" type="text" value="<?php if(get_option('testimonial_heading_text') !=''){ echo get_option('testimonial_heading_text'); }else{ echo 'Client Testimonials';}?>"  />
				</td>
			</tr>
		
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="main_box_bg_color"><?php _e('Main Box Background Color','');?></label>
				</th>
				<td>
				<input name="main_box_bg_color" type="text" value="<?php echo get_option('main_box_bg_color'); ?>" class="wp-color-picker-field" data-default-color="#405448" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="all_text_color"><?php _e('All Text Color','');?></label>
				</th>
				<td>
				<input name="all_text_color" type="text" value="<?php echo get_option('all_text_color'); ?>" class="wp-color-picker-field" data-default-color="#fff" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width: 370px;">
					<label for="client_content_box_color"><?php _e('Client Content Box Color','');?></label>
				</th>
				<td>
				<input name="client_content_box_color" type="text" value="<?php echo get_option('client_content_box_color'); ?>" class="wp-color-picker-field" data-default-color="#2a4126" />
				</td>
			</tr>
		</table>
		
        <p class="submit">
		<input type="hidden" name="saved" value="saved"/>
        <input type="submit" name="testmonial_setting_submit" class="button-primary" value="Save Changes" />
		  <?php if(function_exists('wp_nonce_field')) wp_nonce_field('testmonial_setting_submit', 'testmonial_setting_submit'); ?>
        </p>
    </form>
</div>

