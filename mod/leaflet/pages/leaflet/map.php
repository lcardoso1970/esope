<?php
/**
 * Elgg Leaflet map browser
 * 
 * @package ElggLeaflet
 * @author Florian DANIEL aka Facyla
 * @copyright Florian DANIEL 2014-2015
 * @link http://id.facyla.fr/
 */

/* Notes :
 * Started by a Leaflet map
 * Added controls, markers, basic interaction
 * Added awesome markers
 * 
 */

/* TODO : 
	load markers from external/generated file
	provide map URL (with access code)
*/

$title = elgg_echo('leaflet:map');
$content = '';

// BUILD MAP
$content .= elgg_view('leaflet/basemap', array('map_id' => 'leaflet-main-map'));
$content .= elgg_view('leaflet/locateonmap');
$content .= elgg_view('leaflet/clickonmap');
$content .= elgg_view('leaflet/searchonmap');


// Compose page content
//echo elgg_view('pageshell', array('head' => $head, 'body' => $body, 'title' => $title));
$body = elgg_view_layout('one_column', array('title' => $title, 'content' => $content));
echo elgg_view_page($title, $body);


