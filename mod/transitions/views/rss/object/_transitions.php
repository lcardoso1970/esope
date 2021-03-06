<?php
/**
 * View for transitions objects
 *
 * @package Transitions
 */

$full = elgg_extract('full_view', $vars, FALSE);
$list_type = elgg_extract('list_type', $vars, FALSE);
$transitions = elgg_extract('entity', $vars, FALSE);

if (!$transitions) { return TRUE; }

$body = '';

$owner = $transitions->getOwnerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $transitions->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($transitions->description, 137);
}
// Limit to max chars
if (strlen($excerpt) >= 140) { $excerpt = elgg_get_excerpt($excerpt, 137); }

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "transitions/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($transitions->time_created);

// The "on" status changes for comments, so best to check for !Off
if ($transitions->comments_on != 'Off') {
	$comments_count = $transitions->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $transitions->getURL() . '#comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$transitions_icon = "";
// show icon
//if(!empty($transitions->icontime)) {
	$params = $vars;
	$params["align"] = 'none';
	if ($full) {
		$params["size"] = 'master';
	} else {
		$params["size"] = 'gallery';
		if (elgg_in_context("listing") || ($list_type != 'gallery')) {
			$params["size"] = 'listing';
			$params["align"] = 'right';
		}
	}
//}
$transitions_icon = elgg_view_entity_icon($transitions, $params["size"], $params);
$transitions_icon = trim($transitions_icon);
$transitions_icon_url = $transitions->getIconURL($params["size"]);

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'catalogue',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) { $metadata = ''; }


// Stats and actions blocks : likes, contributions (links + comments)
$stats = '';
if (elgg_is_active_plugin('likes')) {
	$num_of_likes = \Elgg\Likes\DataService::instance()->getNumLikes($transitions);
	$stats .= '<i class="fa fa-heart"></i> ' . $num_of_likes . ' &nbsp; ';
}
$stats .= '<i class="fa fa-comments"></i> ' . $transitions->countComments() . ' &nbsp; ';
//$stats .= '<i class="fa fa-tags"></i> ' . count($transitions->tags_contributed) . ' &nbsp; ';
//$stats .= '<i class="fa fa-thumbs-o-up"></i> ' . count($transitions->links_supports) . ' &nbsp; ';
//$stats .= '<i class="fa fa-thumbs-o-down"></i> ' . count($transitions->links_invalidates) . ' &nbsp; ';
$stats .= '<i class="fa fa-link"></i> ' . count($transitions->links) . ' &nbsp; ';




if ($full) {
		// Full view
	if (elgg_in_context('transitions-news')) {
		echo elgg_view('transitions/news', $vars);
	} else {
		echo elgg_view('transitions/view', $vars);
	}
	
} else {
	// brief view
	
	if (elgg_in_context("listing") || ($list_type != 'gallery')) {
		// Listing view
		$category = '';
		if (!empty($transitions->category)) {
			$category = '<span class="transitions-category transitions-' . $transitions->category . '">' . elgg_echo('transitions:category:' . $transitions->category) . '</span> ';
		}
		$params = array(
			'entity' => $transitions,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'content' => $category . $excerpt,
		);
		$params = $params + $vars;
		$list_body = elgg_view('object/elements/summary', $params);
		
		if (!empty($transitions_icon)) {
			//echo elgg_view_image_block($owner_icon, $list_body, array('image_alt' => $transitions_icon));
			echo elgg_view_image_block($transitions_icon, $list_body);
		} else {
			//echo elgg_view_image_block($owner_icon, $list_body);
			echo elgg_view_image_block('', $list_body);
		}
		//echo elgg_view_image_block($transitions_icon, $owner_icon . $list_body);
		
	} else {
		// Gallery view
		// do not show the metadata and controls in gallery view
		$metadata = '';
		$params = array(
			'text' => elgg_get_excerpt($transitions->title, 70),
			'href' => $transitions->getURL(),
			'is_trusted' => true,
		);
		$title_link = elgg_view('output/url', $params);
		
		$category_class = 'transitions-category-' . $transitions->category;
		echo '<div class="transitions-gallery-item ' . $category_class . '">';
			echo '<div class="transitions-gallery-box" style="background-image:url(' . $transitions_icon_url . ');">';
				
				//echo $transitions_icon;
				echo '<div class="transitions-gallery-hover">';
					
					// Entête
					echo '<div class="transitions-gallery-head">';
						if (!empty($transitions->category)) echo '<span class="transitions-category transitions-' . $transitions->category . '">' . elgg_echo('transitions:category:' . $transitions->category) . '</span>';
						//if ($metadata) { echo $metadata; }
						if ($title_link) { echo "<h3>$title_link</h3>"; }
						//echo '<div class="elgg-subtext">' . $subtitle . '</div>';
						//echo elgg_view('object/summary/extend', $vars);
						//echo elgg_view('output/tags', array('tags' => $transitions->tags));
						//echo elgg_view_image_block($owner_icon, $list_body);
	
						// Contenu "texte"
						echo '<div class="elgg-content">' . $excerpt . '</div>';
					
						// Stats et actions possibles : commenter, liker, ajouter une métadonnée/relation
						echo '<div class="transitions-gallery-actions">';
							echo '<div class="transitions-gallery-inner">';
								echo $stats;
							echo '</div>';
						echo '</div>';
					echo '</div>';
					
				echo '</div>';
				
				echo '<div class="clearfloat"></div>';
			echo '</div>';
		echo '</div>';
	}
}

