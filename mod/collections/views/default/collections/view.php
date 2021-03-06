<?php
$embed = elgg_extract('embed', $vars);
// Determines if collection elements should be displayed full_view or not
$full_content = elgg_extract('full_content', $vars, true);
//if ($full_content !== false) { $full_content = true; }

// Get collection
$collection = elgg_extract('entity', $vars);
// Alternate method (more friendly with cmspages)
if (!$collection) {
	$guid = elgg_extract('guid', $vars);
	$collection = get_entity($guid);
}
if (!elgg_instanceof($collection, 'object', 'collection')) { $collection = collections_get_entity_by_name($guid); }
if (!elgg_instanceof($collection, 'object', 'collection')) { return; }

elgg_load_js('elgg.collections.collections');
elgg_load_js('lightbox');
elgg_load_css('lightbox');

$metadata = '';
if (!elgg_in_context('widgets')) {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $collection,
		'handler' => 'collection',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}


// Collection elements
//$collection_content = '<ul class="collections-listing">';
$collection_content = '<div class="collections-listing">';
$entities = (array) $collection->entities;
$entities_comment = (array) $collection->entities_comment;
elgg_push_context('widgets');
foreach($entities as $k => $entity_guid) {
	$publication = get_entity($entity_guid);
	if (elgg_instanceof($publication, 'object')) {
		$publication_comment = $entities_comment[$k];
		$collection_content .= '<hr class="hidden" />';
		//$collection_content .= '<li>';
		if ($full_content) {
			$collection_content .= '<div class="collections-item-entity full">';
		} else {
			$collection_content .= '<div class="collections-item-entity">';
		}
		
		//$collection_content .= $publication->title . '<br /><em>' . $publication_comment . '</em>';
		//$collection_content .= elgg_view_entity($publication, array('full_view' => false, 'list_type' => 'gallery'));
		$list_type = 'gallery';
		if ($full_content) { $list_type = 'list'; }
		$collection_content .= elgg_view_entity($publication, array('full_view' => $full_content, 'list_type' => $list_type, 'embed' => $full_content));
		
		$collection_content .= '<blockquote><p>' . $publication_comment . '</blockquote>';
		
		//$collection_content .= '</li>';
		$collection_content .= '</div>';
	}
}
elgg_pop_context();
//$collection_content .= '</ul>';
$collection_content .= '</div>';


// Compose full collection view
$icon = elgg_view_entity_icon($collection, 'large', array('size' => 'large', 'align' => 'left'));
$description = elgg_view('output/longtext', array('value' => $collection->description));

$owner = $collection->getOwnerEntity();
$owner_image = elgg_view_entity_icon($owner, 'medium', array('use_hover' => false, 'use_link' => false));
$owner_link = elgg_view('output/url', array(
	//'href' => "transitions/owner/$owner->username",
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($collection->time_created);
$owner_image .= '<p class="elgg-subtext">' . $author_text . ' ' . $date . '</p>';



// Embeddable content
$content = '';
$content .= elgg_view_image_block($icon, $description, array('image_alt' => $owner_image));
/*
$content .= $icon;
$content .= $description;
$content .= '<div class="clearfloat"></div><br />';
*/
//$content .= '<h3>' . elgg_echo('collections:entities:count', array(count($entities))) . '</h3>';
$content .= $collection_content;
$content .= '<div class="clearfloat"></div><br />';

// Display embed code if not already embedded
if ($embed) {
	//echo '<h3><a href="' . $collection->getURL() . '" target="_blank">' . $collection->title . '</a></h3>';
	echo '<h2 class="elgg-heading-main"><a href="' . $collection->getURL() . '" target="_blank">' . $collection->title . '</a></h2>';
	echo $content;
	return;
}


// Full page view : add wrapping

// TABS BLOCK
$params = array(
	'tabs' => array(),
	'id' => "collections-action-tabs",
	'style' => "margin-bottom:0;",
);
$tab_content = '';

$first_tab = true;
// Add new contribution (any member, if allowed by author)
if (elgg_is_logged_in() && ($collection->write_access_id > 0)) {
	$params['tabs'][] = array('title' => elgg_echo('collections:addentity'), 'url' => "#collections-{$collection->guid}-addentity", 'selected' => true);
	if (elgg_is_logged_in()) {
		$tab_content .= elgg_view_form('collection/addentity', array('id' => "collections-{$collection->guid}-addentity", 'class' => "collections-tab-content"), array('guid' => $collection->guid));
		//$tab_content .= '<div class="clearfloat"></div><br />';
	} else {
		$tab_content .= '<div id="collections-'. $collection->guid . '-addentity" class="collections-tab-content">' . elgg_echo('collections:accountrequired') . '</div>';
	}
	$first_tab = false;
}

// Permalink and share links
if ($first_tab) {
	$params['tabs'][] = array('title' => elgg_echo('collections:share'), 'url' => "#collections-{$collection->guid}-share", 'selected' => true);
} else {
	$params['tabs'][] = array('title' => elgg_echo('collections:share'), 'url' => "#collections-{$collection->guid}-share");
}
$share_links = '';
if (elgg_is_active_plugin('socialshare')) {
	$share_links .= '<p>' . elgg_echo('collections:socialshare:details') . '</p>';
	$share_links .= '<div class="collections-socialshare">' . elgg_view('socialshare/extend', array('entity' => $collection)) . '</div>';
}
$permalink = '<p>' . elgg_echo('collections:permalink:details') . '<br /><input type="text" readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);" value="' . $collection->getURL() . '"></p>';
if (elgg_is_active_plugin('shorturls')) {
	$short_link = '<p>' . elgg_echo('collections:shortlink:details') . '<br /><input type="text" readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);" value="' . elgg_get_site_url() . 's/' . $collection->guid . '"></p>';
}
if ($first_tab) {
	$tab_content .= elgg_view_module('info', false, $share_links . $permalink . $short_link, array('id' => "collections-{$collection->guid}-share", 'class' => "collections-tab-content"), array('guid' => $collection->guid));
} else {
	$tab_content .= elgg_view_module('info', false, $share_links . $permalink . $short_link, array('id' => "collections-{$collection->guid}-share", 'class' => "collections-tab-content hidden"), array('guid' => $collection->guid));
}

// Embed code
$params['tabs'][] = array('title' => elgg_echo('collections:embed'), 'url' => "#collections-{$collection->guid}-embed");
$embed_code = '<p>' . elgg_echo('collections:embed:details') . '</p><textarea readonly="readonly" onClick="this.setSelectionRange(0, this.value.length);">&lt;iframe src="' . $collection->getURL() . '?embed=full" style="width:320px; height:400px;" /&gt;</textarea>';
// Embed code
$tab_content .= elgg_view_module('info', false, $embed_code, array('id' => "collections-{$collection->guid}-embed", 'class' => "collections-tab-content hidden"), array('guid' => $collection->guid));

// Render tabs block
$content .= elgg_view('navigation/tabs', $params);
$content .= '<div class="elgg-tabs-content">';
$content .= $tab_content;
$content .= '<div class="clearfloat"></div>';
$content .= '</div>';



// RENDER CONTENT
echo '<div class="collections-view-wrapper">';
if (!empty($metadata)) {
	echo $metadata;
	echo '<div class="clearfloat"></div>';
}
echo $content;
echo '</div>';


