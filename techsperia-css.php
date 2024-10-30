<?php

/*
 * Plugin Name: Custom CSS by Techsperia
 * Plugin URI: http://www.techsperia.com/custom-css-to-wordpress-posts-pages/
 * Description: This plugin will add a simple box in your Posts and Pages edit screens which will give you the awesome ability to add custom CSS to that particular post or page! All you have to do is add your CSS code in the newly formed box! Pretty neat, isn't it?
 * Version: 1.0
 * Author: Raj Mehta
 * Author URI: https://www.twitter.com/raj_himself
 * License: GPLv2 or later
 
 * Built on the code written by Kerrick Long (https://twitter.com/KerrickLong)

    Copyright 2014  Raj Mehta  (email : raj@techsperia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'ts_custom_css_hooks');
add_action('save_post', 'ts_save_custom_css');
add_action('wp_head','ts_insert_custom_css');
function ts_custom_css_hooks() {
	add_meta_box('custom_css', 'Custom CSS', 'ts_custom_css_input', 'post', 'normal', 'high');
	add_meta_box('custom_css', 'Custom CSS', 'ts_custom_css_input', 'page', 'normal', 'high');
}
function ts_custom_css_input() {
	global $post;
	echo '<input type="hidden" name="custom_css_noncename" id="custom_css_noncename" value="'.wp_create_nonce('custom-css').'" />';
	echo '<textarea name="custom_css" id="custom_css" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'_custom_css',true).'</textarea>';
}
function ts_save_custom_css($post_id) {
	if (!wp_verify_nonce($_POST['custom_css_noncename'], 'custom-css')) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_css = $_POST['custom_css'];
	update_post_meta($post_id, '_custom_css', $custom_css);
}
function ts_insert_custom_css() {
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
			echo '<style type="text/css">'.get_post_meta(get_the_ID(), '_custom_css', true).'</style>';
		endwhile; endif;
		rewind_posts();
	}
}

?>