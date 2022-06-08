<?php
/*
Genera la plantilla XML
*/
global $maximo_imagenes;

//Obtiene el listado de todas las imágenes
$imagenes   = get_transient( 'xml_sitemap_image' );
if ( $imagenes === false ) {
    $imagenes = $wpdb->get_results( "SELECT P1.ID, P1.post_parent FROM $wpdb->posts P1 LEFT JOIN $wpdb->posts P2 ON P1.post_parent = P2.ID WHERE P1.post_type = 'attachment' AND P1.post_mime_type like 'image%' AND P1.post_parent > 0 and P2.post_status = 'publish' ORDER BY P1.post_date desc" ); //Consulta
    set_transient( 'xml_sitemap_image', $imagenes, 30 * DAY_IN_SECONDS );
    APGSitemapImage::desactivar();
}

//Añade la cabecera
status_header( '200' ); // force header( 'HTTP/1.1 200 OK' ) for sites without posts
header( 'Content-Type: text/xml; charset=' . get_bloginfo( 'charset' ), true );

//Hay que dividir el sitemap en varios
$numero_feed        = preg_replace( '/[^0-9]/', '', $wp->request );
if ( count( $imagenes ) > $maximo_imagenes && ! $numero_feed ) {
    echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<!-- Created by APG Google Image Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) -->
<!-- generated-on="' . date( 'Y-m-d\TH:i:s+00:00' ) . '" -->
<sitemapindex xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
    for ( $i = 1; $i <= ceil( count( $imagenes ) / $maximo_imagenes ); $i++ ) {
        echo '<sitemap>
    <loc>' . home_url( '/' ) . "sitemap-image-$i.xml" . '</loc>
  </sitemap>';
    }
    echo '</sitemapindex>';

    exit();
}

//Inicia la plantilla
echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<!-- Created by APG Google Image Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) -->
<!-- generated-on="' . date( 'Y-m-d\TH:i:s+00:00' ) . '" -->
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

global $wp_query;

$wp_query->is_404	= false;// force is_404(  ) condition to false when on site without posts
$wp_query->is_feed	= true;	// force is_feed(  ) condition to true so WP Super Cache includes the sitemap in its feeds cache
$dominio			= $_SERVER['SERVER_NAME'];

if ( empty( $imagenes ) ) {
	echo "</urlset>";
	
	return false;
} else {
	$entrada_anterior  = false;
	$primera_imagen    = false;
    if ( $numero_feed ) {
        $offset     = ( $numero_feed - 1 ) * $maximo_imagenes;
        $imagenes   = array_slice( $imagenes, $offset, $maximo_imagenes );
    }

    foreach ( $imagenes as $imagen ) {

		$entrada_actual= $imagen->post_parent;
		$url_de_imagen = wp_get_attachment_url( $imagen->ID );
		if ( $entrada_actual != $entrada_anterior ) {
			$url = get_permalink( $entrada_actual );
			if ( ! $url ) {
				$url = "https://" . $_SERVER['SERVER_NAME'] . "/";
			}
			
			if ( $primera_imagen == true ) {
				echo "\t" . '</url>' . PHP_EOL;
				$primera_imagen = false;
			}
			
			echo "\t" . '<url>' . PHP_EOL;
			echo "\t\t" . '<loc>' . htmlspecialchars( $url ) . '</loc>' . PHP_EOL;
			echo "\t\t" . '<image:image>' . PHP_EOL;
			
			if ( stristr( $url_de_imagen, $dominio ) !== false ) {
				echo "\t\t\t" . '<image:loc>' . $url_de_imagen . '</image:loc>' . PHP_EOL;
			} else {
				echo "\t\t\t" . '<image:loc>' . preg_replace( '/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}/', $dominio, $url_de_imagen, 1 ) . '</image:loc>' . PHP_EOL;
			}
						
			echo "\t\t" . '</image:image>' . PHP_EOL;
			
			$primera_imagen      = true;
			$entrada_anterior    = $entrada_actual;
		} else {
			echo "\t\t" . '<image:image>' . PHP_EOL;
			echo "\t\t\t" . '<image:loc>' . $url_de_imagen . '</image:loc>' . PHP_EOL;
			echo "\t\t" . '</image:image>' . PHP_EOL;
		}
	}
	echo "\t" . '</url>' . PHP_EOL;
}

echo "</urlset>";
