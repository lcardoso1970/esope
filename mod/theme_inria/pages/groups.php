<?php
/**
 * Elgg groups plugin everyone page
 *
 * @package ElggBookmarks
 */

elgg_set_context('groups');

/*
elgg_pop_breadcrumb();
elgg_push_breadcrumb($owner->name);
*/
elgg_push_breadcrumb(elgg_echo('search'));


$title = false;
$content = '';
$sidebar = '';


$hide_directory = elgg_get_plugin_setting('hide_directory', 'esope');
if ($hide_directory == 'yes') { gatekeeper(); }


$filter = get_input('filter', 'search');
if (!in_array($filter, array('search', 'community', 'discover', 'member'))) { $filter = 'search'; }
//elgg_require_js('elgg/spinner'); // @TODO make spinner work...


if ($filter == 'search') {
}

if (in_array($filter, array('search'))) {
}
elgg_push_context('search');


switch($filter) {
	case 'discover':
	case 'community':
		$sidebar = false;
		$content .= '<h2>Groupes à découvrir</h2>';
		$field_settings = elgg_get_entities_from_metadata(array('types' => 'object', 'subtype' => 'custom_group_field', 'metadata_names' => 'metadata_name', 'metadata_values' => 'community'));
		if ($field_settings) {
			foreach($field_settings[0]->getOptions() as $community => $label) {
				if (empty($label)) { continue; }
				$community_groups_count = theme_inria_get_community_groups($community, true);
				if ($community_groups_count > 1) {
					$num_groups = elgg_echo('theme_inria:community:groups:count', array($community_groups_count));
				} else {
					$num_groups = elgg_echo('theme_inria:community:groups:count:singular', array($community_groups_count));
				}
				$community = elgg_get_friendly_title($community);
				$content .= '<div class="iris-groups-community iris-community-' . $community . '">';
					$content .= '<a href="' . elgg_get_site_url() . 'groups/?community=' . $label . '"><div class="iris-community-hover hidden"><i class="fa fa-eye"></i> &nbsp; ' . elgg_echo('theme_inria:groups:discover:view') . '</div></a>';
					$content .= '<div class="iris-community-icon">';
					$content .= '<img src="' . elgg_get_site_url() . 'mod/theme_inria/graphics/communities/' . $community . '_100.png" />';
					$content .= '</div>';
					$content .= '<div class="iris-community-body">';
						$content .= '<div class="iris-community-groups-count">' . $num_groups . '</div>';
						$content .= '<h3><a href="' . elgg_get_site_url() . 'groups/?community=' . $label . '">' . elgg_echo('theme_inria:communities') . ' ' . $label . '</a></h3>';
						$content .= '<p>' . elgg_echo('theme_inria:community:description:' . $community) . '</p>';
					$content .= '</div>';
				$content .= '</div>';
			}
		}
		break;
	
	case 'member':
		$sidebar = false;
		$username = get_input('username');
		$user = get_user_by_username($username);
		if (!elgg_instanceof($user, 'user')) { $user = elgg_get_logged_in_user_entity(); }
		
			// Invalid user provided : can be not set, or a wrong one (no access)
			if (!elgg_instanceof($user, 'user')) {
				register_error(elgg_echo('noaccess'));
				forward('groups');
			}
			
			
			// Favorite groups first - only for self
			$favorite_guids = array();
			if ($user->guid == elgg_get_logged_in_user_guid()) {
				$favorite_options = array(
					'type' => 'group', 'relationship' => 'favorite', 
					'relationship_guid' => $user->guid, 'inverse_relationship' => true, 
					'limit' => false, 'order_by' => "r.time_created DESC",
				);
				$favorite_groups = elgg_get_entities_from_relationship($favorite_options);
				if ($favorite_groups) {
					foreach ($favorite_groups as $ent) {
						if ($ent->isMember()) {
							$favorite_guids[] = $ent->guid;
							//$favorite_ents[] = $ent;
						}
					}
				}
				if (sizeof($favorite_guids) > 0) {
					$groups_content .= elgg_list_entities(array('guids' => $favorite_guids));
				}
			}
			// Other groups
			$options = array(
				'type' => 'group', 
				'relationship' => 'member', 
				'relationship_guid' => $user->guid, 
				'inverse_relationship' => false, 
				'full_view' => false, 
				'limit' => false,
				'no_results' => elgg_echo('groups:none'),
			);
			// Exclude favorite groups if any (already listed above)
			if (sizeof($favorite_guids) > 0) {
				$options['wheres'][] = "e.guid NOT IN (" . implode(',', $favorite_guids) . ")";
			}
			// Exclude subgroups from listing ?
			if (elgg_is_active_plugin('au_subgroups')) {
				$options['wheres'][] = "NOT EXISTS ( SELECT 1 FROM " . elgg_get_config('dbprefix') . "entity_relationships WHERE guid_one = e.guid AND relationship = '" . AU\SubGroups\AU_SUBGROUPS_RELATIONSHIP . "' )";
			}
			$groups_content .= elgg_list_entities_from_relationship($options);
			
			// Add title + count
			$options['count'] = true;
			$count = elgg_get_entities_from_relationship($options);
			if ($user->guid == elgg_get_logged_in_user_guid()) {
				$content .= '<h2>' . elgg_echo("groups:yours") . " ($count)" . '</h2>';
			} else {
				$content .= '<h2>' . elgg_echo("groups:user", array($user->name)) . " ($count)" . '</h2>';
			}
			$content .= $groups_content;
			
			// Bloc s'abonner + créer
			$content .= '<div class="iris-groups-member-new">';
			$content .= '<div class="iris-groups-member-new-image">+</div>';
			$content .= '<div class="iris-groups-member-new-body">';
				$content .= elgg_view('output/url', array('href' => 'groups', 'text' => elgg_echo('theme_inria:groups:register'), 'class' => 'elgg-button elgg-button-action'));
				// La création de groupe est réservée aux membres Inria
				if (esope_get_user_profile_type() == 'inria') {
					$content .= ' &nbsp; ' . elgg_view('output/url', array('href' => 'groups/add', 'text' => elgg_echo('groups:add'), 'class' => 'elgg-button elgg-button-action'));
				}
			$content .= '</div>';
			$content .= '</div>';
		break;
	
	case 'search':
	default:
		$sidebar .= elgg_view('esope/groups/search', $vars);
		
		$content .= '<div class="iris-search-sort">';
			/*
			$num_groups = elgg_get_entities(array('type' => 'group', 'count' => true));
			$content .= '<span class="iris-search-count">' . $num_groups . ' ' . elgg_echo('groups') . '</span>';
			*/
			$order_opt = array(
					'desc' => elgg_echo('groups:order:desc'),
					'asc' => elgg_echo('groups:order:asc'),
					'alpha' => elgg_echo('groups:order:alpha'),
					'popular' => elgg_echo('groups:order:popular'),
				);
			$content .= '<span class="iris-search-order">' . elgg_echo('members:order:select') . ' ' . elgg_view('input/select', array('name' => 'iris_groups_search_order', 'options_values' => $order_opt, 'value' => get_input('order_by'))) . '</span>';
		$content .= '</div>';
		//$content .= '<div id="esope-search-results">' . elgg_echo('esope:search:nosearch') . '</div>';
		$content .= '<div id="esope-search-results"></div>';
		
}
if ($filter != 'search') { elgg_pop_context(); }


if ($filter == 'search') {
	$body = elgg_view_layout('iris_search', array(
		'title' => $title,
		'content' => $content,
		'sidebar' => $sidebar,
		'q' => get_input('q'),
		'filter' => 'search',
	));
} else {
	$body = elgg_view_layout('iris_listing', array(
		'title' => $title,
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => $filter,
	));
}

echo elgg_view_page($title, $body);

