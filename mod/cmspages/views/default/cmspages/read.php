<?php
/**
* Elgg read CMS page view
* @package Elggcmspages
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* @author Facyla
* @copyright Facyla 2011
* @link http://id.facyla.fr/
* Note : This view may render more than the pure content (description), as it's used 
*/

if ($vars['pagetype']) {
  
  //$cmspages = get_entities_from_metadata('pagetype', $vars['pagetype'], "object", "cmspage", 0, 1, 0, "", 0, false); // 1.6
  $options = array(
      'metadata_names' => array('pagetype'), 'metadata_values' => array($vars['pagetype']),
      'types' => 'object', 'subtypes' => 'cmspage',
      //'owner_guid' => 0, 'site_guid' => 0,
      'limit' => 1, 'offset' => 0, 'order_by' => '', 
      'count' => false,
    );
  $cmspages = elgg_get_entities_from_metadata($options);
  
  if ($cmspages) {
    
    $cmspage = $cmspages[0];
    $title = $cmspage->pagetitle;
    
    if($cmspage) {
      $content = $cmspage->description 
        . elgg_view('output/tags', array('tags' => $cmspage->tags));
    /*
      $content .= $cmspage->guid; // who cares ?
      $content .= $cmspage->access_id;
      // These are for future developments
      $content .= $cmspage->container_guid; // should be set as page_owner
      $content .= $cmspage->parent_guid; // can be used for vertical links
      $content .= $cmspage->sibling_guid; // can be used for horizontal links
    */
    } else {
      register_error(elgg_echo('cmspages:notset'));
      forward();
    }
    
    elgg_set_context(elgg_echo('item:object:cmspages') . '&nbsp;: ' . $title);
    
    // Users who can edit this should get a direct edit link
    if ( elgg_is_logged_in() && (in_array($_SESSION['guid'], explode(',', elgg_get_plugin_setting('editors', 'cmspages')))) || elgg_is_admin_logged_in() || (elgg_is_active_plugin('multisite') && (elgg_is_admin_logged_in())) ) {
      $content .= '<small><p style="text-align:right;"><a href="' . $vars['url'] . 'pg/cmspages/index.php?pagetype=' . $vars['pagetype'] . '"><kbd>[&nbsp;Modifier&nbsp;]</kbd></a></p></small>';
    }

    // Display through the correct canvas area
    $content = elgg_view('page/elements/wrapper', array('body' => $content));
    echo elgg_view_layout("one_column", array('title' => $title, 'content' => $content));
  }
}
