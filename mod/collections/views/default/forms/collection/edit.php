<?php
elgg_load_js('elgg.collections.collections');

elgg_load_js('lightbox');
elgg_load_css('lightbox');
elgg_require_js('jquery.form');
elgg_load_js('elgg.embed');

// Get current collection (if exists)
$guid = get_input('guid', false);
$collection = get_entity($guid);
$container_guid = get_input('container_guid', false);
$container = get_entity($container_guid);
$add_guid = get_input('add_guid', false);

$entity_guid = get_input('entity_guid', false);
$entity_guid = explode(',', $entity_guid);
$entity_guid = array_filter($entity_guid);

$content = '';
$sidebar = '';

// Get collection vars
if (elgg_instanceof($collection, 'object', 'collection')) {
	$collection_title = $collection->title; // Collection title, for easier listing
	$collection_name = $collection->name; // Collection name, for URL and embeds
	if (empty($collection_name) && !empty($collection_title)) {
		$collection_name = elgg_get_friendly_title($collection_title);
	}
	$collection_description = $collection->description; // Clear description of what this collection is for
	// Complete collection content - except the first-level <ul> tag (we could use an array instead..) - Use several blocks si we can have an array of individual entities
	$collection_entities = (array) $collection->entities;
	$collection_entities_comment = (array) $collection->entities_comment;
	$collection_access = $collection->access_id; // Default access level
	
} else {
	$collection_css = elgg_get_plugin_setting('css', 'collection'); // CSS
	$collection_access = get_default_access(); // Default access level
	$collection_entities = $entity_guid;
	$collection_entities_comment = array();
}

// Options
$access_opt = array('0' => elgg_echo('collections:access:draft'), '2' => elgg_echo('collections:access:published'));
$write_access_opt = array('2' => elgg_echo('collections:write:open'), '0' => elgg_echo('collections:write:closed'));


// Edit form
$content = '';

// Param vars
if ($collection) { $content .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid)) . '</p>'; }

// Titre
$content .= '<p><label>' . elgg_echo('collections:edit:title') . ' ' . elgg_view('input/text', array('name' => 'title', 'value' => $collection_title)) . '</label><br /><em>' . elgg_echo('collections:edit:title:details') . '</em></p>';

// Identifiant (slurl)
/*
$content .= '<p><label>' . elgg_echo('collections:edit:name') . ' ' . elgg_view('input/text', array('name' => 'name', 'value' => $collection_name, 'style' => "width: 40ex; max-width: 80%;")) . '</label><br /><em>' . elgg_echo('collections:edit:name:details') . '</em></p>';
*/

// Description
$content .= '<label>' . elgg_echo('collections:edit:description') . '</label><br /><em>' . elgg_echo('collections:edit:description:details') . '</em>' . elgg_view('input/longtext', array('name' => 'description', 'value' => $collection_description, 'style' => 'height:15ex;')) . '';



$sidebar .= '<p style="text-align:right;">' . elgg_view('input/submit', array('value' => elgg_echo('collections:edit:submit'), 'class' => "elgg-button elgg-button-action")) . '</p>';

// Illustration
$sidebar .= '<p><label for="collection_icon">';
if ($collection) {
	$sidebar .= elgg_echo("collection:icon");
} else {
	$sidebar .= elgg_echo("collection:icon:new");
}
$sidebar .= '</label><br />';
$sidebar .= '<em>' . elgg_echo('collection:icon:details') . '</em><br />';
$sidebar .= elgg_view("input/file", array("name" => "icon", "id" => "collection_icon"));
if ($collection && $collection->icontime) {
	$sidebar .= '<br /><img src="' . $collection->getIconURL('listing') . '" /><br />';
	$sidebar .= elgg_view("input/checkbox", array('name' => "remove_icon", 'value' => "yes"));
	$sidebar .= elgg_echo("collection:icon:remove");
}
$sidebar .= '</p>';

// Access
//$content .= '<p><label>' . elgg_echo('collections:edit:access') . ' ' . elgg_view('input/access', array('name' => 'access_id', 'value' => $collection_access)) . '</label><br /><em>' . elgg_echo('collections:edit:access:details') . '</em></p>';
$sidebar .= '<p><label>' . elgg_echo('collections:edit:access') . ' ' . elgg_view('input/access', array('name' => 'access_id', 'value' => $collection_access, 'options_values' => $access_opt)) . '</label><br /><em>' . elgg_echo('collections:edit:access:details') . '</em></p>';

$sidebar .= '<div class="clearfloat"></div>';

// Open access to collection (users can add content)
//$sidebar .= '<p><label>' . elgg_echo('collections:edit:access') . ' ' . elgg_view('input/access', array('name' => 'access_id', 'value' => $collection_access)) . '</label><br /><em>' . elgg_echo('collections:edit:access:details') . '</em></p>';
$sidebar .= '<p><label>' . elgg_echo('collections:edit:write_access') . ' ' . elgg_view('input/access', array('name' => 'write_access_id', 'value' => $collection_write_access, 'options_values' => $write_access_opt)) . '</label><br /><em>' . elgg_echo('collections:edit:write_access:details') . '</em></p>';

$sidebar .= '<div class="clearfloat"></div>';


// 2 columns layout
$title = elgg_echo('collections:edit');
$content = <<<___HTML
<h2>$title</h2>

<div class="flexible-block" style="width:56%; float:left;">
$content
</div>

<div class="flexible-block" style="width:40%; float:right;">
$sidebar
</div>
<div class="clearfloat"></div>
___HTML;


// ENTITIES
// Sortable blocks + JS add new block
$content .= '<div class="collection-edit-entities">';
$content .= '<p><strong>' . elgg_echo('collections:edit:content') . '</strong><br />';
$content .= '<em>' . elgg_echo('collections:edit:content:details') . '</em></p>';


// Collections entities (sortable)
if (is_array($collection_entities)) {
	foreach($collection_entities as $k => $entity_guid) {
		$content .= elgg_view('collections/input/entity', array('guid' => $collection->guid, 'entity_guid' => $entity_guid, 'entity_comment' => $collection_entities_comment[$k], 'offset' => $k));
	}
} else {
	$content .= elgg_view('collections/input/entity', array());
}
$content .= '</div>';

//$content .= '<div class="clearfloat"></div>';
// Add new entity
$content .= elgg_view('input/button', array(
		'id' => 'collection-edit-add-entity',
		'value' => elgg_echo('collections:edit:addentity'),
		'class' => 'elgg-button collection-edit-highlight',
	));
$content .= '<div class="clearfloat"></div><br />';




/* AFFICHAGE DE LA PAGE D'ÉDITION */


/*
echo '<div class="clearfloat"></div><br />';

// Informations on embed and insert
if ($collection) {
	echo '<h3><i class="fa fa-info-circle"></i> ' . elgg_echo('collections:embed:instructions') . '</h3>';
	echo '<p><blockquote>';
	echo elgg_echo('collections:iframe:instructions', array($collection->guid)) . '<br />';
	if (elgg_is_active_plugin('shortcodes')) { echo elgg_echo('collections:shortcode:instructions', array($collection->guid)) . '<br />'; }
	if (elgg_is_active_plugin('cmspages')) {
		echo elgg_echo('collections:cmspages:instructions', array($collection->guid)) . '<br />';
		if (elgg_is_active_plugin('shortcodes')) { echo elgg_echo('collections:cmspages:instructions:shortcode', array($collection->guid)) . '<br />'; }
	}
	echo '</blockquote></p>';
}
*/

// Prévisualisation
/*
if ($collection) {
	echo '<div class="clearfloat"></div><br /><br />';
	echo '<a href="' . $collection->getURL() . '" style="float:right" target="_blank" class="elgg-button elgg-button-action">' . elgg_echo('collections:edit:view') . '</a>';
	echo '<h2>' . elgg_echo('collections:edit:preview') . '</h2>';
	echo elgg_view('collections/view', array('entity' => $collection));
}
*/


// Affichage du formulaire
// Display the form - Affichage du formulaire
echo elgg_view('input/form', array('action' => elgg_get_site_url() . "action/collection/edit", 'body' => $content, 'id' => "collection-edit-form", 'enctype' => 'multipart/form-data'));

