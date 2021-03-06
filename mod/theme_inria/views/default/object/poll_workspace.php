<?php
/**
 * Elgg poll individual post view
 *
 * @uses $vars['entity'] Optionally, the poll post to view
 */

$poll = elgg_extract('entity', $vars);
$full = elgg_extract('full_view', $vars);

if (!$poll) { return TRUE; }

$page_owner = elgg_get_page_owner_entity();
$own = elgg_get_logged_in_user_entity();

$owner = $poll->getOwnerEntity();
$container = $poll->getContainerEntity();
$categories = elgg_view('output/categories', $vars);

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
		'href' => "poll/owner/$owner->username",
		'text' => $owner->name,
		'is_trusted' => true,
));
$author_text = '<strong>' . $owner_link . '</strong>';
$tags = elgg_view('output/tags', array('tags' => $poll->tags));
$date = '<span class="elgg-river-timestamp">' . elgg_view_friendly_time($poll->time_created) . '</span>';

$allow_close_date = elgg_get_plugin_setting('allow_close_date','poll');
if (($allow_close_date == 'yes') && (isset($poll->close_date))) {
	$date_day = gmdate('j', $poll->close_date);
	$date_month = gmdate('m', $poll->close_date);
	$date_year = gmdate('Y', $poll->close_date);
	$friendly_time = $date_day . '. ' . elgg_echo("poll:month:$date_month") . ' ' . $date_year;
	$poll_state = $poll->isOpen() ? 'open' : 'closed';
	$closing_date = "<div class='poll_closing-date-{$poll_state}'><b>" . elgg_echo('poll:poll_closing_date', array($friendly_time)) . '</b></div>';
}

$responses = $poll->countAnnotations('vote');
if ($responses == 1) {
	$noun = elgg_echo('poll:noun_response');
} else {
	$noun = elgg_echo('poll:noun_responses');
}
$responses = "<div>" . $responses . " " . $noun . "</div>";

// TODO: support comments off
// The "on" status changes for comments, so best to check for !Off
$comments_link = '';
if ($poll->comments_on != 'Off') {
	$comments_count = $poll->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $poll->getURL() . '#poll-comments',
			'text' => $text,
			'is_trusted' => true
		));
	}
}

// do not show the metadata and controls in widget view
$metadata = '';
if (!elgg_in_context('widgets')) {
	$metadata = elgg_view_menu('entity', array(
			'entity' => $poll,
			'handler' => 'poll',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz'
	));
}


//$description = elgg_get_excerpt($poll->description) . ;
//$description = '<a href="' . $poll->getUrl() . '" class="iris-object-readmore">' . elgg_get_excerpt($poll->description) . '<span class="readmore">' . elgg_echo('theme_inria:readmore:vote') . '</span></a>';
if ($poll->isOpen() && !$poll->hasVoted($own)) {
	$description = '<a href="' . $poll->getUrl() . '" class="iris-object-readmore">' . elgg_get_excerpt($poll->description) . '<span class="readmore">' . elgg_echo('theme_inria:readmore:vote') . '</span></a>';
} else {
	$description = '<a href="' . $poll->getUrl() . '" class="iris-object-readmore">' . elgg_get_excerpt($poll->description) . '<span class="readmore">' . elgg_echo('theme_inria:readmore:view') . '</span></a>';
}
if (!empty($description)) { $description = '<div class="" style="padding: 0.5rem 0">' . $description . '</div>'; }

//echo elgg_view('poll/body', $vars);
//$content = elgg_view('poll/body', $vars);
$content = elgg_view('object/poll_content_workspace', $vars);



echo '<div class="object-poll-workspace" style="margin-top: 1rem; margin-bottom: 1rem;">';
	echo '<div class="" style="margin-bottom: 0.5rem;">' . $date . '<br />' .  $author_text . '</div>';
	echo '<div class="entity-title"><h3>' . $poll->title . '</h3></div>';
	echo $responses;
	echo '<div class="clearfix">';
		echo $description;
		echo $content;
	echo '</div>';
	//echo '<div class="clearfloat"></div>';
echo '</div>';

