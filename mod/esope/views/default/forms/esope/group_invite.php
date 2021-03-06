<?php
/**
 * Elgg groups invite form extend
 * IMPORTANT : we're extending a view that will be embedded into a form, so take care to close and reopen "wrapper' HTML tags (<form><div>)
 *
 * @package ElggGroups
 */

$invite_metadata = elgg_get_plugin_setting('groups_invite_metadata', 'esope');
if (empty($invite_metadata)) { return; }
$metadata_search_fields = esope_get_input_array($invite_metadata);

$group = $vars['entity'];
$owner = $group->getOwnerEntity();
$forward_url = $group->getURL();

$own = elgg_get_logged_in_user_entity();
$ownguid = elgg_get_logged_in_user_guid();

// Get input fields
//$metadata_search_fields = array('inria_location', 'inria_location_main', 'epi_ou_service');
$query = '';
$meta_fields = get_input("metadata");
foreach ($metadata_search_fields as $field) {
	$$field = $meta_fields["$field"];
	$query .= $$field; // Used to determine if we have any filter
	//if (!empty($$field)) $content .= "FIELD $field = " . $$field . "<br />";
}


// FORM CONTENT
$content = '';

// End normal invite form
//$content .= "</div>";
$content .= "</fieldset></form>";


// Start search form based on members' metadata
$content .= '<form id="esope-search-form-invite-groups" method="POST" class="elgg-form elgg-form-groups-invite-search"><fieldset>';
//$content .= '<div class="blockform">';
$content .= '<h3>' . elgg_echo('esope:groupinvite:search') . '</h3>';


// Step 1. Search form using LDAP fields
$content .= '<h4>' . elgg_echo('esope:groupinvite:search:select') . '</h4>';
$content .= '<br />';
// Préparation du formulaire : on utilise la config du thème + adaptations spécifiques pour notre cas
// Note : on peut récupérer les résultats sur cette page plutôt qu'en AJAX, si on veut...
$metadata_search = '';

// Build metadata search fields
if (elgg_is_active_plugin('profile_manager')) {
	// Metadata options fetching will only work if those are stored somewhere
	foreach ($metadata_search_fields as $metadata) {
		$name = "metadata[$metadata]";
		$meta_title = elgg_echo($metadata);
		// Allow search on any meta that is set, even if not defined in profile manager
		$meta_input = esope_make_search_field_from_profile_field(array('metadata' => $metadata, 'name' => $name, 'auto-options' => true, 'value' => $$metadata));
		if (!$meta_input) {
			/* Alert admin ?
			if (elgg_is_admin_logged_in()) {
				register_error('Metadata not set: ' . $metadata . ' (' . $meta_title . ')<br />Using raw text input instead');
			}
			*/
			// Use text input replacement so we can search meta even if not defined by profile_manager (other plugin, etc.)
			$meta_input = elgg_view('input/text', array('name' => $name, 'value' => $$metadata));
		}
		$metadata_search .= '<div class="esope-search-metadata esope-search-metadata-select"><label>' . ucfirst($meta_title) . $meta_input . '</label></div>';
	}
} else {
	// We'll rely on text inputs then
	foreach ($metadata_search_fields as $metadata) {
		$name = "metadata[$metadata]";
		$meta_title = elgg_echo($metadata);
		$metadata_search .= '<div class="esope-search-metadata esope-search-metadata-text"><label>' . ucfirst($meta_title) . elgg_view('input/text', array('name' => $name, 'value' => $$metadata)) . '</label></div>';
	}
}
$content .= elgg_view('input/securitytoken');
$content .= elgg_view('input/hidden', array('name' => 'entity_type', 'value' => 'user'));
$content .= $metadata_search . '<div class="clearfloat"></div>';
$content .= '<p>' . elgg_view('input/submit', array('value' => elgg_echo('search:go'))) . '</p>';



// Step 2. Handle search form and display results in the invite form
// Formulaire d'invitation
$content .= '<div class="clearfloat"></div><br />';
$content .= '<h4>' . elgg_echo('esope:groupinvite:search:invite') . '</h4>';
// @TODO add selected results to .elgg-user-picker-list ? as : <input type="hidden" name="members[]" value="XXX">
if (!empty($query)) {
	$max_results = 500;
	$users = esope_esearch(array('returntype' => 'entities'), $max_results);
	$return_count = count($users);
	if ($users) {
		//$content .= "</div>";
		// Close search form and open a new form to invite found users
		$content .= '</fieldset></form>';
		$content .= '<form id="esope-search-form-invite-results" method="POST" class="elgg-form elgg-form-alt mtm elgg-form-groups-invite-results" action="' . elgg_get_site_url() . 'action/groups/invite"><fieldset>';
		//$content .= '<div class="blockform">';
		$content .= elgg_view('input/securitytoken');
		$content .= "<script>
		$(document).ready(function() {
			$('#group-invite-user-selectall').click(function(event) {
			if(this.checked) {
				$('.group-invite-user').each(function() { this.checked = true; });
			}else{
				$('.group-invite-user').each(function() { this.checked = false; });
			}
			});
		});
		</script>";
		// Display results
		if ($return_count > $max_results) { $content .= '<span class="esope-morethanmax">' . elgg_echo('esope:search:morethanmax') . '</span>'; }
		$content .= '<p>';
		if ($return_count > 1) {
			$content .= elgg_echo('esope:search:nbresults', array($return_count));
		} else {
			$content .= elgg_echo('esope:search:nbresult', array($return_count));
		}
		$content .= '<ul>';
		foreach ($users as $ent) {
			if ($group->isMember($ent)) {
				//$content .= '<li><img src="' . $ent->getIcon('topbar') . '" /> ' . elgg_echo('groups:add:alreadymember', array($ent->name)) . '</li>';
				$content .= '<li><label><input type="checkbox" class="group-invite-user" disabled="disabled"/> <img src="' . $ent->getIcon('topbar') . '" /> ' . elgg_echo('groups:add:alreadymember', array($ent->name)) . '</label></li>';
			} else {
				$content .= '<li><label><input type="checkbox" name="user_guid[]" value="' . $ent->guid . '" class="group-invite-user" /> <img src="' . $ent->getIcon('topbar') . '" /> ' . $ent->name . '</label></li>';
			}
		}
		$content .= '</ul></p>';
		$content .= "<p><label><input type=\"checkbox\" id=\"group-invite-user-selectall\"> " . elgg_echo('select:all') . "</label></p>";

		// Invitation ou inscription ?
		$allowregister = elgg_get_plugin_setting('allowregister', 'esope');
		if ($allowregister == 'yes') {
			$content .= ' <p><label>' . elgg_echo('esope:groups:allowregister') . '</label> ' . elgg_view('input/select', array('name' => 'group_register', 'options_values' => array('no' => elgg_echo('option:no'), 'yes' => elgg_echo('option:yes')))) . '</p>';
		}
		$content .= '<div class="elgg-foot">';
		$content .= elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
		$content .= elgg_view('input/hidden', array('name' => 'group_guid', 'value' => $group->guid));
		$content .= elgg_view('input/submit', array('value' => elgg_echo('invite')));
		//$content .= '</div>';
	} else {
		$content .= '<span class="esope-noresult">' . elgg_echo('esope:search:noresult') . '</span>';
	}
} else {
	$content .= '<span class="esope-noresult">' . elgg_echo('esope:search:nosearch') . '</span>';
}

// Note : closing </form> is rendered by forms/groups/invite view (inner extend)

$content .= '<div class="clearfloat"></div>';

echo $content;

