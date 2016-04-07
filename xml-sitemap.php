<?php
/*
Plugin Name: Google Image Sitemap Feed With Multisite Support
Version: 1.2.0.2
Plugin URI: http://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/
Description: Dynamically generates a Google Image Sitemap and automatically submit updates to Google and Bing. No settings required. Compatible with WordPress Multisite installations. Created from <a href="http://profiles.wordpress.org/users/timbrd/" target="_blank">Tim Brandon</a> <a href="http://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/" target="_blank"><strong>Google News Sitemap Feed With Multisite Support</strong></a> and <a href="http://profiles.wordpress.org/labnol/" target="_blank">Amit Agarwal</a> <a href="http://wordpress.org/plugins/google-image-sitemap/" target="_blank"><strong>Google XML Sitemap for Images</strong></a> plugins.
Author URI: http://www.artprojectgroup.es/
Author: Art Project Group
Requires at least: 2.6
Tested up to: 4.5

Text Domain: xml_image_sitemap
Domain Path: /i18n/languages

@package Google Image Sitemap Feed With Multisite Support
@category Core
@author Art Project Group
*/

/* --------------------
 *  AVAILABLE HOOKS
 * --------------------
 *
 * FILTERS
 *	xml_sitemap_url	->	Filters the URL used in the sitemap reference in robots.txt
 *				( receives an ARRAY and MUST return one; can be multiple urls ) 
 *				and for the home URL in the sitemap ( receives a STRING and MUST )
 *				return one ) itself. Useful for multi language plugins or other 
 *				plugins that affect the blogs main URL... See pre-defined filter
 *				XMLSitemapImageFeed::qtranslate() in XMLSitemapImageFeed.class.php as an
 *				example.
 * ACTIONS
 *	[ none at this point, but feel free to request, suggest or code one : ) ]
 *	
 */

//Igual no deberías poder abrirme
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

//Definimos constantes
define( 'DIRECCION_xml_image_sitemap', plugin_basename( __FILE__ ) );

//Definimos las variables
$xml_image_sitemap = array( 	
	'plugin' 		=> 'Google Image Sitemap Feed With Multisite Support', 
	'plugin_uri' 	=> 'google-image-sitemap-feed-with-multisite-support', 
	'donacion' 		=> 'http://www.artprojectgroup.es/tienda/donacion',
	'soporte' 		=> 'http://www.wpprojectgroup.es/tienda/ticket-de-soporte',
	'plugin_url' 	=> 'http://www.artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support', 
	'ajustes' 		=> '', 
	'puntuacion' 	=> 'http://wordpress.org/support/view/plugin-reviews/google-image-sitemap-feed-with-multisite-support'
 );

//Carga el idioma
load_plugin_textdomain( 'xml_image_sitemap', null, dirname( DIRECCION_xml_image_sitemap ) . '/i18n/languages' );

//Enlaces adicionales personalizados
function xml_image_sitemap_enlaces( $enlaces, $archivo ) {
	global $xml_image_sitemap;

	if ( $archivo == DIRECCION_xml_image_sitemap ) {
		$enlaces[] = '<a href="' . $xml_image_sitemap['donacion'] . '" target="_blank" title="' . __( 'Make a donation by ', 'xml_image_sitemap' ) . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[] = '<a href="'. $xml_image_sitemap['plugin_url'] . '" target="_blank" title="' . $xml_image_sitemap['plugin'] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/artprojectgroup" title="' . __( 'Follow us on ', 'xml_image_sitemap' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __( 'Follow us on ', 'xml_image_sitemap' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://plus.google.com/+ArtProjectGroupES" title="' . __( 'Follow us on ', 'xml_image_sitemap' ) . 'Google+" target="_blank"><span class="genericon genericon-googleplus-alt"></span></a> <a href="http://es.linkedin.com/in/artprojectgroup" title="' . __( 'Follow us on ', 'xml_image_sitemap' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[] = '<a href="http://profiles.wordpress.org/artprojectgroup/" title="' . __( 'More plugins on ', 'xml_image_sitemap' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . __( 'Contact with us by ', 'xml_image_sitemap' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . __( 'Contact with us by ', 'xml_image_sitemap' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[] = xml_image_sitemap_plugin( $xml_image_sitemap['plugin_uri'] );
	}
	
	return $enlaces;
}
add_filter( 'plugin_row_meta', 'xml_image_sitemap_enlaces', 10, 2 );

//Añade el botón de configuración
function xml_image_sitemap_enlace_de_ajustes( $enlaces ) { 
	global $xml_image_sitemap;

	$enlaces_de_ajustes = array(
		'<a href="' . $xml_image_sitemap['soporte'] . '" title="' . __( 'Support of ', 'xml_image_sitemap' ) . $xml_image_sitemap['plugin'] .'">' . __( 'Support', 'apg_shipping' ) . '</a>'
	);
	foreach( $enlaces_de_ajustes as $enlace_de_ajustes )	{
		array_unshift( $enlaces, $enlace_de_ajustes );
	}
	
	return $enlaces; 
}
$plugin = DIRECCION_xml_image_sitemap; 
add_filter( "plugin_action_links_$plugin", 'xml_image_sitemap_enlace_de_ajustes' );

//Constantes
define( 'XMLSIF_VERSION', '1.2.0.2' );
define( 'XMLSIF_MEMORY_LIMIT', '128M' );

if ( file_exists( dirname( __FILE__ ) . '/google-image-sitemap-feed-mu' ) ) {
	define( 'XMLSIF_PLUGIN_DIR', dirname( __FILE__ ) . '/google-image-sitemap-feed-mu' );
} else {
	define( 'XMLSIF_PLUGIN_DIR', dirname( __FILE__ ) );		
}

//Clase
if ( class_exists( 'XMLSitemapImageFeed' ) || include( XMLSIF_PLUGIN_DIR . '/includes/XMLSitemapImageFeed.class.php' ) ) {
	XMLSitemapImageFeed::go();
}

//Obtiene toda la información sobre el plugin
function xml_image_sitemap_plugin( $nombre ) {
	global $xml_image_sitemap;
	
	$argumentos = ( object ) array( 
		'slug' => $nombre 
	);
	$consulta = array( 
		'action' => 'plugin_information', 
		'timeout' => 15, 
		'request' => serialize( $argumentos )
	);
	$respuesta = get_transient( 'xml_image_sitemap_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_post( 'http://api.wordpress.org/plugins/info/1.0/', array( 
			'body' => $consulta)
		);
		set_transient( 'xml_image_sitemap_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( !is_wp_error( $respuesta ) ) {
		$plugin = get_object_vars( unserialize( $respuesta['body'] ) );
	} else {
		$plugin['rating'] = 100;
	}

	$rating = array(
	   'rating'	=> $plugin['rating'],
	   'type'	=> 'percent',
	   'number'	=> $plugin['num_ratings'],
	);
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'xml_image_sitemap' ), $xml_image_sitemap['plugin'] ) . '" href="' . $xml_image_sitemap['puntuacion'] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Carga las hojas de estilo
function xml_image_sitemap_muestra_mensaje() {
	wp_register_style( 'xml_image_sitemap_fuentes', plugins_url( 'assets/fonts/stylesheet.css', __FILE__ ) ); //Carga la hoja de estilo global
	wp_enqueue_style( 'xml_image_sitemap_fuentes' ); //Carga la hoja de estilo global
}
add_action( 'admin_init', 'xml_image_sitemap_muestra_mensaje' );

//Eliminamos todo rastro del plugin al desinstalarlo
function xml_image_sitemap_desinstalar() {
	delete_option( 'gn-sitemap-image-feed-mu-version' );
	delete_transient( 'xml_sitemap_image' );
}
register_uninstall_hook( __FILE__, 'xml_image_sitemap_desinstalar' );
