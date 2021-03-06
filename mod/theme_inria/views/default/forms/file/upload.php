<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// Adds prevent_notification form field

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_page_owner_guid();
}
$container = get_entity($container_guid);
if (!(elgg_instanceof($container, 'group') || elgg_instanceof($container, 'user'))) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$guid = elgg_extract('guid', $vars, null);

$hide_entity = 'no';
if ($vars['entity']) { $hide_entity = $vars['entity']->hide_entity; }

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

$upload_limit = elgg_echo('file:upload_limit', array(elgg_format_bytes($max_upload)));

$is_embed = false;
if (elgg_in_context('embed')) { $is_embed = true; }
?>
<div class="mbm elgg-text-help">
	<?php echo $upload_limit; ?>
</div>
<?php
if ($is_embed) {
	?>
	<div class="home-static-container" style="width:42%; margin-right:3%; display:inline-block;">
		<label><?php echo $file_label; ?></label><br />
		<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
	</div>
	<?php
	// Access
	echo '<div class="home-static-container" style="width:30%; margin-right:3%; display:inline-block;"><label>' . elgg_echo('access') . '</label><br />' . elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id, 'entity' => get_entity($guid), 'container_guid' => $container_guid, 'entity_type' => 'object', 'entity_subtype' => 'file')) . '</div>';
	// Submit
	echo '<div class="home-static-container" style="width:20%; display:inline-block;">' . elgg_view('input/submit', array('value' => $submit_label)) . '</div>';
} else {
	?>
	<div>
		<label><?php echo $file_label; ?></label><br />
		<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
	</div>
	<?php
}
?>

<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php
	if ($is_embed) {
		echo elgg_view('input/plaintext', array('name' => 'description', 'value' => $desc));
	} else {
		echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc));
	}
	?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>

<?php
// File tools support
if (elgg_is_active_plugin('file_tools')) {
	if(file_tools_use_folder_structure()){
		$parent_guid = 0;
		if($file = elgg_extract("entity", $vars)){
		if($folders = $file->getEntitiesFromRelationship(array('relationship'=>FILE_TOOLS_RELATIONSHIP, 'inverse_relationship'=>true, 'limit'=>1))){
				$parent_guid = $folders[0]->getGUID();
			}
		}
		?>
		<div>
			<label><?php echo elgg_echo("file_tools:forms:edit:parent"); ?><br />
			<?php
				echo elgg_view("input/folder_select", array("name" => "folder_guid", "value" => $parent_guid));
			?>
			</label>
		</div>
	<?php
	}
}

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

if (!$is_embed) {
	?>
	<div>
		<label><?php echo elgg_echo('access'); ?></label><br />
		<?php echo elgg_view('input/access', array(
			'name' => 'access_id',
			'value' => $access_id,
			'entity' => get_entity($guid),
			'entity_type' => 'object',
			'entity_subtype' => 'file',
		)); ?>
	</div>
	<?php
}
?>

<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
	if ($guid) {
		echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
	}

	// Note : select defaults to no notification in embed context
	if (!$vars['entity'] || $is_embed) {
		echo elgg_view('prevent_notifications/prevent_form_extend', array());
	}
	// Allow to hide file in workspace listings, using a generic metadata
	echo '<p><label>' . elgg_echo('theme_inria:hide_entity:select') . ' ' . elgg_view('input/select', array('name' => 'hide_entity', 'value' => $hide_entity, 'options_values' => array('no' => elgg_echo('theme_inria:hide_entity:no'), 'yes' => elgg_echo('theme_inria:hide_entity:yes')))) . '</label></p>';

	echo elgg_view('input/submit', array('value' => $submit_label));
	if (!$is_embed) {
		echo elgg_view('input/hidden', array('name'=>'backtoform', 'value'=> 'no'));
		$submit_again_label = $submit_label . ' ' . elgg_echo('theme_inria:submitandcreateagain');
		echo elgg_view('input/submit', array('value' => $submit_again_label, 'onClick' => 'javascript:$(\'input[name="backtoform"]\').val("yes");'));
	}
	?>
</div>

