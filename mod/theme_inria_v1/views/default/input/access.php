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
 *
 * @uses $vars['entity_type']            Optional. Type of the entity
 * @uses $vars['entity_subtype']         Optional. Subtype of the entity
 * @uses $vars['container_guid']         Optional. Container GUID of the entity
 * @usee $vars['entity_allows_comments'] Optional. (bool) whether the entity uses comments - used for UI display of access change warnings
 *
 */

// bail if set to a unusable value
if (isset($vars['options_values'])) {
	if (!is_array($vars['options_values']) || empty($vars['options_values'])) {
		return;
	}
}

// Esope : update access select options depending on context / settings
$remove_access_levels = array();
// Standard cases = read/write access + group visibility and membership
// vis = visibilité du groupe, membership = adhésion au groupe
$standard_cases = array('access_id', 'write_access_id', 'vis', 'membership');
// Content cases = read and write access
$content_cases = array('access_id', 'write_access_id');
// Do not modify cases = when access is used with very custom values that should not be too much tweaked...
// Group membership is not a standard access level - rather an access setting
$donotmodify_cases = array('membership');

// Do we have a real value ?
$no_current_value = false;
if (!isset($vars['value']) || ($vars['value'] == ACCESS_DEFAULT)) {
	$no_current_value = true;
}

$entity_allows_comments = elgg_extract('entity_allows_comments', $vars, true);
unset($vars['entity_allows_comments']);

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-input-access';

// this will be passed to plugin hooks ['access:collections:write', 'user'] and ['default', 'access']
$params = array();

$keys = array(
	'entity' => null,
	'entity_type' => null,
	'entity_subtype' => null,
	'container_guid' => null,
	'purpose' => 'read',
);
foreach ($keys as $key => $default_value) {
	$params[$key] = elgg_extract($key, $vars, $default_value);
	unset($vars[$key]);
}

/* @var ElggEntity $entity */
$entity = $params['entity'];

if ($entity) {
	$params['value'] = $entity->access_id;
	$params['entity_type'] = $entity->type;
	$params['entity_subtype'] = $entity->getSubtype();
	$params['container_guid'] = $entity->container_guid;

	if ($entity_allows_comments && ($entity->access_id != ACCESS_PUBLIC)) {
		$vars['data-comment-count'] = (int) $entity->countComments();
		$vars['data-original-value'] = $entity->access_id;
	}
}

$container = elgg_get_page_owner_entity();
if (!$params['container_guid'] && $container) {
	$params['container_guid'] = $container->guid;
}

// don't call get_default_access() unless we need it
if ($no_current_value) {
	if ($entity) {
		$vars['value'] = $entity->access_id;
	} else if (empty($vars['options_values']) || !is_array($vars['options_values'])) {
		$vars['value'] = get_default_access(null, $params);
	} else {
		$options_values_ids = array_keys($vars['options_values']);
		$vars['value'] = $options_values_ids[0];
	}
}

$params['value'] = $vars['value'];

// don't call get_write_access_array() unless we need it
// Esope : do not set access list if any value has been passed (= it is forced)
//if (!isset($vars['options_values']) && $no_current_value) {
// Note : not loading write access when value is set blocks editing access level afterwards
if (!isset($vars['options_values'])) {
	$vars['options_values'] = get_write_access_array(0, 0, false, $params);
}
/* Esope: the default feature is unclear and even misleading to users due to improper translation
 * It doesn't default the value, nor limit the available access levels...
 */
// Inria : restricted access => DO NOT force to same level as group, only default
// Eg. use case when a private group publishes a public newsletter...
$restricted_content_access = false;
// should we tell users that public/logged-in access levels will be ignored?
if (($container instanceof ElggGroup)
	&& $container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY
	&& !elgg_in_context('group-edit')
	&& !($entity instanceof ElggGroup)) {
	$show_override_notice = true;
	$restricted_content_access = true;
	// Inria : always add container access level
	$vars['options_values'][$container->access_id] = get_readable_access_level($container->access_id);
	// Inria : always set default access to container access level
	if ($no_current_value) { $vars['value'] = $container->access_id; }
} else {
	$show_override_notice = false;
}


/* Esope main access tweaks
 * - set some defaults in various contexts
 * - remove some unwanted access levels globally
 */

/* Supprime le niveau d'accès Public => Membres connectés
 * @TODO if we do that, do it only under specific circumstances eg. walled garden,  group settings...
	*/
$walled_garden = elgg_get_config('walled_garden');
if ($walled_garden && !elgg_is_admin_logged_in()) {
	if (isset($vars['options_values'][2]) && in_array($vars['name'], $standard_cases)) { unset($vars['options_values'][2]); }
} else {
	// Inria : Même dans les groupes en accès restreints ou en mode Walled Garden, on veut pouvoir autoriser quelques pages et fichiers publics
	if (!isset($vars['options_values'][2]) && in_array($vars['name'], $standard_cases)) { $vars['options_values'][2] = elgg_echo('esope:access:public'); }
}

/* Auto-update current public value to loggedin / MAJ auto accès Public => Membres
// @TODO auto-update is not something we want to do (use scripting instead if required)
//if (($vars['value'] == 2) && in_array($vars['name'], $standard_cases)) { $vars['value'] = 1; }
*/

// Esope : groups => set defaults and support subgroups
if (elgg_instanceof($container, 'group')) {
	// Useful vars for all group checks
	$group_acl = $container->group_acl;
	
	// Esope : default group content access value (if not set or default)
	if ($no_current_value) {
		if (in_array($vars['name'], $content_cases)) {
			// Define default group content access method
			if ($container->membership == 2) {
				$defaultaccess = elgg_get_plugin_setting('opengroups_defaultaccess', 'esope');
				if (empty($defaultaccess)) { $defaultaccess = 'groupvis'; }
			} else {
				$closedgroups_defaultaccess = elgg_get_plugin_setting('closedgroups_defaultaccess', 'esope');
				if (empty($defaultaccess)) { $defaultaccess = 'group'; }
			}
			// If access policy says group only, always default to group acl
			if ($restricted_content_access) {
				$defaultaccess = 'group';
			}
			// Now set default content access value
			switch($defaultaccess) {
				case 'group': $vars['value'] = $group_acl; break;
				case 'groupvis': $vars['value'] = $container->access_id; break;
				case 'members': $vars['value'] = 1; break;
				case 'public': $vars['value'] = 2; break;
				case 'default': /* Do not set (let original check do it) $vars['value'] = get_default_access(); */ break;
				default: $vars['value'] = $group_acl;
			}
			// Add default to available options if needed
			if (!isset($vars['options_values'][$vars['value']])) { $vars['options_values'][$vars['value']] = get_readable_access_level($vars['value']); }
		}
	}
	
	// Subgroups : add all parents groups access ids
	if (elgg_is_active_plugin('au_subgroups')) {
		$group = $container;
		$parent_level = 1;
		while($parent = AU\SubGroups\get_parent_group($group)) {
			//$vars['options_values'][$parent->group_acl] = $parent->name . " ($parent_level)";
			$vars['options_values'][$parent->group_acl] = elgg_echo('esope:subgroups:access:parent', array($parent->name, $parent_level));
			$group = $parent;
			$parent_level++;
		}
	}
	
}


// Esope : suppression de certains niveaux d'accès / remove some unwanted access levels
// Permet de n'autoriser que certains niveaux aux membres et admins
// Note : has to be defined after write access array
if (!in_array($vars['name'], $donotmodify_cases)) {
	// Admin and members settings
	if (!elgg_is_admin_logged_in()) {
		// Exclude member access levels
		$user_exclude_access = elgg_get_plugin_setting('user_exclude_access', 'esope');
		if (!empty($user_exclude_access)) { $remove_access_levels = explode(',', $user_exclude_access); }
	} else {
		// Exclude some admin access levels
		$admin_exclude_access = elgg_get_plugin_setting('admin_exclude_access', 'esope');
		if (!empty($admin_exclude_access)) { $remove_access_levels = explode(',', $admin_exclude_access); }
	}
	// If access policy says group only, let's at least default to group acl
	/* Note : this is done through a hook
	if ($restricted_content_access) {
		// Remove unwanted access levels
		$remove_access_levels[] = '2';
		$remove_access_levels[] = '1';
		array_unique($remove_access_levels);
	}
	*/
	if (is_array($remove_access_levels)) {
		foreach ($remove_access_levels as $key) {
			//echo "$key => {$vars['options_values'][$key]}<br />";
			if (isset($vars['options_values'][$key])) { unset($vars['options_values'][$key]); }
		}
	}
}

if (!isset($vars['disabled'])) {
	$vars['disabled'] = false;
}

// if access is set to a value not present in the available options, add the option
if (!isset($vars['options_values'][$vars['value']])) {
	//$acl = get_access_collection($vars['value']);
	//$display = $acl ? $acl->name : elgg_echo('access:missing_name');
	//$vars['options_values'][$vars['value']] = $display;
	// Esope : this is more clear (displays Limited if no access to collection)
	$vars['options_values'][$vars['value']] = get_readable_access_level($vars['value']);
}


// should we tell users that public/logged-in access levels will be ignored?
if (($container instanceof ElggGroup)
	&& $container->getContentAccessMode() === ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY
	&& !elgg_in_context('group-edit')
	&& !($entity instanceof ElggGroup)) {
	$show_override_notice = true;
} else {
	$show_override_notice = false;
}

// Esope : never display an empty or invalid access select
if (!is_array($vars['options_values']) || sizeof($vars['options_values']) < 1) { return; }

if ($show_override_notice) {
	$vars['data-group-acl'] = $container->group_acl;
}

// Esope : replace select by access notice if only one option, and only if it is actually a content access level
if ((sizeof($vars['options_values']) > 1) || (is_array($content_cases) && !in_array($vars['name'], $content_cases))) {
	echo elgg_view('input/select', $vars);
} else {
	// Only 1 option, so display access and hide select
	echo elgg_view('output/access', array('value' => $vars['value']));
	echo elgg_view('input/hidden', $vars);
}

if ($show_override_notice) {
	// Esope : Use custom override if there was a previous value
	if (!$no_current_value && !in_array($vars['value'], array('0', $group_acl))) {
		echo elgg_format_element('p', ['class' => 'elgg-text-help'], elgg_echo('esope:access:overridenotice:existingvalue'));
	} else {
		echo elgg_format_element('p', ['class' => 'elgg-text-help'], elgg_echo('esope:access:overridenotice'));
	}
}
