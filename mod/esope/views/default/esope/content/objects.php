<?php
/* This view provides a quick list of objects, eg. icon + title
 * It is focused on the actual content rather than the social part : content type and title rather than author icon or dates.
 */

$objects = elgg_extract('entities', $vars);

$content = '';

if (is_array($objects)) {
	foreach($objects as $ent) {
		//$icon = elgg_view_entity_icon($ent, 'tiny');
		$subtype = $ent->getSubtype();
		$icon = elgg_echo("esope:icon:$subtype");
		// Skip undefined icons
		if ($icon == 'esope:icon:$subtype') { $icon = ''; }
		
		$text = '';
		if (!empty($ent->title)) {
			$text .= $ent->title;
		} else if (!empty($ent->name)){
			$text .= $ent->name;
		} else {
			$excerpt = $ent->excerpt;
			if (empty($excerpt)) { $excerpt = $ent->briefdescription; }
			if (empty($excerpt)) { $excerpt = $ent->description; }
			// Always limit length
			$text = elgg_get_excerpt($excerpt, 140);
		}
		
		$link = elgg_view('output/url', array(
				'href' => $ent->getURL(),
				'text' => $text,
			));
		
		$content .= elgg_view_image_block($icon, $link);
	}
}

echo <<<HTML
<div class="objects-wrapper">
	$content
	<div class="clearfloat"></div>
</div>
HTML;

