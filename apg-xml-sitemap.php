<?php
/*
Plugin Name: APG Google Image Sitemap Feed
Version: 3.0.0
Plugin URI: https://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/
Description: Dynamically generates a Google Image Sitemap and automatically submits updates through IndexNow. No settings required. Compatible with WordPress Multisite installations. Created from <a href="https://profiles.wordpress.org/users/timbrd/" target="_blank">Tim Brandon</a> <a href="https://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/" target="_blank"><strong>Google News Sitemap Feed With Multisite Support</strong></a> and <a href="https://profiles.wordpress.org/labnol/" target="_blank">Amit Agarwal</a> <a href="https://wordpress.org/plugins/google-image-sitemap/" target="_blank"><strong>Google XML Sitemap for Images</strong></a> plugins.
Author URI: https://artprojectgroup.es/
Author: Art Project Group
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 7.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Text Domain: google-image-sitemap-feed-with-multisite-support
Domain Path: /languages

@package APG Google Image Sitemap Feed
@category Core
@author Art Project Group
*/

//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Definimos constantes
define( 'APG_IMAGE_SITEMAP_BASE', plugin_basename( __FILE__ ) );
define( 'APG_IMAGE_SITEMAP_VERSION', '3.0.0' );
define( 'APG_IMAGE_SITEMAP_ARCHIVO', __FILE__ );
define( 'APG_IMAGE_SITEMAP_RUTA', plugin_dir_path( __FILE__ ) );

//Funciones generales de APG
include_once APG_IMAGE_SITEMAP_RUTA . 'includes/admin/funciones-apg.php';

//Aviso de plugins SEO que ya publican un sitemap de imágenes
if ( is_admin() ) {
    include_once APG_IMAGE_SITEMAP_RUTA . 'includes/admin/aviso-seo.php';
}

//Clase
include_once APG_IMAGE_SITEMAP_RUTA . 'includes/admin/clases/xml.php';

//Borra los datos que el plugin guarda en el sitio activo
function apg_image_sitemap_limpia_sitio() {
    delete_transient( 'xml_sitemap_image' );
    delete_transient( 'apg_image_sitemap_plugin' );
    delete_option( 'apg_image_sitemap_indexnow' );
    delete_option( 'apg_image_sitemap_version' );
    delete_option( 'gn-sitemap-image-feed-mu-version' ); //Esta opción ya no es necesaria

    //El descarte del aviso se guarda por usuario
    delete_metadata( 'user', 0, 'apg_image_sitemap_aviso_descartado', '', true );
}

//Controla si se ha actualizado el plugin
function apg_image_sitemap_actualiza( $upgrader_object, $opciones ) {
    if ( ! is_array( $opciones ) || ! isset( $opciones[ 'action' ], $opciones[ 'type' ] ) ) {
        return;
    }

    if ( 'update' !== $opciones[ 'action' ] || 'plugin' !== $opciones[ 'type' ] ) {
        return;
    }

    //La actualización individual informa en «plugin» y la masiva en «plugins»
    $plugins = [];
    if ( isset( $opciones[ 'plugins' ] ) && is_array( $opciones[ 'plugins' ] ) ) {
        $plugins = $opciones[ 'plugins' ];
    } elseif ( isset( $opciones[ 'plugin' ] ) ) {
        $plugins = [ $opciones[ 'plugin' ] ];
    }

    if ( ! in_array( APG_IMAGE_SITEMAP_BASE, $plugins, true ) ) {
        return;
    }

    delete_transient( 'xml_sitemap_image' );
    delete_option( 'gn-sitemap-image-feed-mu-version' ); //Esta opción ya no es necesaria
    delete_option( 'apg_image_sitemap_version' ); //Obliga a regenerar las reglas con el código ya actualizado
}
add_action( 'upgrader_process_complete', 'apg_image_sitemap_actualiza', 10, 2 );

//Elimina todo rastro del plugin al desinstalarlo
function apg_image_sitemap_desinstalar() {
    if ( is_multisite() ) {
        foreach ( get_sites( [ 'fields' => 'ids', 'number' => 0 ] ) as $sitio ) {
            switch_to_blog( $sitio );
            apg_image_sitemap_limpia_sitio();
            restore_current_blog();
        }

        return;
    }

    apg_image_sitemap_limpia_sitio();
}
register_uninstall_hook( __FILE__, 'apg_image_sitemap_desinstalar' );

//Controla la activación del plugin
function apg_image_sitemap_activador() {
    APG_Image_Sitemap_XML::activar();
}
register_activation_hook( __FILE__, 'apg_image_sitemap_activador' );

//Controla la desactivación del plugin
function apg_image_sitemap_desactivador() {
    APG_Image_Sitemap_XML::desactivar();
}
register_deactivation_hook( __FILE__, 'apg_image_sitemap_desactivador' );
