<?php
/**
 * List a user's or group's tasks
 *
 * @package ElggPages
 */

gatekeeper();

$owner = elgg_get_page_owner_entity();
if (!$owner) { forward('tasks/all'); }

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('tasks:owner', array($owner->name));

elgg_push_breadcrumb($owner->name);

elgg_register_title_button();

$content = elgg_list_entities(array(
	'types' => 'object', 'subtypes' => 'task_top',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => $limit, 'full_view' => false,
));
if (!$content) { $content = '<p>' . elgg_echo('tasks:none') . '</p>'; }

$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) { $filter_context = 'mine'; }

$sidebar = elgg_view('tasks/sidebar/navigation');
$sidebar .= elgg_view('tasks/sidebar');

$params = array(
	'title' => $title,
	'filter_context' => $filter_context,
	'content' => $content, 'sidebar' => $sidebar,
);

if (elgg_instanceof($owner, 'group')) { $params['filter'] = ''; }

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

