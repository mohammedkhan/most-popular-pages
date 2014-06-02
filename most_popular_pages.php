<?php
/**
 * Plugin Name: Most Popular Pages
 * Description: A wordpress plugin that uses Google Analytics to display your 10 most popular pages from the last 30 days
 * Author: Mohammed Khan
 */
 
//Add button under 'Settings' that leads to a form to input Google Analytics details
//This borrows heavily from one of WordPress's worked examples (http://codex.wordpress.org/Creating_Options_Pages)
add_action('admin_menu', 'most_popular_pages_menu');
add_action('admin_init', 'page_init'));

//Add a hyperlink to the site's menu bar
add_filter('wp_nav_menu_items', 'generate_hyperlink', 10, 2);

function most_popular_pages_menu() {
	add_options_page('Most Popular Pages Settings', 'Most Popular Pages', 'manage_options', 'most-popular-pages', 'most_popular_pages_options');
}

function most_popular_pages_options() {
	if (!current_user_can('manage_options'))  {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	$this->options = get_option('my_option_name');
?>
<div class="wrap">
	<h2>Most Popular Pages Settings</h2>
	<form method="post" action="options.php">
	<?php
	settings_fields('my_option_group');   
	do_settings_sections('most-popular-pages');
	submit_button(); 
	?>
	</form>
</div>
<?php
}

function page_init() {
	register_setting(
		'my_option_group',
		'my_option_name'
	);

	add_settings_section(
		'setting_section_id',
		'Google API Settings',
		'Visit https://console.developers.google.com to generate the following settings:',
		'most-popular-pages'
	);  

	add_settings_field(
		'client_id',
		'Client ID',
		'client_id_callback',
		'most-popular-pages',
		'setting_section_id'
	);      

	add_settings_field(
		'client_secret', 
		'Client Secret', 
		'client_secret_callback', 
		'most-popular-pages',
		'setting_section_id'
	);

	add_settings_field(
		'client_redirect_uri', 
		'Client Redirect URI', 
		'client_redirect_uri_callback', 
		'most-popular-pages',
		'setting_section_id'
	);

	add_settings_field(
		'developer_key', 
		'Developer Key', 
		'developer_key_callback', 
		'most-popular-pages',
		'setting_section_id'
	);
}

function client_id_callback() {
	printf(
		'<input type="text" id="client_id" name="my_option_name[client_id]" value="%s" />',
		isset($this->options['client_id']) ? $this->options['client_id'] : ''
	);
}

function client_secret_callback() {
	printf(
		'<input type="text" id="client_secret" name="my_option_name[client_secret]" value="%s" />',
		isset($this->options['client_secret']) ? $this->options['client_secret'] : ''
	);
}

function client_redirect_uri_callback() {
	printf(
		'<input type="text" id="client_redirect_uri" name="my_option_name[client_redirect_uri]" value="%s" />',
		isset($this->options['client_redirect_uri']) ? $this->options['client_redirect_uri'] : ''
	);
}

function developer_key_callback() {
	printf(
		'<input type="text" id="developer_key" name="my_option_name[developer_key]" value="%s" />',
		isset($this->options['developer_key']) ? $this->options['developer_key'] : ''
	);
}

function generate_hyperlink($items, $args) {
	if ($args->theme_location == 'main')) {
		$items .= '<a href="/wp-content/plugins/most-popular-pages/list_results.php">Most Popular Pages</a>';
	}
	return $items;
}

?>
