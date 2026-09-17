<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/**
 * Anuncia el sitemap de imágenes dentro del índice que genera WordPress.
 *
 * El índice del núcleo sólo lista lo que devuelven los proveedores registrados, y este
 * devuelve directamente las direcciones del plugin en lugar de dejar que el núcleo las
 * construya, porque el XML lo sirve el propio plugin con el espacio de nombres de Google.
 */
class APG_Image_Sitemap_Proveedor extends WP_Sitemaps_Provider {
    /**
     * Constructor.
     */
    public function __construct() {
        $this->name         = 'apg-image';
        $this->object_type  = 'apg-image';
    }

    /**
     * Direcciones que se añaden al índice.
     *
     * @return array
     */
    public function get_sitemap_entries() {
        $division   = APG_Image_Sitemap_XML::dame_division();
        $fecha      = APG_Image_Sitemap_XML::fecha( $division[ 'lastmod' ] );

        /* Un índice de sitemaps no puede contener otro índice, así que cuando el sitemap
        está dividido se declaran los parciales y no el principal. */
        if ( $division[ 'total' ] > 1 ) {
            $entradas   = [];
            for ( $i = 1; $i <= $division[ 'total' ]; $i++ ) {
                $entradas[] = [
                    'loc'       => home_url( "/sitemap-image-$i.xml" ),
                    'lastmod'   => $fecha,
                ];
            }

            return $entradas;
        }

        return [
            [
                'loc'       => home_url( '/sitemap-image.xml' ),
                'lastmod'   => $fecha,
            ],
        ];
    }

    /**
     * El XML lo sirve el plugin, no el núcleo, así que no hay listado que devolver.
     *
     * @param int    $page_num       Número de página.
     * @param string $object_subtype Subtipo.
     * @return array
     */
    public function get_url_list( $page_num, $object_subtype = '' ) {
        return [];
    }

    /**
     * El XML lo sirve el plugin, no el núcleo, así que no hay páginas que contar.
     *
     * @param string $object_subtype Subtipo.
     * @return int
     */
    public function get_max_num_pages( $object_subtype = '' ) {
        return 0;
    }
}
