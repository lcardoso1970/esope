<?php
/**
 * Brainstorm group sidebar
 */
$page_owner = elgg_get_page_owner_entity();
$user = elgg_get_logged_in_user_guid();

if (elgg_instanceof($page_owner) && $page_owner->canWriteToContainer($user)) {

	elgg_load_library('brainstorm:utilities');

	echo elgg_view('brainstorm/sidebar-points');

	$body = elgg_view('brainstorm/sidebar-idea');
	echo elgg_view_module('aside', '', $body);

	echo elgg_view('page/elements/tagcloud_block', array(
		'subtypes' => 'idea',
		'owner_guid' => $page_owner->guid,
	));

}
