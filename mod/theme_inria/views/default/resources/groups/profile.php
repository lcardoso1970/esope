<?php

// Determine current workspace (page_owner, potentially a subgroup)
$guid = elgg_extract('group_guid', $vars);
$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	register_error('groups:error:invalid');
	forward();
}
elgg_set_page_owner_guid($guid);
// Determine main group
$main_group = theme_inria_get_main_group($group);



// turn this into a core function
global $autofeed;
$autofeed = true;
$url = elgg_get_site_url();
elgg_push_context('group_profile');
elgg_entity_gatekeeper($guid, 'group');
elgg_push_breadcrumb($group->name);

groups_register_profile_buttons($group);


$content = '';
$sidebar = '';
$sidebar_alt = '';

if (elgg_group_gatekeeper(false)) {
	
	// Workspaces tabs
	$workspaces_tabs = '';
	// Main workspace
	$workspaces_tabs .= '<div class="group-workspace-tabs"><ul class="elgg-tabs elgg-htabs">';
		if ($group->guid == $main_group->guid) { $workspaces_tabs .= '<li class="elgg-state-selected">'; } else { $workspaces_tabs .= '<li>'; }
		$workspaces_tabs .= '<a href="' . $main_group->getURL() . '">' . elgg_echo('theme_inria:workspace') . '<br />' . $main_group->name . '</a></li>';
		// Onglets des sous-groupes
		// Note : on prend tous les sous-groupes qq soit le niveau - mais on ne pourra créer de nouveaux sous-groupes qu'au 1er niveau
		$all_subgroups_guids = AU\SubGroups\get_all_children_guids($main_group);
		if ($all_subgroups_guids) {
			foreach($all_subgroups_guids as $guid) {
				$ent = get_entity($guid);
				if ($ent->guid == $group->guid) { $workspaces_tabs .= '<li class="elgg-state-selected">'; } else { $workspaces_tabs .= '<li>'; }
				$workspaces_tabs .= '<a href="' . $ent->getURL() . '">' . elgg_echo('theme_inria:workspace') . '<br />' . $ent->name . '</a></li>';
			}
		}
	$workspaces_tabs .= '</ul></div>';
	
	
	// Compose content
	$content .= $workspaces_tabs;
	
	$content .= '<div class="group-workspace-main">';
		
		$content .= elgg_view('theme_inria/groups/profile_info', array('group' => $group));
		
	$content .= '</div>';
	
	
	// Autres espaces de travail : on va sur l'URL de l'espace de travail correspondant (seul les onglets sont communs)
	/*if ($subgroups) {
		foreach($subgroups as $subgroup) {
			$content .= '<div class="group-workspace-X">';
				$content .= '<div class="group-workspace-about">';
				$content .= '<h3>A propos</h3>';
				$content .= '<p>' . $subgroup->description . '</p>';
				$content .= '</div>';
				$content .= '<h3>Propriétaire / Responsables (X)</h3>';
				$content .= '<p>' . 'XXX XXX XXX' . '</p>';
				$content .= '<h3>Tous les membres</h3>';
				$content .= '<p>' . 'XXX XXX XXX' . '</p>';
				//$content .= '<h3>Invitations en attente (X)</h3>';
				//$content .= elgg_view('groups/invitationrequests');
				$content .= '<h3>Demandes d\'adhésion en attente (X)</h3>';
				$requests = elgg_get_entities_from_relationship(array('type' => 'user', 'relationship' => 'membership_request', 'relationship_guid' => $subgroup->guid, 'inverse_relationship' => true));
				$content .= elgg_view('groups/membershiprequests', array('requests' => $requests));
				$content .= '<p>' . 'XXX XXX XXX' . '</p>';
			$content .= '</div>';
		}
	}
	*/
	
	
	$content .= $workspaces_tabs;
	
	// Activité (sociale)
	$content .= '<div class="group-workspace-main">';
		
		$content .= elgg_view('theme_inria/groups/profile_activity', array('group' => $group));
		
	$content .= '</div>';
	
	
	
	// Config
	$sidebar .= elgg_view('theme_inria/groups/sidebar', $vars);
	
	
	// Membres : total et en ligne
	$sidebar_alt .= '<h3>' . elgg_echo('members') . '</h3>';
	$sidebar_alt .= '<div class="group-members-count">' . $group->getMembers(array('count' => true)) . '</div>';
	$sidebar_alt .= '<h3>' . elgg_echo('members:online') . '</h3>';
	$sidebar_alt .= elgg_view('groups/sidebar/online_groupmembers', array('entity' => $group));
	
	
}





$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'sidebar-alt' => $sidebar_alt,
	'title' => $group->name,
);
$body = elgg_view_layout('iris_group', $params);

echo elgg_view_page($group->name, $body);
