<?php
function preview_blog_draft_register_settings() {
	add_option('preview_blog_draft_name', __('Coming Soon!', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN));
	add_option('preview_blog_draft_post_status', array("draft"));
	register_setting('preview_blog_draft_options', 'preview_blog_draft_name');
	register_setting('preview_blog_draft_options', 'preview_blog_draft_post_status');
}
add_action('admin_init', 'preview_blog_draft_register_settings');

function preview_blog_draft_register_options_page() {
	add_options_page(__('Preview Blog Draft Options Page', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), __('Preview Blog Draft', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), 'manage_options', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN.'-options', 'preview_blog_draft_options_page');
}
add_action('admin_menu', 'preview_blog_draft_register_options_page');

function preview_blog_draft_get_checked($checkbox_name, $check_value){
	if(is_array($checkbox_name)){
		if(in_array($check_value, $checkbox_name)){
			?> checked="checked"<?php
		}
	}
}

function preview_blog_draft_get_checkbox_option($checkbox_name, $checkbox_value, $checkbox_id){
	for($num = 0; $num < count($checkbox_id); $num++){
		$checkbox_value_each = $checkbox_value[$num];
		$checkbox_id_each = $checkbox_id[$num];
		?>
		<input type="checkbox" name="<?php echo $checkbox_name; ?>[]" id="<?php echo $checkbox_id_each; ?>" value="<?php echo $checkbox_id_each; ?>"<?php preview_blog_draft_get_checked(get_option($checkbox_name), $checkbox_id_each); ?>><label for="<?php echo $checkbox_id_each; ?>"><?php echo $checkbox_value_each; ?></label>
	<?php
	}
}

function preview_blog_draft_options_page() {
?>
<div class="wrap">
	<h2><?php _e("Preview Blog Draft Options Page", PREVIEW_BLOG_DRAFT_TEXT_DOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('preview_blog_draft_options'); ?>
		<h3><?php _e("General Options", PREVIEW_BLOG_DRAFT_TEXT_DOMAIN); ?></h3>
			<p><?php printf(__('You can use %s with this plugin too.', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), '<a href="http://wordpress.org/plugins/last-post-redirect/" target="_blank">Last Post Redirect</a>'); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="preview_blog_draft_name"><?php _e("Words that you want to show in draft post: ", PREVIEW_BLOG_DRAFT_TEXT_DOMAIN); ?></label></th>
					<td>
						<input type="text" name="preview_blog_draft_name" id="preview_blog_draft_name" value="<?php echo get_option('preview_blog_draft_name'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e("Status of post you want to show the message for: ", PREVIEW_BLOG_DRAFT_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php preview_blog_draft_get_checkbox_option("preview_blog_draft_post_status", array(__('Publish Post', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), __('Draft', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), __('Future Post', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN), __('Private Post', PREVIEW_BLOG_DRAFT_TEXT_DOMAIN)), array('publish', 'draft', 'future', 'private')); ?>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>