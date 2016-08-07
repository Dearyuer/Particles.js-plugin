<?php 
/*
Plugin Name: ParticlesJS
Plugin URI: http://codrips.com
Description: particles.js plugin
Version: 1.0.0
Author: Dearyuer
Author URI: http://codrips.com
License: GPLv2 or Later
*/ 

require_once(plugin_dir_path(__FILE__).'/app/utl.php');

add_action('wp_enqueue_scripts', function(){
	$particle_enable_state = get_option("palette_particle_toggle");
	if($particle_enable_state && is_home()){
		wp_enqueue_script( 'particles',get_template_directory_uri().'/js/particles.min.js', [], false, true);
		wp_enqueue_script( 'app',get_template_directory_uri().'/js/app.js', [], false, true);
		require_once(plugin_dir_path(__FILE__).'/app/appData.php');
		require_once(plugin_dir_path(__FILE__).'/app/appL10n.php');
		wp_enqueue_style( 'particles_style',get_template_directory_uri().'/css/particle.css');
	}
});


function particles_custom_scripts( $hook_suffix ){
	wp_enqueue_style( 'admin_style', get_template_directory_uri().'/css/admin.css');
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'my-script-handle', get_template_directory_uri(). '/js/color-picker.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts' , 'particles_custom_scripts');


add_action( 'admin_menu', 'particles_js_add_menu_page' );

function particles_js_add_menu_page() {
    add_menu_page(
        'particles',
        __('ParticleJS','palette'),
        'manage_options',
        'particles',
        'particles_js_add_menu_page_fn',
        'dashicons-art',
        61
    );
}

function particles_js_add_menu_page_fn(){
	if (!current_user_can('manage_options')){
	  wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$noChangesSaved = 0;
	$paletteOptionParticle = "palette_particle_toggle";
	$particle_effect_state;


	
	require_once( plugin_dir_path(__FILE__)."/app/appData.php");
	if( isset($_POST['particle_image_submit']) && check_admin_referer( "particle_image" , "particle_image_nonce") ) {

		if( isset($_POST['particle_effect_checkbox']) && $_POST['particle_effect_checkbox']  == 'particle'){
			$particle_effect_state = get_option($paletteOptionParticle);
			if( empty($particle_effect_state) ) {
				update_option( $paletteOptionParticle, 1 );
				echo "<p>updated to true </p>";
				$noChangesSaved++;
			}
		}else{
			$particle_effect_state = get_option($paletteOptionParticle);
			if( !empty($particle_effect_state) ){
				update_option( $paletteOptionParticle, 0 );
				echo "<p>updated to false</p>";
				$noChangesSaved++;
			}
		}

		if ( 
			isset( $_POST['particle_image_nonce'], $_POST['post_id'] ) 
			&& wp_verify_nonce( $_POST['particle_image_nonce'], 'particle_image' )
			&& current_user_can( 'manage_options' )
		) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			
			// Let WordPress handle the upload.
			// Remember, 'particle_image' is the name of our file input in our form above.
			$particle_image_attachment_id = media_handle_upload( 'particle_image', $_POST['post_id'] );
			if ( is_wp_error( $particle_image_attachment_id ) ) { ?>
				<div class="notice notice-error">
					<p><?php _e("There's an error while uploading","palette") ?></p>
				</div>
			<?php
			} else {
				if(isset($particle_image_attachment_id)){
					update_option($particle_settings["image_id"],$particle_image_attachment_id);
					update_option($particle_settings["image_src"], wp_get_attachment_image_src($particle_image_attachment_id)[0]);
				}
				// echo wp_get_attachment_image($particle_image_attachment_id);
				?>

				<div class="notice notice-success">
					<p><strong><?php _e('Uploaded.', 'palette' ); ?></strong></p>
				</div>
				<?php
			} 
		}

	}//( 'palette_particle_effect_submit', 'palette_particle_effect_submit_nonce')
	if( 
	isset($_POST['palette_particle_effect_submit']) && check_admin_referer( 'palette_particle_effect_submit', 'palette_particle_effect_submit_nonce') 
	){

		require_once(plugin_dir_path(__FILE__)."/app/appException.php");
		
		require_once(plugin_dir_path(__FILE__)."/app/appLoop.php");
		
		
		if($noChangesSaved == 0){
			echo '<div class="update-nag"> No changes saved. </div>';
		} elseif($noChangesSaved == -1){
			// do nothing
		}else{
			echo '<div class="notice notice-success">'.__("Updated settings").'</div>';
		}
	}

	require_once(plugin_dir_path(__FILE__)."/app/appWrapSideOne.php");
}

?>



