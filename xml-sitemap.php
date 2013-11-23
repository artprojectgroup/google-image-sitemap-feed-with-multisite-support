<?php
/*
Plugin Name: Google Image Sitemap Feed With Multisite Support
Version: 0.3
Plugin URI: http://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/
Description: Dynamically generates a Google Image Sitemap and automatically submit updates to Google and Bing. No settings required. Compatible with WordPress Multisite installations. Created from <a href="http://profiles.wordpress.org/users/timbrd/" target="_blank">Tim Brandon</a> <a href="http://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/" target="_blank"><strong>Google News Sitemap Feed With Multisite Support</strong></a> and <a href="http://profiles.wordpress.org/labnol/" target="_blank">Amit Agarwal</a> <a href="http://wordpress.org/plugins/google-image-sitemap/" target="_blank"><strong>Google XML Sitemap for Images</strong></a> plugins.
Author: Art Project Group
Author URI: http://www.artprojectgroup.es/

Text Domain: xml_image_sitemap
Domain Path: /lang
License: GPL2
*/

/*  Copyright 2013  artprojectgroup  (email : info@artprojectgroup.es)

    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* --------------------
 *  AVAILABLE HOOKS
 * --------------------
 *
 * FILTERS
 *	xml_sitemap_url	->	Filters the URL used in the sitemap reference in robots.txt
 *				(receives an ARRAY and MUST return one; can be multiple urls) 
 *				and for the home URL in the sitemap (receives a STRING and MUST)
 *				return one) itself. Useful for multi language plugins or other 
 *				plugins that affect the blogs main URL... See pre-defined filter
 *				XMLSitemapImageFeed::qtranslate() in XMLSitemapImageFeed.class.php as an
 *				example.
 * ACTIONS
 *	[ none at this point, but feel free to request, suggest or code one :) ]
 *	
 */

//Carga el idioma
load_plugin_textdomain( 'xml_image_sitemap', null, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

//Enlaces adicionales personalizados
function xml_sitemap_image_enlaces($enlace, $archivo) {
	$plugin = plugin_basename(__FILE__);

	if ($archivo == $plugin) 
	{
		$enlace[] = '<a href="http://www.artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support" target="_blank" title="Art Project Group">' . __('Visit the official plugin website', 'xml_image_sitemap') . '</a>';
		$enlace[] = '<a href="http://www.artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins" target="_blank" title="Art Project Group">' . __('<strong>Google XML Sitemaps</strong> compatibility fix', 'xml_image_sitemap') . '</a>';
		$enlace[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4LDTBG4ZB4FTJ" target="_blank" title="PayPal"><img alt="Google Image Sitemap Feed With Multisite Support" src="' . __('https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif', 'xml_image_sitemap') . '" width="53" height="15" style="vertical-align:text-bottom;"></a>';
	}
		
	return $enlace;
}
add_filter('plugin_row_meta', 'xml_sitemap_image_enlaces', 10, 2);

//CONSTANTS
define('XMLSIF_VERSION','0.2');
define('XMLSIF_MEMORY_LIMIT','128M');

if (file_exists(dirname(__FILE__).'/google-image-sitemap-feed-mu')) define('XMLSIF_PLUGIN_DIR', dirname(__FILE__).'/google-image-sitemap-feed-mu');
else define('XMLSIF_PLUGIN_DIR', dirname(__FILE__));		

//CLASS
if( class_exists('XMLSitemapImageFeed') || include( XMLSIF_PLUGIN_DIR . '/XMLSitemapImageFeed.class.php' ) ) XMLSitemapImageFeed::go();

