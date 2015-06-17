<?php
/*
* Plugin Name: Admin Notices (WP Hacks)
* Version: 0.1.0b
* Description: Create Admin Notices in the dashboard. Great for friendly reminders to editors and authors. 
* Author: Michael Fitzpatrick-Ruth (alpha1beta)
* Author URI: https://github.com/WP-Hacks/Admin-Notices
* Text Domain: wphacks
* License: GPL v2 or later
*/
/*
TODO: Remove old metadata based notice types
TODO: remove preview button
TODO: add custom type colors
TODO: Add Default Colors for Message and User Error
TODO: Add System for Dismissible messages (per user closing)
TODO: Conditions
TODO: Multiple Hooks?
TODO: network support
TODO: Support Font Awesome, if installed
TODO: Add/Update Plugin Links

Limitation: Title and Message only allow a,i,b,img, and br
Limitation: Notices are not actually dismissible

*/
if ( ! function_exists('register_wphacks_admin_notices') ) {

// Register Custom Post Type
function register_wphacks_admin_notices() {
	$labels = array(
		'name'                => _x( 'Notices', 'Post Type General Name', 'wphacks' ),
		'singular_name'       => _x( 'Notice', 'Post Type Singular Name', 'wphacks' ),
		'menu_name'           => __( 'Notices', 'wphacks' ),
		'name_admin_bar'      => __( 'Notice', 'wphacks' ),
		'parent_item_colon'   => __( 'Parent Notice:', 'wphacks' ),
		'all_items'           => __( 'All Notices', 'wphacks' ),
		'add_new_item'        => __( 'Add New Notice', 'wphacks' ),
		'add_new'             => __( 'Add New', 'wphacks' ),
		'new_item'            => __( 'New Notice', 'wphacks' ),
		'edit_item'           => __( 'Edit Notice', 'wphacks' ),
		'update_item'         => __( 'Update Notice', 'wphacks' ),
		'view_item'           => __( 'View Notice', 'wphacks' ),
		'search_items'        => __( 'Search Notice', 'wphacks' ),
		'not_found'           => __( 'Not found', 'wphacks' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wphacks' ),
	);
	$args = array(
		'label'               => __( 'wphacks_admin_notices', 'wphacks' ),
		'description'         => __( 'Admin Notices', 'wphacks' ),
		'labels'              => $labels,
		'supports'            => array(''),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 60,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'wph_adm_notices', $args );

}

// Hook into the 'init' action
add_action( 'init', 'register_wphacks_admin_notices', 10 );

}

if ( ! function_exists( 'register_wphacks_notice_types' ) ) {

// Register Custom Taxonomy
function register_wphacks_notice_types() {

	$labels = array(
		'name'                       => _x( 'Notice Types', 'Taxonomy General Name', 'wphacks' ),
		'singular_name'              => _x( 'Notice Type', 'Taxonomy Singular Name', 'wphacks' ),
		'menu_name'                  => __( 'Types', 'wphacks' ),
		'all_items'                  => __( 'All Types', 'wphacks' ),
		'parent_item'                => __( '', 'wphacks' ),
		'parent_item_colon'          => __( '', 'wphacks' ),
		'new_item_name'              => __( 'New Item Name', 'wphacks' ),
		'add_new_item'               => __( 'Add New Notice Type', 'wphacks' ),
		'edit_item'                  => __( 'Edit Item', 'wphacks' ),
		'update_item'                => __( 'Update Item', 'wphacks' ),
		'view_item'                  => __( 'View Notice Type', 'wphacks' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'wphacks' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'wphacks' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wphacks' ),
		'popular_items'              => __( 'Popular Items', 'wphacks' ),
		'search_items'               => __( 'Search Items', 'wphacks' ),
		'not_found'                  => __( 'Not Found', 'wphacks' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => false,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'wphacks_admin_notice_types', array( 'wph_adm_notices' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'register_wphacks_notice_types', 0 );

}

register_activation_hook(__FILE__, 'wphacks_hook_in_default_admin_types' );
function wphacks_hook_in_default_admin_types(){
add_action( 'init', 'register_wphacks_notice_types', 0 );
register_wphacks_notice_types();
wphacks_setup_default_notice_types();
}

function wphacks_setup_default_notice_types(){
	wp_insert_term('Update','wphacks_admin_notice_types',array('slug'=>'updated','description'=>'Default. Allows Use of the wordprss default style for Update Admin Notices'));

	wp_insert_term('Update (Nag)','wphacks_admin_notice_types',array('slug'=>'update-nag','description'=>'Default. Allows Use of the wordprss default style for Update-Nag Admin Notices'));
	wp_insert_term('Error','wphacks_admin_notice_types',array('slug'=>'error','description'=>'Default. Allows Use of the wordprss default style for Error Admin Notices'));
	wp_insert_term('Message','wphacks_admin_notice_types',array('slug'=>'message','description'=>'Provided by WP Hacks Admin Notices - a custom admin notice type'));

	wp_insert_term('User Error','wphacks_admin_notice_types',array('slug'=>'user-error','description'=>'Provided by WP Hacks Admin Notices - a custom admin notice type'));
}





register_meta('post','_notice_type','wphacks_sanitize_notice_type','wphacks_can_edit_admin_notice');
register_meta('post','_notice_icon','wphacks_sanitize_notice_icon','wphacks_can_edit_admin_notice');
register_meta('post','_notice_icon_color','wphacks_sanitize_notice_icon_color','wphacks_can_edit_admin_notice');
register_meta('post','_notice_is_dismissible','wphacks_sanitize_notice_is_dismissible','wphacks_can_edit_admin_notice');
register_meta('post','_notice_importance','wphacks_sanitize_notice_importance','wphacks_can_edit_admin_notice');

function wphacks_sanitize_notice_type($input){
	//TODO make this work
	return sanitize_html_class($input);
}
function wphacks_sanitize_notice_icon($input){
	//TODO make this work
	return sanitize_text_field($input);
}
function wphacks_sanitize_notice_icon_color($input){
	//TODO make this work
	return sanitize_text_field($input);
}

function wphacks_sanitize_notice_importance($input){
	return intval($input);
}
function wphacks_can_edit_admin_notice(){
	global $post;
	if(!current_user_can('edit_post', $post->ID)){
		return false;
		//TODO: how do you tell the user? doing_it_wrong?
	}
	return true;
}

add_action('save_post','wphacks_save_admin_notice_icon',10,3);
function wphacks_save_admin_notice_icon($post_id, $post, $update){
	if(get_post_type($post) == "wph_adm_notices"){
		$icon = $_POST['_notice_icon'];
		//sanitize
		//escape
		update_post_meta($post_id,'_notice_icon', $icon);
	}
}

add_action('save_post','wphacks_save_admin_notice_type',10,3);
function wphacks_save_admin_notice_type($post_id, $post, $update){
	if(get_post_type($post) == "wph_adm_notices"){
		$notice_type = $_POST['_notice_type'];
		//sanitize
		//escape
		$name = get_term_by('id',$notice_type,'wphacks_admin_notice_types')->name;
		wp_set_post_terms( $post_id,$name, 'wphacks_admin_notice_types',false);
	}
}

add_action('save_post','wphacks_save_admin_notice_icon_color',10,3);
function wphacks_save_admin_notice_icon_color($post_id, $post, $update){
	if(get_post_type($post) == "wph_adm_notices"){
		$icon_color = $_POST['_notice_icon_color'];
		//sanitize
		//escape
		update_post_meta($post_id,'_notice_icon_color', $icon_color);
	}
}

add_action('save_post','wphacks_save_admin_notice_is_dismissible',10,3);
function wphacks_save_admin_notice_is_dismissible($post_id, $post, $update){
	if(get_post_type($post) == "wph_adm_notices"){
		$notice_is_dismissible = $_POST['_notice_is_dismissible'];
		//sanitize
		//escape
		update_post_meta($post_id,'_notice_is_dismissible', $notice_is_dismissible);
	}
}

add_action('save_post','wphacks_save_admin_notice_importance',10,3);
function wphacks_save_admin_notice_importance($post_id, $post, $update){
	if(get_post_type($post) == "wph_adm_notices"){
		$notice_importance = $_POST['_notice_importance'];
		//sanitize
		//escape
		update_post_meta($post_id,'_notice_importance', $notice_importance);
	}
}

add_action('admin_menu','wphacks_remove_default_admin_notice_types_metabox');
function wphacks_remove_default_admin_notice_types_metabox(){
	remove_meta_box('tagsdiv-wphacks_admin_notice_types','wph_adm_notices','side');
}

add_action('add_meta_boxes','wphacks_register_metabox_new_admin_notice');
function wphacks_register_metabox_new_admin_notice(){
	add_meta_box('wphacks_admin_notice','Notice Details','wphacks_render_metabox_new_admin_notice','wph_adm_notices','normal','high','');
}

function wphacks_get_admin_notice_types(){
return apply_filters("wphacks_admin_notice_types",$notice_types = array(
		'update'=>'Update',
		'error'=>'Error',
		'update-nag'=>'Update (Nag)',
		'message'=>'message',
	));
}

function wphacks_render_metabox_new_admin_notice(){
	global $post;
	//TODO Move styles to CSS
	?>
	<style>
	.dashicons.delete {
	color: #D54E21;
	}
	</style>	
	<label>Notice Type</label>
	<?php
		$id = (array_values(wp_get_post_terms($post->ID, "wphacks_admin_notice_types",array('fields'=>'ids')))[0]);
		$tax_args = array(
			'taxonomy' =>'wphacks_admin_notice_types',
			'hide_empty' =>'0',
			'name' =>'_notice_type',
			'selected' => $id,
		);
		
		wp_dropdown_categories($tax_args);
		?>
	<label>Icon</label>
	<select name="_notice_icon">
		<?php
			$icons = array(
			'dashicons-info' => 'Info',	
			'dashicons-flag' => 'Flag',	
			'dashicons-yes' => 'Yes (Check)',	
			);
			foreach($icons as $icon_class=>$icon_name){
				if($icon_class == get_post_meta($post->ID, '_notice_icon',true)){
					echo '<option selected="selected" value="'. esc_attr($icon_class) .'">'. esc_attr($icon_name) .'</option>'. "\n";
				} else {
					echo '<option value="'. esc_attr($icon_class) .'">'. esc_attr($icon_name) .'</option>'. "\n";
				}
			}
		?>
	</select>
	<label>Icon Color</label>
	<input type="color" name="_notice_icon_color" maxlength="6" value="<?php echo esc_attr(get_post_meta($post->ID,'_notice_icon_color',true)) ;?>">
	<label title="Importance positions multiple notices relative to each other, higher the number, the higher up the page">Importance</label>
	<select name="_notice_importance">
		<?php
			$importance = array(
				'100' => 'High',
				'50' => 'Medium',
				'10' => 'Normal',
				'1' => 'Low',
			);
			$selected_importance = get_post_meta($post->ID,'_notice_importance',true);
			foreach($importance as $importance_num=>$importance_name){
				if(selected($selected_importance,$importance_num,false)){
					echo '<option selected="selected" value="'. esc_attr($importance_num) .'">'. esc_attr($importance_name) .'</option>'. "\n";
				} else {
					echo '<option value="'. esc_attr($importance_num) .'">'. esc_attr($importance_name) .'</option>'. "\n";
				}
			}
		?>
	</select>
	<input type="checkbox" <?php checked(get_post_meta($post->ID,'_notice_is_dismissible',true),1,true); ?> name="_notice_is_dismissible" value="1"><label>Dismissible</label>

	<p><input type="text" name="post_title" class="widefat" placeholder="Message Title" value="<?php echo esc_attr($post->post_title); ?>"></p>
	<p><input type="text" name="post_content" class="widefat" placeholder="Message (Required)" value="<?php echo $post->post_content; ?>"></p>
		
	<label>Conditions</label>
	<p class="controls"><a href="" class="dashicons dashicons-plus-alt" title="Add Condition for displaying this notice"></a></a>
	<p class="repeatable draggable"><a href="" title="Remove this Condition" class="dashicons dashicons-dismiss delete"></a> Conditions Go Here</p>
	<template id="wphacks-admin-notice-condition">
		<p class="repeatable draggable">
		<a href="" title="Remove this Condition" class="dashicons dashicons-dismiss delete"></a>
		
		</p>
	</template>
	<?php
}

//add_action('network_admin_notice','wphacks_do_admin_notice_network');
//add_action('user_admin_notice','wphacks_do_admin_notice_user');
add_action('admin_notices','wphacks_do_admin_notice_default');
//add_action('all_admin_notices','wphacks_do_admin_notice_all');

function wphacks_do_admin_notice_network(){
	wphacks_admin_notices("network");
}
function wphacks_do_admin_notice_user(){
	wphacks_admin_notices("user");
}
function wphacks_do_admin_notice_default(){
	wphacks_admin_notices();
}
function wphacks_do_admin_notice_all(){
	wphacks_admin_notices("all");
}

//add_action('admin_notices','wphacks_admin_notices');
function wphacks_admin_notices($admin_notice_hook = ""){
//function wphacks_admin_notice($hooks = array(),$ids = array(),$type, $title, $message, $actions = array(), $dismissible){
	if( is_array($hook) && !empty($hook) ){
		global $hook_suffix;
		print_r( $hook_suffix );
		
		if(!in_array($hooks,$hook_suffix)){
			return;
		}
	}
	if( is_array($ids) && !empty($ids) ){
		global $post;

	}
	$id = "3117";
	if(apply_filters("wphacks_admin_notice_{$id}",true)){
		//echo
		$admin_notice_query_args = array(
			'post_status' => array('publish'),
			'post_type' => array('wph_adm_notices'),
			'order'	=> 'DESC',
			'meta_key' => '_notice_importance',
			'orderby' => 'meta_value_num',
			
		);
		switch($admin_notice_hook){
			case "network":
			break;
			case "user":
			break;
			case "all":
			break;
			case empty($admin_notice_hook):
				//metakey _notice_type == 
			break;
		}
		
		//TODO Meta Query
		$admin_notice_query = get_posts($admin_notice_query_args);
		foreach($admin_notice_query as $post){
		setup_postdata($post);
		
		
		//$type = strip_tags(get_post_meta($post->ID, "_notice_type",true));
		$type = array_values(wp_get_post_terms($post->ID, "wphacks_admin_notice_types",array('fields'=>'ids')))[0];

		$type = get_term_by('id',$type,'wphacks_admin_notice_types')->slug;


		$title = $post->post_title;
		$message = $post->post_content;
		$icon = (get_post_meta($post->ID, "_notice_icon",true));
		$icon_color = strip_tags(get_post_meta($post->ID, "_notice_icon_color",true));
		$notice_is_dismissible = strip_tags(get_post_meta($post->ID, "_notice_is_dismissible",true));
		//let them be escaped on a one off basis
		wphacks_admin_notice($message, $type, $title, $icon, $icon_color, $notice_is_dismissible, $post, $admin_notice_hook);
		wp_reset_postdata();
		}
	}
}


function wphacks_admin_notice($message, $type = "updated", $title = "",$icon ="", $icon_color = "", $notice_is_dismissible = 0, $post = null, $action = ""){
	$type = apply_filters('wphacks_notice_type',$type);
	if(!in_array($type,wphacks_get_admin_notice_types())){
		//check if term exists
		//return false;
	}
	$type = esc_attr($type);
	$title = __(do_shortcode(apply_filters('wphacks_notice_title',$title),"wphacks"));
	$message = __(do_shortcode(apply_filters('wphacks_notice_message',$message)),"wphacks");
	$icon = esc_attr(apply_filters('wphacks_notice_icon',$icon));
	$icon_color = esc_attr(apply_filters('wphacks_notice_icon_color',$icon_color));
	
	$notice_is_dismissible = apply_filters('wphacks_notice_icon_color',$notice_is_dismissible);
	$class = array();
	$class[] = $type;
	if(!empty($notice_is_dismissible)){
		$class[] = "notice is-dismissible";
	}
	if(!empty($action)){
		$class[] = $action;
	}
	$class = 'class="' . join( ' ', get_post_class($class, $post_id ) ) . '"';
	
	
	echo '<div id="post-'. esc_attr($post->ID) .'"'. $class .'><p>'. (!empty($icon) ? '<span class="dashicons '. $icon .'"'. (!empty($icon_color) ? 'style="color:'. $icon_color .';"' : '') .'></span>' : '') .'<b>'. $title .'</b> '. $message . (current_user_can('edit_post',$post->Id) ? ' <span style="text-align: right; float:right;"><a href="'. get_edit_post_link($post->ID).'" target="_blank">(edit)</a></span>' : '') .'</p></div>';
	//TODO make this a sprintf();
	return true;
}


add_filter('wphacks_notice_title','wphacks_notice_title_strip_tags');
function wphacks_notice_title_strip_tags($title){
	return strip_tags($title,'<a>,<br>,<i>,<b>,<img>');
}
add_filter('wphacks_notice_message','wphacks_notice_message_strip_tags');
function wphacks_notice_message_strip_tags($message){
	return strip_tags($message,'<a>,<br>,<i>,<b>,<img>');
}


add_action('quick_edit_custom_box','wphacks_admin_notices_quick_edit');
function wphacks_admin_notices_quick_edit($column_name, $post_type){
	if($post_type == "wph_adm_notices"){
	
	
	}
}









//Since These aren't public, there's no way to view them. Remove that link. 
//TODO: Should this be replaced with Preview?
add_filter('post_row_actions','wphacks_admin_notices_remove_view_action',10,2);
function wphacks_admin_notices_remove_view_action($actions, $post){
	if(get_post_type($post) == "wph_adm_notices"){
		unset($actions['view']);
	}
	return $actions;
}

add_filter('post_row_actions','wphacks_admin_notices_conditionally_add_activate_action',10,2);
add_filter('post_row_actions','wphacks_admin_notices_conditionally_add_deactivate_action',10,2);

function wphacks_admin_notices_conditionally_add_activate_action($actions, $post){
	if(get_post_type($post) == "wph_adm_notices"){
		if(get_post_status($post) == "draft"){
			$actions['activate'] = '<a href="">Activate</a>';
		}
	}
	return $actions;
}
function wphacks_admin_notices_conditionally_add_deactivate_action($actions, $post){
	if(get_post_type($post) == "wph_adm_notices"){
		if(get_post_status($post) == "publish"){
			$actions['deactivate'] = '<a href="">Deactivate</a>';
		}
	}
	return $actions;
}


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wphacks_admin_notices_add_action_links' );
function wphacks_admin_notices_add_action_links ( $links ) {
$support = array('<a href="https://github.com/WP-Hacks/Admin-Notices/issues">Support</a>');
return array_merge( $links, $support);
}

add_filter('plugin_row_meta','wphacks_admin_notices_plugin_row_meta',10,4);
function wphacks_admin_notices_plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status ){
	if($plugin_file == plugin_basename(__FILE__)){
		$release_notes = array('<a href="">Release Notes</a>');
		$source = array('<a href="https://github.com/WP-Hacks/Admin-Notices">Source</a>');
		return array_merge($plugin_meta,$release_notes,$source);
	} 
	return $plugin_meta;
}
?>
