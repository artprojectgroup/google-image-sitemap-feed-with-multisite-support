<?php
/*
Plugin Name: APG Google Image Sitemap Feed
Version: 2.0.1
Plugin URI: https://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/
Description: Dynamically generates a Google Image Sitemap and automatically submit updates to Google and Bing. No settings required. Compatible with WordPress Multisite installations. Created from <a href="https://profiles.wordpress.org/users/timbrd/" target="_blank">Tim Brandon</a> <a href="https://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/" target="_blank"><strong>Google News Sitemap Feed With Multisite Support</strong></a> and <a href="https://profiles.wordpress.org/labnol/" target="_blank">Amit Agarwal</a> <a href="https://wordpress.org/plugins/google-image-sitemap/" target="_blank"><strong>Google XML Sitemap for Images</strong></a> plugins.
Author URI: https://artprojectgroup.es/
Author: Art Project Group
Requires at least: 2.6
Tested up to: 6.1

Text Domain: google-image-sitemap-feed-with-multisite-support
Domain Path: /languages

@package Google Image Sitemap Feed With Multisite Support
@category Core
@author Art Project Group
*/

//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Definimos constantes
define( 'DIRECCION_apg_image_sitemap', plugin_basename( __FILE__ ) );

//Funciones generales de APG
include_once( 'includes/admin/funciones-apg.php' );

//Clase
include( 'includes/admin/clases/xml.php' );

//Controla si se ha actualizado el plugin 
function apg_image_sitemap_actualiza( $upgrader_object, $opciones ) {
    $plugin_apg = plugin_basename( __FILE__ );
 
    if ( $opciones[ 'action' ] == 'update' && $opciones[ 'type' ] == 'plugin' ) {
        foreach ( $opciones[ 'plugins' ] as $plugin_apg ) {
            if ( $plugin == $current_plugin_path_name ) {
                global $wp_rewrite;

                $wp_rewrite->flush_rules(); //Regenera los enlaces permanentes
                delete_option( 'gn-sitemap-image-feed-mu-version' ); //Esta opción ya no es necesaria
                delete_transient( 'xml_sitemap_image' );
            }
        }
    }
}
add_action( 'upgrader_process_complete', 'apg_image_sitemap_actualiza',10, 2);

//Elimina todo rastro del plugin al desinstalarlo
function apg_image_sitemap_desinstalar() {
	delete_transient( 'xml_sitemap_image' );
}
register_uninstall_hook( __FILE__, 'apg_image_sitemap_desinstalar' );

//Controla la desactivación del plugin
function apg_image_sitemap_desactivador() {
    APGSitemapImage::desactivar();
}
register_deactivation_hook( __FILE__, 'apg_image_sitemap_desactivador' );
