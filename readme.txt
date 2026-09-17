=== APG Google Image Sitemap Feed ===
Contributors: artprojectgroup 
Donate link: https://artprojectgroup.es/tienda/donacion
Tags: Google Image Sitemap, sitemap, sitemap-image.xml, images, IndexNow
Requires at least: 5.0
Tested up to: 7.2
Requires PHP: 7.4
Stable tag: 3.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Builds sitemap-image.xml on the fly, an image sitemap for Google. Nothing to configure.

== Description ==
**APG Google Image Sitemap Feed** serves a virtual `sitemap-image.xml` with every image your published content shows. There are no settings: install it, activate it, done.

= Features =
* Nothing to configure. It runs on its own from the moment you activate it.
* Works on WordPress Multisite installations.
* Collects the featured image, the WooCommerce product gallery, attached images and the ones embedded in the post content.
* Only publishes content of public post types, each entry with its own `lastmod`.
* Tells search engines about every change through [IndexNow](https://www.indexnow.org/), which shares the notification with Bing, Yandex, Naver and Seznam.
* Generates and serves its own IndexNow key, so there is no key file to create or upload.
* Announces itself in `robots.txt` and in the WordPress sitemap index, which is how Google finds a sitemap now that the ping endpoint is gone.
* Warns you in the dashboard when an SEO plugin is already publishing an image sitemap and this one would be redundant.
* Splits into several sitemaps every 50,000 entries, with an index at `sitemap-image.xml`.
* Works with [qTranslate](https://wordpress.org/plugins/qtranslate/) and [Media File Renamer](https://wordpress.org/plugins/media-file-renamer/).

= Translations =
* English ([**Art Project Group**](https://artprojectgroup.es/)).
* Spanish ([**Art Project Group**](https://artprojectgroup.es/)).

= Support =
**Art Project Group** offers paid [**technical support**](https://artprojectgroup.es/tienda/ticket-de-soporte) to set up or install **APG Google Image Sitemap Feed**.

= Origin =
**APG Google Image Sitemap Feed** was built on top of [*Google News Sitemap Feed With Multisite Support*](https://wordpress.org/plugins/google-news-sitemap-feed-with-multisite-support/) by [Tim Brandon](https://profiles.wordpress.org/users/timbrd/) and [*Google XML Sitemap for Images*](https://wordpress.org/plugins/google-image-sitemap/) by [Amit Agarwal](https://profiles.wordpress.org/labnol/). Both are excellent plugins that did not cover everything we needed, and neither of them would this one exist without.

= Companion plugin =
Pair it with [**APG Google Video Sitemap Feed**](https://wordpress.org/plugins/google-video-sitemap-feed-with-multisite-support/), which builds `sitemap-video.xml` the same way.

= Worth knowing =
Users have reported problems when running it alongside the latest multisite-capable release of **Google XML Sitemaps**. [How to fix the Google XML Sitemaps incompatibility with our plugins](https://artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) explains what happens and how to solve it.

= More information =
Our website has more about [**APG Google Image Sitemap Feed**](https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed).

= Feedback =
Tell us what you think at:

* [APG Google Image Sitemap Feed](https://artprojectgroup.es/plugins-para-wordpress/apg-google-image-sitemap-feed) on Art Project Group.
* [Art Project Group](https://www.facebook.com/artprojectgroup) on Facebook.
* [@artprojectgroup](https://twitter.com/artprojectgroup) on Twitter.

= More plugins =
You will find more [WordPress plugins](https://artprojectgroup.es/plugins-para-wordpress) at [Art Project Group](https://artprojectgroup.es) and on our [WordPress profile](https://profiles.wordpress.org/artprojectgroup/).

= GitHub =
Development happens at [GitHub](https://github.com/artprojectgroup/google-image-sitemap-feed-with-multisite-support), where the readme is also available in Spanish.

== Installation ==
1. Pick one:
 * Upload the `google-image-sitemap-feed-with-multisite-support` folder to `/wp-content/plugins/` over FTP.
 * Upload the ZIP file from *Plugins -> Add New -> Upload* in your WordPress admin.
 * Search for **APG Google Image Sitemap Feed** under *Plugins -> Add New* and click *Install Now*.
2. Activate it from the *Plugins* menu.
3. That is all. If it turns out useful, consider a [*donation*](https://artprojectgroup.es/tienda/donacion).

== Frequently Asked Questions ==
= Does it need any configuration? =
No. The plugin runs on its own.

= Does it work on WordPress Multisite? =
Yes, on every site of the network.

= Which images end up in the sitemap? =
The ones each published entry actually shows: its featured image, its WooCommerce product gallery, the images attached to it and the ones embedded in its content. Entries in the trash, password-protected ones and non-public post types stay out.

= How does it notify search engines? =
Through [IndexNow](https://www.indexnow.org/), which passes the notification to Bing, Yandex, Naver and Seznam. Google shut down its sitemap ping endpoint in 2023 and now discovers changes on its own, so it is worth submitting `sitemap-image.xml` in *Google Search Console* once.

= Do I have to set up the IndexNow key? =
No. The plugin creates it the first time you activate the plugin and serves it from the root of your site, which is where search engines look for it.

= What if I already use Yoast, Rank Math, All in One SEO or SEOPress? =
Those plugins already publish a sitemap that includes your images, so this one will show a dashboard notice telling you it is probably redundant. Running both does no harm, because Google discards duplicate URLs, but you only need one.

= Are there known incompatibilities? =
Yes, with **Google XML Sitemaps**. That plugin claims every possible sitemap type, which leaves the WordPress rewrite rules in the wrong order. [How to fix the Google XML Sitemaps incompatibility with our plugins](https://artprojectgroup.es/como-arreglar-la-incompatibilidad-de-google-xml-sitemaps-con-nuestros-plugins) covers the fix.

= Where do I get support? =
**Art Project Group** offers a paid [**technical support**](https://artprojectgroup.es/tienda/ticket-de-soporte) service to set up or install **APG Google Image Sitemap Feed**.

*Art Project Group does not provide free technical support of any kind.*

== Screenshots ==
1. What sitemap-image.xml looks like.

== Changelog ==
= 3.0.0 =
* Replaced the notifications to Google and Bing, whose endpoints no longer exist, with IndexNow.
* The sitemap now announces itself in `robots.txt` and in the WordPress sitemap index, which is how Google discovers it since the ping endpoint was retired.
* The sitemap now includes the featured image, the product gallery images and the ones embedded in the content, not only the images attached to the entry.
* Only images belonging to public post types are published, each entry with its `lastmod`.
* Fixed the XML namespace and the generation date, which stopped Google from reading the sitemap.
* Sitemap URLs no longer go through a redirect, and paginated ones that do not exist return a 404.
* Each entry appears once with all of its images together, and the sitemap splits every 50,000 entries instead of every 1,000 images.
* Images in the trash and images of password-protected entries no longer show up in the sitemap.
* Dashboard notice when an SEO plugin already publishes an image sitemap and this one becomes redundant.
* The sitemap refreshes when an image is uploaded, edited or deleted, and its cache is no longer cleared on every editor autosave.
* The site address no longer comes from the visitor's request header, which can be spoofed.
* Every output is escaped and several PHP notices are gone.
* Brought the code in line with the current wordpress.org review requirements: license header, WordPress API queries instead of raw SQL, and prefixed classes and constants.
* Compatibility updated up to WordPress 7.2.
= 2.0.2.2 =
* Minor fixes.
= 2.0.2.1 =
* Minor fixes.
= 2.0.2 =
* Minor fixes.
= 2.0.1 =
* Minor fixes.
= 2.0 =
* Adapted to the new data structure.
* Multiple sitemaps generated every 1,000 images.
= 1.2.0.4 =
* Updated the SQL query.
= 1.2.0.3 =
* Updated links and minor changes.
= 1.2.0.2 =
* Updated support links and minor changes.
= 1.2.0.1 =
* Updated the font package. New Google+ icon.
* Updated translations.
= 1.2 =
* Reorganised the plugin internals to follow the WordPress standards.
= 1.1.1 =
* Cache is now cleared when new content is published.
= 1.1 =
* Fixed an error that raised a warning on PHP above 5.2.
= 1.0.1 =
* Fixed an error that printed the wrong URLs on entries with several images.
= 1.0 =
* Support for the [Media File Renamer](https://wordpress.org/plugins/media-file-renamer/) plugin.
= 0.9 =
* Fixed an error that wiped the whole configuration when deactivating the plugin.
* Minor fix that avoided an error code while collecting information about the plugin.
* Queries improved with the WordPress Transients API.
= 0.8.1 =
* Changed the donation link.
= 0.8 =
* New function that cleans the database when uninstalling the plugin.
= 0.7 =
* Fixed the encoding of the RSS entities.
= 0.6 =
* Minor code fixes.
= 0.5 =
* Stylesheets updated for the new WordPress 8.
* Minor code fixes.
= 0.4 =
* New buttons and links.
= 0.3 =
* Minor code fixes.
* Minor translation fix.
= 0.2 =
* Minor changes and code fixes.
* New links.
* Updated the information texts.
= 0.1 =
* First release.

== Upgrade Notice ==
= 3.0.0 =
* Real coverage of the images on each entry, discovery through `robots.txt` and IndexNow, and several XML fixes.

== Translations ==
* *English*: by [**Art Project Group**](https://artprojectgroup.es/) (default language).
* *Spanish*: by [**Art Project Group**](https://artprojectgroup.es/).

== Support ==
**APG Google Image Sitemap Feed** is free, and **Art Project Group** only provides [**technical support**](https://artprojectgroup.es/tienda/ticket-de-soporte) as a paid service. Art Project Group does not provide free technical support of any kind.

== Donation ==
Has **APG Google Image Sitemap Feed** been useful on your site? A [small donation](https://artprojectgroup.es/tienda/donacion) helps us keep improving it and building more free plugins for the WordPress community.

== Thanks ==
* To [Tim Brandon](https://profiles.wordpress.org/users/timbrd/) and [Amit Agarwal](https://profiles.wordpress.org/labnol/), whose plugins inspired **APG Google Image Sitemap Feed**.
* To everyone using it.
* To everyone helping to improve it.
* To everyone who donates.
* To everyone who sends us a kind word.

Thank you all!
