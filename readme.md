# APG Google Image Sitemap Feed

Contributors: artprojectgroup

Donate link: https://artprojectgroup.es/tienda/donacion

Tags: Google Image Sitemap, sitemap, sitemap-image.xml, images, IndexNow

Requires at least: 5.0

Tested up to: 7.2

Requires PHP: 7.4

Stable tag: 3.0.0

License: GPLv3

License URI: https://www.gnu.org/licenses/gpl-3.0.html

Genera al vuelo el archivo sitemap-image.xml, un mapa de sitio de imágenes para Google. No hay nada que configurar.

## Descripción

**APG Google Image Sitemap Feed** sirve un `sitemap-image.xml` virtual con todas las imágenes que muestra tu contenido publicado. No tiene ajustes: lo instalas, lo activas y ya está.

### Características

- No hay nada que configurar, funciona solo desde que lo activas.
- Es compatible con instalaciones de WordPress multisitio.
- Recoge la imagen destacada, la galería de producto de WooCommerce, las imágenes adjuntas y las incrustadas en el contenido.
- Solo publica contenido de tipos públicos, y cada entrada lleva su `lastmod`.
- Avisa a los buscadores de cada cambio mediante [IndexNow](https://www.indexnow.org/), que reparte el aviso entre Bing, Yandex, Naver y Seznam.
- Genera y sirve su propia clave de IndexNow, así que no hay ningún archivo que crear ni subir.
- Se anuncia en `robots.txt` y en el índice de sitemaps de WordPress, que es como Google encuentra un sitemap ahora que retiró el ping.
- Te avisa en el escritorio cuando un plugin SEO ya publica un sitemap de imágenes y este sobraría.
- Se divide en varios sitemaps cada 50.000 entradas, con un índice en `sitemap-image.xml`.
- Funciona con [qTranslate](https://wordpress.org/plugins/qtranslate/) y [Media File Renamer](https://wordpress.org/plugins/media-file-renamer/).

### Traducciones

- Español ([**Art Project Group**](https://artprojectgroup.es/)).
- English ([**Art Project Group**](https://artprojectgroup.es/)).

### Soporte técnico

**Art Project Group** te ofrece [**soporte técnico**](https://artprojectgroup.es/tienda/ticket-de-soporte) de pago para configurar o instalar **APG Google Image Sitemap Feed**.

### Origen

**APG Google Image Sitemap Feed** se programó a partir de [*Google News Sitemap Feed With Multisite Support*](https://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/), de [Tim Brandon](https://profiles.wordpress.org/timbrd/), y de [*Google XML Sitemap for Images*](https://wordpress.org/plugins/google-image-sitemap/), de [Amit Agarwal](https://profiles.wordpress.org/labnol/). Son dos plugins magníficos que no cubrían todo lo que necesitábamos, y sin su trabajo este no existiría.

### Complementos

Combínalo con [**APG Google Video Sitemap Feed**](https://wordpress.org/plugins/google-video-sitemap-feed-with-multisite-support/), que genera `sitemap-video.xml` de la misma forma.

### Muy importante

Se han descrito errores al usarlo junto a la última versión de **Google XML Sitemaps** con soporte multisitio. En [¿Cómo arreglar la incompatibilidad de Google XML Sitemaps con nuestros plugins?](https://artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) tienes qué pasa y cómo resolverlo.

### Más información

En nuestra web encontrarás más sobre [**APG Google Image Sitemap Feed**](https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed).

### Comentarios

Cuéntanos qué te parece en:

- [APG Google Image Sitemap Feed](https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) en Art Project Group.
- [Art Project Group](https://www.facebook.com/artprojectgroup) en Facebook.
- [@artprojectgroup](https://twitter.com/artprojectgroup) en Twitter.

### Más plugins

Tienes más [plugins para WordPress](https://artprojectgroup.es/plugins-para-wordpress) en [Art Project Group](https://artprojectgroup.es) y en nuestro perfil de [WordPress](https://profiles.wordpress.org/artprojectgroup/).

### GitHub

El desarrollo está en [GitHub](https://github.com/artprojectgroup/google-image-sitemap-feed-with-multisite-support).

## Instalación

1. Puedes:
 - Subir la carpeta `google-image-sitemap-feed-with-multisite-support` al directorio `/wp-content/plugins/` por FTP.
 - Subir el archivo ZIP desde *Plugins -> Añadir nuevo -> Subir* en tu escritorio de WordPress.
 - Buscar **APG Google Image Sitemap Feed** en *Plugins -> Añadir nuevo* y pulsar *Instalar ahora*.
2. Activarlo desde el menú *Plugins*.
3. Ya está. Si te resulta útil, plantéate una [*donación*](https://artprojectgroup.es/tienda/donacion).

## Preguntas frecuentes

### ¿Necesita configuración?

No, el plugin funciona solo.

### ¿Es compatible con instalaciones de WordPress multisitio?

Sí, en todos los sitios de la red.

### ¿Qué imágenes acaban en el sitemap?

Las que muestra cada entrada publicada: su imagen destacada, su galería de producto de WooCommerce, las que tiene adjuntas y las incrustadas en su contenido. Quedan fuera las entradas en la papelera, las protegidas por contraseña y los tipos de contenido no públicos.

### ¿Cómo avisa a los buscadores?

Mediante [IndexNow](https://www.indexnow.org/), que pasa el aviso a Bing, Yandex, Naver y Seznam. Google cerró en 2023 la dirección que recibía estos avisos y ahora descubre los cambios por su cuenta, así que conviene declarar `sitemap-image.xml` una vez en *Google Search Console*.

### ¿Hay que configurar la clave de IndexNow?

No. El plugin la crea la primera vez que lo activas y la sirve desde la raíz del sitio, que es donde la buscan los buscadores.

### ¿Y si ya uso Yoast, Rank Math, All in One SEO o SEOPress?

Esos plugins ya publican un sitemap que incluye tus imágenes, así que este te mostrará un aviso en el escritorio diciéndote que probablemente sobra. Tener los dos no hace daño, porque Google descarta las direcciones repetidas, pero solo necesitas uno.

### ¿Existen incompatibilidades?

Sí, con **Google XML Sitemaps**. Ese plugin reclama todos los tipos de sitemap posibles, lo que deja las reglas de reescritura de WordPress en un orden erróneo. En [¿Cómo arreglar la incompatibilidad de Google XML Sitemaps con nuestros plugins?](https://artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) está la solución.

### ¿Dónde consigo soporte?

**Art Project Group** ofrece un servicio de [**soporte técnico**](https://artprojectgroup.es/tienda/ticket-de-soporte) de pago para configurar o instalar **APG Google Image Sitemap Feed**.

*Art Project Group no presta ningún tipo de soporte técnico gratuito.*

## Capturas de pantalla

1. Aspecto de sitemap-image.xml.

## Changelog

### 3.0.0

- Sustituido el aviso a Google y a Bing, cuyas direcciones ya no existen, por IndexNow.
- El sitemap se anuncia ahora en `robots.txt` y en el índice de sitemaps de WordPress, que es como Google lo descubre desde que retiró el ping.
- Ahora entran en el sitemap la imagen destacada, las de la galería de producto y las incrustadas en el contenido, y no sólo las adjuntas a la entrada.
- Sólo se publican las imágenes de tipos de contenido públicos, con su fecha de modificación en `lastmod`.
- Corregidos el espacio de nombres y la fecha del XML, que impedían que Google lo interpretase.
- Las direcciones del sitemap ya no pasan por una redirección, y las paginadas que no existen devuelven un error 404.
- Cada entrada aparece una sola vez, con todas sus imágenes juntas, y el sitemap se divide cada 50.000 entradas en lugar de cada 1.000 imágenes.
- Las imágenes de la papelera y las de las entradas protegidas por contraseña ya no aparecen en el sitemap.
- Aviso en el escritorio cuando un plugin SEO ya publica un sitemap de imágenes y este resulta redundante.
- El sitemap se actualiza al subir, editar o borrar una imagen, y su caché ya no se vacía en cada autoguardado del editor.
- La dirección del sitio ya no se toma de la cabecera del visitante, que se puede manipular.
- Escapadas todas las salidas del plugin y corregidos varios avisos de PHP.
- Adecuación del código a los requisitos actuales de revisión de wordpress.org: licencia en la cabecera, consultas mediante la API de WordPress y prefijos en clases y constantes.
- Actualizada la compatibilidad hasta WordPress 7.2.

### 2.0.2.2

- Pequeños arreglos.

### 2.0.2.1

- Pequeños arreglos.

### 2.0.2

- Pequeños arreglos.

### 2.0.1

- Pequeños arreglos.

### 2.0

- Adecuación de la nueva estructura de datos.
- Generación de múltiples sitemaps por cada 1.000 imágenes.

### 1.2.0.4

- Actualización de la consulta SQL.

### 1.2.0.3

- Actualización de enlaces y pequeñas actualizaciones.

### 1.2.0.2

- Actualización de enlaces de soporte y pequeñas actualizaciones.

### 1.2.0.1

- Actualización del paquete de fuentes. Nuevo icono de Google+.
- Actualización de las traducciones.

### 1.2

- Modificación de la estructura interna del plugin para ajustarse a los estándares de WordPress.

### 1.1.1

- Añadido borrado de caché al publicar nuevo contenido.

### 1.1

- Arreglo de error que provocaba un mensaje de error en versiones superiores a la 5.2 de PHP.

### 1.0.1

- Arreglo de error que provocaba que no se mostraran las URLs correctas en las entradas con múltiples imágenes.

### 1.0

- Soporte del plugin [Media File Renamer](https://wordpress.org/plugins/media-file-renamer/).

### 0.9

- Arreglo de error que borraba toda la configuración al desactivar el plugin.
- Corrección menor que evita la aparición de un código de error al recopilar información sobre el plugin.
- Uso de la API Transients de WordPress para mejorar las consultas.

### 0.8.1

- Cambio del enlace de donación.

### 0.8

- Añadida nueva función que limpia la base de datos al desinstalar el plugin.

### 0.7

- Arreglo en la codificación de las entidades RSS.

### 0.6

- Arreglos menores en el código.

### 0.5

- Actualización de las hojas de estilo acorde al nuevo WordPress 8.
- Arreglo de pequeños errores en el código.

### 0.4

- Inclusión de nuevos botones y enlaces.

### 0.3

- Pequeños arreglos de código.
- Pequeño arreglo de la traducción.

### 0.2

- Pequeñas modificaciones y arreglos de código.
- Inclusión de enlaces.
- Actualización de los textos de información.

### 0.1

- Versión inicial.

## Aviso de actualización

### 3.0.0

- Cobertura real de las imágenes de cada entrada, descubrimiento por `robots.txt` e IndexNow, y corrección de varios errores del XML.

## Soporte técnico

**APG Google Image Sitemap Feed** es gratuito, y **Art Project Group** solo presta [**soporte técnico**](https://artprojectgroup.es/tienda/ticket-de-soporte) de pago. Art Project Group no presta ningún tipo de soporte técnico gratuito.

## ¿Por qué hay dos readmes?

El `readme.txt` que lee wordpress.org está en inglés, porque el directorio de plugins exige que su ficha esté en ese idioma. Este `readme.md` es la versión en español, y es la que verás aquí en GitHub.

Seguimos pensando que la comunidad hispana de WordPress es lo bastante amplia como para no abocarla al inglés, así que mantenemos en español la documentación, los tutoriales y el soporte de nuestros plugins.

## Donación

¿Te ha resultado útil **APG Google Image Sitemap Feed**? Una [pequeña donación](https://artprojectgroup.es/tienda/donacion) nos ayuda a seguir mejorándolo y a crear más plugins gratuitos para la comunidad de WordPress.

## Gracias

- A [Tim Brandon](https://profiles.wordpress.org/timbrd/) y a [Amit Agarwal](https://profiles.wordpress.org/labnol/), cuyos plugins inspiraron **APG Google Image Sitemap Feed**.
- A todos los que lo usáis.
- A todos los que ayudáis a mejorarlo.
- A todos los que realizáis donaciones.
- A todos los que nos animáis con vuestros comentarios.

¡Muchas gracias a todos!
