<?php
/**
 * Body of river item
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 * @uses $vars['responses']   Alternate responses (comments, replies, etc.)
 */

$item = $vars['item'];

$menu = elgg_view_menu('river', array(
	'item' => $item,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

// river item header
$timestamp = elgg_get_friendly_time($item->getPostedTime());

$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary', array('item' => $vars['item'])));
if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	));
}

$message = elgg_extract('message', $vars, false);
if ($message !== false) {
	$message = "<div class=\"elgg-river-message\">$message</div>";
}

$attachments = elgg_extract('attachments', $vars, false);
if ($attachments !== false) {
	$attachments = "<div class=\"elgg-river-attachments clearfix\">$attachments</div>";
}

$responses = elgg_view('river/elements/responses', $vars);
if ($responses) {
	$responses = "<div class=\"elgg-river-responses\">$responses</div>";
}

// Toutes ces infos habituellement affichées sont regroupées sous forme de bloc dépliable
if (elgg_in_context('widgets')) {
  if (!empty($message) || !empty($attachments) || !empty($responses)) {
    $urlicon = $vars['url'] . 'mod/adf_public_platform/img/theme/';
    $message = '<a class="ouvrir" href="#" title="Plus d\'informations sur ' . get_entity($item->object_guid)->title . '"><img src="' . $urlicon . 'ensavoirplus.png" alt="Dérouler" /></a><div class="plus">' . $message . $attachments . $responses . '</div>';
  }
} else {
  $message = $message . $attachments;
  if (!empty($responses)) {
    $urlicon = $vars['url'] . 'mod/adf_public_platform/img/theme/';
    $message .= '<a class="ouvrir" href="#" title="Plus d\'informations sur ' . get_entity($item->object_guid)->title . '"><img src="' . $urlicon . 'ensavoirplus.png" alt="Dérouler" /></a><div class="plus">' . $responses . '</div>';
  }
}

$group_string = '';
$object = $item->getObjectEntity();
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}


// Affichage de la page
echo <<<RIVER
$menu
<div class="elgg-river-summary">$summary $group_string <span class="elgg-river-timestamp">$timestamp</span></div>
$message
RIVER;
