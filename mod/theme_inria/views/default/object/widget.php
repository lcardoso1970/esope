<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 */

$widget = $vars['entity'];
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}

$show_access = elgg_extract('show_access', $vars, true);
// Esope : Don't display access where it is clearly not needed (dashboard)
if (elgg_in_context('dashboard')) $show_access = false;

// @todo catch for disabled plugins
$widget_types = elgg_get_widget_types('all');

$handler = $widget->handler;

$title = $widget->getTitle();

$edit_area = '';
$can_edit = $widget->canEdit();
if ($can_edit) {
	$edit_area = elgg_view('object/widget/elements/settings', array(
		'widget' => $widget,
		'show_access' => $show_access,
	));
}
$controls = elgg_view('object/widget/elements/controls', array(
	'widget' => $widget,
	'show_edit' => $edit_area != '',
));

// don't show content for default widgets
if (elgg_in_context('default_widgets')) {
	$content = '';
} else {
	if (elgg_view_exists("widgets/$handler/content")) {
		$content = elgg_view("widgets/$handler/content", $vars);
	} else {
		elgg_deprecated_notice("widgets use content as the display view", 1.8);
		$content = elgg_view("widgets/$handler/view", $vars);
	}
}

$widget_id = "elgg-widget-$widget->guid";
$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-$handler");
$widget_class = "elgg-module elgg-module-widget";
if ($can_edit) {
	$widget_class .= " elgg-state-draggable $widget_instance";
} else {
	$widget_class .= " elgg-state-fixed $widget_instance";
}

$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$widget_class = "$widget_class $additional_class";
}

// Niveau d'accès des widgets : seulement pour l'auteur
if ($show_access && ($widget->owner_guid == elgg_get_logged_in_user_guid())) $access = '<span style="">' . elgg_view('output/access', array('entity' => $widget, 'hide_text' => true)) . '</span>';

/*
$widget_header = <<<HEADER
	<header class="elgg-widget-handle clearfix">
	$controls
	<h2>$title $access</h2>
	</header>
HEADER;
*/
$widget_header = <<<HEADER
	<div class="elgg-widget-handle clearfix">
	$controls
	<h3 class="elgg-widget-title">$title</h3>
	</div>
HEADER;

$widget_body = <<<BODY
	$edit_area
	<div class="elgg-widget-content" id="elgg-widget-content-$widget->guid">
		$content
	</div>
BODY;

echo elgg_view_module('widget', '', $widget_body, array(
	'class' => $widget_class,
	'id' => $widget_id,
	'header' => $widget_header,
));

