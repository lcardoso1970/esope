<?php
/**
* Elgg menus
* 
* Menus editor page
* New menus : any menu except reserved ones
* Reserved menus : custom menu + behavior = full replacement (by a new menu) OR Add items, + Remove items (remove listed names)
* 
*/

/* Available menu vars :
name => STR Menu item identifier (required)
text => STR Menu item display text as HTML (required)
href => STR Menu item URL (required) (false for non-links.
section => STR Menu section identifier 
link_class => STR A class or classes for the tag 
item_class => STR A class or classes for the tag 
parent_name => STR Identifier of the parent menu item 
contexts => ARR Page context strings 
title => STR Menu item tooltip 
selected => BOOL Is this menu item currently selected? 
confirm => STR If set, the link will be drawn with the output/confirmlink view instead of output/url. 
data => ARR Custom attributes stored in the menu item.
$item = array('name', 'text', 'href', 'section', 'link_class', 'item_class', 'parent_name', 'contexts', 'title', 'selected', 'confirm', 'data');
*/

$content = '';

/*
// get site menu items
$menu = $menu['site'];
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu('name');
$menu_items = $menu['default'];
*/

$action = get_input('action', 'edit');

$menu_name = get_input('menu_name', false);
// Clean menu name, useful for new names
$menu_name = elgg_get_friendly_title($menu_name);

elgg_load_js('elgg.elgg_menus.edit');

// Tous les menus...
$menus = elgg_get_config('menus');

// Site reserved menus
$reserved_menus = array('topbar', 'page', 'site', 'extras', 'embed', 'footer', 'filter', 'admin', 'title', 'admin_title');
if (elgg_is_active_plugin('cmspages')) $reserved_menus[] = 'cmspages_categories';

// Sélecteur de menu
// Option vide...
$menu_opts = array('' => '');
// Menus du système
if ($menus) foreach ($menus as $name => $menu_items) { $menu_opts[$name] = $name; }
// Menus personnalisés
$custom_menus = elgg_get_plugin_setting('menus', 'elgg_menus');
if ($custom_menus) foreach ($custom_menus as $name) { $menu_opts[$name] = $name; }
if (!in_array($menu_name, $menu_opts)) { $menu_opts[$menu_name] = elgg_echo('elgg_menus:new') . '&nbsp;: ' . $menu_name; }

$content .= '<form style="float:right;" id="menu-editor-form-new">';
$content .= elgg_view('input/hidden', array('name' => "action", 'value' => "edit"));
$content .= 'ou <label>' . elgg_echo('elgg_menus:new') . elgg_view('input/text', array('name' => "menu_name", 'placeholder' => elgg_echo('elgg_menus:name'), 'style' => "width:12ex; margin-right:1ex;")) . '</label>';
$content .= elgg_view('input/submit', array('value' => elgg_echo('create')));
$content .= '</form>';

$content .= '<form id="menu-editor-form-select">';
//$content .= '<a href="?new_menu=yes" style="float:right;" class="elgg-button elgg-button-action">Créer un nouveau menu</a>';
$content .= '<label>' . elgg_echo('elgg_menus:selectedit') . ' ' . elgg_view('input/pulldown', array('name' => 'menu_name', 'options_values' => $menu_opts, 'value' => $menu_name)) . '</label>';
$content .= elgg_view('input/submit', array('value' => elgg_echo('edit'), 'style' => 'float:none;'));
$content .= '</form>';
$content .= '<div class="clearfloat"></div><br />';


switch ($action) {
	// Création nouveau menu
	case 'add':
		break;
	
	case 'edit':
	case 'view':
	default:
		if ($menu_name) {
			
			// @TODO PROCESS POST form data
			$number_of_items = get_input('number_of_items');
			$count = 0;
			$new_items = array();
			if ($number_of_items) {
				$new_name = get_input('name');
				$new_href = get_input('href');
				$new_text = get_input('text');
				$new_title = get_input('title');
				$new_confirm = get_input('confirm');
				$new_item_class = get_input('item_class');
				$new_link_class = get_input('link_class');
				$new_section = get_input('section');
				$new_priority = get_input('priority');
				$new_contexts = get_input('contexts');
				$new_selected = get_input('selected');
				$new_parent_name = get_input('parent_name');
				//error_log("Loading item $i data : $new_title / $new_input_type / $new_empty_value / $new_required"); // debug
				// Title is the only required value for items (default on text input)
				for($i=0; $i<$number_of_items; $i++) {
					if ($new_name[$i]) {
						// Set defaults
						if (empty($new_priority[$i])) { $new_priority[$i] = '100'; }
						// Add/update item
						$new_items[] = array(
								'name' => $new_name[$i],
								'href' => $new_href[$i],
								'text' => $new_text[$i],
								'title' => $new_title[$i],
								'confirm' => $new_confirm[$i],
								'item_class' => $new_item_class[$i],
								'link_class' => $new_link_class[$i],
								'section' => $new_section[$i],
								'priority' => $new_priority[$i],
								'contexts' => $new_contexts[$i],
								'selected' => $new_selected[$i],
								'parent_name' => $new_parent_name[$i],
								// Adjust display order to received order
								//'display_order' => ($count + 1) * 10,
							);
						$count++;
					}
				}
			}
			
			// Save menu configuration
			$content .= '<pre>' . print_r($new_items, true) . '</pre>';
			$menu_config = serialize($new_items);
			$custom_menus = elgg_set_plugin_setting("menu-$menu_name", $menu_config, 'elgg_menus');
			
			// @TODO : get generated menu (from config)
			//elgg_menus_get_custom_menu($menu_name, $replace = true);
			// Avec mode de gestion si menu pré-existant : replace, ou merge sinon
			
			
			// Display current menu
			$content .= '<div id="menu-editor-preview" style="float:right;">';
			$content .= elgg_view('output/url', array(
					'text' => elgg_echo('elgg_menus:preview', array($menu_name)), 
					'href' => elgg_get_site_url() . "elgg_menus/preview/$menu_name?embed=inner",
					'class' => 'elgg-lightbox elgg-button elgg-button-action',
				));
			$content .= '</div>';
			
			$content .= '<form id="menu-editor-form-edit">';
			$content .= elgg_view('input/hidden', array('name' => "action", 'value' => "edit"));
			
			//$content .= elgg_view('input/submit', array('value' => elgg_echo('save')));
			$content .= '<h2>' . elgg_echo('elgg_menus:edit:title') . '&nbsp;: ' . $menu_name . '</h2>';
			
			// Edition du titre du menu, si nom non réservé
			$content .= '<div class="clearfloat"></div>';
			if (!in_array($menu_name, $reserved_menus)) {
				$content .= '<label>' . elgg_echo('elgg_menus:name') . '&nbsp;: ' . elgg_view('input/text', array('name' => 'menu_name', 'value' => $menu_name)) . '</label>';
			} else {
				$content .= elgg_view('input/hidden', array('name' => 'menu_name', 'value' => $menu_name));
			}
			
			// Options générales du menu
			
			// Structure the menu (section group + tree + order)
			$menu = $menus[$menu_name];
			/* Structured menu
			*/
			$menu = elgg_trigger_plugin_hook('register', "menu:$menu_name", $vars, $menus[$menu_name]);
			$sort_by = elgg_extract('sort_by', $vars, 'priority'); // text, name, priority, register, ou callback php
			$builder = new ElggMenuBuilder($menu);
			$menu = $builder->getMenu($sort_by);
			$i = 0;
			//foreach ($menus[$menu_name] as $i => $menu_item) {
			/* Uncomment for Structured menu
			*/
			foreach ($menu as $section_name => $section_items) {
				$content .= '<fieldset><legend>Section : ' . $section_name . '</legend>';
				$content .= '<div class="menu-editor-items">';
				/* Uncomment for unstructured menu
				$content .= '<div class="menu-editor-items">';
				foreach ($menu as $menu_item) {
				*/
				foreach ($section_items as $menu_item) {
					if (!($menu_item instanceof ElggMenuItem)) continue;
					$i++;
					$content .= elgg_view('elgg_menus/input/menu_item', array('menu_item' => $menu_item, 'id' => 'menu-editor-item-' . $i));
				}
				//$content .= '</div>';
			/* Uncomment for Structured menu
			*/
				$content .= '</div>';
				$content .= '</fieldset>';
			}
			
			$content .= '<div id="menu-editor-newitems" class="menu-editor-items"></div>';
			$content .= elgg_view('input/hidden', array('name' => 'number_of_items', 'id' => 'menu-editor-numitems', 'value' => $i));
			
			$content .= elgg_view('input/submit', array('value' => elgg_echo('save')));
			$content .= '</form>';
			$content .= '<div class="clearfloat"></div>';
			
			// Ajout entrée de menu
			$content .= '<h3>' . elgg_echo('elgg_menus:edit:newitem') . '</h3>';
			//$content .= elgg_view('elgg_menus/input/menu_item', array('menu_item' => false, 'id' => 'menu-editor-newitem'));
			$content .= elgg_view('input/button', array(
					'id' => 'menu-editor-add-item',
					'value' => elgg_echo('elgg_menus:edit:newitem'),
					'class' => 'elgg-button elgg-button-action',
				));
			
			// Ajout de section : pas forcément utile car déterminé via champ texte...
			// Ou alors on créé les sections avant et on drop dans la bonne section...?
			$content .= '<h3>' . elgg_echo('elgg_menus:edit:newsection') . '</h3>';
			$content .= '<p>' . elgg_echo('elgg_menus:edit:newsection:details') . '</p.>';
			//$content .= '<p><a href="javascript:void(0)," onClick="$(\'#menu-editor-form-edit\').append(\'<fieldset><legend>TEST</legend></fieldset>\');" class="elgg-button elgg-button-action">' . elgg_echo('elgg_menus:edit:newsection') . '</a></p>';
			
		}
	
}



echo '<div id="menu-editor-admin">';
echo $content;
echo '</div>';
?>

<script type="text/javascript">
$(document).ready(function(){
	$(".menu-editor-items").sortable({ // initialisation de Sortable sur le container parent
		placeholder: 'menu-editor-highlight', // classe du placeholder ajouté lors du déplacement
		connectWith: '.menu-editor-items', 
		// Custom callback function
		update: function(event, ui) {
			//ui.item
			// Changement d'ordre : priorité entre celle des entrées suivante et précédente 
			// (au moins identique à celle entrée précédente, car l'ordre sera conservé, de facto si on enregistre dans l'ordre)
			
			// Changement de contexte : default si default ou vide
		}
	});
});
</script>

