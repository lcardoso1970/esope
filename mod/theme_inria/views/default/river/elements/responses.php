<?php
/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */

// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return;
}

$item = $vars['item'];
/* @var ElggRiverItem $item */
$object = $item->getObjectEntity();

// Iris : allow comments on responses (top object)
$subtype = $object->getSubtype();
$top_object = $object;
//error_log($object->guid .' // container='.$subtype . ' // object='.$object->getSubtype().' == '.print_r($vars['item'], true));
if (in_array($subtype, array('comment', 'discussion_reply', 'groupforumtopic'))) {
	while(elgg_instanceof($container, 'object')) {
		if (elgg_instanceof($container, 'object')) { $top_object = $container; }
		$parent_container = $container->getContainerEntity();
		if ($parent_container) { $container = $parent_container; }
	}
}
// @TODO
if ($top_object->guid != $object->guid) {
	$object = $top_object;
	$comment_count = $object->countComments();
	// Avoid listing comments on users, groups, sites...
	//if ($comment_count) {
	if ($comment_count && elgg_instanceof($object, 'object')) {
		$comments = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $object->getGUID(),
			'limit' => 3,
			'order_by' => 'e.time_created desc',
			'distinct' => false,
		));

		// why is this reversing it? because we're asking for the 3 latest
		// comments by sorting desc and limiting by 3, but we want to display
		// these comments with the latest at the bottom.
		$comments = array_reverse($comments);

		echo elgg_view_entity_list($comments, array('list_class' => 'elgg-river-comments'));

		if ($comment_count > count($comments)) {
			$url = $object->getURL();
			$params = array(
				'href' => $url,
				'text' => elgg_echo('river:comments:all', array($comment_count)),
				'is_trusted' => true,
			);
			$link = elgg_view('output/url', $params);
			echo "<div class=\"elgg-river-more\">$link</div>";
		}
	}

	// inline comment form
	if ($object->canComment()) {
		$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
		$body_vars = array('entity' => $object, 'inline' => true);
		echo elgg_view_form('comment/save', $form_vars, $body_vars);
	}
	return true;
}

// annotations and comments do not have responses
//if ($item->annotation_id != 0 || !$object || $object instanceof ElggComment) {
if ($item->annotation_id != 0 || !$object || $object instanceof ElggComment) { return; }

$comment_count = $object->countComments();

// Avoid listing comments on users, groups, sites...
//if ($comment_count) {
if ($comment_count && elgg_instanceof($object, 'object')) {
	$comments = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $object->getGUID(),
		'limit' => 3,
		'order_by' => 'e.time_created desc',
		'distinct' => false,
	));

	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	echo elgg_view_entity_list($comments, array('list_class' => 'elgg-river-comments'));

	if ($comment_count > count($comments)) {
		$url = $object->getURL();
		$params = array(
			'href' => $url,
			'text' => elgg_echo('river:comments:all', array($comment_count)),
			'is_trusted' => true,
		);
		$link = elgg_view('output/url', $params);
		echo "<div class=\"elgg-river-more\">$link</div>";
	}
}

// inline comment form
// @TODO handle forum replies (status = open|closed)
if ($object->canComment()) {
	$form_vars = array('id' => "comments-add-{$object->getGUID()}", 'class' => 'hidden');
	$body_vars = array('entity' => $object, 'inline' => true);
	echo elgg_view_form('comment/save', $form_vars, $body_vars);
}

