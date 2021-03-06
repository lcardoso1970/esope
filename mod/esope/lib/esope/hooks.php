<?php


/* Filtrage via le page_handler
 * 
 * Principes de conception : 
 * - ne filtre que si on l'a explicitement demandé quelque part (pas de modification du comportement par défaut
 * 
*/
function esope_route($hook, $type, $return, $params) {
	$home = elgg_get_site_url();
	
	// Page handler et segments de l'URL
	// Note : les segments commencent après le page_handler (ex.: URL: groups/all donne 0 => 'all')
	$handler = $return['handler'];
	$segments = $return['segments'];
	//echo print_r($segments, true); // debug
	//register_error($handler . ' => ' . print_r($segments, true));
	//error_log('DEBUG externalmembers ROUTE : ' . $handler . ' => ' . print_r($segments, true));
	
	if (!elgg_is_logged_in()) {
		// Il n'y a verrouillage du profil que si cette option est explicitement activée (pour ne pas modifier le comportement par défaut)
		$public_profiles = elgg_get_plugin_setting('public_profiles', 'esope');
		if ($public_profiles == 'yes') {
			if ($handler == 'profile') {
				$username = $segments[0];
				if ($user = get_user_by_username($username)) {
					esope_user_profile_gatekeeper($user);
				}
			}
		}
	}
	
	//	@todo : Pour tous les autres cas => déterminer le handler et ajuster le comportement
	//register_error("L'accès à ces pages n'est pas encore déterminé : " . $handler . ' / ' . print_r($segments, true));
	//error_log("L'accès à ces pages n'est pas encore déterminé : " . $handler . ' / ' . print_r($segments, true));
	
	/* Valeurs de retour :
	 * return false; // Interrompt la gestion des handlers
	 * return $return; // Laisse le fonctionnement habituel se poursuivre
	*/
	// Par défaut on ne fait rien du tout
	return $return;
}

function esope_public_profile_hook($hook, $type, $return, $params){
	$user_guid = (int) get_input('guid');
	$public_profile = get_input('public_profile');
	// On ne modifie que si le réglage global est actif
	// Attention : modifier l'access_id de l'user directement est une *fausse bonne idée* : ça pose de nombreux problème pour s'identifier, etc.
	// Si différent de 2 on ne s'identifie plus...
	$public_profiles = elgg_get_plugin_setting('public_profiles', 'esope');
	if ($public_profiles == 'yes') {
		if (!empty($user_guid) && !empty($public_profile)) {
			if ($user = get_user($user_guid)) {
				if ($user->canEdit()) {
					$user->public_profile = $public_profile;
					if ($user->save()) {
						system_message(elgg_echo('esope:action:public_profile:saved'));
						if ($public_profile == 'yes') {
							system_message(elgg_echo('esope:usersettings:public_profile:public'));
						} else {
							system_message(elgg_echo('esope:usersettings:public_profile:private'));
						}
					} else {
						register_error(elgg_echo('esope:action:public_profile:error'));
					}
				}
			}
		}
	}
}


/**
 * htmLawed filtering of data
 *
 * Called on the 'validate', 'input' plugin hook
 *
 * Triggers the 'config', 'htmlawed' plugin hook so that plugins can change
 * htmlawed's configuration. For information on configuraton options, see
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
 *
 * @param string $hook	 Hook name
 * @param string $type	 The type of hook
 * @param mixed	$result Data to filter
 * @param array	$params Not used
 * @return mixed
 */
function esope_htmlawed_filter_tags($hook, $type, $result, $params) {
	$var = $result;
	elgg_load_library('htmlawed');
	
	// Get plugin settings
	$safe = elgg_get_plugin_setting('safe', 'htmlawed');
	if ($safe == 'yes') $safe = true; else $safe = false;
	$elements = elgg_get_plugin_setting('elements', 'htmlawed');
	$deny_attribute = elgg_get_plugin_setting('deny_attribute', 'htmlawed');
	if ($deny_attribute === false) { $deny_attribute = 'on*'; }
	
	// seems to handle about everything we need.
	$htmlawed_config = array();
	
	/* /!\ Liste blanche des balises autorisées
	 * Accepte des syntaxes positives : 'iframe,embed,object,param,video,script,style'
	 * Ou avec filtre : "* -script"	 => bloque <script> seulement
	 * DOC : http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.3
	 */
	if (!empty($elements)) { $htmlawed_config['elements'] = $elements; }
	
	// true est trop radical, à moins de lister toutes les balises autorisées ci-dessus
	// DOC : http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.6
	$htmlawed_config['safe'] = $safe;
	
	// Attributs interdits
	// DOC : http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s3.4
	$htmlawed_config['deny_attribute'] = $deny_attribute;
	
	// Filtrage supplémentaires des attributs autorisés (cf. start de htmLawed) : 
	// bloque tous les styles non explicitement autorisé
	//$htmlawed_config['hook_tag'] = 'htmlawed_tag_post_processor';
	
	$htmlawed_config['schemes'] = '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto';
	
	// apparent this doesn't work.
	// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
	
	// add nofollow to all links on output
	if (!elgg_in_context('input')) { $htmlawed_config['anti_link_spam'] = array('/./', ''); }
	
	$htmlawed_config = elgg_trigger_plugin_hook('config', 'htmlawed', null, $htmlawed_config);
	
	if (!is_array($var)) {
		$result = htmLawed($var, $htmlawed_config);
	} else {
		array_walk_recursive($var, 'htmLawedArray', $htmlawed_config);
		$result = $var;
	}
	
	return $result;
}




// Permet l'accès à diverses pages en mode "walled garden"
function esope_public_pages($hook, $type, $return, $params) {
	// Add very useful pages !
	$return[] = 'resetpassword.*';
	$return[] = 'uservalidationbyemail.*';
	
	// Get and prepare valid domain config array from plugin settings
	$publicpages = elgg_get_plugin_setting('publicpages', 'esope');
	$publicpages = preg_replace('/\r\n|\r/', "\n", $publicpages);
	// Add csv support - cut also on ";" and ","
	$publicpages = str_replace(array(' ', '<p>', '</p>'), '', $publicpages); // Delete all white spaces
	$publicpages = str_replace(array(';', ','), "\n", $publicpages);
	$publicpages = explode("\n",$publicpages);
	foreach ($publicpages as $publicpage) {
		if (!empty($publicpage)) $return[] = $publicpage;
	}
	/* Les pages à rendre accessibles doivent correspondre à l'URL exacte, ou utiliser le wildcard .*
	$return[] = 'page-publique';
	$return[] = 'page-avec-params.*';
	$return[] = 'rubrique-publique/.*';
	*/
	return $return;
}



function esope_threads_topic_menu_setup($hook, $type, $return, $params){
	//return $return; // Pas besoin d'ajouter le form si on l'affiche d'entrée de jeu
	
	$entity = $params['entity'];
	
	elgg_load_library('elgg:threads');
	elgg_load_js('jquery.plugins.parsequery');
	elgg_load_js('elgg.threads');
	
	$group = $entity->getContainerEntity();
	$topic = threads_top($entity->guid);
	
	// Facyla : on limite ça aux objets : n'a pas de sens pour groupes et membres
	if(elgg_instanceof($entity, 'object') 
		&& ($group && $group->canWriteToContainer() || elgg_is_admin_logged_in()) 
		&& $topic 
		&& $topic->status != 'closed'
		) {
		$url = elgg_http_add_url_query_elements($topic->getURL(), array(
			'box' => 'reply',
			'guid' => $entity->guid,
		));
		//$url .= '#elgg_add_comment_' . $entity->guid;

		$options = array('name' => 'reply', 'href' => $url, 'text' => elgg_echo('reply'),'text_encode' => false, 'priority' => 200);
		$return[] = ElggMenuItem::factory($options);
	}
	return $return;
}


/* Modifications des menus de l'owner_block : sélectionne l'outil utilisé
 * Et supprime certains éléments de menu choisis du menu du membre, si demandé dans la config
 */
function esope_owner_block_menu($hook, $type, $return, $params) {
	// Menu user
	if (elgg_instanceof(elgg_get_page_owner_entity(), 'user')) {
		$remove_user_tools = elgg_get_plugin_setting('remove_user_menutools', 'esope');
		if (!empty($remove_user_tools)) {
			$remove_user_tools = explode(',', $remove_user_tools);
			if ($return) foreach ($return as $key => $item) {
				$name = $item->getName();
				//echo $name . ","; // debug: helps finding name if you don't want to look into each plugin hook
				if (in_array($name, $remove_user_tools)) unset($return[$key]);
				else if (elgg_in_context($name)) $item->setSelected();
			}
		}
	} else {
		if ($return) foreach ($return as $key => $item) {
			if (elgg_in_context($item->getName())) $item->setSelected();
		}
	}
	
	// Tri alphabétique des entrées du menu
	//usort($return, 'esope_menu_alpha_cmp'); 
	return $return;
}
// Tri alphabétique des entrées du menu
// Note : il n'y a normalement qu'un seul menu 'default'
function esope_sort_menu_alpha($hook, $type, $return, $params) {
	foreach ($return as $key => $menu) {
		usort($return[$key], 'esope_menu_alpha_cmp');
	}
	return $return;
}

/* Boutons des widgets */
// @TODO : make this a view, for easier theming..
function esope_elgg_widget_menu_setup($hook, $type, $return, $params) {
	$urlicon = elgg_get_site_url() . 'mod/esope/img/theme/';
	
	$widget = $params['entity'];
	$show_edit = elgg_extract('show_edit', $params, true);
	
	// Use icon, but override with FontAwesome if available
	$widget_title = $widget->getTitle();
	$collapse = array(
			'name' => 'collapse',
			'text' => '<i class="fa fa-caret-square-o-down"></i>',
			'title' => strip_tags(elgg_echo('widget:toggle', array($widget_title))),
			'href' => "#elgg-widget-content-$widget->guid",
			'link_class' => 'elgg-widget-collapse-button masquer',
			'rel' => 'toggle',
			'priority' => 900
		);
	$return[] = ElggMenuItem::factory($collapse);
	
	if ($widget->canEdit()) {
		$delete = array(
				'name' => 'delete',
				'text' => '<i class="fa fa-times"></i>',
				'title' => strip_tags(elgg_echo('widget:delete', array($widget_title))),
				'href' => "action/widgets/delete?widget_guid=" . $widget->guid,
				'is_action' => true,
				'link_class' => 'elgg-widget-delete-button suppr',
				'id' => "elgg-widget-delete-button-$widget->guid",
				'data-elgg-widget-type' => $widget->handler,
				'priority' => 900
			);
		$return[] = ElggMenuItem::factory($delete);

		if ($show_edit) {
			$edit = array(
					'name' => 'settings',
					'text' => '<i class="fa fa-gear"></i>',
					'title' => strip_tags(elgg_echo('widget:edit', array($widget_title))),
					'href' => "#widget-edit-$widget->guid",
					'link_class' => "elgg-widget-edit-button config",
					'rel' => 'toggle',
					'priority' => 800,
				);
			$return[] = ElggMenuItem::factory($edit);
		}
	}
	
	return $return;
}


if (elgg_is_active_plugin('au_subgroups')) {
	/**
	 * re/routes some urls that go through the groups handler
	 */
	// Esope Note : checking for "delete" segment
	function esope_subgroups_groups_router($hook, $type, $return, $params) {
		AU\SubGroups\breadcrumb_override($return);
		
		// subgroup options
		if ($return['segments'][0] == 'subgroups') {
			elgg_load_library('elgg:groups');
			$group = get_entity($return['segments'][2]);
			// ESOPE : also check for delete segment
			if (!elgg_instanceof($group, 'group') || (($group->subgroups_enable == 'no') && ($return['segments'][1] != "delete"))) {
				return $return;
			}
	
			elgg_set_context('groups');
			elgg_set_page_owner_guid($group->guid);
			
			switch ($return['segments'][1]) {
				case 'add':
				$return = array(
					'identifier' => 'au_subgroups',
					'handler' => 'au_subgroups',
					'segments' => array(
						'add',
						$group->guid
					)
				);
				
				return $return;
					break;
					
				case 'delete':
				$return = array(
					'identifier' => 'au_subgroups',
					'handler' => 'au_subgroups',
					'segments' => array(
						'delete',
						$group->guid
					)
				);
				
				return $return;
					break;
				
				case 'list':
				$return = array(
					'identifier' => 'au_subgroups',
					'handler' => 'au_subgroups',
					'segments' => array(
						'list',
						$group->guid
					)
				);
				
				return $return;
				break;
			}
		}
		
		// need to redo closed/open tabs provided by group_tools - if it's installed
		if ($return['segments'][0] == 'all' && elgg_is_active_plugin('group_tools')) {
			$filter = get_input('filter', false);
			
			if(empty($filter) && ($default_filter = elgg_get_plugin_setting("group_listing", "group_tools"))){
				$filter = $default_filter;
				set_input("filter", $default_filter);
			}
			
			if(in_array($filter, array("open", "closed", "alpha"))){
				$return = array(
						'identifier' => 'au_subgroups',
						'handler' => 'au_subgroups',
						'segments' => array(
							'openclosed',
							$filter
						)
					);
				
				return $return;
			}
		}
	}
	
	
	/** Esope version : don't break the whole process for some users
	 * by filtering the invited users
	 * @TODO : allow direct registration through recursive group join ? 
	 * not sure it's a good thing for non-global admins...
	 * Note : this hook is enabled only if au_subgroups is enabled !
	 */
	function esope_au_subgroups_group_invite($hook, $type, $return, $params) {
		$user_guid = get_input('user_guid'); // Can be an array
		$group_guid = get_input('group_guid');
		$group = get_entity($group_guid);
	
		$parent = AU\SubGroups\get_parent_group($group);
	
		// if $parent, then this is a subgroup they're being invited to
		// make sure they're a member of the parent
		if ($parent) {
			if (!is_array($user_guid)) {
				$user_guid = array($user_guid);
			}
		
			/* Invite type check : probably bad idea to allow subgroup owners to register directly to the parent groups...
			$allowregister = elgg_get_plugin_setting('allowregister', 'esope');
			if ($allowregister == 'yes') { $group_register = get_input('group_register', false); }
			*/
		
			$invalid_users = array();
			foreach($user_guid as $guid) {
				// ESOPE : Use less intensive DB check with GUID and not entities...
				//if (is_group_member($parent->guid, $guid)) {
				if (check_entity_relationship($guid, 'member', $parent->guid)) {
					$valid_user_guid[] = $guid;
				} else {
					// We'll need the entity here for proper error display
					if ($user = get_user($guid)) { $invalid_users[] = $user; }
				}
			}
		
			if (count($invalid_users)) {
				// ESOPE : Set new invite valid list
				set_input('user_guid', $valid_user_guid);
			
				// Explain what went wrong with who..
				$error_suffix = "<ul>";
				foreach($invalid_users as $user) {
					$error_suffix .= "<li>{$user->name}</li>";
				}
				$error_suffix .= "</ul>";

				register_error(elgg_echo('au_subgroups:error:invite') . $error_suffix);
				// ESOPE : Do NOT break the invite process (for the valid ones)
				//return false;
			}
		}
		// No need to return anything
		//return $return;
	}
	
}


/* Modification du menu de la page
 * Sélection du menu basée sur l'URL de la page sans paramètre
 * Select menu without using the URL parameters : e.g. /messages/inbox/admin?unread=true will select menu /messages/inbox/admin too
 * Also select parent menus based on page handler : /members/popular will also select /members
 */
function esope_prepare_menu_page_hook($hook, $type, $return, $params) {
	$base_url = explode('?', current_page_url());
	$base_url = $base_url[0];
	if (!empty($base_url)) {
		foreach($return as $menu_block) {
			foreach($menu_block as $element) {
				$href = $element->getHref();
				if (!empty($href)) {
					// Select base menu (based on base url, without parameters)
					if ($href == $base_url) { $element->setSelected(); }
					// Select parent menu (based on page handler sequence)
					if (strpos($base_url, $href) === 0) { $element->setSelected(); }
				}
			}
		}
	}
	return $return;
}


// Adds Favicon link in page head
function esope_page_head_hook($hook, $type, $return, $params) {
	
	// Displays the default shortcut icon
	$favicon = elgg_get_plugin_setting('faviconurl', 'esope');
	$url = elgg_get_site_url();
	if (empty($favicon)) {
		$favicon = $url . '_graphics/favicon.ico';
	} else {
		$favicon = $url . $favicon;
	}
	
	// Set main favicon
	$return['links']['icon-ico'] = array('rel' => 'icon', 'href' => $favicon);
	// Set apple touch icon
	$return['links']['apple-touch-icon'] = array('rel' => 'apple-touch-icon', 'href' => $favicon);
	
	/* @TODO : several enhancements :
	 * - provide setting for each icon (and specify constraints on each)
	 * - check type before adding 'type' property' (can also be GIF, PNG...)
	 * - provide setting for each icon ?
	 * Since then, disable default icons that cannot be controlled by admin
	 *
		$return['links']['icon-16'] = array('rel' => 'icon', 'href' => $favicon, 'sizes' => '16x16', 'type' => 'image/png');
		$return['links']['icon-32'] = array('rel' => 'icon', 'href' => $favicon, 'sizes' => '32x32', 'type' => 'image/png');
		$return['links']['icon-64'] = array('rel' => 'icon', 'href' => $favicon, 'sizes' => '64x64', 'type' => 'image/png');
		$return['links']['icon-128'] = array('rel' => 'icon', 'href' => $favicon, 'sizes' => '128x128', 'type' => 'image/png');
		$return['links']['icon-vector'] = array('rel' => 'apple-touch-icon', 'href' => $url . '_graphics/favicon.svg', 'sizes' => '16x16 32x32 48x48 64x64 128x128', 'type' => 'image/svg+xml');
	 */
	unset($return['links']['icon-16']);
	unset($return['links']['icon-32']);
	unset($return['links']['icon-64']);
	unset($return['links']['icon-128']);
	unset($return['links']['icon-vector']);
	
	// Set print CSS
	$return['links']['stylesheet-print'] = array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => $url . 'mod/esope/print.css', 'media' => 'print');
	
	return $return;
}


// Perform some post-registration actions (join groups, etc.)
function esope_register_user_hook($hook, $type, $return, $params) {
	if (is_array($params)) {
		$user = elgg_extract("user", $params);
		if (elgg_instanceof($user, "user")) {
			if (!$user->isBanned()) {
				$group_guids = get_input('join_groups', false);
				if ($group_guids) {
					// We'll process it later, at first login
					// The input should be an array, but allow comma-separated input as well for extendability reasons
					// e.g. for registration using URL (GET)
					if (!is_array($group_guids)) { $group_guids = explode(',', $group_guids); }
					$user->join_groups = $group_guids;
				}
			}
		}
	}
	// We don't change anything to the process
	return $return;
}


// Redirection après login
function esope_public_forward_login_hook($hook_name, $reason, $location, $parameters) {
	if (!elgg_is_logged_in()) {
		//register_error("TEST : " . $_SESSION['last_forward_from'] . " // " . $parameters['current_url']);
		// Si jamais la valeur de retour n'est pas définie, on le fait
		if (empty($_SESSION['last_forward_from'])) $_SESSION['last_forward_from'] = $parameters['current_url'];
		return elgg_get_site_url() . 'login';
	}
	return null;
}

// Vérification des URL de redirection : si redirection vers un REFERER HTTP externe, forward vers l'accueil
function esope_forward_hook($hook_name, $reason, $location, $parameters) {
	$forward_url = $parameters['forward_url'];
	if ($forward_url == $_SERVER['HTTP_REFERER']) {
		if ((strpos($forward_url, 'http:') === 0) || (strpos($forward_url, 'https:') === 0)) {
			$site_url = elgg_get_site_url();
			if (strpos($forward_url, $site_url) !== 0) {
				return $site_url;
			}
		}
	}
	return null;
}


/* Returns an absolute time
 * $timestamp : the UNIX timestamp
 */
function esope_friendly_time_hook($hook, $type, $return, $params) {
	global $esope_friendly_time_mode;
	global $esope_friendly_time_months;
	//global $esope_friendly_time_format;
	
	$timestamp = $params['time'];
	
	// Mode should default to auto
	if (!isset($esope_friendly_time_mode)) {
		$mode = elgg_get_plugin_setting('friendly_time_mode', 'esope');
	}
	
	// Don't change anything
	if ($mode == 'no') { return $returnvalue; }
	// If we're not forcing, use automatic mode <=> don't change anything if not older than a day
	if (($mode != 'yes') && (time() - $timestamp < 60*60*24)) { return $returnvalue; }
	
	// Otherwise we use our new format
	/*
	if (!isset($esope_friendly_time_format)) {
		$format = elgg_get_plugin_setting('friendly_time_format', 'esope');
		if (empty($format)) $format = elgg_echo('date:format:friendly');
		$date = date('d/m/Y H:i', $timestamp);
	}
	*/
	
	if (!isset($esope_friendly_time_months)) {
		$$esope_friendly_time_months = array();
		for ($i=1; $i<=12; $i++) {
			$esope_friendly_time_months[$i] = elgg_echo("date:month:$i");
		}
	}
	
	// Compose date
	$date = elgg_echo('date:time:on') . ' ' . date('j', $timestamp);
	
	$month = date('n', $timestamp);
	$date .= ' ' . strtolower($esope_friendly_time_months[$month]);
	
	$date .= ' ' . date('Y', $timestamp);
	/*
	$date .= ' ' . elgg_echo('date:time:at') . ' ';
	$date .= date('H:i', $timestamp);
	*/
	return $date;
}




// Menu that appears on hovering over a user profile icon
function esope_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	
	// Allow admins to perform some new actions, except only to other admins
	if (elgg_is_admin_logged_in()) {
		if (!($user->isAdmin())) {
			// Email removal is limited to non-valid LDAP users, only if they have a non-empty email
			if (!empty($user->email)){
				$url = "action/admin/remove_user_email?guid=" . $user->getGUID();
				$title = elgg_echo("esope:action:remove_user_email");
				$item = new ElggMenuItem('remove_user_email', $title, $url);
				$item->setSection('admin');
				$item->setConfirmText(elgg_echo("esope:removeemail:areyousure", array($user->email)));
				$return[] = $item;
			}
			// @TODO : Archive user can only apply if not archived yet
			// @TODO : Un-archive can be useful too
		}
		return $return;
	}
}


// Add the regular group search method to main search
function esope_search_groups_hook($hook, $type, $value, $params) {
	$q = sanitise_string($params['query']);
	$dbprefix = elgg_get_config('dbprefix');
	$limit = sanitise_int(get_input('limit', 10));
	
	$params = array(
			'types' => 'group',
			'joins' => array("JOIN {$dbprefix}groups_entity as ge ON e.guid = ge.guid"),
			'wheres' => array("(ge.name LIKE '$q%' OR ge.name LIKE '% $q%' OR ge.description LIKE '%$q%')"),
			'count' => true,
		);
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ge', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);
	
	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$name = search_get_highlighted_relevant_substrings($entity->name, $q);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $q);
		$entity->setVolatileData('search_matched_description', $description);
	}
	
	return array(
		'entities' => $entities,
		'count' => $count,
	);
}



/* NOTIFICATIONS */

// Provides a wrapper around _elgg_send_email_notification so it listens for $result and blocks sending, and also enables to pass params when sending the email
// Note : we could also provide a blocking hook, but other plugins can register to the same hook to block notifications, so why bother...
function esope_elgg_send_email_notification($hook, $type, $result, $params) {
	
	// Do not notify again if email notification already sent ?
	if ($result === true) {
		//error_log("DEBUG ESOPE elgg_send_email_notification : already sent");
		return true;
	}
	
	// Trigger hook to add custom params (eg. attachments)
	// @TODO replace by using prepare hook
	//$params = elgg_trigger_plugin_hook('notify:entity:params', 'object', $params, $params);
	
	// Trigger blocking hook
	// @TODO blocking hook should be global, otherwise better use the recipients hook to update the list
	
	return _elgg_send_email_notification($hook, $type, $result, $params);
}



// @TODO See http://reference.elgg.org/1.12/notification_8php_source.html#l00593 for $params
/**
 * Send an email notification
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has anyone sent a message yet?
 * @param array  $params Hook parameters
 * @return bool
 * @access private
 */
function esope_block_email_recipients($hook, $type, $result, $params) {
	/*
	echo '<pre>' . print_r($handler, true) . '</pre>';
	echo '<pre>' . print_r($from, true) . '</pre>';
	echo '<pre>' . print_r($to, true) . '</pre>';
	echo '<pre>' . print_r($subject, true) . '</pre>';
	echo '<pre>' . print_r($body, true) . '</pre>';
	echo '<pre>' . print_r($params, true) . '</pre>';
	*/
	// Trigger hook to enable email blocking for plugins which handle email control through eg. roles or status
	// Note : better avoid using the same hook because it requires to build the exact same params as in elgg_send_email, 
	// but other plugins may have changed them before (other sender, etc.), so let's just use another one.

	// @TODO : is the hook used by any plugin ? otherwise we can use recipients hook, or this one directly
	//$result = elgg_trigger_plugin_hook('email_block', 'system', array('to' => $to, 'from' => $from, 'subject' => $subject, 'body' => $body, 'params' => $params), null);
	return $result;
}


/**
 * Prepare a notification message about a new wire post
 * ESOPE : add support for group Wire messages
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function esope_thewire_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];
	$descr = $entity->description;
	$container = $entity->getContainerEntity();
	
	$user_wire_url = elgg_get_site_url() . 'thewire/owner/' . $owner->guid;
	$group_wire_url = false; // Group Wire URL + false if no group container
	if (elgg_instanceof($container, 'group')) {
		$group_wire_url = elgg_get_site_url() . 'thewire/group/' . $container->guid;
	}
	
	// Message subject
	$subject = elgg_echo('thewire:notify:subject', array($owner->name), $language);
	if ($entity->reply) {
		$parent = thewire_get_parent($entity->guid);
		if ($parent) {
			$parent_owner = $parent->getOwnerEntity();
			if ($group_wire_url) {
				$body = elgg_echo('thewire:notify:group:reply', array($owner->name, $parent_owner->name, $container->name), $language);
			} else {
				$body = elgg_echo('thewire:notify:reply', array($owner->name, $parent_owner->name), $language);
			}
		}
	} else {
		if ($group_wire_url) {
			$body = elgg_echo('thewire:notify:group:post', array($owner->name, $container->name), $language);
		} else {
			$body = elgg_echo('thewire:notify:post', array($owner->name), $language);
		}
	}
	
	// Message body
	$body .= "\n\n" . $descr . "\n\n";
	$body .= elgg_echo('thewire:notify:footer', array($entity->getURL()), $language);
	// Container Wire messages link
	if ($group_wire_url) {
		$body .= "\n" . elgg_echo('thewire:notify:footer:group', array($group_wire_url), $language);
	} else {
		$body .= "\n" . elgg_echo('thewire:notify:footer:user', array($owner-name, $user_wire_url), $language);
	}
	
	$notification->subject = $subject;
	$notification->body = $body;
	if ($group_wire_url) {
		$notification->summary = elgg_echo('thewire:notify:group:summary', array($container->name, $descr), $language);
	} else {
		$notification->summary = elgg_echo('thewire:notify:summary', array($descr), $language);
	}

	return $notification;
}


/* Event_calendar enhancements */

// Add .ics file attachment to email notification params
// Note : $params == $returnvalue (see http://reference.elgg.org/1.12/notification_8php_source.html#l00627)
function esope_event_calendar_add_attachment_params($hook, $type, $returnvalue, $params) {
	// Another hook handler returned a non-array, let's not override it
	if (!is_array($returnvalue)) { return; }
	
	// Note : ne pas utiliser la méthode ($object = elgg_extract('object', $params['params']);) :
	// Elle semble OK en théorie mais elle ne fonctionne correctement que pour le 1er destinataire...
	
	/** @var Elgg\Notifications\Notification */
	$notification = elgg_extract('notification', $params['params']);
	if ($notification instanceof Elgg\Notifications\Notification) {
		$object = elgg_extract('object', $notification->params);
		//$object = elgg_extract('object', $params['params']);
		if (elgg_instanceof($object, 'object', 'event_calendar')) {
			$mimetype = 'text/calendar';
			$filename = 'event.ics';
			$file_content = elgg_view('event_calendar/attached_event_calendar', array('entity' => $object));
			$file_content = elgg_view('event_calendar/attached_event_calendar_wrapper', array('body' => $file_content));
			$file_content = chunk_split(base64_encode($file_content));
			$returnvalue['params']['attachments'][] = array('mimetype' => $mimetype, 'filename' => $filename, 'content' => $file_content);
		}
	}
	return $returnvalue;
}

/** Prepare the message content
 * Returns a more meaningful message for events
 * Note : no-hook function because we need to be able to use it with the 
 * 
 * @param ElggObject $entity
 * @param ElggUser $to_entity
 * @param string $language
 */
function esope_event_calendar_ics_notify_message($entity, $params) {
	if (elgg_instanceof($entity, 'object', 'event_calendar')) {
		if (!$params['language']) { $params['language'] = get_current_language(); }
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		$ics_file_details = ''; // @TODO : add a message for attached files ?
		return elgg_echo('event_calendar:ics:notification', array(
				$owner->name,
				$title,
				$descr,
				$entity->getURL(),
				$ics_file_details,
			), $language);
	}
	return false;
}

// Overrides the default notification message
function esope_event_calendar_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$language = $params['language'];

	// Title for the notification
	//$notification->subject = elgg_echo('event_calendar:notify:subject', array($entity->title), $language);
	
	// Message body for the notification
	$notification->body = esope_event_calendar_ics_notify_message($entity, array('to_entity' => $to_entity, 'language' => $language));
	
	// The summary text is used e.g. by the site_notifications plugin
	//$notification->summary = elgg_echo('event_calendar:notify:summary', array($entity->title), $language);
	
	return $notification;
}



/**
 * Get any entity that matches a query that looks like a GUID
 * Note : we need to display this result only once, in the proper subtype
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function esope_search_guid_hook($hook, $type, $value, $params) {
	static $entity = false;
	static $searched_type = null;
	static $searched_subtype = null;
	
	$query = sanitise_string($params['query']);
	
	// Is there any entity under that GUID ?
	if ($entity === false) {
		$entity = null;
		if (ctype_digit($query)) {
			$ent = get_entity($query);
			if (elgg_instanceof($ent)) {
				$entity = $ent;
				// Also determine search type and subtype
				$entity_type = get_input('entity_type', '');
				if (!empty($entity_type)) { $searched_type = $entity_type; }
				$entity_subtype = get_input('entity_subtype', '');
				if (!empty($entity_subtype)) { $searched_subtype = $entity_subtype; }
			}
		}
		//error_log("Entity found : {$entity->getType()} / {$entity->getSubtype()}       $searched_type, $searched_subtype");
	}
	// Shortcut if no matching entity
	if (!$entity) { return $value; }
	
	// Check triggered hook for handled types and determine subtype
	$subtype = '';
	if (strpos($type, ':') !== false) {
		$types = explode(':', $type);
		$type = $types[0];
		$subtype = $types[1];
	}
	// Handle only regular search types
	if (!in_array($type, array('object', 'user', 'group', 'site'))) { return $value; }
	
	// Exclude cases where an unproper hook is used (coherence between subtype search and search hook subtype filter)
	// Otherwise : duplicates when searching for object type (only)
	if (!empty($subtype) && ($entity->getSubtype() != $subtype)) { return $value; }
	if (!is_null($searched_subtype) && ($entity->getSubtype() != $searched_subtype)) { return $value; }
	
	// Return only results from the proper hook
	if (!empty($subtype) && elgg_instanceof($entity, $type, $subtype)) {
		switch($entity->getType()) {
			case 'user':
			case 'group':
			case 'site':
				$title = $entity->name;
				break;
			case 'object':
			default:
				$title = $entity->title;
		}
		$title = search_get_highlighted_relevant_substrings("$title (GUID $query)", $params['query']);
		$entity->setVolatileData('search_matched_title', $title);
		
		// Add to previous values (never replace previous results !)
		if ($value) {
			$value['entities'][] = $entity;
			$value['count'] = $value['count']+1;
		} else {
			$value = array(
				'entities' => array($entity),
				'count' => 1,
			);
		}
	}
	return $value;
}



// Cas particulier si contenu public dans groupe privé : changement de layout ?
/*
function esope_layout_hook($hook, $type, $value, $params) {
	$page_owner = elgg_get_page_owner_entity();
	if (elgg_instanceof($page_owner, 'group') && !has_access_to_entity($page_owner)) {
		// Remove group page owner
		elgg_set_page_owner_guid(1);
		// Update breadcrumbs
		global $CONFIG;
		foreach ($CONFIG->breadcrumbs as $k => $breadcrumb) {
			if (strpos($breadcrumb['link'], 'groups/') !== false) { unset($CONFIG->breadcrumbs[$k]); }
		}
		$value = 'one_column';
	}
	return $value;
}
*/


// Overrides function email
// Replacement function is defined in lib/esope/htmlt_email_handler.php
function esope_html_email_handler_emailHandler($hook, $type, $return_value, $params) {
	static $plugin_setting;
	
	if (!isset($plugin_setting)) {
		$plugin_setting = false;
	
		// do we need to handle sending of mails?
		if (elgg_get_plugin_setting('notifications', 'html_email_handler') === 'yes') {
			$plugin_setting = true;
		}
	}
	
	if (!$plugin_setting) {
		return ;
	}
	
	// if someone else handled sending they should return true|false
	if (empty($return_value) || !is_array($return_value)) {
		return;
	}
	
	$additional_params = elgg_extract('params', $return_value);
	if (is_array($additional_params)) {
		$return_value = array_merge($return_value, $additional_params);
	}
	
	return esope_html_email_handler_send_email($return_value);
}



// Override so admins can use it the same way as group members (container and access level)
function esope_embed_longtext_menu($hook, $type, $items, $vars) {
	if (elgg_get_context() == 'embed') { return $items; }
	$url = 'embed';
	$page_owner = elgg_get_page_owner_entity();
	//if (elgg_instanceof($page_owner, 'group') && $page_owner->isMember()) {
	if (elgg_instanceof($page_owner, 'group') && ($page_owner->isMember() || $page_owner->canEdit())) {
		$url = 'embed?container_guid=' . $page_owner->getGUID();
	}

	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	elgg_require_js('jquery.form');
	elgg_load_js('elgg.embed');

	$text = elgg_echo('embed:media');

	// if loaded through ajax (like on /activity), pull in JS libs manually
	// hack for #6422 because we haven't converted everything to amd yet
	if (elgg_in_context('ajax')) {
		$externals = elgg_get_config('externals_map');
		$embed = elgg_extract('elgg.embed', $externals['js']);
		$lightbox_js = elgg_extract('lightbox', $externals['js']);
		$lightbox_css = elgg_extract('lightbox', $externals['css']);
		
		$text .= <<<___JS
<script>
	require(['jquery.form']);
	if (typeof $.fancybox === 'undefined') {
		$.getScript('$lightbox_js->url');
		$('head').append('<link rel="stylesheet" href="$lightbox_css->url"></link>');
	}
	if (typeof elgg.embed === 'undefined') {
		$.getScript('$embed->url');
	}
</script>
___JS;
	}

	$items[] = ElggMenuItem::factory(array(
		'name' => 'embed',
		'href' => 'javascript:void()',
		'data-colorbox-opts' => json_encode([
			'href' => elgg_normalize_url($url),
		]),
		'text' => $text,
		'rel' => "embed-lightbox-{$vars['id']}",
		'link_class' => "elgg-longtext-control elgg-lightbox embed-control embed-control-{$vars['id']}",
		'priority' => 10,
	));
	
	return $items;
}




