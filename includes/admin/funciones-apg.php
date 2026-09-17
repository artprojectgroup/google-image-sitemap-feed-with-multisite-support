<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

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

//Número máximo de imágenes por entrada, que es el límite que impone Google
function apg_image_sitemap_maximo_imagenes() {
    $maximo = (int) apply_filters( 'apg_image_sitemap_maximo_imagenes', 1000 );

    return ( $maximo > 0 && $maximo <= 1000 ) ? $maximo : 1000;
}

//Número máximo de entradas por sitemap, que es el límite del protocolo
function apg_image_sitemap_maximo_entradas() {
    $maximo = (int) apply_filters( 'apg_image_sitemap_maximo_entradas', 50000 );

    return ( $maximo > 0 && $maximo <= 50000 ) ? $maximo : 50000;
}

//Enlaces adicionales personalizados
function apg_image_sitemap_enlaces( $enlaces, $archivo ) {
	global $apg_image_sitemap;

	if ( $archivo === APG_IMAGE_SITEMAP_BASE ) {
		$enlaces[]	= '<a href="' . esc_url( $apg_image_sitemap[ 'donacion' ] ) . '" target="_blank" title="' . esc_attr( __( 'Make a donation by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'APG' ) . '"><span class="genericon genericon-cart"></span></a>';
		$enlaces[]	= '<a href="'. esc_url( $apg_image_sitemap[ 'plugin_url' ] ) . '" target="_blank" title="' . esc_attr( $apg_image_sitemap[ 'plugin' ] ) . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[]	= '<a href="https://www.facebook.com/artprojectgroup" title="' . esc_attr( __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Facebook' ) . '" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . esc_attr( __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Twitter' ) . '" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://es.linkedin.com/in/artprojectgroup" title="' . esc_attr( __( 'Follow us on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'LinkedIn' ) . '" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[]	= '<a href="https://profiles.wordpress.org/artprojectgroup/" title="' . esc_attr( __( 'More plugins on ', 'google-image-sitemap-feed-with-multisite-support' ) . 'WordPress' ) . '" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[]	= '<a href="mailto:info@artprojectgroup.es" title="' . esc_attr( __( 'Contact us by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'e-mail' ) . '"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . esc_attr( __( 'Contact us by ', 'google-image-sitemap-feed-with-multisite-support' ) . 'Skype' ) . '"><span class="genericon genericon-skype"></span></a>';
		$enlaces[]	= apg_image_sitemap_plugin( $apg_image_sitemap[ 'plugin_uri' ] );
	}
	
	return $enlaces;
}
add_filter( 'plugin_row_meta', 'apg_image_sitemap_enlaces', 10, 2 );

//Devuelve el enlace a la valoración del plugin
function apg_image_sitemap_valoracion( $estrellas ) {
	global $apg_image_sitemap;

	return '<a title="' . esc_attr( sprintf(
			/* translators: %s: name of the plugin. */
			__( 'Please, rate %s:', 'google-image-sitemap-feed-with-multisite-support' ),
			$apg_image_sitemap[ 'plugin' ]
		) ) . '" href="' . esc_url( $apg_image_sitemap[ 'puntuacion' ] . '?rate=5#postform' ) . '" class="estrellas">' . $estrellas . '</a>';
}

//Obtiene toda la información sobre el plugin
function apg_image_sitemap_plugin( $nombre ) {
	$desconocida	= esc_html__( 'Unknown rating', 'google-image-sitemap-feed-with-multisite-support' );
	$plugin			= get_transient( 'apg_image_sitemap_plugin' );

	if ( false === $plugin ) {
		$respuesta	= wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . rawurlencode( $nombre ), [ 'timeout' => 10 ] );

		//Una respuesta fallida no se guarda en caché para poder reintentarlo en la siguiente carga
		if ( is_wp_error( $respuesta ) || 200 !== wp_remote_retrieve_response_code( $respuesta ) ) {
			return apg_image_sitemap_valoracion( $desconocida );
		}

		$plugin		= json_decode( wp_remote_retrieve_body( $respuesta ) );
		if ( ! is_object( $plugin ) || ! isset( $plugin->rating, $plugin->num_ratings ) ) {
			return apg_image_sitemap_valoracion( $desconocida );
		}

		set_transient( 'apg_image_sitemap_plugin', $plugin, 24 * HOUR_IN_SECONDS );
	}

	if ( ! is_object( $plugin ) || ! isset( $plugin->rating, $plugin->num_ratings ) ) {
		return apg_image_sitemap_valoracion( $desconocida );
	}

	if ( ! function_exists( 'wp_star_rating' ) ) {
		require_once ABSPATH . 'wp-admin/includes/template.php';
	}

	ob_start();
	wp_star_rating( [
		'rating'	=> (float) $plugin->rating,
		'type'		=> 'percent',
		'number'	=> (int) $plugin->num_ratings,
	] );
	$estrellas		= ob_get_clean();

	return apg_image_sitemap_valoracion( $estrellas );
}

//Hoja de estilo
function apg_image_sitemap_estilo( $pantalla ) {
	//El hook identifica la pantalla sin necesidad de leer la petición del visitante
	if ( 0 !== strpos( (string) $pantalla, 'plugins.php' ) ) {
		return;
	}

	wp_enqueue_style( 'apg_image_sitemap_fuentes', plugins_url( 'assets/fonts/stylesheet.css', APG_IMAGE_SITEMAP_ARCHIVO ), [], APG_IMAGE_SITEMAP_VERSION ); //Carga la hoja de estilo
}
add_action( 'admin_enqueue_scripts', 'apg_image_sitemap_estilo' );
