=== Google Image Sitemap Feed With Multisite Support ===
Contributors: artprojectgroup 
Donate link: http://www.artprojectgroup.es/donacion
Tags: Google, Google Image, Google Image Sitemap, sitemap, sitemap-image.xml, qTranslate, Media File Renamer
Requires at least: 2.6
Tested up to: 3.9
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Genera dinámicamente el archivo sitemap-image.xml, un mapa de sitio de imágenes para Google. No requiere ningún tipo de configuración.

== Description ==
[*Español*](http://wordpress.org/plugins/google-image-sitemap-feed-with-multisite-support/) - [*English*](http://goo.gl/u9zMFp) - [*Italiano*](http://goo.gl/ZcndP4) - [*Français*](http://goo.gl/yyWCYT) - [*Deutsch*](http://goo.gl/1mJWrO) 

**Google Image Sitemap Feed With Multisite Support** genera dinámicamente un mapa de sitio de imágenes para Google creando un archivo `sitemap-image.xml` virtual. 

= Características =
* No requiere ningún tipo de configuración, por lo que funciona de forma totalmente autónoma y automática.
* Es totalmente compatible con instalaciones de WordPress multisitio.
* Totalmente compatible con los plugins [qTranslate](http://wordpress.org/plugins/qtranslate/) y [Media File Renamer](http://wordpress.org/plugins/media-file-renamer/).
* Informa automáticamente a Google y a Bing cada vez que publiquemos una nueva entrada o página.

= Origen =
**Google Image Sitemap Feed With Multisite Support** ha sido programado a partir de los plugins [*Google News Sitemap Feed With Multisite Support*](http://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/) de [Tim Brandon](http://profiles.wordpress.org/users/timbrd/) y [*Google XML Sitemap for Images*](http://wordpress.org/plugins/google-image-sitemap/) de [Amit Agarwal](http://profiles.wordpress.org/labnol/), que aún siendo magníficos plugins no ofrecían todas las características que necesitábamos. Aún así su trabajo ha sido completamente imprescindible para la realización de este plugin.

= Complementos =
Se recomienda el uso de **Google Image Sitemap Feed With Multisite Support** junto a [**Google Video Sitemap Feed With Multisite Support**](http://wordpress.org/plugins/google-video-sitemap-feed-with-multisite-support/) que genera el archivo `sitemap-video.xml`, y [**Google Mobile Sitemap Feed With Multisite Support**](http://wordpress.org/plugins/google-mobile-sitemap-feed-with-multisite-support/) que genera el archivo `sitemap-mobile.xml`.

= Muy importante =
Se han descrito errores al utilizarlo conjuntamente con la última versión de **Google XML Sitemaps** con soporte para WordPress multisitio. Los errores están descritos en [¿Cómo arreglar la incompatibilidad de Google XML Sitemaps con nuestros plugins?](http://www.artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) donde encontrarás toda la información necesaria para solucionar la incompatibilidad detectada.

= Más información =
En nuestro sitio web oficial puede obtener más información sobre [**Google Image Sitemap Feed With Multisite Support**](http://www.artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support). 

= Comentarios =
No olvides dejarnos tu comentario en:

* [Google Image Sitemap Feed With Multisite Support](http://www.artprojectgroup.es/plugins-para-wordpress/google-image-sitemap-feed-with-multisite-support) en Art Project Group.
* [Art Project Group](https://www.facebook.com/artprojectgroup) en Facebook.
* [@artprojectgroup](https://twitter.com/artprojectgroup) en Twitter.
* [+ArtProjectGroupES](https://plus.google.com/+ArtProjectGroupES/) en Google+.

= Más plugins =
Recuerda que puedes encontrar más [plugins para WordPress](http://www.artprojectgroup.es/plugins-para-wordpress) en [Art Project Group](http://www.artprojectgroup.es) y en nuestro perfil en [WordPress](http://profiles.wordpress.org/artprojectgroup/).

== Installation ==
1. Puedes:
 * Subir la carpeta `google-image-sitemap-feed-with-multisite-support` al directorio `/wp-content/plugins/` vía FTP. 
 * Subir el archivo ZIP completo vía *Plugins -> Añadir nuevo -> Subir* en el Panel de Administración de tu instalación de WordPress.
 * Buscar **Google Image Sitemap Feed With Multisite Support** en el buscador disponible en *Plugins -> Añadir nuevo* y pulsar el botón *Instalar ahora*.
2. Activar el plugin a través del menú *Plugins* en el Panel de Administración de WordPress.
3. Listo, ahora ya puedes disfrutar de él, y si te gusta y te resulta útil, hacer una [*donación*](http://www.artprojectgroup.es/donacion).

== Frequently Asked Questions ==
= ¿Necesita configuración? =
No, el plugin es totalmente autónomo.

= ¿Es compatible con instalaciones de WordPress multisitio? =
Si, es completamente compatible.

= ¿Existen incompatibilidades? =
Si, se han descrito errores al utilizarlo conjuntamente con el plugin **Google XML Sitemaps**. Los errores están provocados por un orden erróneo de las reglas de redirección de WordPress, ya que **Google XML Sitemaps** interpreta todos los tipos de mapas de sitios posibles. En [¿Cómo arreglar la incompatibilidad de Google XML Sitemaps con nuestros plugins?](http://www.artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) encontrarás toda la información sobre esta incompatibilidad y la solución a la misma.

== Screenshots ==
1. Captura de pantalla de sitemap-image.xml.

== Changelog ==
= 1.0.1 =
* Arreglo de error que provocaba que no se mostraran las URLs correctas en las entradas con múltiples imágenes.
= 1.0 =
* Soporte del plugin [Media File Renamer](http://wordpress.org/plugins/media-file-renamer/).
= 0.9 =
* Arreglo de error que borraba toda la configuración al desactivar el plugin.
* Corrección menor que evita la aparición de un código de error al recopilar información sobre el plugin.
* Uso de la API Transients de WordPress para mejorar las consultas.
= 0.8.1 =
* Cambio del enlace de donación.
= 0.8 =
* Añadida nueva función que limpia la base de datos al desinstalar el plugin.
= 0.7 =
* Arreglo en la codificación de las entidades RSS.
= 0.6 =
* Arreglos menores en el código.
= 0.5 =
* Actualización de las hojas de estilo acorde al nuevo WordPress 8.
* Arreglo de pequeños errores en el código.
= 0.4 =
* Inclusión de nuevos botones y enlaces.
= 0.3 =
* Pequeños arreglos de código.
* Pequeño arreglo de la traducción.
= 0.2 =
* Pequeñas modificaciones y arreglos de código.
* Inclusión de enlaces.
* Actualización de los textos de información.
= 0.1 =
* Versión inicial.

== Upgrade Notice ==
= 1.0.1 =
* Arreglo de error que provocaba que no se mostraran las URLs correctas en las entradas con múltiples imágenes.
= 1.0 =
* Soporte del plugin [Media File Renamer](http://wordpress.org/plugins/media-file-renamer/).
= 0.9 =
* Arreglo de error que borraba toda la configuración al desactivar el plugin.
* Corrección menor que evita la aparición de un código de error al recopilar información sobre el plugin.
* Uso de la API Transients de WordPress para mejorar las consultas.
= 0.8.1 =
* Cambio del enlace de donación.
= 0.8 =
* Añadida nueva función que limpia la base de datos al desinstalar el plugin.
= 0.7 =
* Arreglo en la codificación de las entidades RSS.
= 0.6 =
* Arreglos menores en el código.
= 0.5 =
* Actualización de las hojas de estilo acorde al nuevo WordPress 8.
* Arreglo de pequeños errores en el código.
= 0.4 =
* Inclusión de nuevos botones y enlaces.
= 0.3 =
* Pequeños arreglos de código.
* Pequeño arreglo de la traducción.
= 0.2 =
* Pequeñas modificaciones y arreglos de código.
* Inclusión de enlaces.
* Actualización de los textos de información.

==Traducciones ==
* *English*: by **Art Project Group** (default language).
* *Español*: por **Art Project Group**.

== ¿Por qué está esta página en español? ==
Mientras WordPress no nos permita a los desarrolladores realizar esta página en más de un idioma, elegiremos siempre el español.

A pesar de que es una apuesta muy arriesgada y de que reduce mucho las posibilidades de propagación de nuestros plugins, creemos que la comunidad hispana de WordPress es lo suficientemente amplia como para abocarla al idioma inglés hasta el final de los tiempos.

Por ello regalamos a esa gran comunidad hispana nuestros plugins con interfaces, instrucciones, tutoriales, soporte y páginas web en *WordPress.org* en español.

Esperamos que os guste nuestra iniciativa.

== Donación ==
¿Te ha gustado y te ha resultado útil **Google Image Sitemap Feed With Multisite Support** en tu sitio web? Te agradeceríamos una [pequeña donación](http://www.artprojectgroup.es/donacion) que nos ayudará a seguir mejorando este plugin y a crear más plugins totalmente gratuitos para toda la comunidad WordPress.

== Gracias ==
* A [Tim Brandon](http://profiles.wordpress.org/users/timbrd/) y [Amit Agarwal](http://profiles.wordpress.org/labnol/) por sus grandes plugins que han inspirado **Google Image Sitemap Feed With Multisite Support**.
* A todos los que lo usáis.
* A todos los que ayudáis a mejorarlo.
* A todos los que realizáis donaciones.
* A todos los que nos animáis con vuestros comentarios.

¡Muchas gracias a todos!