<?php
/* Hook functions
 * Hooks usually return $result, or other meaningful result
 * $result is passed to the next registered hook
 * $result value may block upcoming registered hooks
 * $params is an array passed by hook triggering code
 
 * See hook triggering code for reference on $result handling and passed $params
 */

function phpoffice_somehook($hook, $type, $result, $params) {
	// Extract some data from $params
	//$entity = elgg_extract("entity", $params);
	
	// @TODO : Perform some tests and actions
	//if (elgg_instanceof($entity, "object")) {}
	
	return $result;
}


/**
 * Format and return the URL for phpoffice.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of phpoffice.
 */
/*
function phpoffice_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'phpoffice')) {
		$friendly_title = elgg_get_friendly_title($entity->title);
		return "phpoffice/view/{$entity->guid}/$friendly_title";
	}
}
*/

/*
// Define object icon : custom or default
function phpoffice_icon_hook($hook, $entity_type, $returnvalue, $params) {
	if (!empty($params) && is_array($params)) {
		$entity = $params["entity"];
		if (elgg_instanceof($entity, "object", "phpoffice")) {
			$size = $params["size"];
			if (!empty($entity->icontime)) {
				$icontime = "{$entity->icontime}";
				$filehandler = new ElggFile();
				//$filehandler->owner_guid = $entity->guid; // Entity-based folder
				$filehandler->owner_guid = $entity->getOwnerGUID(); // Owner-based folder
				$filehandler->setFilename("phpoffice/" . $entity->getGUID() . $size . ".jpg");
				if ($filehandler->exists()) {
					return elgg_get_site_url() . "phpoffice/icon/{$entity->getGUID()}/$size/$icontime.jpg";
				}
			}
			// Use default image instead
			if (!empty($entity->category)) {
				$file_name = $entity->category . '.jpg';
			} else {
				$file_name = 'default.jpg';
			}
			return elgg_get_site_url() . "mod/phpoffice/graphics/icons/$size/$file_name";
		}
	}
}
*/

