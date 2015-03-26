<?php
$plugin = $vars['entity'];

// Set options
$yn_opts = array('yes' => elgg_echo('survey:settings:yes'), 'no' => elgg_echo('survey:settings:no'));
$layout_opts = array('one_column' => '1 colonne', 'one_sidebar' => '2 colonnes', 'two_sidebar' => '3 colonnes', 'cmspages' => 'Pleine largeur (sans marge)', 'custom' => 'Layout personnalisé');
$pageshell_opts = array('default' => 'Par défaut', 'iframe' => 'iframe', 'custom' => 'Personnsalisé');


// Set defaults
//if (!isset($plugin->cms_mode)) $plugin->cms_mode = 'no';
if (!isset($plugin->layout)) $plugin->layout = 'one_column';
if (!isset($plugin->pageshell)) $plugin->pageshell = 'default';


/* @todo : ce serait intéressant de pouvoir le définir indépendament d'externalblog aussi - on verra à l'usage
$layout_options = array( 
		'one_column' => elgg_echo('externalblog:settings:layout:one_column'), 
		'right_column' => elgg_echo('externalblog:settings:layout:right_column'), 
		'two_column' => elgg_echo('externalblog:settings:layout:two_column'), 
		'three_column' => elgg_echo('externalblog:settings:layout:three_column'), 
		'four_column' => elgg_echo('externalblog:settings:layout:four_column'), 
		'five_column' => elgg_echo('externalblog:settings:layout:five_column'), 
	);
*/
/*
$layout_options = array( 
		'' => elgg_echo('cmspages:settings:layout:default'), 
		'exbloglayout' => elgg_echo('cmspages:settings:layout:externalblog'), 
	);
echo '<br /><label style="clear:left;">' . elgg_echo('cmspages:settings:layout') . '</label>';
echo elgg_view('input/dropdown', array('name' => 'params[layout]', 'options_values' => $layout_options, 'value' => $plugin->layout));
echo '<p>' . elgg_echo('cmspages:settings:layout:help') . '</p>';
*/

echo '<p>' . elgg_echo('cmspages:editurl') . ' <a href="' . $vars['url'] . 'cmspages/" class="elgg-button elgg-button-action" target="_blank">' . $vars['url'] . 'cmspages/</a></p>';

echo '<p><label style="clear:left;">' . elgg_echo('cmspages:settings:editors') . '</label>';
echo elgg_view('input/text', array('name' => 'params[editors]', 'value' => $plugin->editors)) . '<br />';
echo '<em>' . elgg_echo('cmspages:settings:editors:help') . '</em>';
$editors = explode(',', $plugin->editors);
// Affichage des éditeurs actuels
$users_count = elgg_get_entities(array('types' => 'user', 'guids' => $editors, 'count' => true));
if ($users_count > 0) {
	$users = elgg_get_entities(array('types' => 'user', 'guids' => $editors, 'limit' => 100));
	echo '<br /><strong>' . elgg_echo('cmspages:editors:list') . ' :</strong>';
	foreach ($users as $ent) {
		echo $ent->name . ' (' . $ent->guid . '), ';
	}
	if ($users_count > 100) echo '...';
}
echo '</p>';

/* Auteurs
echo '<p><label style="clear:left;">' . elgg_echo('cmspages:settings:authors') . '</label>';
echo elgg_view('input/text', array('name' => 'params[authors]', 'value' => $plugin->authors)) . '<br />';
$editors = explode(',', $plugin->authors);
echo elgg_echo('cmspages:settings:authors:help');
// Affichage des éditeurs actuels
$users_count = elgg_get_entities(array('types' => 'user', 'guids' => $authors, 'count' => true));
if ($users_count > 0) {
	$users = elgg_get_entities(array('types' => 'user', 'guids' => $authors, 'limit' => 100));
	echo '<br /><strong>' . elgg_echo('cmspages:authors:list') . ' :</strong>';
	foreach ($users as $ent) {
		echo $ent->name . ' (' . $ent->guid . '), ';
	}
	if ($users_count > 100) echo '...';
}
echo '</p>';
*/

// @TODO : liste des layouts autorisés


/* @TODO : activer le mode "CMS"
	- permet de définir les layouts à utiliser (par défaut avec interface du site, ou layout personnalisé)
	- si layout personnalisé, définition des zones
*/
//echo '<fieldset><legend>' . elgg_echo('cmspages:cms_mode') . '</legend>';
	
	/* Unused : let's use it by default
	echo '<p><label>' . elgg_echo('cmspages:settings:cms_mode') . ' ';
	echo elgg_view('input/dropdown', array('name' => 'params[cms_mode]', 'value' => $plugin->cms_mode, 'options_values' => $yn_opts));
	echo '</label><br />' . elgg_echo('cmspages:settings:cms_mode:details');
	echo '</p>';
	*/
	
	// Default layout : existing, or custom
	// @TODO allow to use arbitrary layout ?  eg. template selector instead of 1 predefined template name ?
	echo '<p><label>' . elgg_echo('cmspages:settings:layout') . ' ';
	echo elgg_view('input/dropdown', array('name' => 'params[layout]', 'value' => $plugin->layout, 'options_values' => $layout_opts)) . '</label>';
	echo ' &nbsp; <a href="' . $vars['url'] . 'cmspages/cms-layout-sidebar" target="_blank" class="elgg-button elgg-button-action">' . elgg_echo('cmspages:layout:sidebar') . '</a>';
	echo ' &nbsp; <a href="' . $vars['url'] . 'cmspages/cms-layout-sidebar-alt" target="_blank" class="elgg-button elgg-button-action">' . elgg_echo('cmspages:layout:sidebar_alt') . '</a>';
	echo ' &nbsp; <a href="' . $vars['url'] . 'cmspages/cms-layout" target="_blank" class="elgg-button elgg-button-action">' . elgg_echo('cmspages:editlayout') . '</a>';
	echo '<br /><em>' . elgg_echo('cmspages:settings:layout:details') . '</em>';
	echo '</p>';
	
	// Default pageshell : default / iframe (no Elgg interface) / or custom
	echo '<p><label>' . elgg_echo('cmspages:settings:pageshell') . ' ';
	echo elgg_view('input/dropdown', array('name' => 'params[pageshell]', 'value' => $plugin->pageshell, 'options_values' => $pageshell_opts));
	echo '</label> &nbsp; <a href="' . $vars['url'] . 'cmspages/cms-pageshell" target="_blank" class="elgg-button elgg-button-action">' . elgg_echo('cmspages:editpageshell') . '</a>';
	echo '<br /><em>' . elgg_echo('cmspages:settings:pageshell:details') . '</em>';
	echo '</p>';
	
//echo '</fieldset>';
	
	// Categories : syntax name::title::parent_name
	echo '<p><label>' . elgg_echo('cmspages:settings:categories') . ' ';
	echo elgg_view('input/plaintext', array('name' => 'params[categories]', 'value' => $plugin->categories));
	echo '</label>';
	echo '<br /><em>' . elgg_echo('cmspages:settings:categories:details') . '</em>';
	echo '</p>';
	
//echo '</fieldset>';


