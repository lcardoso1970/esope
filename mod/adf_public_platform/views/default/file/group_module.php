<?php
/**
 * Group file module
 */

$group = elgg_get_page_owner_entity();

if ($group->file_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "file/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'title' => elgg_echo('file:group') . ', ' . elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'file',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('file:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "file/add/$group->guid",
	'text' => elgg_echo('file:add'),
	'title' => elgg_echo('file:group') . ', ' . elgg_echo('file:add'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('file:group'),
	'class' => 'elgg-module-group-file',
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
