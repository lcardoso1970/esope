<?php
/**
 * River view for group discussion replies
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$reply = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();

/* Iris v2 : Subject : no link
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
 */
$subject_link = '<strong>' . $subject->name . '</strong>';

$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => $target->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
));

$reply_link = elgg_view('output/url', array(
	//'href' => $reply->getURL(),
	'href' => $subject->getURL(),
	//'text' => elgg_echo('river:reply:view'),
	'text' => elgg_echo('theme_inria:readmore'),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
));

$summary = elgg_echo('river:reply:object:groupforumtopic', array($subject_link, $target_link));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => elgg_get_excerpt($reply->description). ' ' .$reply_link,
	'summary' => $summary,
));
