<?php
/**
 * default_icons plugin
 *
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Florian DANIEL aka Facyla
 * @copyright Florian DANIEL aka Facyla 2015
 * @link http://id.facyla.fr/
 */

// Init plugin
elgg_register_event_handler('init', 'system', 'default_icons_init');


/**
 * Init default_icons plugin.
 */
function default_icons_init() {
	
	elgg_extend_view('css', 'default_icons/css');
	
	// Main plugin classes
	elgg_register_library('facyla:elgg:default_icons', elgg_get_plugins_path() . 'default_icons/classes/ElggDefaultIcons.php');
	elgg_load_library('facyla:elgg:default_icons');
	
	// Register PHP libraries
	//elgg_register_library('sebsauvage:vizhash', elgg_get_plugins_path() . 'default_icons/vendor/sebsauvage/vizhash/vizhash_gd.php');
	// Better packaged as classes
	elgg_register_library('exorithm:unique_image', elgg_get_plugins_path() . 'default_icons/classes/ExorithmUniqueImage.php');
	elgg_register_library('sebsauvage:vizhash', elgg_get_plugins_path() . 'default_icons/classes/SebsauvageVizHash.php');
	elgg_register_library('splitbrain:php-ringicon', elgg_get_plugins_path() . 'default_icons/vendors/splitbrain/RingIcon.php');
	elgg_register_library('tiborsaas:ideinticon', elgg_get_plugins_path() . 'default_icons/vendors/tiborsaas/Ideinticon/identicon.class.php');
	
	// Load a PHP library (can also be loaded from the page_handler or from specific views)
	//elgg_load_library('sebsauvage:vizhash');
	//elgg_load_library('splitbrain:php-ringicon');
	
	
	// Hooks so we can override default icons
	//elgg_register_plugin_hook_handler();
	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'default_icons_user_hook', 1000);
	
	
	/* Some useful elements :
	
	// Register actions
	// Actions should be defined in actions/default_icons/action_name.php
	$action_base = elgg_get_plugins_path() . 'systems_game/actions/';
	elgg_register_action('systems_game/edit', $action_base . 'edit.php');
	elgg_register_action('systems_game/delete', $action_base . 'delete.php');
	
	
	// Register a view to simplecache
	// Useful for any view that do not change, usually CSS or JS or other static data or content
	elgg_register_simplecache_view('css/default_icons');
	$css_url = elgg_get_simplecache_url('css', 'default_icons');
	
	// Register JS script - use with : elgg_load_js('default_icons');
	$js_url = elgg_get_plugins_path() . 'default_icons/vendors/default_icons.js';
	elgg_register_js('default_icons', $js_url, 'head');
	
	// Register CSS - use with : elgg_load_css('default_icons');
	$css_url = elgg_get_plugins_path() . 'default_icons/vendors/default_icons.css';
	elgg_register_css('default_icons', $css_url, 500);
	
	// Get a plugin setting
	$setting = elgg_get_plugin_setting('setting_name', 'default_icons');
	
	// Get a user plugin setting (makes sense only if logged in)
	if (elgg_is_logged_in()) {
		$user_guid = elgg_get_logged_in_user_guid();
		$usersetting = elgg_get_plugin_user_setting('user_plugin_setting', $user_guid, 'default_icons');
	}
	
	// Register hook - see /admin/develop_tools/inspect?inspect_type=Hooks
	elgg_register_plugin_hook_handler('login', 'user', 'default_icons_somehook');
	
	// Register event - see /admin/develop_tools/inspect?inspect_type=Events
	elgg_register_event_handler('create','object','default_icons_someevent');
	
	// Override icons
	elgg_register_plugin_hook_handler("entity:icon:url", "object", "default_icons_icon_hook");
	
	// override the default url to view a default_icons object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'default_icons_set_url');
	
	*/
	
	// Register a page handler on "default_icons/"
	elgg_register_page_handler('default_icons', 'default_icons_page_handler');
	
	
}


// Include page handlers, hooks and events functions
/*
include_once(elgg_get_plugins_path() . 'default_icons/lib/default_icons/hooks.php');
include_once(elgg_get_plugins_path() . 'default_icons/lib/default_icons/events.php');
include_once(elgg_get_plugins_path() . 'default_icons/lib/default_icons/functions.php');
*/


// Page handler
// Loads pages located in default_icons/pages/default_icons/
function default_icons_page_handler($page) {
	$base = elgg_get_plugins_path() . 'default_icons/pages/default_icons';
	switch ($page[0]) {
		/*
		case 'view':
			set_input('guid', $page[1]);
			include "$base/view.php";
			break;
		*/
		// Display icon based on URL parameters
		case 'icon': set_input('action', 'render');
		default:
			if (!empty($page[1])) { set_input('seed', $page[1]); }
			// This will not remain
			if (!empty($page[2])) { set_input('algorithm', $page[2]); }
			if (!empty($page[3])) { set_input('width', $page[3]); }
			if (!empty($page[4])) { set_input('num', $page[4]); }
			if (!empty($page[5])) { set_input('mono', $page[5]); }
			include "$base/index.php";
	}
	return true;
}


/**
 * Replaces a default user icon by an auto-generated one
 * Note : to override all user icons, register a new hook in your plugin with a proper priority and overriding rules
 * Caution : as is, this hook cannot detect cases where icon is set but not available (this will default to '_graphics/icons/default/')
 */
/* Notes : 
 * '_graphics/icons/user/' correspond à une icône non définie
 * en cas d'erreur, on a l'icône par défaut '_graphics/icons/default/' qui correspond à une icône définie mais non trouvée
 */
function default_icons_user_hook($hook, $type, $return, $params) {
	static $algorithm = false;
	static $enabled = false;
	if (!$algorithm) {
		$enabled = elgg_get_plugin_setting('default_user');
		if ($enabled != 'no') {
			$enabled = true;
			$algorithm = elgg_get_plugin_setting('default_user_alg');
			$algorithm_opt = default_icons_get_algorithms();
			if (!isset($algorithm_opt[$algorithm])) { $algorithm = 'ringicon'; }
		} else {
			$enabled = false;
		}
	}
	// Detect default icon (but cannot use file_exists because it's an URL)
	if ($enabled && (strpos($return, '_graphics/icons/user/') !== false)) {
		// GUID seed will ensure static result on a single site (so an entity with same GUID on another site will have the same rendering)
		// Username-based seed enables portable avatar on other sites
		$seed = $params['entity']->guid;
		$size = $params['size'];
		$icon_sizes = elgg_get_config('icon_sizes');
		$img_base_url = elgg_get_site_url() . "default_icons/icon?seed=$seed";
		if (!isset($icon_sizes[$size])) { $size = 'medium'; }
		/*
		if (!empty($num)) $img_base_url .= "&num=$num";
		if (!empty($background)) $img_base_url .= "&background=$background";
		if (!empty($mono)) $img_base_url .= "&mono=$mono";
		*/
		$img_base_url .= "&algorithm=$algorithm";
		$img_base_url .= '&width=' . $icon_sizes[$size]['w'];
		return $img_base_url;
	}
	
	return $return;
}


// Returns list of available algorithms
function default_icons_get_algorithms() {
	return ['ringicon' => "RingIcon", 'vizhash' => "VizHash", 'unique_image' => "Exorithm Unique image", 'ideinticon' => "Ideinticon"];
}



