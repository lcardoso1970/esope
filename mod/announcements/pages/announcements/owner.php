<?php
/**
 * Elgg announcements plugin everyone page
 *
 * @package ElggAnnouncements
 */

$username = get_input('username', elgg_get_logged_in_user_entity()->username);
$page_owner = get_user_by_username($username);
if (!elgg_instanceof($page_owner, 'user')) {
	register_error(elgg_echo('announcements:error:invaliduser'));
	forward('announcements/all');
}

elgg_push_breadcrumb($page_owner->name);

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'announcement',
	'owner_guid' => $page_owner->guid,
	'full_view' => false,
	'view_toggle_type' => false
));

if (!$content) {
	$content = elgg_echo('announcements:none');
}

$title = elgg_echo('announcements:owner', array($page_owner->name));

elgg_register_title_button();

$body = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter' => '', // Removes filter menu (nonsense for announcements)
));

echo elgg_view_page($title, $body);
