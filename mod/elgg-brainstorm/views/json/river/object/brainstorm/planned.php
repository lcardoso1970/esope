<?php
/**
 * Update idea river entry from status planned
 *
 * @package Brainstorm
 */

global $jsonexport;

$object = $vars['item']->getObjectEntity();
$subject = $vars['item']->getSubjectEntity();
$container = $object->getContainerEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

$group_link = elgg_view('output/url', array(
	'href' => $container->getURL(),
	'text' => $container->name,
	'is_trusted' => true,
));
$group_string = elgg_echo('river:ingroup', array($group_link));

$status_array = unserialize($container->brainstorm_status);
$status_string = $status_array['planned'] ? $status_array['planned'] : elgg_echo('brainstorm:planned');

$excerpt = "<span class=\"status planned\">$status_string</span>&nbsp;";
$excerpt .= strip_tags(elgg_get_excerpt($object->status_info, 140));

$vars['item']->summary = elgg_echo('river:update:object:idea', array($subject_link, $object_link, $group_string)) . ' ' . $group_string;
$vars['item']->message = $excerpt;

$jsonexport['results'][] = $vars['item'];