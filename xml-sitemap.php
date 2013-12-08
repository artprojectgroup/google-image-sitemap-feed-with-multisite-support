<?php
/*
Plugin Name: Google Image Sitemap Feed With Multisite Support
Version: 0.4
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
 
//Definimos las variables
$xml_image_sitemap = array(	'plugin' => 'Google Image Sitemap Feed With Multisite Support', 
								'plugin_uri' => 'google-image-sitemap-feed-with-multisite-support', 
								'plugin_url' => 'http://www.artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support', 
								'paypal' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4LDTBG4ZB4FTJ', 
								'ajustes' => '', 
								'imagen' => '', 
								'puntuacion' => 'http://wordpress.org/support/view/plugin-reviews/google-image-sitemap-feed-with-multisite-support');

//Carga el idioma
load_plugin_textdomain('xml_image_sitemap', null, dirname(plugin_basename(__FILE__)) . '/lang');

//Enlaces adicionales personalizados
function xml_sitemap_image_enlaces($enlaces, $archivo) {
	global $xml_image_sitemap;

	$plugin = plugin_basename(__FILE__);

	if ($archivo == $plugin) 
	{
		$plugin = xml_image_sitemap_plugin($xml_image_sitemap['plugin_uri']);
		$enlaces[] = '<a href="http://www.artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins" target="_blank" title="Art Project Group">' . __('<strong>Google XML Sitemaps</strong> compatibility fix', 'xml_image_sitemap') . '</a>';
		$enlaces[] = '<a href="' . $xml_image_sitemap['paypal'] . '" target="_blank" title="' . __('Make a donation by ', 'xml_image_sitemap') . 'PayPal"><span class="icon-paypal"></span></a>';
		$enlaces[] = '<a href="'. $xml_image_sitemap['plugin_url'] . '" target="_blank" title="' . $xml_image_sitemap['plugin'] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/artprojectgroup" title="' . __('Follow us on ', 'xml_image_sitemap') . 'Facebook" target="_blank"><span class="icon-facebook6"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __('Follow us on ', 'xml_image_sitemap') . 'Twitter" target="_blank"><span class="icon-social19"></span></a> <a href="https://plus.google.com/+ArtProjectGroupES" title="' . __('Follow us on ', 'xml_image_sitemap') . 'Google+" target="_blank"><span class="icon-google16"></span></a> <a href="http://es.linkedin.com/in/artprojectgroup" title="' . __('Follow us on ', 'xml_image_sitemap') . 'LinkedIn" target="_blank"><span class="icon-logo"></span></a>';
		$enlaces[] = '<a href="http://profiles.wordpress.org/artprojectgroup/" title="' . __('More plugins on ', 'xml_image_sitemap') . 'WordPress" target="_blank"><span class="icon-wordpress2"></span></a>';
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . __('Contact with us by ', 'xml_image_sitemap') . 'e-mail"><span class="icon-open21"></span></a> <a href="skype:artprojectgroup" title="' . __('Contact with us by ', 'xml_image_sitemap') . 'Skype"><span class="icon-social6"></span></a>';
		$enlaces[] = '<div class="star-holder rate"><div style="width:' . esc_attr(str_replace(',', '.', $plugin['rating'])) . 'px;" class="star-rating"></div><div class="star-rate"><a title="' . __('***** Fantastic!', 'xml_image_sitemap') . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=5#postform" target="_blank"><span></span></a> <a title="' . __('**** Great', 'xml_image_sitemap') . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=4#postform" target="_blank"><span></span></a> <a title="' . __('*** Good', 'xml_image_sitemap') . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=3#postform" target="_blank"><span></span></a> <a title="' . __('** Works', 'xml_image_sitemap') . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=2#postform" target="_blank"><span></span></a> <a title="' . __('* Poor', 'xml_image_sitemap') . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=1#postform" target="_blank"><span></span></a></div></div>';
	}
	
	return $enlaces;
}
add_filter('plugin_row_meta', 'xml_sitemap_image_enlaces', 10, 2);

//Constantes
define('XMLSIF_VERSION','0.4');
define('XMLSIF_MEMORY_LIMIT','128M');

if (file_exists(dirname(__FILE__).'/google-image-sitemap-feed-mu')) define('XMLSIF_PLUGIN_DIR', dirname(__FILE__).'/google-image-sitemap-feed-mu');
else define('XMLSIF_PLUGIN_DIR', dirname(__FILE__));		

//Clase
if (class_exists('XMLSitemapImageFeed') || include(XMLSIF_PLUGIN_DIR . '/XMLSitemapImageFeed.class.php')) XMLSitemapImageFeed::go();

//Obtiene toda la información sobre el plugin
function xml_image_sitemap_plugin($nombre) {
	$argumentos = (object) array('slug' => $nombre);
	$consulta = array('action' => 'plugin_information', 'timeout' => 15, 'request' => serialize($argumentos));
	$url = 'http://api.wordpress.org/plugins/info/1.0/';
	$respuesta = wp_remote_post($url, array('body' => $consulta));
	$plugin = unserialize($respuesta['body']);
	//echo '<pre>' . print_r($plugin, true) . '</pre>';
	
	return get_object_vars($plugin);
}

//Comprueba si hay que mostrar el mensaje de configuración
function xml_image_sitemap_muestra_mensaje() {
	wp_register_style('xml_image_sitemap_fuentes', plugins_url('fonts/stylesheet.css', __FILE__)); //Carga la hoja de estilo global
	wp_enqueue_style('xml_image_sitemap_fuentes'); //Carga la hoja de estilo global
}
add_action('admin_init', 'xml_image_sitemap_muestra_mensaje');
