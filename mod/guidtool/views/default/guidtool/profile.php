<?php
/**
 * Elgg GUID Tool
 * 
 * @package ElggGUIDTool
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

$entity_guid = $vars['entity_guid'];
$entity = get_entity($entity_guid);

if (elgg_instanceof($entity)) {
	if ($entity->isEnabled()) {
		// Entity is enabled
		//echo '<p>' . elgg_echo('guidtool:entity:enabled') . '</p>';
		// @TODO disable it ?
	} else {
		// Entity is not enabled (ie. it is hidden)
		echo '<p>' . elgg_echo('guidtool:entity:disabled') . '</p>';
		// @TODO enable it ?
	}
} else {
	echo '<p>' . elgg_echo('guidtool:entity:invalid') . '</p>';
}

echo elgg_view('export/entity', array('entity' => $entity));

