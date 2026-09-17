<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/*
Clase que controla todo lo relacionado con el  XML
*/
class APG_Image_Sitemap_XML {
    //Nombre de la opción donde se guarda la clave de IndexNow
    const CLAVE_INDEXNOW = 'apg_image_sitemap_indexnow';

    //Nombre de la opción donde se guarda la versión instalada
    const VERSION = 'apg_image_sitemap_version';

    //Mientras se desactiva el plugin, rewrite() no debe añadir sus reglas
    private static $desactivando = false;

	public function __construct() {
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'do_feed_sitemap-image', [ $this, 'carga_plantilla' ], 10, 1 );
        add_filter( 'generate_rewrite_rules', [ $this, 'rewrite' ] );
        add_filter( 'query_vars', [ $this, 'variables' ] );
        add_action( 'template_redirect', [ $this, 'sirve_clave_indexnow' ], 5 );
        add_filter( 'redirect_canonical', [ $this, 'evita_redireccion' ], 10, 1 );
        add_filter( 'robots_txt', [ $this, 'robots' ], 10, 2 );
        add_action( 'wp_sitemaps_init', [ $this, 'indice_del_nucleo' ] );
        add_action( 'enviar_ping', [ $this, 'envia_ping' ], 10, 1 );
        //Actúa cuando cambia el contenido que aparece en el sitemap
        add_action( 'transition_post_status', [ $this, 'cambia_estado' ], 999, 3 );
        add_action( 'post_updated', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'delete_post', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'add_attachment', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'edit_attachment', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'delete_attachment', [ $this, 'programa_ping' ], 999, 1 );
	}

    //Funciones iniciales
	public function init() {
		if ( defined( 'QT_LANGUAGE' ) ) {
			add_filter( 'xml_sitemap_url', [ $this, 'qtranslate' ], 99 );
		}

        /* Al actualizar, WordPress regenera las reglas con el código anterior todavía en
        memoria, así que hay que volver a hacerlo una vez con el nuevo ya cargado. */
        if ( get_option( self::VERSION ) !== APG_IMAGE_SITEMAP_VERSION ) {
            update_option( self::VERSION, APG_IMAGE_SITEMAP_VERSION, false );
            self::clave_indexnow(); //Se crea antes de regenerar las reglas, porque una de ellas la contiene
            flush_rewrite_rules();
        }
	}

	//Carga la plantilla del XML
	public function carga_plantilla() {
		load_template( plugin_dir_path( __FILE__ ) . 'contenido-xml.php' );
	}

    //Registra las variables de consulta propias
    public function variables( $variables ) {
        $variables[]    = 'apg_image_sitemap_pagina';
        $variables[]    = 'apg_image_sitemap_indexnow';

        return $variables;
    }

	//Añade el sitemap a los enlaces permanentes
	public function rewrite( $wp_rewrite ) {
        if ( self::$desactivando ) {
            return;
        }

        /* La paginación se resuelve con una única regla con captura, así que el número de
        sitemaps puede cambiar sin necesidad de regenerar los enlaces permanentes. */
        $feed_rules             = [
            'sitemap-image\.xml$'           => $wp_rewrite->index . '?feed=sitemap-image',
            'sitemap-image-([0-9]+)\.xml$'  => $wp_rewrite->index . '?feed=sitemap-image&apg_image_sitemap_pagina=$matches[1]',
        ];

        //Archivo de verificación de IndexNow, que debe llamarse como la clave
        $clave                  = self::clave_indexnow();
        if ( $clave ) {
            $feed_rules[ preg_quote( $clave, '/' ) . '\.txt$' ]  = $wp_rewrite->index . '?apg_image_sitemap_indexnow=1';
        }

		$wp_rewrite->rules      = $feed_rules + $wp_rewrite->rules;
	}

    // qTranslate
	public function qtranslate( $input ) {
		global $q_config;

        if ( ! function_exists( 'qtrans_convertURL' ) ) {
            return $input;
        }

		if ( is_array( $input ) ) { // got an array? return one!
            $return = [];
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

    //Devuelve la clave de IndexNow y la crea la primera vez
    public static function clave_indexnow() {
        $clave  = get_option( self::CLAVE_INDEXNOW );

        //IndexNow sólo admite entre 8 y 128 caracteres alfanuméricos o guiones
        if ( ! is_string( $clave ) || ! preg_match( '/^[a-zA-Z0-9-]{8,128}$/', $clave ) ) {
            $clave  = wp_generate_password( 32, false, false );
            update_option( self::CLAVE_INDEXNOW, $clave, false );
        }

        return $clave;
    }

    /**
     * Tipos de entrada que aparecen en el sitemap.
     *
     * @return array
     */
    public static function dame_tipos_de_entradas() {
        $tipos  = get_post_types( [ 'public' => true ], 'names' );
        unset( $tipos[ 'attachment' ] ); //Las páginas de adjunto no son contenido

        return array_values( (array) apply_filters( 'apg_image_sitemap_tipos_de_entradas', $tipos ) );
    }

    /**
     * Devuelve las entradas publicadas con las imágenes que muestran.
     *
     * El adjunto sólo dice dónde se subió el archivo, no dónde se usa, así que la
     * imagen destacada elegida desde la biblioteca nunca llegaba al sitemap. Aquí se
     * recogen las tres procedencias reales: destacada, contenido y adjuntas.
     *
     * @return array
     */
    public static function dame_entradas() {
        $entradas   = get_transient( 'xml_sitemap_image' );
        if ( is_array( $entradas ) ) {
            return $entradas;
        }

        $tipos      = self::dame_tipos_de_entradas();
        if ( empty( $tipos ) ) {
            set_transient( 'xml_sitemap_image', [], DAY_IN_SECONDS );

            return [];
        }

        $entradas   = [];
        $maximo     = apg_image_sitemap_maximo_imagenes();
        $lote       = 200;
        $desde      = 0;

        do {
            //Se recorre por lotes para no cargar en memoria el contenido de todo el sitio
            $consulta   = new WP_Query( [
                'post_type'                 => $tipos,
                'post_status'               => 'publish',
                'has_password'              => false,
                'posts_per_page'            => $lote,
                'offset'                    => $desde,
                'orderby'                   => 'date',
                'order'                     => 'DESC',
                'no_found_rows'             => true,
                'ignore_sticky_posts'       => true,
                'update_post_term_cache'    => false,
            ] );

            $filas      = $consulta->posts;
            if ( empty( $filas ) ) {
                break;
            }

            //Adjuntas: una sola consulta para todo el lote, que además llena su caché
            $adjuntas   = [];
            foreach ( get_posts( [
                'post_type'                 => 'attachment',
                'post_status'               => 'inherit',
                'post_mime_type'            => 'image',
                'post_parent__in'           => wp_list_pluck( $filas, 'ID' ),
                'posts_per_page'            => -1,
                'orderby'                   => [ 'menu_order' => 'ASC', 'date' => 'ASC' ],
                'no_found_rows'             => true,
                'update_post_term_cache'    => false,
            ] ) as $adjunta ) {
                $adjuntas[ (int) $adjunta->post_parent ][] = (int) $adjunta->ID;
            }

            /* Primera pasada: se reúnen los identificadores de imagen de todo el lote para
            llenar la caché de una vez, en lugar de una consulta por imagen. */
            $por_entrada    = [];
            $todas          = [];
            foreach ( $filas as $fila ) {
                $identificador                  = (int) $fila->ID;
                $por_entrada[ $identificador ]  = self::dame_identificadores( $identificador, $adjuntas[ $identificador ] ?? [] );
                $todas                          = array_merge( $todas, $por_entrada[ $identificador ] );
            }
            if ( ! empty( $todas ) ) {
                _prime_post_caches( array_unique( $todas ), false, true );
            }

            //Segunda pasada: ya con la caché llena, se construyen las direcciones
            foreach ( $filas as $fila ) {
                $identificador  = (int) $fila->ID;
                $imagenes       = self::dame_imagenes( $por_entrada[ $identificador ], $fila->post_content, $maximo );

                if ( empty( $imagenes ) ) {
                    continue;
                }

                $enlace         = get_permalink( $identificador );
                if ( ! $enlace ) {
                    continue;
                }

                $entradas[]     = [
                    'url'       => $enlace,
                    'lastmod'   => $fila->post_modified_gmt,
                    'imagenes'  => $imagenes,
                ];
            }

            $desde += $lote;
        } while ( count( $filas ) === $lote );

        set_transient( 'xml_sitemap_image', $entradas, DAY_IN_SECONDS );

        return $entradas;
    }

    /**
     * Reúne los identificadores de las imágenes que muestra una entrada.
     *
     * @param int   $identificador Identificador de la entrada.
     * @param array $adjuntas      Identificadores de las imágenes adjuntas.
     * @return array
     */
    private static function dame_identificadores( $identificador, $adjuntas ) {
        //La destacada va primera porque es la imagen principal de la página
        $destacada          = (int) get_post_thumbnail_id( $identificador );
        $identificadores    = $destacada ? [ $destacada ] : [];

        //Galería de producto de WooCommerce, que ni es adjunta ni aparece en el contenido
        $galeria            = get_post_meta( $identificador, '_product_image_gallery', true );
        if ( is_string( $galeria ) && '' !== $galeria ) {
            $identificadores = array_merge( $identificadores, array_map( 'absint', explode( ',', $galeria ) ) );
        }

        $identificadores    = array_merge( $identificadores, array_map( 'absint', (array) $adjuntas ) );

        return array_values( array_unique( array_filter( $identificadores ) ) );
    }

    /**
     * Construye las direcciones de las imágenes de una entrada, sin repetirlas.
     *
     * @param array  $identificadores Identificadores de las imágenes.
     * @param string $contenido       Contenido de la entrada.
     * @param int    $maximo          Número máximo de imágenes.
     * @return array
     */
    private static function dame_imagenes( $identificadores, $contenido, $maximo ) {
        $urls               = [];

        foreach ( $identificadores as $imagen ) {
            $url            = wp_get_attachment_url( $imagen );
            if ( $url ) {
                $urls[ $url ]   = true;
            }
        }

        //Incrustadas en el contenido, que pueden no ser adjuntos de esta entrada
        if ( is_string( $contenido ) && false !== stripos( $contenido, '<img' ) ) {
            if ( preg_match_all( '/<img[^>]+?src\s*=\s*["\']([^"\']+)["\']/i', $contenido, $coincidencias ) ) {
                foreach ( $coincidencias[ 1 ] as $url ) {
                    $url    = trim( html_entity_decode( $url, ENT_QUOTES, 'UTF-8' ) );
                    //Las incrustadas en base64 no son direcciones que Google pueda rastrear
                    if ( '' === $url || 0 === stripos( $url, 'data:' ) ) {
                        continue;
                    }

                    $urls[ $url ]   = true;
                }
            }
        }

        return array_slice( array_keys( $urls ), 0, $maximo );
    }

    /**
     * Pinta el XML del sitemap.
     *
     * Vive en la clase y no en el ámbito global de la plantilla para que sus variables
     * no acaben siendo variables globales del sitio.
     *
     * @return void
     */
    public static function pinta() {
        global $wp, $wp_query;

        //Obtiene las entradas publicadas con las imágenes que muestran
        $entradas               = self::dame_entradas();
        $maximo_entradas        = apg_image_sitemap_maximo_entradas();
        $juego_de_caracteres    = get_bloginfo( 'charset' );
        $total_de_feeds         = ( ! empty( $entradas ) ) ? (int) ceil( count( $entradas ) / $maximo_entradas ) : 1;

        /* El número de sitemap llega en la variable de consulta, pero se mantiene la lectura de
        la petición para las instalaciones que todavía no han regenerado los enlaces permanentes. */
        $numero_feed            = (int) get_query_var( 'apg_image_sitemap_pagina' );
        if ( ! $numero_feed && isset( $wp->request ) && preg_match( '#sitemap-image-([0-9]+)\.xml$#', (string) $wp->request, $coincidencias ) ) {
            $numero_feed        = (int) $coincidencias[ 1 ];
        }

        //Un sitemap que ya no existe no debe responder con un XML vacío
        if ( $numero_feed > $total_de_feeds ) {
            $wp_query->is_404   = true;
            $wp_query->is_feed  = false;
            status_header( 404 );

            return;
        }

        $wp_query->is_404       = false;
        $wp_query->is_feed      = true;

        //Añade la cabecera
        status_header( 200 );
        header( 'Content-Type: text/xml; charset=' . $juego_de_caracteres, true );

        //Hay que dividir el sitemap en varios
        if ( $total_de_feeds > 1 && ! $numero_feed ) {
            echo '<?xml version="1.0" encoding="' . esc_attr( $juego_de_caracteres ) . '"?>
        <!-- Created by APG Google Image Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) -->
        <!-- generated-on="' . esc_html( gmdate( 'Y-m-d\TH:i:s+00:00' ) ) . '" -->
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            for ( $i = 1; $i <= $total_de_feeds; $i++ ) {
                //La fecha del sitemap parcial es la de la entrada más reciente que contiene
                $tramo          = array_slice( $entradas, ( $i - 1 ) * $maximo_entradas, $maximo_entradas );
                $mas_reciente   = '';
                foreach ( $tramo as $entrada ) {
                    if ( $entrada[ 'lastmod' ] > $mas_reciente ) {
                        $mas_reciente = $entrada[ 'lastmod' ];
                    }
                }

                echo '<sitemap>
            <loc>' . esc_url( home_url( "/sitemap-image-$i.xml" ) ) . '</loc>
            <lastmod>' . esc_html( self::fecha( $mas_reciente ) ) . '</lastmod>
          </sitemap>';
            }
            echo '</sitemapindex>';

        	return;
        }

        //Inicia la plantilla
        echo '<?xml version="1.0" encoding="' . esc_attr( $juego_de_caracteres ) . '"?>
        <!-- Created by APG Google Image Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) -->
        <!-- generated-on="' . esc_html( gmdate( 'Y-m-d\TH:i:s+00:00' ) ) . '" -->
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

        /* El dominio sale de home_url() y no de $_SERVER, que el visitante puede manipular con
        la cabecera Host en algunas configuraciones de servidor. */
        $dominio    = wp_parse_url( home_url(), PHP_URL_HOST );

        if ( ! empty( $entradas ) ) {
            if ( $numero_feed ) {
                $entradas   = array_slice( $entradas, ( $numero_feed - 1 ) * $maximo_entradas, $maximo_entradas );
            }

            foreach ( $entradas as $entrada ) {
        		echo "\t" . '<url>' . PHP_EOL;
        		echo "\t\t" . '<loc>' . esc_url( $entrada[ 'url' ] ) . '</loc>' . PHP_EOL;
        		echo "\t\t" . '<lastmod>' . esc_html( self::fecha( $entrada[ 'lastmod' ] ) ) . '</lastmod>' . PHP_EOL;

                foreach ( $entrada[ 'imagenes' ] as $url_de_imagen ) {
                    /* Las imágenes servidas desde otro dominio se reescriben al del sitio, que es lo
                    que espera Google y lo que necesitan las instalaciones multisitio. */
                    $dominio_de_imagen  = wp_parse_url( $url_de_imagen, PHP_URL_HOST );
                    if ( $dominio && $dominio_de_imagen && $dominio_de_imagen !== $dominio ) {
                        $url_de_imagen  = str_replace( '://' . $dominio_de_imagen, '://' . $dominio, $url_de_imagen );
                    }
                    $url_de_imagen      = apply_filters( 'apg_image_sitemap_url_de_imagen', $url_de_imagen, $entrada );

                    echo "\t\t" . '<image:image>' . PHP_EOL;
                    echo "\t\t\t" . '<image:loc>' . esc_url( $url_de_imagen ) . '</image:loc>' . PHP_EOL;
                    echo "\t\t" . '</image:image>' . PHP_EOL;
                }

        		echo "\t" . '</url>' . PHP_EOL;
        	}
        }

        echo "</urlset>";
    }

    /**
     * Convierte una fecha de la base de datos al formato del protocolo.
     *
     * @param string $fecha_gmt Fecha en GMT.
     * @return string
     */
    public static function fecha( $fecha_gmt ) {
        $marca  = ( is_string( $fecha_gmt ) && '' !== $fecha_gmt && '0000-00-00 00:00:00' !== $fecha_gmt ) ? strtotime( $fecha_gmt . ' UTC' ) : false;

        return gmdate( 'Y-m-d\TH:i:s+00:00', $marca ? $marca : time() );
    }

    /**
     * Número de sitemaps de imágenes y fecha del más reciente.
     *
     * @return array
     */
    public static function dame_division() {
        $entradas   = self::dame_entradas();
        $maximo     = apg_image_sitemap_maximo_entradas();
        $reciente   = '';
        foreach ( $entradas as $entrada ) {
            if ( $entrada[ 'lastmod' ] > $reciente ) {
                $reciente = $entrada[ 'lastmod' ];
            }
        }

        return [
            'total'     => ( ! empty( $entradas ) ) ? (int) ceil( count( $entradas ) / $maximo ) : 1,
            'lastmod'   => $reciente,
        ];
    }

    /**
     * Declara el sitemap de imágenes en el índice que genera el propio WordPress.
     *
     * El índice del núcleo se construye únicamente con los proveedores registrados, así
     * que la única forma admitida de aparecer en wp-sitemap.xml es registrar uno.
     *
     * @return void
     */
    public function indice_del_nucleo() {
        if ( ! class_exists( 'WP_Sitemaps_Provider' ) || ! function_exists( 'wp_register_sitemap_provider' ) ) {
            return;
        }

        require_once plugin_dir_path( __FILE__ ) . 'proveedor.php';

        wp_register_sitemap_provider( 'apg-image', new APG_Image_Sitemap_Proveedor() );
    }

    /* Declara el sitemap en robots.txt, que junto a Search Console es la única forma que
    documenta Google de darle a conocer un sitemap desde que retiró el ping. */
    public function robots( $salida, $publico ) {
        //Un sitio marcado como no indexable no debe anunciar sus sitemaps
        if ( '0' === (string) $publico ) {
            return $salida;
        }

        $linea  = 'Sitemap: ' . esc_url_raw( home_url( '/sitemap-image.xml' ) );
        if ( false !== strpos( (string) $salida, $linea ) ) {
            return $salida;
        }

        return rtrim( (string) $salida ) . "\n" . $linea . "\n";
    }

    /* Evita que la redirección canónica de WordPress añada la barra final a las URL del
    plugin, que terminan en .xml o en .txt y responderían con un 301 innecesario. */
    public function evita_redireccion( $redireccion ) {
        if ( get_query_var( 'apg_image_sitemap_indexnow' ) || 'sitemap-image' === get_query_var( 'feed' ) ) {
            return false;
        }

        return $redireccion;
    }

    //Sirve el archivo de verificación de IndexNow
    public function sirve_clave_indexnow() {
        if ( ! get_query_var( 'apg_image_sitemap_indexnow' ) ) {
            return;
        }

        status_header( 200 );
        header( 'Content-Type: text/plain; charset=UTF-8' );
        echo esc_html( self::clave_indexnow() );
        exit;
    }

	//Envía la URL modificada a IndexNow, que la comparte con Bing, Yandex, Naver y Seznam
	public function envia_ping( $url = '' ) {
        $url        = esc_url_raw( (string) $url );
        if ( ! $url ) {
            $url    = home_url( '/' );
        }

        $dominio    = wp_parse_url( home_url(), PHP_URL_HOST );
        //Sin dominio público no hay nada que notificar
        if ( ! $dominio || false === strpos( $dominio, '.' ) ) {
            return;
        }

        if ( ! apply_filters( 'apg_image_sitemap_envia_indexnow', true, $url ) ) {
            return;
        }

        $clave      = self::clave_indexnow();
        wp_remote_post( 'https://api.indexnow.org/indexnow', [
            'timeout'   => 10,
            'blocking'  => false,
            'headers'   => [
                'Content-Type'  => 'application/json; charset=utf-8',
            ],
            'body'      => wp_json_encode( [
                'host'          => $dominio,
                'key'           => $clave,
                'keyLocation'   => home_url( '/' . $clave . '.txt' ),
                'urlList'       => [ $url ],
            ] ),
        ] );
	}

    //Actúa sólo cuando el contenido entra o sale del sitemap
    public function cambia_estado( $nuevo_estado, $estado_anterior, $entrada ) {
        if ( $nuevo_estado === $estado_anterior ) {
            return;
        }

        if ( 'publish' !== $nuevo_estado && 'publish' !== $estado_anterior ) {
            return;
        }

        $this->programa_ping( $entrada );
    }

	//Programa el aviso a los buscadores web
	public function programa_ping( $entrada ) {
        $entrada    = get_post( $entrada );
        if ( ! $entrada ) {
            return;
        }

        /* Los autoguardados y las revisiones no aparecen en el sitemap, y sin este filtro
        cada pulsación del editor invalidaba la caché y programaba un aviso. */
        if ( wp_is_post_revision( $entrada ) || wp_is_post_autosave( $entrada ) ) {
            return;
        }

        //Las imágenes del sitemap cuelgan siempre de la entrada a la que están adjuntas
        if ( 'attachment' === $entrada->post_type ) {
            if ( 0 === (int) $entrada->post_parent || 0 !== strpos( (string) $entrada->post_mime_type, 'image/' ) ) {
                return;
            }

            $entrada    = get_post( $entrada->post_parent );
            if ( ! $entrada ) {
                return;
            }
        }

        if ( ! is_post_type_viewable( $entrada->post_type ) ) {
            return;
        }

		delete_transient( 'xml_sitemap_image' );

        if ( 'publish' !== $entrada->post_status || '' !== $entrada->post_password ) {
            return;
        }

        $url        = get_permalink( $entrada );
        if ( $url ) {
            wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'enviar_ping', [ $url ] );
        }
	}

	//Activa el plugin
	public static function activar() {
        update_option( self::VERSION, APG_IMAGE_SITEMAP_VERSION, false );
        self::clave_indexnow(); //Se crea antes de regenerar las reglas, porque una de ellas la contiene
        delete_transient( 'xml_sitemap_image' );
        flush_rewrite_rules();
	}

	//Desactiva el plugin
	public static function desactivar() {
        self::$desactivando = true;

        wp_unschedule_hook( 'enviar_ping' ); //Cancela los avisos pendientes
        delete_option( self::VERSION ); //Al reactivarlo hay que volver a regenerar las reglas
        delete_transient( 'xml_sitemap_image' );
        flush_rewrite_rules(); //Regenera los enlaces permanentes ya sin las reglas del plugin
	}
}
new APG_Image_Sitemap_XML();
