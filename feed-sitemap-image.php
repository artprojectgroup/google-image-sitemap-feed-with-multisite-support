<?php
/**
 * XML Sitemap Feed Template for displaying an XML Sitemap feed.
 *
 * @package Google Image Sitemap Feed With Multisite Support plugin for WordPress
 */

status_header('200'); // force header('HTTP/1.1 200 OK') for sites without posts
header('Content-Type: text/xml; charset=' . get_bloginfo('charset'), true);

echo '<?xml version="1.0" encoding="'.get_bloginfo('charset').'"?>
<!-- Created by Art Project Group (http://www.artprojectgroup.es/) -->
<!-- generated-on="'.date('Y-m-d\TH:i:s+00:00').'" -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

// Request
$posts = $wpdb->get_results ("SELECT post_title,post_excerpt,post_parent,guid FROM $wpdb->posts
                                     WHERE post_type = 'attachment'
                                     AND post_mime_type like 'image%'
                                     AND post_parent > 0
                                     ORDER BY post_date desc
");

global $wp_query;
$wp_query->is_404 = false;	// force is_404() condition to false when on site without posts
$wp_query->is_feed = true;	// force is_feed() condition to true so WP Super Cache includes the sitemap in its feeds cache
$domain = $_SERVER['SERVER_NAME'];

if (empty ($posts)) return false;
else
{
	foreach ($posts as $post) 
	{
		$cur_post_id= $post->post_parent;
		if($cur_post_id != $prev_post_id) 
		{
			$post_url = get_permalink($cur_post_id);
			if($first_time == 1) 
			{
				echo "\t".'</url>'."\n";
				$first_time = 0;
			}
			echo "\t".'<url>'."\n";
			echo "\t\t".'<loc>'.htmlspecialchars($post_url).'</loc>'."\n";
			echo "\t\t".'<image:image>'."\n";
			if (stristr($post->guid, $domain) !== false) echo "\t\t\t".'<image:loc>'.$post->guid.'</image:loc>'."\n";
			else echo "\t\t\t".'<image:loc>'.preg_replace('/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}/', $domain, $post->guid, 1).'</image:loc>'."\n";
			if ($post->post_excerpt) echo "\t\t\t".'<image:caption>'.htmlspecialchars($post->post_excerpt).'</image:caption>'."\n";
			if ($post->post_title) echo "\t\t\t".'<image:title>'.htmlspecialchars($post->post_title).'</image:title>'."\n";
			echo "\t\t".'</image:image>'."\n";
			$first_time = 1;
			$prev_post_id = $cur_post_id;
		}
		else 
		{
			echo "\t\t".'<image:image>'."\n";
			echo "\t\t\t".'<image:loc>'.$post->guid.'</image:loc>'."\n";
			if ($post->post_excerpt) echo "\t\t\t".'<image:caption>'.htmlspecialchars($post->post_excerpt).'</image:caption>'."\n";
			if ($post->post_title) echo "\t\t\t".'<image:title>'.htmlspecialchars($post->post_title).'</image:title>'."\n";
			echo "\t\t".'</image:image>'."\n";
		}
	}
	echo "\t".'</url>'."\n";
}

echo "</urlset>";
?>
