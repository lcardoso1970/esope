<?php
/**
 * Group admins
 *
 * @uses $vars['entity']    ElggEntity
 * @uses $vars['title']     Title link (optional) false = no title, '' = default
 * @uses $vars['metadata']  HTML for entity metadata and actions (optional)
 * @uses $vars['subtitle']  HTML for the subtitle (optional)
 * @uses $vars['tags']      HTML for the tags (optional)
 * @uses $vars['content']   HTML for the entity content (optional)
 */

$entity = $vars['entity'];
$groupowner = $entity->getOwnerEntity();


// Propriétaire un peu à part, en premier
echo '<strong>' . elgg_echo('group_operators:operators') . '</strong><br />';
echo '<span style="float:left;" title="' . elgg_echo('group_operators:owner') . '">' . elgg_view_entity_icon($groupowner, 'tiny') . '</span>';
// @TODO : risque de doublon dans le cas où un responsable a récupéré la propriété du groupe
echo elgg_list_entities_from_relationship(array('types'=>'user', 'relationship' => 'operator', 'relationship_guid' => $entity->guid, 'inverse_relationship' => TRUE, 'order_by' => 'r.time_created DESC', "list_type" => "gallery", "gallery_class" => "elgg-gallery-users", "pagination" => false, 'size' => 'tiny'));
echo '<div class="clearfloat"></div>';
// Lien admin des responsables de groupes
/*
if ($entity->canEdit()) {
	echo '<a href="' . elgg_get_site_url() . 'group_operators/manage/' . $entity->guid . '">' . elgg_echo('group_operators:manage') . '</a>';
}
*/
?>
<div class="clearfloat"></div><br />

