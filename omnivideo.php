<?php
/*
Plugin Name: OmniVideo
Plugin URI: http://www.colorlabsproject.com/plugins/omnivideo/
Description: Enhance your WordPress website with the OmniVideo Photo Gallery. You can easily add your videos from your Youtube, Vimeo or Daily Motion channel. Adding the gallery is very simple, you just have to add new post/page, then select gallery icon, select OmniVideo then you can select which video source you want to insert.
Version: 1.0
Author: ColorLabs & Company
Author URI: http://www.colorlabsproject.com

Copyright 2013 ColorLabs & Company

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function theme_name_scripts() {
	wp_enqueue_script( 'omnivideo-min', plugin_dir_url( __FILE__ ).'js/bootstrap.js', array('jquery') );
	wp_enqueue_script( 'omnivideo-js', plugin_dir_url( __FILE__ ).'js/scripts.js', array('jquery'));
	wp_enqueue_style( 'omnivideo-style', plugin_dir_url( __FILE__ ).'css/omnivideo.css' );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );	

function hide_gallery() {
	wp_enqueue_style('omnivideo-admin-css', plugins_url('css/admin.css', __FILE__), array());
}
add_action('admin_print_styles', 'hide_gallery');

/* media menu */
// add the tab
add_filter('media_upload_tabs', 'my_upload_tab');
function my_upload_tab($tabs) {
	$tabs['mytabname'] = "OmniVideo";
	return $tabs;
}

// call the new tab with wp_iframe
add_action('media_upload_mytabname', 'add_my_new_form');
function add_my_new_form() {
	wp_iframe( 'my_new_form' );
}

// the tab content
function my_new_form() {
	media_upload_header();
	wp_enqueue_script( 'media-editor' );
	wp_enqueue_style( 'media-views' );
	require_once(plugin_dir_path(__FILE__)."/omnivideo-form.php");
}

/* tab media */		

/* daily api */
function omnivideo_get_dailymotion($atts){
	extract(shortcode_atts(array(
		'username' => '',
		'result' => '1',
		'type' => 'image',
		'column' => '1',
		'description' => true,
	), $atts));

  $output = '';
  if ($username =='') {
		$output = '<h3>Oopssss! forgot to fill username!</h1>';
	}else{   		

		$data = wp_remote_get('https://api.dailymotion.com/user/'.$username.'/videos?fields=url,id,description,embed_url%2Cid%2Cthumbnail_url%2Ctitle%2Curl&limit='.$result);
		if(!is_wp_error($data)){
		
			$response = json_decode( $data['body'] );
			foreach ($response->list as $entry):      
				$output .=	'<li class="gallery-item">';
				$output .=	'<div class="omnivideo-thumb">';
				if ($type =='redirect') {
					$output .= '<a href="'.esc_html($entry->url).'" target="_blank" title="'.esc_html($entry->title).'"><img src="'.esc_html($entry->thumbnail_url).'" /></a>';
				}else{
							
					$output .= '<a href="#video-modal" data-iframe="http://www.dailymotion.com/embed/video/'.esc_html($entry->id).'" data-toggle="modal" title="'.esc_html($entry->title).'" class="test-pop"><img src="'.esc_html($entry->thumbnail_url).'" /></a>';
								
					if ($description == 'true') { 
						$output .= '<div class="omni-description">'.esc_html($entry->description).'</div>';
					}
							
					$output .= '<div class="modal fade" id="video-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h3 class="modal-title" id="myModalLabel">
																<?php echo esc_html($entry->title); ?>
															</h3>
														</div>
														<div class="modal-body">								
														</div>	
													</div>
												</div>
											</div>';
				} 
				
				$output .= '</div>';
				$output .= '<div class="gallery-caption">'.esc_html($entry->title).'</div>';	
				$output .= '</li>';
			
			endforeach;  
		}
	}
	return $output;					
}
/* daily api */


/* youtube api */
function omnivideo_get_youtube($atts){
  extract(shortcode_atts(array(
		'username' => '',
	  'result' => '1',
	  'type' => 'image',
	  'description' => false, 
  ), $atts));
  
	$output = '';
  if ($username =='') {
		$output = '<h3>Oopssss! forgot to fill username!</h1>';
	}else{   
		$data = wp_remote_get('http://gdata.youtube.com/feeds/api/users/'.$username.'/uploads/?start-index=1&max-results='.$result);
		
		if(!is_wp_error($data)){	
			$response =  new SimpleXMLElement($data['body']);
			
			foreach ($response->entry as $entry):
				$vid_id = '';
				if ( preg_match('#videos/([^/]+)$#', $entry->id, $matches) ) {
					$vid_id = $matches[1];
				}
				
				if ( $vid_id ):

					$output .= '<li class="gallery-item">';
					$output .= '<div class="omnivideo-thumb">';
						if ($type =='redirect') {
							$output .= '<a href="http://youtube.com/watch?v='.$vid_id.'" target="_blank" title="'.esc_html($entry->title).'">';
							$output .= '<img src="http://i1.ytimg.com/vi/'.$vid_id.'/0.jpg" /></a>';
						}else{
							$output .= '<a href="#video-modal" data-iframe="//www.youtube.com/embed/'.$vid_id.'"  data-toggle="modal" title="'.esc_html($entry->title).'" class="test-pop"><img src="http://i1.ytimg.com/vi/'.$vid_id.'/0.jpg" /></a>';
							
							if ($description == true) {
								$output .= '<div class="omni-description">'.esc_html($entry->content).'</div>';
							}	
						
							$output .= '<div class="modal fade" id="video-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3 class="modal-title" id="myModalLabel">'.esc_html($entry->title).'</h3>
										</div>

										<div class="modal-body">
										</div>
											
									</div>
								</div>
							</div>';
						}
						
					$output .= '</div>';
					$output .= '<div class="gallery-caption">'.esc_html($entry->title).'</div>';	
					$output .= '</li>';

				endif;
			endforeach;
			
		}
	}
	return $output;
} 


/* vimeo api */
function omnivideo_get_vimeo($atts){
  extract(shortcode_atts(array(
    'username' => '',
	  'result' => '1',
	  'type' => 'image',
	  'description' => false,
  ), $atts));

	$output = '';
  if ($username =='') {
		$output = '<h3>Oopssss! forgot to fill username!</h1>';
	}else{   
	
		$data = wp_remote_get('http://vimeo.com/api/v2/'. $username .'/videos.json');
		$counter = 1;
		
		if(!is_wp_error($data)){
			$response = json_decode( $data['body'] );	

			foreach ($response as $entry):      
						
				$output .= '<li class="gallery-item">';
				$output .= '<div class="omnivideo-thumb">';
							
				if ($type =='popup') {
							
					$output .= '<a href="#video-modal" data-iframe="//player.vimeo.com/video/'.esc_html($entry->id).'" data-toggle="modal" class="test-pop" ><img src="'. esc_html($entry->thumbnail_medium).'" /></a>';
					
					if ($description == true) { 
						$output .= '<div class="omni-description">'.esc_html($entry->description).'</div>';
					}
								
					$output .= '<div class="modal fade" id="video-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h3 class="modal-title"></h3>
								
								</div>
								<div class="modal-body">
								
								</div>							
							</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>';
				}else{ 
					$output .= '<a href="'.esc_html($entry->url).'" target="_blank" title="'.esc_html($entry->title).'"><img src="'.esc_html($entry->thumbnail_medium).'" /></a>';
				} 
				$output .= '</div>';
				$output .= '<div class="gallery-caption">'.esc_html($entry->title).'</div>';	
				$output .= '</li>';
					
				if($counter==$result) break;
				$counter++;
			
			endforeach;
		}
	}
	return $output;
}
/* vimeo api */
			
function omnivideo_code($atts){
  extract(shortcode_atts(array(
    'source' => 'youtube',
    'username' => 'UCkiXdz9KUfeyPLN_cYAxveg',
	'result' => '6',
	'type' => 'image',
	'column' => '2',
	'description' => false,
	
    ), $atts));
  $output = '<div class="omnivideo-wrapper">
							<ul class="gallery gallery-columns-'.$column.'">';
  if('youtube'==$source){
		$output .= omnivideo_get_youtube($atts);
  }elseif('vimeo'==$source){
		$output .= omnivideo_get_vimeo($atts);
  }else{
		$output .= omnivideo_get_dailymotion($atts);
	}
	$output .= '</ul></div>';
	return $output;
}
add_shortcode('omnivideo', 'omnivideo_code');



/**
 * Adds a simple WordPress pointer to Settings menu
 */
 
function thsp_enqueue_pointer_script_style( $hook_suffix ) {
	
	// Assume pointer shouldn't be shown
	$enqueue_pointer_script_style = false;

	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	// Check if our pointer is not among dismissed ones
	if( !in_array( 'thsp_settings_pointer', $dismissed_pointers ) ) {
		$enqueue_pointer_script_style = true;
		
		// Add footer scripts using callback function
		add_action( 'admin_print_footer_scripts', 'thsp_pointer_print_scripts' );
	}

	// Enqueue pointer CSS and JS files, if needed
	if( $enqueue_pointer_script_style ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	
}
add_action( 'admin_enqueue_scripts', 'thsp_enqueue_pointer_script_style' );

function thsp_pointer_print_scripts() {

	$pointer_content  = "<h3>OmniVideo Plugin is active!</h3>";
	$pointer_content .= "<p>You can start add a post/page and click on <b>add media</b> button to add your videos</p>";
	?>
	
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('#menu-plugins').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
										pointer: 'thsp_settings_pointer', // pointer ID
										action: 'dismiss-wp-pointer'
								});
							}
		}).pointer('open');
	});
	//]]>
	</script>

<?php
}
?>