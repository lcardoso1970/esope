<?php
/**
 * Transitions² public homepage
 *
 */

// no RSS feed with a "widget" front page
/*
global $autofeed;
$autofeed = FALSE;
*/


$content = '';
$title = elgg_echo('theme_transitions2:news');
$sidebar = '';
elgg_set_context('transitions');
elgg_push_context('view');
elgg_push_context('transitions-news');

// Avoid adding HTML in RSS or any other viewtype
$is_html_viewtype = true;
if ((elgg_get_viewtype() == 'rss') || (elgg_get_viewtype() == 'ical')) { $is_html_viewtype = false; }


// Event et Editorial sélectionnées par la rédaction
$list_options = array('types' => 'object', 'subtypes' => 'transitions', 'limit' => 12, 'full_view' => true, 'item_class' => 'transitions-item', 'metadata_name_value_pairs' => array(array('name' => 'featured', 'value' => 'featured'), array('name' => 'category', 'value' => array('event', 'editorial'))));

// HTML vs RSS/ICAL content
if ($is_html_viewtype) {
	$content .= '<a href="' . current_page_url() . '?view=rss" class="float-alt"><i class="fa fa-rss"></i>&nbsp;' . elgg_echo('theme_transitions2:news:rss') . '</a>';
	$content .= '<h2>' . elgg_echo('theme_transitions2:news:title') . '</h2>';
	$content .= '<div class="transitions-news">';
}
$content .= elgg_list_entities_from_metadata($list_options);
if ($is_html_viewtype) { $content .= '</div>'; }

echo elgg_view_page($title, $content);

