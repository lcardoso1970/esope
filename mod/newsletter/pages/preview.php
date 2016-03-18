<?php

gatekeeper();

$guid = (int) get_input('guid');

// validate input
if (empty($guid)) {
	register_error(elgg_echo('InvalidParameterException:MissingParameter'));
	forward(REFERER);
}

$entity = get_entity($guid);
if (empty($entity) || !$entity->canEdit()) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

if (!elgg_instanceof($entity, 'object', Newsletter::SUBTYPE)) {
	register_error(elgg_echo('ClassException:ClassnameNotClass', [$guid, elgg_echo('item:object:' . Newsletter::SUBTYPE)]));
	forward(REFERER);
}

$newsletter_content = elgg_view_layout('newsletter', ['entity' => $entity]);
echo newsletter_apply_url_postfix($newsletter_content, $entity);

echo elgg_view('newsletter/buttons', ['entity' => $entity, 'type' => 'preview']);