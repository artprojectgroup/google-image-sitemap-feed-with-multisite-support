<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/*
Clase que controla todo lo relacionado con el  XML
*/
class APGSitemapImage {
	public function __construct() {		
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'do_feed_sitemap-image', [ $this, 'carga_plantilla' ], 10, 1 );
        add_filter( 'generate_rewrite_rules', [ $this, 'rewrite' ] );
        add_action( 'enviar_ping', [ $this, 'envia_ping' ], 10, 1 ); 
        //Actúa cuando se publica una página, una entrada o se borra una entrada
        add_action( 'publish_post', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'publish_page', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'delete_post', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'pre_post_update', [ $this, 'programa_ping' ], 999, 1 );
	}

    //Funciones iniciales
	public function init() {
		if ( defined( 'QT_LANGUAGE' ) ) {
			add_filter( 'xml_sitemap_url', [ $this, 'qtranslate' ], 99 );
		}
	}
    
	//Carga la plantilla del XML
	public function carga_plantilla() {
		load_template( plugin_dir_path( __FILE__ ) . 'contenido-xml.php' );
	}

	//Añade el sitemap a los enlaces permanentes
	public function rewrite( $wp_rewrite ) {
        global $maximo_imagenes;
        
        $feed_rules           = [ 
            'sitemap-image.xml$'    => $wp_rewrite->index . '?feed=sitemap-image' 
        ];
        $imagenes             = get_transient( 'xml_sitemap_image' );
        if ( ! empty ( $imagenes ) && ceil( count( $imagenes ) / $maximo_imagenes ) > 1 ) {
            for ( $i = 1; $i <= ceil( count( $imagenes ) / $maximo_imagenes ); $i++ ) {
                $feed_rules[ "sitemap-image-$i.xml$" ]   = $wp_rewrite->index . "?feed=sitemap-image";
            }
        }
		$wp_rewrite->rules    = $feed_rules + $wp_rewrite->rules;
	}

    // qTranslate
	public function qtranslate( $input ) {
		global $q_config;

		if ( is_array( $input ) ) { // got an array? return one!
			foreach ( $input as $url ) {
				foreach( $q_config[ 'enabled_languages' ] as $language ) {
					$return[] = qtrans_convertURL( $url, $language );
				}
			}
		} else {
			$return = qtrans_convertURL( $input ); // not an array? just convert the string.
		}

		return $return;
	}

	//Envía el ping a Google y Bing
	public function envia_ping() {
        $url      = urlencode( home_url( '/' ) . "sitemap-image.xml" );
		$ping     = [ 
			"https://www.google.com/webmasters/sitemaps/ping?sitemap=$url", 
			"https://www.bing.com/webmaster/ping.aspx?siteMap=$url" 
		];
		$opciones = [
            'timeout'   => 10,
        ];
		foreach( $ping as $url ) {
			wp_remote_get( $url, $opciones );
		}
	}

	//Programa el ping a los buscadores web
	public function programa_ping() {
		delete_transient( 'xml_sitemap_image' );
		wp_schedule_single_event( time(), 'enviar_ping' );
	}

	//Desactiva el plugin
	public static function desactivar() {
		global $wp_rewrite;

		remove_filter( 'generate_rewrite_rules', [ __CLASS__, 'rewrite' ] );
		$wp_rewrite->flush_rules();
	}
}
new APGSitemapImage();