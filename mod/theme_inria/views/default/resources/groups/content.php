<?php
// PH spécifique pour ces pages d'interfaces similaires entre tous les contenus 
// => ça facilitera la mise en place des filtres, et plus encore celle des listings
// par ex. groups/resources/$subtype/all|ine|draft ... (ou content, publications)

// Determine current workspace (page_owner, potentially a subgroup)
$guid = elgg_extract('group_guid', $vars);
$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	register_error('groups:error:invalid');
	forward();
}
elgg_group_gatekeeper();

elgg_set_page_owner_guid($guid);
// Determine main group
$main_group = theme_inria_get_main_group($group);

$own = elgg_get_logged_in_user_entity();

// Subtype filter for content activity
$subtype_opt = theme_inria_group_object_subtypes_opt($group);
$subtype = get_input('subtype', ''); // Subtype filter (as in GET)
$filter = get_input('filter', ''); // Owner filter all|mine|draft
$subtypes = ''; // Subtype(s) string or array (for elgg_get_entities)
if (!isset($subtype_opt[$subtype]) || empty($subtype) || ($subtype == 'all')) { forward($url . 'groups/workspace/' . $group->guid); }
switch($subtype) {
	case 'pages':
		$subtypes = array('page', 'page_top');
		break;
	default:
		$subtypes = $subtype;
}


// turn this into a core function
global $autofeed;
$autofeed = true;
$url = elgg_get_site_url();
elgg_push_context('group_workspace');
elgg_entity_gatekeeper($guid, 'group');
elgg_push_breadcrumb($group->name);

groups_register_profile_buttons($group);

$content = '';
$sidebar = '';
$sidebar_alt = '';



// Add content filter : All | Mine | Drafts/private_access
$base_url = $url . 'groups/content/' . $group->guid . '/' . $subtype . '/';



/*
// generate a list of default tabs
$tabs = array(
	'all' => array(
		'text' => elgg_echo('all'),
		'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
		'selected' => ($filter_context == 'all'),
		'priority' => 200,
	),
	'mine' => array(
		'text' => elgg_echo('mine'),
		'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
		'selected' => ($filter_context == 'mine'),
		'priority' => 300,
	),
	'friend' => array(
		'text' => elgg_echo('friends'),
		'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
		'selected' => ($filter_context == 'friends'),
		'priority' => 400,
	),
);

foreach ($tabs as $name => $tab) {
	$tab['name'] = $name;
	elgg_register_menu_item('filter', $tab);
}
$content .= elgg_view_menu('filter');
*/


// Entités par date (*pas* la river/l'activité)
//elgg_push_context('workspace');
$db_prefix = elgg_get_config('dbprefix');
$content_activity_opt = array(
	'type' => 'object',
	'wheres' => array(
		"(e.container_guid = $group->guid OR e.owner_guid = $group->guid)",
	),
	'limit' => 10,
	'pagination' => true,
);
if (!empty($subtypes)) { $content_activity_opt['subtypes'] = $subtypes; }

// Count all filters results
$content_activity_mine_opt = $content_activity_opt;
$content_activity_mine_opt['owner_guid'] = $own->guid;
$content_activity_draft_opt = $content_activity_mine_opt;
$content_activity_draft_opt['wheres'][] = "e.access_id = '0'"; // works also for blogs (draft mode forces access to 0)
$count_all = elgg_get_entities($content_activity_opt + ['count' => true]);
$count_mine = elgg_get_entities($content_activity_mine_opt + ['count' => true]);
$count_draft = elgg_get_entities($content_activity_draft_opt + ['count' => true]);

switch($filter) {
	case 'mine':
		$count = $count_mine;
		$entities_list = elgg_list_entities($content_activity_mine_opt);
		break;
	case 'draft':
		$count = $count_draft;
		$entities_list = elgg_list_entities($content_activity_draft_opt);
		break;
	case 'all':
	default:
		$count = $count_all;
		$entities_list = elgg_list_entities($content_activity_opt);
}
//elgg_pop_context();




// Compose page content

// Nouvelle publication
if ($group->isMember()) {
	switch($subtype) {
		case 'event_calendar':
		case 'newsletter':
		case 'poll':
		case 'blog':
		case 'bookmarks':
		case 'file':
		default:
			$content .= '<a href="' . $url . $subtype . '/add/' . $own->guid . '" class="elgg-button elgg-button-action float-alt" rel="nofollow">' . elgg_echo($subtype.':add') . '</a>';
	}
}

// Filtres
$content .= '<div class="group-content-filter tabs">';
	if ($filter == 'all') { $class = 'elgg-state-selected'; } else { $class = ''; }
	$content .= '<a href="' . $base_url . 'all" class="' . $class . '" rel="nofollow">' . elgg_echo('theme_inria:contentfilter:all') . ' (' . $count_all . ')</a>';
	if ($filter == 'mine') { $class = 'elgg-state-selected'; } else { $class = ''; }
	$content .= '<a href="' . $base_url . 'mine" class="' . $class . '" rel="nofollow">' . elgg_echo('theme_inria:contentfilter:mine') . ' (' . $count_mine . ')</a>';
	if ($filter == 'draft') { $class = 'elgg-state-selected'; } else { $class = ''; }
	$content .= '<a href="' . $base_url . 'draft" class="' . $class . '" rel="nofollow">' . elgg_echo('theme_inria:contentfilter:draft') . ' (' . $count_draft . ')</a>';
$content .= '</div>';
// Résultats
if ($count > 0) {
	$content .= $entities_list;
} else {
	$content .= '<div class="group-profile-main"><p>' . elgg_echo('theme_inria:groups:content:no_result') . '</p></div>';
}
//$sidebar = '<div class="elgg-sidebar iris-group-sidebar"><div class="iris-sidebar-content">' . $sidebar . '</div></div>';

$title = $group->name;

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'sidebar-alt' => false,
	'title' => $title,
);
$body = elgg_view_layout('iris_group', $params);

echo elgg_view_page($group->name, $body);
