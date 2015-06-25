<?php
//$url = elgg_get_site_url();

// Define dropdown options
//$yes_no_opt = array('yes' => elgg_echo('option:yes'), 'no' => elgg_echo('option:no'));


// Set default value
if (!isset($vars['entity']->main_locale)) { $vars['entity']->main_locale = get_current_language(); }


// Example yes/no setting
//echo '<p><label>' . elgg_echo('plugin_template:settings:settingname'). ' ' . elgg_view('input/dropdown', array('name' => 'params[settingname]', 'options_values' => $yes_no_opt, 'value' => $vars['entity']->settingname)) . '</label><br /><em>' . elgg_echo('plugin_template:settings:settingname:details'). '</em></p>';


// Set default lancguage code
echo '<p><label>' . elgg_echo('multilingual:settings:main_locale'). ' ' . elgg_view('input/text', array('name' => 'params[main_locale]', 'value' => $vars['entity']->main_locale)) . '</label><br /><em>' . elgg_echo('multilingual:settings:main_locale:details'). '</em></p>';

// Available languages
echo '<p><label>' . elgg_echo('multilingual:settings:locales'). ' ' . elgg_view('input/text', array('name' => 'params[locales]', 'value' => $vars['entity']->locales)) . '</label><br /><em>' . elgg_echo('multilingual:settings:locales:details'). '</em></p>';


