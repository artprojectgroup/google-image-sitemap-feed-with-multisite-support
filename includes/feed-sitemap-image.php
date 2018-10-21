<?php
/**
 * XML Sitemap Feed Template for displaying an XML Sitemap feed.
 *
 * @package Google Image Sitemap Feed With Multisite Support plugin for WordPress
 */

//Procesa correctamente las entidades del RSS
$entity_custom_from	= false; 
$entity_custom_to	= false;

function sitemap_image_html_entity( $data ) {
	global $entity_custom_from, $entity_custom_to;
	
	if ( !is_array( $entity_custom_from ) || !is_array( $entity_custom_to ) ) {
		$array_position = 0;
		foreach ( get_html_translation_table( HTML_ENTITIES ) as $key => $value ) {
			switch ( $value ) {
				case '&nbsp;':
					break;
				case '&gt;':
				case '&lt;':
				case '&quot;':
				case '&apos;':
				case '&amp;':
					$entity_custom_from[$array_position] = $key; 
					$entity_custom_to[$array_position] = $value; 
					$array_position++; 
					break; 
				default: 
					$entity_custom_from[$array_position] = $value; 
					$entity_custom_to[$array_position] = $key; 
					$array_position++; 
			} 
		}
	}
	return str_replace( $entity_custom_from, $entity_custom_to, $data ); 
}

status_header( '200' ); // force header( 'HTTP/1.1 200 OK' ) for sites without posts
header( 'Content-Type: text/xml; charset=' . get_bloginfo( 'charset' ), true );

echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<!-- Created by Google Image Sitemap Feed With Multisite Support by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support) -->
<!-- generated-on="' . date( 'Y-m-d\TH:i:s+00:00' ) . '" -->
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9/" xmlns:image="https://www.google.com/schemas/sitemap-image/1.1/">' . PHP_EOL;

$imagenes = get_transient( 'xml_sitemap_image' );
if ( $imagenes === false ) {
     $imagenes = $wpdb->get_results( "SELECT ID, post_title, post_excerpt, post_content, post_parent FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type like 'image%' AND post_parent > 0 ORDER BY post_date desc" ); //Consulta
     set_transient( 'xml_sitemap_image', $imagenes, 30 * DAY_IN_SECONDS );
}

global $wp_query;

$wp_query->is_404	= false;// force is_404(  ) condition to false when on site without posts
$wp_query->is_feed	= true;	// force is_feed(  ) condition to true so WP Super Cache includes the sitemap in its feeds cache
$dominio			= $_SERVER['SERVER_NAME'];

if ( empty( $imagenes ) ) {
	echo "</urlset>";
	
	return false;
} else {
	$entrada_anterior	= false;
	$primera_imagen	= false;
	foreach ( $imagenes as $imagen ) {
		$entrada_actual= $imagen->post_parent;
		$url_de_imagen = wp_get_attachment_url( $imagen->ID );
		if ( $entrada_actual != $entrada_anterior ) {
			$url = get_permalink( $entrada_actual );
			if ( !$url ) {
				$url = "http://" . $_SERVER['SERVER_NAME'] . "/";
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
			
			if ( $imagen->post_content ) {
				echo "\t\t\t" . '<image:caption>' . sitemap_image_html_entity( htmlspecialchars( $imagen->post_content ) ) . '</image:caption>' . PHP_EOL;
			}

			if ( $imagen->post_title ) {
				echo "\t\t\t" . '<image:title>' . sitemap_image_html_entity( htmlspecialchars( $imagen->post_title ) ) . '</image:title>' . PHP_EOL;
			}
			
			echo "\t\t" . '</image:image>' . PHP_EOL;
			
			$primera_imagen = true;
			$entrada_anterior = $entrada_actual;
		} else {
			echo "\t\t" . '<image:image>' . PHP_EOL;
			echo "\t\t\t" . '<image:loc>' . $url_de_imagen . '</image:loc>' . PHP_EOL;
			if ( $imagen->post_content ) {
				echo "\t\t\t" . '<image:caption>' . sitemap_image_html_entity( htmlspecialchars( $imagen->post_content ) ) . '</image:caption>' . PHP_EOL;
			}
			if ( $imagen->post_title ) {
				echo "\t\t\t" . '<image:title>' . sitemap_image_html_entity( htmlspecialchars( $imagen->post_title ) ) . '</image:title>' . PHP_EOL;
			}
			echo "\t\t" . '</image:image>' . PHP_EOL;
		}
	}
	echo "\t" . '</url>' . PHP_EOL;
}

echo "</urlset>";
