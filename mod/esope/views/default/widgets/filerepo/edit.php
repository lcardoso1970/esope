<?php
/**
 * Elgg file widget edit view
 *
 * @package ElggFile
 */


$widget_id = $vars['entity']->guid;


// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 4;
}

$params = array(
	'name' => 'params[num_display]',
	'id' => 'num_display_'.$widget_id,
	'value' => $vars['entity']->num_display,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20),
);
$select = elgg_view('input/select', $params);

?>
<div>
	<label for="num_display_<?php echo $widget_id; ?>"><?php echo elgg_echo('file:num_files'); ?>:</label>
	<?php echo $select; ?>
</div>
