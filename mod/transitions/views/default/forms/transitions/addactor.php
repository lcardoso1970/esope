<?php
/**
 * Edit transitions add actor to a project
 *
 * @package Transitions
 */

$actor_guid = elgg_get_sticky_value('transitions-addactor', 'actor_guid');


//echo '<h3>' . elgg_echo('transitions:form:addactor') . '</h3>';
echo '<p><em>' . elgg_echo('transitions:addactor:details') . '</em></p>';
echo elgg_view('transitions/input/addactor', array('name' => 'actor_guid', 'guid' => $vars['guid'], 'value' => $actor_guid));

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));
echo elgg_view('input/submit', array('value' => elgg_echo('transitions:addactor:submit')));

