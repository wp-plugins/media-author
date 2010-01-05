<?php
/*
Plugin Name: Media Author
Plugin URI: http://wordpress.com/extend/plugins/media-author
Description: Allows you to change the author of a piece of media
Plugin Author: John Luetke
Version: 1.0
Author URI: http://johnluetke.net
*/

function media_author_plugin_save ($args) {
	
	if (isset($_POST['post_author'])) {
		$args['post_author'] = $_POST['post_author'];
	}

	return $args;
}

/*
 * This function is the preferred method, but it relies on a fix not yet implemented. See
 * Wordpress ticket #11705
 */
function media_author_plugin_dropdown ($args) {
	$user_list = get_users_of_blog();
	$user_array = array();

	foreach ($user_list as $user) {
		$user_array[] = array(
			'value' => $user->user_id,
			'label' => $user->display_name
		);
	}
	
	$users = array('post_author' => array (
		'label' => __('Author'),
		'input' => 'select',
		'value' => $user_array,
		'helps' => __('Media Author')));

	return array_merge($args, $users);
}

/*
 * This is the usable method, as of Wordpress 2.9.0
 */
function media_author_plugin_dropdown_2($args) {
	$user_list = get_users_of_blog();
	$html = "<select name='post_author' id='post_author'>";

	foreach ($user_list as $user) {
		$html .= "<option value='".$user->user_id."'>".$user->display_name."</option>";
	}

	$html .= "</select>";
	
	$users = array('post_author' => array (
		'label' => __('Author'),
		'input' => 'html',
		'html' => $html,
		'helps' => __('Media Author')));

	return array_merge($args, $users);
}

add_filter('attachment_fields_to_save', 'media_author_plugin_save', 5);
add_filter('attachment_fields_to_edit', 'media_author_plugin_dropdown_2', 5, array('selected' => 1));
