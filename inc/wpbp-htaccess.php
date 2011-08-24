<?php  

if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') !== false) {
	function wpbp_htaccess_writable() {
		if (!is_writable(get_home_path() . '.htaccess')) {
			add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writeable ', 'wpbp'), admin_url('options-permalink.php')) . "</p></div>';"));
		};
	}
	
	add_action('admin_init', 'wpbp_htaccess_writable');
	
	// Rewrites DO NOT happen for child themes
	// rewrite /wp-content/themes/wpbp/css/ to /css/
	// rewrite /wp-content/themes/wpbp/js/  to /js/
	// rewrite /wp-content/themes/wpbp/img/ to /js/
	// rewrite /wp-content/plugins/ to /plugins/
	
	function wpbp_flush_rewrites() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	function wpbp_add_rewrites($content) {
		$theme_name = next(explode('/themes/', get_stylesheet_directory()));
		global $wp_rewrite;
		$wpbp_new_non_wp_rules = array(
			'css/(.*)'     => 'wp-content/themes/'. $theme_name . '/css/$1',
			'js/(.*)'      => 'wp-content/themes/'. $theme_name . '/js/$1',
			'resize/(.*)'  => 'wp-content/themes/wpboilerplate/img/resize.php',
			'img/(.*)'     => 'wp-content/themes/'. $theme_name . '/img/$1',
			'plugins/(.*)' => 'wp-content/plugins/$1'
		);
		$wp_rewrite->non_wp_rules += $wpbp_new_non_wp_rules;
	}
	
	add_action('admin_init', 'wpbp_flush_rewrites');
	
	function wpbp_clean_assets($content) {
	    $theme_name = next(explode('/themes/', $content));
	    $current_path = '/wp-content/themes/' . $theme_name;
	    $new_path = '';
	    $content = str_replace($current_path, $new_path, $content);
	    return $content;
	}
	
	function wpbp_clean_plugins($content) {
	    $current_path = '/wp-content/plugins';
	    $new_path = '/plugins';
	    $content = str_replace($current_path, $new_path, $content);
	    return $content;
	}
	
	// only use clean urls if the theme isn't a child or an MU (Network) install
	// overide
	if (!is_multisite() && !is_child_theme()) {
		add_action('generate_rewrite_rules', 'wpbp_add_rewrites');
		if (!is_admin()) { 
			add_filter('plugins_url', 'wpbp_clean_plugins');
			add_filter('bloginfo', 'wpbp_clean_assets');
			add_filter('stylesheet_directory_uri', 'wpbp_clean_assets');
			add_filter('template_directory_uri', 'wpbp_clean_assets');
		}
	}
	
	function wpbp_add_h5bp_htaccess($rules) {
		global $wp_filesystem;
	
		if (!defined('FS_METHOD')) define('FS_METHOD', 'direct');
		if (is_null($wp_filesystem)) WP_Filesystem(array(), ABSPATH);
		
		if (!defined('WP_CONTENT_DIR'))
		define('WP_CONTENT_DIR', ABSPATH . 'wp-content');	
	
		$theme_name = next(explode('/themes/', get_template_directory()));
		$filename = WP_CONTENT_DIR . '/themes/' . $theme_name . '/inc/h5bp-htaccess';
	
		$rules .= $wp_filesystem->get_contents($filename);
		
		return $rules;
	}
	
	add_action('mod_rewrite_rules', 'wpbp_add_h5bp_htaccess');
}

?>
