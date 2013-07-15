<?php
/*
Plugin Name: Google Image Sitemap Feed With Multisite Support
Version: 0.1
Plugin URI: http://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/
Description: Genera dinámicamente un mapa de sitio de imágenes para Google, informando a Google y Bing automáticamente. No requiere configuración. Compatible con instalaciones de WordPress multisitio. Creado a partir del plugin de <a href="http://profiles.wordpress.org/users/timbrd/">Tim Brando</a> <a href="http://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/"><strong>Google News Sitemap Feed With Multisite Support</strong></a> y el plugin de <a href="http://www.labnol.org/internet/google-image-sitemap-for-wordpress/14125/">Amit Agarwal</a> <a href="http://wordpress.org/plugins/google-image-sitemap/"><strong>Google XML Sitemap for Images</strong></a>.
Author: Art Project Group
Author URI: http://www.artprojectgroup.es/
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

/* --------------------
 *      CONSTANTS
 * -------------------- */
define('XMLSIF_VERSION','1.0');
define('XMLSIF_MEMORY_LIMIT','128M');

if (file_exists(dirname(__FILE__).'/google-image-sitemap-feed-mu'))
	define('XMLSIF_PLUGIN_DIR', dirname(__FILE__).'/google-image-sitemap-feed-mu');
else
	define('XMLSIF_PLUGIN_DIR', dirname(__FILE__));		

/* -----------------
 *      CLASS
 * ----------------- */

if( class_exists('XMLSitemapImageFeed') || include( XMLSIF_PLUGIN_DIR . '/XMLSitemapImageFeed.class.php' ) )
	XMLSitemapImageFeed::go();

