<?php
/**
 * Multilingual translate content page
 *
 */

$guid = get_input('guid');
$lang = get_input('lang', 'en');
if (!empty($lang)) $lang_name = elgg_echo($lang);

$title = elgg_echo('multilingual:translate');
$content = '';
$sidebar = '';


$entity = get_entity($guid);
if (elgg_instanceof($entity)) {
	$base_url = $entity->getURL() . '?lang=';
	$languages = multilingual_available_languages();
	// Get main entity
	//$main_entity = multilingual_get_main_entity($entity);
	// Get target language translation, if is exists
	$translation = multilingual_get_translation($entity, $lang);
	
	$content .= '<br />';
	$content .= elgg_view_module('info', elgg_echo('multilingual:translate:instructions:title'), '<div class="elgg-content">' . elgg_echo('multilingual:translate:instructions') . '</div>');
	
	// Main content
	if ($translation) {
		$content .= '<blockquote>' . elgg_echo('multilingual:translate:alreadyexists') . '</blockquote>';
		//system_message(elgg_echo('multilingual:translate:alreadyexists'));
	} else {
		$translation = multilingual_add_translation($entity, $lang);
		//system_message(elgg_echo('multilingual:translate:newcreated'));
		$content .= '<blockquote>';
		if (elgg_instanceof($translation)) {
			$content .= elgg_echo('multilingual:translate:newcreated');
		} else {
			$content .= elgg_echo('multilingual:error:cannottranslate');
		}
		$content .= '</blockquote>';
	}
	
	if (elgg_instanceof($translation)) {
		$content .= '<h3>' . elgg_echo('multilingual:translate:version', array($lang_name)) . '</h3>';
		$content .= elgg_view_entity($translation, array('full_view' => true, 'lang' => $lang));
		$content .= '<br />';
	}
	
	
	// SIDEBAR
	// Original version
	$l_code = $entity->lang;
	//$l_code = $main_entity->lang;
	if (empty($l_code)) { $l_code = get_current_language(); }
	$l_name = $languages[$l_code];
	$sidebar .= '<h3>' . elgg_echo('multilingual:translate:original') . '</h3>';
	$sidebar .= '<p><a href="' . $base_url . '"><img src="' . elgg_get_site_url() . 'mod/multilingual/graphics/flags/' . $l_code . '.gif" alt="' . $l_name . '" /> ' . $l_name . ' (' . $l_code . ') : ' . $entity->title . ' (' . $entity->guid . ')</a></p>';
	$sidebar .= '<br />';
	unset($languages[$l_code]);
	
	$sidebar .= '<h3>' . elgg_echo('multilingual:translate:otherversions') . '</h3>';
	// Get all translations
	$translations = multilingual_get_translations($entity);
	if ($translations) {
		foreach ($translations as $ent) {
			if (!empty($ent->lang)) $l_name = elgg_echo($ent->lang);
			$sidebar .= '<p><a href="' . $base_url . $ent->lang . '"><img src="' . elgg_get_site_url() . 'mod/multilingual/graphics/flags/' . $ent->lang . '.gif" alt="' . $l_name . '" /> ' . $l_name . ' (' . $ent->lang . ') : ' . $ent->title . ' (' . $ent->guid . ')</a>';
			if ($ent->lang == $lang) { $sidebar .= '<br /><em>' . elgg_echo('multilingual:translate:currentediting') . '</em>'; }
			$sidebar .= '</p>';
			// Remove from new translations array
			unset($languages[$ent->lang]);
		}
	} else {
		$sidebar .= '<p>' . elgg_echo('multilingual:translate:nootherversion') . '</p>';
	}
	$sidebar .= '<br />';
	
	$sidebar .= '<h3>' . elgg_echo('multilingual:translate:otherlanguages') . '</h3>';
	if (count($languages) > 0) {
		foreach ($languages as $l_code => $l_name) {
			$sidebar .= '<p><a href="' . elgg_get_site_url() . 'multilingual/translate/' . $entity->guid . '/' . $l_code . '"><img src="' . elgg_get_site_url() . 'mod/multilingual/graphics/flags/' . $l_code . '.gif" alt="' . $l_name . '" /> ' . elgg_echo('multilingual:menu:translateinto', array($languages[$l_code])) . '</a></p>';
		}
	} else {
		$sidebar .= '<p>' . elgg_echo('multilingual:translate:nomissinglanguage') . '</p>';
	}
	$sidebar .= '<br />';
	
	
} else {
	register_error(elgg_echo('multilingual:translate:missingentity'));
}


$body = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);

