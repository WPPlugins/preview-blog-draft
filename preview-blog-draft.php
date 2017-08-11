<?php
/*

**************************************************************************

Plugin Name:  Preview Blog Draft
Plugin URI:   http://www.arefly.com/preview-blog-draft/
Description:  Let visitors can see the title of your draft post and content of "Coming Soon!"
Version:      1.3.6
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  preview-blog-draft
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

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

**************************************************************************/

define("PREVIEW_BLOG_DRAFT_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("PREVIEW_BLOG_DRAFT_FULL_DIR", plugin_dir_path( __FILE__ ));
define("PREVIEW_BLOG_DRAFT_TEXT_DOMAIN", "preview-blog-draft");

/* Plugin Localize */
function preview_blog_draft_load_plugin_textdomain() {
	load_plugin_textdomain(PREVIEW_BLOG_DRAFT_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'preview_blog_draft_load_plugin_textdomain');

include_once PREVIEW_BLOG_DRAFT_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function preview_blog_draft_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.PREVIEW_BLOG_DRAFT_TEXT_DOMAIN.'-options').'">'.__("Settings", PREVIEW_BLOG_DRAFT_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'preview_blog_draft_action_links');

function show_draft_posts($posts, $query){
	if(is_single()){
		global $wp_query, $wpdb;
		$post_status = get_page_by_path($wp_query->query_vars['name'], OBJECT, 'post')->post_status;
		$post_status_option = get_option('preview_blog_draft_post_status');
		if( (in_array($post_status, $post_status_option)) || ($post_status == $post_status_option) ){
			$posts = $wpdb->get_results($wp_query->request);
			header('HTTP/1.1 404 Not Found');
			http_response_code(404);
			status_header(404);
		}
	}
	return $posts;
}
add_filter('the_posts', 'show_draft_posts', 10, 2);

function preview_draft($content){
	global $post, $current_user;
	$post_status = $post->post_status;
	$post_status_option = get_option('preview_blog_draft_post_status');
	if( ($post->post_author != $current_user->ID) || (!is_super_admin()) ){
		if( (in_array($post_status, $post_status_option)) || ($post_status == $post_status_option) ){
			return get_option('preview_blog_draft_name');
		}
	}
	return $content;
}
add_filter('the_content', 'preview_draft');