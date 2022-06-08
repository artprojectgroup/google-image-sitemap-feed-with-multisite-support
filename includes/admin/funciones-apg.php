<?php
//Definimos las variables
$apg_image_sitemap = array( 	
	'plugin' 		=> 'APG Google Image Sitemap Feed', 
	'plugin_uri' 	=> 'google-image-sitemap-feed-with-multisite-support', 
	'donacion' 		=> 'https://artprojectgroup.es/tienda/donacion',
	'soporte' 		=> 'https://artprojectgroup.es/tienda/ticket-de-soporte',
	'plugin_url' 	=> 'https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed', 
	'ajustes' 		=> '', 
	'puntuacion' 	=> 'https://wordpress.org/support/view/plugin-reviews/google-image-sitemap-feed-with-multisite-support'
 );

//Número máximo de imágenes por feed
$maximo_imagenes    = 1000;

//Carga el idioma
function apg_image_sitemap_inicia_idioma() {
    load_plugin_textdomain( 'google-image-sitemap-feed-with-multisite-support', null, dirname( DIRECCION_apg_image_sitemap ) . '/languages' );
}
add_action( 'plugins_loaded', 'apg_image_sitemap_inicia_idioma' );

//Enlaces adicionales personalizados
function apg_image_sitemap_enlaces( $enlaces, $archivo ) {
	global $apg_image_sitemap;

	if ( $archivo == DIRECCION_apg_image_sitemap ) {
		$plugin		= apg_image_sitemap_plugin( $apg_image_sitemap['plugin_uri'] );
		$enlaces[]	= '<a href="' . $apg_image_sitemap['donacion'] . '" target="_blank" title="' . __( 'Make a donation by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[]	= '<a href="'. $apg_image_sitemap['plugin_url'] . '" target="_blank" title="' . $apg_image_sitemap['plugin'] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[]	= '<a href="https://www.facebook.com/artprojectgroup" title="' . __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://es.linkedin.com/in/artprojectgroup" title="' . __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[]	= '<a href="https://profiles.wordpress.org/artprojectgroup/" title="' . __( 'More plugins on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[]	= '<a href="mailto:info@artprojectgroup.es" title="' . __( 'Contact with us by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . __( 'Contact with us by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[]	= apg_image_sitemap_plugin( $apg_image_sitemap['plugin_uri'] );
	}
	
	return $enlaces;
}
add_filter( 'plugin_row_meta', 'apg_image_sitemap_enlaces', 10, 2 );

//Obtiene toda la información sobre el plugin
function apg_image_sitemap_plugin( $nombre ) {
	global $apg_image_sitemap;

	$respuesta	= get_transient( 'apg_image_sitemap_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . $nombre  );
		set_transient( 'apg_image_sitemap_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( ! is_wp_error( $respuesta ) ) {
		$plugin = json_decode( wp_remote_retrieve_body( $respuesta ) );
	} else {
	   return '<a title="' . sprintf( __( 'Please, rate %s:', 'google-image-sitemap-feed-with-multisite-support' ), $apg_image_sitemap[ 'plugin' ] ) . '" href="' . $apg_image_sitemap[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . __( 'Unknown rating', 'google-image-sitemap-feed-with-multisite-support' ) . '</a>';
	}

    $rating = [
	   'rating'		=> $plugin->rating,
	   'type'		=> 'percent',
	   'number'		=> $plugin->num_ratings,
	];
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'google-image-sitemap-feed-with-multisite-support' ), $apg_image_sitemap[ 'plugin' ] ) . '" href="' . $apg_image_sitemap[ 'puntuacion' ] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Hoja de estilo
function apg_image_sitemap_estilo() {
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'plugins.php' ) !== false ) {
		wp_register_style( 'apg_image_sitemap_fuentes', plugins_url( 'assets/fonts/stylesheet.css', DIRECCION_apg_image_sitemap ) ); //Carga la hoja de estilo
		wp_enqueue_style( 'apg_image_sitemap_fuentes' );
	}
}
add_action( 'admin_enqueue_scripts', 'apg_image_sitemap_estilo' );

