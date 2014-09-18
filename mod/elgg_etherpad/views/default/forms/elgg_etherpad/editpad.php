<?php
/**
 * Add group pad
 * Note : "group" can be misleading, as Etherpad Lite groups are rather access controls, 
 * so they are mapped to Elgg containers which are in this context : user, group or object
 *
 */

$yes_no_opt = array( 'yes' => elgg_echo('option:yes'), 'no' => elgg_echo('option:no') );
$no_yes_opt = array_reverse($yes_no_opt);

$padID = elgg_extract('padID', $vars, '');

if (strpos($padID, '$')) {
	$pad_name = explode('$', $padID);
	$group_id = $pad_name[0];
	$pad_name = $pad_name[1];
} else { $pad_name = $padID; }

$form_body = '';
$form_body .= '<h3>Edition du pad "' . $pad_name . '"';
if ($group_id) $form_body .= ' (groupe ' . $group_id . ')';
$form_body .= '</h3>';

$server = elgg_get_plugin_setting('server', 'elgg_etherpad');

$form_body .= '<p><strong>Adresse du Pad :</strong> ' . $CONFIG->url . '/pad/view/' . $padID . '</p>';

/*
// Base action url for a given pad - can be used to delete pad
$base_action_url = $CONFIG->url . 'action/elgg_etherpad/edit?padID=' . $padID;
$base_action_url = elgg_add_action_tokens_to_url($base_action_url);
*/


// Only for group pads
if ($group_id) {
	$public = elgg_etherpad_is_public($padID);
	$isPasswordProtected = elgg_etherpad_is_password_protected($padID);
	
	$form_body .= '<p><em>' . elgg_echo('elgg_etherpad:forms:creategrouppad:details') . '</em></p>';
	$form_body .= '<br />';
	
	$form_body .= '<p><label>' . elgg_echo('elgg_etherpad:public') . ' ' . elgg_view('input/dropdown', array('name' => 'public', 'value' => $public, 'options_values' => $no_yes_opt)) . '</label> &nbsp; ';
	$form_body .= 'Etat actuel : ';
	if ($public == 'yes') {
		$form_body .= '<i class="fa fa-unlock"></i> PUBLIC ';
	} else if ($public == 'no') {
		$form_body .= '<i class="fa fa-lock"></i> NON PUBLIC ';
	}
	$form_body .= '</p>';
	$form_body .= '<br />';
	
	// Password should not be defined in the form, as we have no way to get it back (but we can change it)
	$form_body .= '<p><label>' . elgg_echo('elgg_etherpad:password') . ' ' . elgg_view('input/text', array('name' => 'password', 'value' => '', 'style' => "width:20ex;")) . '</label> &nbsp; ';
	$form_body .= 'Etat actuel : ';
	if ($isPasswordProtected == 'yes') {
		$form_body .= '<i class="fa fa-key"></i> PROTEGE PAR MOT DE PASSE ';
	} else if ($isPasswordProtected == 'no') {
		$form_body .= '(SANS MOT DE PASSE) ';
	}
	$form_body .= '<br /><em>' . elgg_echo('elgg_etherpad:password:details') . '</em></p>';
	$form_body .= '<br />';
	
} else {
	$form_body .= '<p><em>Ce pad a été créé comme "pas public", et ne peut pas être rendu privé ni protégé par un mot de passe.</em></p>';
}





//$form_body .= '<p><label>' . elgg_echo('elgg_etherpad:container_guid') . ' ' . elgg_view('input/text', array('name' => 'container_guid', 'value' => $container_guid)) . '</label><br /><em></em></p>';
$form_body .= elgg_view('input/hidden', array('name' => 'padID', 'value' => $padID));

$form_body .= elgg_view('input/hidden', array('name' => 'request', 'value' => 'editpad'));

$form_body .= elgg_view('input/submit', array('value' => elgg_echo("elgg_etherpad:editpad")));

echo elgg_view('input/form', array('action' => $vars['url'] . "action/elgg_etherpad/edit", 'body' => $form_body));

echo '<p><a href="' . $CONFIG->url . 'pad/view/' . $padID . '"><i class="fa fa-eye"></i> Afficher la page de visualisation du pad</a></p>';

echo '<iframe src="' . $server . '/p/' . $padID . '?userName=' . rawurlencode($own->name) . '" style="height:400px; width:100%; border:1px inset black;"></iframe>';

