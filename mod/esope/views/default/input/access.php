<?php
/**
 * Elgg access level input
 * Displays a dropdown input field
 *
 * @uses $vars['value']					The current value, if any
 * @uses $vars['options_values'] Array of value => label pairs (overrides default)
 * @uses $vars['name']					 The name of the input field
 * @uses $vars['entity']				 Optional. The entity for this access control (uses access_id)
 * @uses $vars['class']					Additional CSS class
 */

// Esope : exclude some access levels
$user_exclude_access = elgg_get_plugin_setting('user_exclude_access', 'esope');
if (!empty($user_exclude_access)) $user_exclude_access = explode(',', $user_exclude_access);
$admin_exclude_access = elgg_get_plugin_setting('admin_exclude_access', 'esope');
if (!empty($admin_exclude_access)) $admin_exclude_access = explode(',', $admin_exclude_access);


if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-access {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-access";
}

$defaults = array(
	'disabled' => false,
	'value' => get_default_access(),
	'options_values' => get_write_access_array(),
);

/* @var ElggEntity $entity */
$entity = elgg_extract('entity', $vars);
unset($vars['entity']);

// should we tell users that public/logged-in access levels will be ignored?
$container = elgg_get_page_owner_entity();
if (($container instanceof ElggGroup)
		&& $container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY
		&& !elgg_in_context('group-edit')
		&& !($entity && $entity instanceof ElggGroup)) {
	$show_override_notice = true;
} else {
	$show_override_notice = false;
}

if ($entity) {
	$defaults['value'] = $entity->access_id;
}

$vars = array_merge($defaults, $vars);


// Facyla : custom access lists depending on context / settings
$content_cases = array('access_id', 'write_access_id');
$standard_cases = array('access_id', 'write_access_id', 'vis'); // vis = visibilité des groupes
/*
// Supprime le niveau d'accès Public => Membres connectés
if (isset($vars['options_values'][2]) && in_array($vars['name'], $standard_cases)) { unset($vars['options_values'][2]); }
// Change tous les accès Public => Membres connectés
if (($vars['value'] == 2) && in_array($vars['name'], $standard_cases)) { $vars['value'] = 1; }
*/
// Même en Walled Garden, on veut pouvoir autoriser quelques pages publiques
if (!isset($vars['options_values'][2]) && in_array($vars['name'], $standard_cases)) { $vars['options_values'][2] = elgg_echo('esope:access:public'); }

// Facyla : Content gains plugin-set default access to group - only when no value specified
if (!isset($vars['value']) || ($vars['value'] == '-1')) {
	$page_owner = elgg_get_page_owner_entity();
	if (elgg_instanceof($page_owner, 'group') && in_array($vars['name'], $content_cases)) {
		// Default group access
		if ($page_owner->membership == 2) {
			$defaultaccess = elgg_get_plugin_setting('opengroups_defaultaccess', 'esope');
			if (empty($defaultaccess)) $defaultaccess = 'groupvis';
		} else {
			$closedgroups_defaultaccess = elgg_get_plugin_setting('closedgroups_defaultaccess', 'esope');
			if (empty($defaultaccess)) $defaultaccess = 'group';
		}
		switch($defaultaccess) {
			case 'group': $vars['value'] = $page_owner->group_acl; break;
			case 'groupvis': $vars['value'] = $page_owner->access_id; break;
			case 'members': $vars['value'] = 1; break;
			case 'public': $vars['value'] = 2; break;
			case 'default': $vars['value'] = get_default_access(); break;
			default: $vars['value'] = $page_owner->group_acl;
		}
	} else {
		$vars['value'] = get_default_access();
	}
}

// Esope : liste d'exclusion des droits
// permet de n'autoriser que certains niveaux aux membres, voire aux admins
if (elgg_is_admin_logged_in()) {
	if (is_array($admin_exclude_access)) {
		foreach ($vars['options_values'] as $key => $val) {
			if (in_array($key, $admin_exclude_access)) { unset($vars['options_values'][$key]); }
		}
	}
} else {
	if (is_array($user_exclude_access)) {
		foreach ($vars['options_values'] as $key => $val) {
			if (in_array($key, $user_exclude_access)) { unset($vars['options_values'][$key]); }
		}
	}
}

if (is_array($vars['options_values']) && sizeof($vars['options_values']) > 0) {
	if ($show_override_notice) {
		$vars['data-group-acl'] = $container->group_acl;
	}
	echo elgg_view('input/select', $vars);
	if ($show_override_notice) {
		echo "<p class='elgg-text-help'>" . elgg_echo('access:overridenotice')  .  "</p>";
	}
}