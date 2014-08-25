<?php
// XMLSitemapImageFeed CLASS
class XMLSitemapImageFeed {

	public static function go() {		
		global $wpdb;
		if ( $wpdb->blogid && function_exists('get_site_option') && get_site_option('tags_blog_id') == $wpdb->blogid ) 
		{
			// we are on wpmu and this is a tags blog!
			// create NO sitemap since it will be full 
			// of links outside the blogs own domain...
		} 
		else 
		{
			// INIT
			add_action('init', array(__CLASS__, 'init') );
	
			// FEED
			add_action('do_feed_sitemap-image', array(__CLASS__, 'load_template_sitemap_image'), 10, 1);

			// REWRITES
			add_filter('generate_rewrite_rules', array(__CLASS__, 'rewrite') );

			//Envía el ping a Google y Bing
			add_action('enviar_ping', array(__CLASS__, 'EnviaPing'), 10, 1);
			
			//Actúa cuando se publica una página, una entrada o se borra una entrada
			add_action('publish_post', array(__CLASS__, 'ProgramaPing'), 999, 1);
			add_action('publish_page', array(__CLASS__, 'ProgramaPing'), 9999, 1);
			add_action('delete_post', array(__CLASS__, 'ProgramaPing'), 9999, 1);
		}

		// DE-ACTIVATION
		register_deactivation_hook( XMLSIF_PLUGIN_DIR . '/xml-sitemap.php', array(__CLASS__, 'deactivate') );
	}

	// set up the image sitemap template
	public static function load_template_sitemap_image() {
		load_template( XMLSIF_PLUGIN_DIR . '/feed-sitemap-image.php' );
	}

	// REWRITES //
	// add sitemap rewrite rules
	public static function rewrite($wp_rewrite) {
		$feed_rules = array('sitemap-image.xml$' => $wp_rewrite->index . '?feed=sitemap-image',);
		$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
	}

	// DE-ACTIVATION
	public static function deactivate() {
		remove_filter('generate_rewrite_rules', array(__CLASS__, 'rewrite') );
		delete_option('gn-sitemap-image-feed-mu-version');
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	// MULTI-LANGUAGE PLUGIN FILTERS

	// qTranslate
	public static function qtranslate($input) {
		global $q_config;

		if (is_array($input)) // got an array? return one!
		{
			foreach ( $input as $url )
			{
				foreach($q_config['enabled_languages'] as $language) $return[] = qtrans_convertURL($url,$language);
			}
		}
		else $return = qtrans_convertURL($input); // not an array? just convert the string.

		return $return;
	}

	// xLanguage
	public static function xlanguage($input) {
		global $xlanguage;
	
		if (is_array($input)) // got an array? return one!
		{
			foreach ( $input as $url )
			{
				foreach($xlanguage->options['language'] as $language) $return[] = $xlanguage->filter_link_in_lang($url,$language['code']);
			}
		}
	 	else $return = $xlanguage->filter_link($input); // not an array? just convert the string.

		return $return;
	}

	public static function init() {
		// FLUSH RULES after (site wide) plugin upgrade
		if (get_option('gn-sitemap-image-feed-mu-version') != XMLSIF_VERSION) {
			update_option('gn-sitemap-image-feed-mu-version', XMLSIF_VERSION);
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			delete_transient('xml_sitemap_image');
		}

		// check for qTranslate and add filter
		if (defined('QT_LANGUAGE')) add_filter('xml_sitemap_url', array(__CLASS__, 'qtranslate'), 99);

		// check for xLanguage and add filter
		if (defined('xLanguageTagQuery')) add_filter('xml_sitemap_url', array(__CLASS__, 'xlanguage'), 99);
	}

	//Programa el ping a los buscadores web
	public static function ProgramaPing() {
		wp_schedule_single_event(time(),'enviar_ping');
	}

	//Envía el ping a Google y Bing
	public static function EnviaPing() {
		$ping = array("http://www.google.com/webmasters/sitemaps/ping?sitemap=" . urlencode(home_url('/') . "sitemap-image.xml"), "http://www.bing.com/webmaster/ping.aspx?siteMap=" . urlencode(home_url('/') . "sitemap-image.xml"));

		$options['timeout'] = 10;

		foreach($ping as $url) wp_remote_get($url, $options);
	}
	
}
