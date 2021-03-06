<?php
/**
 * Group edit form
 *
 * @package ElggGroups
 */

/* @var ElggGroup $entity */
$entity = elgg_extract("entity", $vars, false);
$translation_prefix = '';
$parent_group = elgg_extract("au_subgroup_of", $vars);
if (elgg_instanceof($parent_group, 'group')) {
	$translation_prefix = 'workspace:';
	$vars['au_subgroup_of'] = $parent_group;
}


// context needed for input/access view
elgg_push_context("group-edit");

if (elgg_instanceof($parent_group, 'group')) {
	$disclaimer = elgg_view('cmspages/view', array('pagetype' => 'info-nouvel-espace-de-travail', 'rawcontent' => true));
} else {
	$disclaimer = elgg_view('cmspages/view', array('pagetype' => 'info-nouveau-groupe', 'rawcontent' => true));
}

// build the group profile fields
$profile_fields = elgg_view("groups/edit/profile", $vars);

// build the group access options
$access_fields = elgg_view("groups/edit/access", $vars);

// build the group tools options
$tools_fields = elgg_view("groups/edit/tools", $vars);

// display the save button and some additional form data
$form_footer = '<div class="elgg-foot">';

if ($entity) {
	$form_footer .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $entity->getGUID()));
}

$form_footer .= elgg_view("input/submit", array("value" => elgg_echo("save"), 'class' => "elgg-button elgg-button-submit float-alt"));
$form_footer .= '<div class="clearfloat"></div>';


// EXISTING GROUP EDIT FORM
if ($entity) {
	$form_footer .= '<div class="iris-group-delete">';
		$form_footer .= '<div class="group-delete-label">';
			$form_footer .= '<h3>' . elgg_echo($translation_prefix.'theme_inria:group:delete') . '</h3>';
			$form_footer .= '<p>' . elgg_echo($translation_prefix.'theme_inria:group:delete:details') . '</p>';
		$form_footer .= '</div>';
	
		$delete_url = "action/groups/delete?guid=" . $entity->getGUID();
		$form_footer .= elgg_view("output/url", array(
			"text" => elgg_echo($translation_prefix."groups:delete"),
			"href" => $delete_url,
			"confirm" => elgg_echo($translation_prefix."groups:deletewarning"),
			"class" => "elgg-button elgg-button-delete float-alt",
		));
	$form_footer .= '</div>';
	
	// Admin only
	if (elgg_is_active_plugin('groups_archive') && elgg_is_admin_logged_in()) {
		$form_footer .= '<div class="iris-group-delete">';
		$form_footer .= '<div class="group-delete-label">';
		$form_footer .= '<h3>' . elgg_echo($translation_prefix.'theme_inria:group:archive') . '</h3>';
		$form_footer .= '<p>' . elgg_echo($translation_prefix.'theme_inria:group:archive:details') . '</p>';
		$form_footer .= '</div>';
		$form_footer .= elgg_view('output/url', array(
			'name' => 'groups-archive',
			'text' => elgg_echo($translation_prefix.'groups_archive:archive'),
			'href' => elgg_get_site_url() . "groups-archive?guid={$entity->guid}&enabled=no",
			"class" => "elgg-button elgg-button-delete float-alt",
			'priority' => 800,
			'is_action' => true,
			'confirm' => elgg_echo($translation_prefix.'groups_archive:confirm'),
		));
	$form_footer .= '</div>';
	}
	
}
$form_footer .= '</div>';

elgg_pop_context();

if (elgg_instanceof($entity, 'group')) {
	echo $disclaimer . $profile_fields . $access_fields . $tools_fields . $form_footer;
	return;
}



// ADD NEW GROUP FORM
if (!$parent_group) {
	// Use custom layout for new groups
	$icon_field = '<div class="iris-sidebar-content">
			<label for="icon">
				<i class="fa fa-camera"></i><br />' . elgg_echo($translation_prefix.'groups:icon:inline') . '
			</label>
		</div>';
	
	$banner_field = '<div class="sidebar-alt iris-group-sidebar-alt">
			<div class="iris-group-image-input">
				<label for="banner">
					<i class="fa fa-camera"></i><br />' . elgg_echo($translation_prefix.'groups:banner:inline') . '
				</label>
			</div>
		</div>';
}

echo <<<HTML
<div class="iris-cols form-groups-add">

	<div class="sidebar iris-group-sidebar">
		$icon_field
	</div>

	<div class="elgg-main">
		$disclaimer
		$profile_fields
		$access_fields
		$tools_fields
		$form_footer
	</div>

	$banner_field

</div>
HTML;

