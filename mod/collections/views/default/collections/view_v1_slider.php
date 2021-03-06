<?php
$embed = elgg_extract('embed', $vars);
// Get collection
$collection = elgg_extract('entity', $vars);
// Alternate method (more friendly with cmspages)
if (!$collection) {
	$guid = elgg_extract('guid', $vars);
	$collection = get_entity($guid);
}
if (!elgg_instanceof($collection, 'object', 'collection')) { $collection = collections_get_entity_by_name($guid); }
if (!elgg_instanceof($collection, 'object', 'collection')) { return; }

$metadata = elgg_view_menu('entity', array(
	'entity' => $collection,
	'handler' => 'collection',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


$collection_content = '';
$entities = (array) $collection->entities;
$entities_comment = (array) $collection->entities_comment;
foreach($entities as $k => $entity_guid) {
	$publication = get_entity($entity_guid);
	$publication_comment = $entities_comment[$k];
	$collection_content .= '<li>';
	//$collection_content .= $publication->title . '<br /><em>' . $publication_comment . '</em>';
	$collection_content .= '<table><tr><td>';
	//$collection_content .= elgg_view_entity($publication, array('full_view' => false, 'list_type' => 'gallery'));
	$collection_content .= elgg_view_entity($publication, array('full_view' => false, 'list_type' => 'list'));
	$collection_content .= '</td>';
	$collection_content .= '</tr><tr>';
	$collection_content .= '<td style="background:black; color:white;"><p>' . $publication_comment . '</p></td>';
	$collection_content .= '</tr></table>';
	$collection_content .= '</li>';
}


$height = '300px;';
$width = '100%;';
$slider_params = array(
		'slidercontent' => $collection_content,
		'height' => $height,
		'width' => $width,
		//'theme' => 'cs-portfolio',
	);

$slider_embed ='';
$slider_embed .= '<div class="clearfloat"></div>';
$slider_embed .= '<div style="height:' . $height . '; width:' . $width . ';" id="collection-' . $collection->guid . '" class="collection-' . $collection->name . '">
	' . elgg_view('slider/slider', $slider_params) . '
</div>';



// Display embed code if not already embedded
if ($embed) {
	echo $slider_embed;
	return;
} else {
	$body = '';
	$body .= elgg_view_entity_icon($collection, 'large', array('size' => 'large', 'align' => 'left'));
	$body .= elgg_view('output/longtext', array('value' => $collection->description));
	$body .= '<h3>' . elgg_echo('collections:entities:count', array(count($entities))) . '</h3>';
	$body .= '<div class="clearfloat"></div><br />';
	$body .= $slider_embed;
	$body .= '<div class="clearfloat"></div><br />';

	// Permalink
	if (elgg_is_active_plugin('shorturls')) {
		$permalink = '<p>' . elgg_echo('collections:permalink:details') . '</p><textarea readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);">' . elgg_get_site_url() . 's/' . $collection->guid . '</textarea>';
	} else {
		$permalink = '<p>' . elgg_echo('collections:permalink:details') . '</p><textarea readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);">' . $collection->getURL() . '</textarea>';
	}
	$body .= elgg_view_module('info', elgg_echo('transitions:permalink'), $permalink);

	// Embed code
	$embed_code = '<p>' . elgg_echo('collections:embed:details') . '</p><textarea readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);">&lt;iframe src="' . $collection->getURL() . '?embed=full" style="width:320px; height:400px;" /&gt;</textarea>';
	// Embed code
	$body .= elgg_view_module('info', elgg_echo('transitions:embed'), $embed_code);
	
	
	$owner = $collection->getOwnerEntity();
	$owner_icon = elgg_view_entity_icon($owner, 'tiny');
	$owner_link = elgg_view('output/url', array(
		'href' => "transitions/owner/$owner->username",
		'text' => $owner->name,
		'is_trusted' => true,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$date = elgg_view_friendly_time($collection->time_created);
	$subtitle = "$author_text $date";
	
	$params = array(
		'entity' => $collection,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
	));
	
}


