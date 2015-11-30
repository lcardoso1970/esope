<?php
// Feedback sidebar

$base_url = elgg_get_site_url() . 'feedback/';


$status_values = array('open', 'closed', 'total');
foreach ($status_values as $status) { $$status = 0; }
$all_feedbacks = elgg_get_entities(array('type' => 'object', 'subtype' => 'feedback', 'limit' => false));
//$total = elgg_get_entities(array('type' => 'object', 'subtype' => 'feedback', 'count' => true));
$total = count($all_feedbacks);
// Catégories de feedback
$about_enabled = feedback_is_about_enabled();
$about_values = feedback_about_values();
// Init all "about" counters (so we can have a 0 value instead of nothing)
foreach ($about_values as $about) { ${"feedback_$about"} = 0; }
// Catégorie de feedback non définie
$undefined_values = array('other', 'feedback', 'undefined');
$other = 0;



// Count and sort feedbacks
if ($all_feedbacks) foreach ($all_feedbacks as $ent) {
	// Stats
	if (!isset($ent->status) || empty($ent->status) || ($ent->status == 'open')) {
		$open++;
		// Sort feedbacks in undefined vs specific about category
		if (!$about_enabled || empty($ent->about) || in_array($ent->about, $undefined_values)) {
			$other++;
		} else {
			if (isset(${"feedback_" . $ent->about})) {
				${"feedback_" . $ent->about}++;
			} else {
				// May still happen, eg. if an "about" value was set before settings were updated to new values
				${"feedback_" . $ent->about} = 1;
			}
		}
		
	} else if ($ent->status == 'closed') { $closed++; }
}



// Sidebar menu - Menu latéral
$sidebar = '<div id="site-categories">';
$sidebar .= '<h2>' . elgg_echo('feedback'). '</h2>';
$sidebar .= '<ul class="elgg-menu elgg-menu-owner-block elgg-menu-owner-block-categories elgg-menu-owner-block-default">';
foreach ($status_values as $status) {
	$selected = '';
	if ((($status == 'total') && (full_url() == $base_url)) || (full_url() == $base_url . "status/$status")) {
		$selected = ' class="elgg-state-selected"';
	}
	if ($$status > 1) {
		$sidebar .= '<li' . $selected . '><a href="'.$base_url . 'status/' . $status . '">' . elgg_echo("feedback:menu:$status", array($$status)) . '</a></li>';
	} else {
		$sidebar .= '<li' . $selected . '><a href="'.$base_url . 'status/' . $status . '">' . elgg_echo("feedback:menu:$status:singular", array($$status)) . '</a></li>';
	}
}
$sidebar .= '</ul>';

// Add open filter only if there are about categories
if ($about_enabled && (sizeof($about_values) > 1)) {
	$sidebar .= '<h2>' . elgg_echo('feedback:status:open'). '</h2>';
	$sidebar .= '<ul class="elgg-menu elgg-menu-owner-block elgg-menu-owner-block-categories elgg-menu-owner-block-default">';

	foreach ($about_values as $about) {
		if (!in_array($about, $undefined_values)) {
			if ($about == $about_filter) { $sidebar .= '<li class="elgg-state-selected">'; } else { $sidebar .= '<li>'; }
			$sidebar .= '<a href="'.$base_url . 'about/' . $about . '">';
			if (${"feedback_$about"} > 1) {
				$sidebar .= elgg_echo("feedback:menu:$about", array(${"feedback_$about"}));
			} else {
				$sidebar .= elgg_echo("feedback:menu:$about:singular", array(${"feedback_$about"}));
			}
			$sidebar .= '</a></li>';
		}
	}
	// Always add "other" (undefined) feedbacks
	if (!$about_filter || in_array($about_filter, $undefined_values)) { $sidebar .= '<li class="elgg-state-selected">'; } else { $sidebar .= '<li>'; }
	$sidebar .= '<a href="'.$base_url . 'about/other">';
	if ($other > 1) {
		$sidebar .= elgg_echo("feedback:menu:other", array($other));
	} else {
		$sidebar .= elgg_echo("feedback:menu:other:singular", array($other));
	}
	$sidebar .= '</a></li></ul>';
}
$sidebar .= '</div>';



echo $sidebar;
