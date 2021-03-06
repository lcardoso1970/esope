<?php
/**
 * Save transitions entity
 *
 * Can be called by clicking save button or preview button. If preview button,
 * we automatically save as draft. The preview button is only available for
 * non-published drafts.
 *
 * Drafts are saved with the access set to private.
 *
 * @package Transitions
 */

// start a new sticky form session in case of failure
elgg_make_sticky_form('transitions-addtag');


// edit or create a new entity
$guid = get_input('guid');
$tags = get_input('tags');

if ($guid) {
	$entity = get_entity($guid);
	if (!elgg_instanceof($entity, 'object', 'transitions')) {
		register_error(elgg_echo('transitions:error:post_not_found'));
		forward(get_input('forward', REFERER));
	}
}

// Add new tag
if (!empty($tags)) {
	$new_tags = string_to_tag_array($tags);
	$tags = (array)$entity->tags_contributed;
	foreach($new_tags as $tag) {
		$tag = filter_tags($tag);
		if (in_array($tag, $tags)) { register_error(elgg_echo('transitions:addtag:alreadyexists')); }
		$tags[] = $tag;
	}
	$tags = array_unique($tags);
	$tags = array_filter($tags);
	$entity->tags_contributed = $tags;
	system_messages(elgg_echo('transitions:addtag:success'));
	elgg_clear_sticky_form('transitions-addtag');
} else {
	register_error(elgg_echo('transitions:addtag:emptytag'));
}


forward($entity->getURL());

