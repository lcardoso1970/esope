<?php
/**
* Elgg bookmarks save action
*
* @package Bookmarks
*/

$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$description = get_input('description');
$address = get_input('address');
$access_id = get_input('access_id');
$tags = get_input('tags');
$guid = get_input('guid');
$share = get_input('share');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

// Check if we need to force access_id update
if ($access_id == "00") {
	$container = get_entity($container_guid);
	// Esope : adjust access_id only for valid containers
	if (elgg_instanceof($container, 'group')) {
		$access_id = $container->group_acl;
	} else if (elgg_instanceof($container, 'user')) {
		// Restricted to user means 0
		$access_id = 0;
	} else if (elgg_instanceof($container, 'site')) {
		// Restricted to site means 1, but there is already a value for that
		$access_id = 1;
	} else {
		// Other values are not valid, but should still fallback to something valid, so use default value
		$access_id = get_default_access();
	}
}

elgg_make_sticky_form('bookmarks');

// don't use elgg_normalize_url() because we don't want
// relative links resolved to this site.
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://$address";
}

if (!$title || !$address) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}

// see https://bugs.php.net/bug.php?id=51192
$php_5_2_13_and_below = version_compare(PHP_VERSION, '5.2.14', '<');
$php_5_3_0_to_5_3_2 = version_compare(PHP_VERSION, '5.3.0', '>=') &&
		version_compare(PHP_VERSION, '5.3.3', '<');

$validated = false;
if ($php_5_2_13_and_below || $php_5_3_0_to_5_3_2) {
	$tmp_address = str_replace("-", "", $address);
	$validated = filter_var($tmp_address, FILTER_VALIDATE_URL);
} else {
	$validated = filter_var($address, FILTER_VALIDATE_URL);
}
if (!$validated) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}

if ($guid == 0) {
	$bookmark = new ElggObject;
	$bookmark->subtype = "bookmarks";
	$bookmark->container_guid = (int)get_input('container_guid', elgg_get_logged_in_user_guid());
	$new = true;
} else {
	$bookmark = get_entity($guid);
	if (!$bookmark->canEdit()) {
		system_message(elgg_echo('bookmarks:save:failed'));
		forward(REFERRER);
	}
}

$tagarray = string_to_tag_array($tags);

$bookmark->title = $title;
$bookmark->address = $address;
$bookmark->description = $description;
$bookmark->access_id = $access_id;
$bookmark->tags = $tagarray;

if ($bookmark->save()) {

	elgg_clear_sticky_form('bookmarks');

	// @todo
	if (is_array($shares) && sizeof($shares) > 0) {
		foreach($shares as $share) {
			$share = (int) $share;
			add_entity_relationship($bookmark->getGUID(), 'share', $share);
		}
	}
	system_message(elgg_echo('bookmarks:save:success'));

	//add to river only if new
	if ($new) {
		elgg_create_river_item(array(
			'view' => 'river/object/bookmarks/create',
			'action_type' => 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $bookmark->getGUID(),
		));
	}

	forward($bookmark->getURL());
} else {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward("bookmarks");
}
