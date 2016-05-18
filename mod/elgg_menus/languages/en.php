<?php
/**
 * English strings
 */

return array(
	'admin:appearance:menus' => "Custom Menus Editor",
	
	'elgg_menus:new' => "New menu",
	'elgg_menus:none' => "No menu",
	'elgg_menus:name' => "Menu name",
	'elgg_menus:name:details' => "If you edit an existing pre-defined menu, a new menu will be created with the elements below.",
	'elgg_menus:section' => "Section",
	'elgg_menus:selectedit' => "Menu to edit",
	'elgg_menus:edit:title' => "Menu edition",
	'elgg_menus:preview' => "Preview \"%s\" menu",
	'elgg_menus:preview:details' => "Only saved changes will be displayed.",
	// Edit
	'elgg_menus:item:edit' => "Edit",
	'elgg_menus:edit:newitem' => "New menu item",
	'elgg_menus:edit:newitem:details' => "To add a new item to the menu, click on the button below.<br />Define its (unique) identifier and its title, and optionally other parameters, then drag and drop it into the desired section.",
	'elgg_menus:add:newitem' => "Add a new menu item",
	'elgg_menus:edit:newsection' => "New menu section",
	'elgg_menus:edit:newsection:details' => "To add a new section, set the new section name for the wanted item, then save your changes.<br />You can also edit directly the section name of an item, then save your changes.",
	'elgg_menus:edit:message' => "The custom menu \"%s\" has been saved.",
	'elgg_menus:edit:error:empty' => "Error: no menu.",
	// Delete
	'elgg_menus:delete' => "Delete this item",
	'elgg_menus:delete:confirm' => "Are your sure you want to delete this item?",
	'elgg_menus:delete:message' => "The custom menu \"%s\" has been successfully deleted.",
	'elgg_menus:delete:error:empty' => "Error: no menu.",
	// Edit form
	'elgg_menus:settings:yes' => "Yes",
	'elgg_menus:settings:no' => "No",
	'elgg_menus:system' => "default",
	'elgg_menus:custom' => "custom",
	'elgg_menus:mode' => "Menu behaviour mode",
	'elgg_menus:mode:details' => "Les menus personnalisés peuvent fonctionner de 2 manières :<br />
		 - \"Fusion\" = les items configurés ci-dessous sont ajoutés au menu existant. Ceci est le mode recommandé, car il permet à d'autres plugins d'ajouter ou d'enlever des éléments du menu, mais vous permet également de retirer certains de ces items du menu si vous le souhiatez (avec les \"Items à supprimer\")<br />
		  - \"Remplacement\" = les items du menu remplacent et annulent le menu par défaut. A n'utiliser que pour des menus statiques, ou lorsque vous souhaitez contrôler totalement les items du menu. Attention, plus aucun plugin ne pourra enregistrer d'item dans ce menu.<br />
		  - \"Par défaut\" = le menu d'origine est utilisé tel quel, le menu personnalisé ne sera pas utilisé.<br />
		  - \"Désactivation\" = aucun menu ne sera affiché.<br />
		  ATTENTION : le remplacement du menu risque de ne pas fonctionner correctement avec les menus par défaut (ceux du système ou générés par d'autres plugins), car ceux-ci ne sont pas tous définis lors de l'initialisation des plugins.",
	'elgg_menus:mode:merge' => "Merge (adds items)",
	'elgg_menus:mode:replace' => "Replace (replaces the menu)",
	'elgg_menus:mode:clear' => "Disabled (no menu)",
	'elgg_menus:mode:disabled' => "Default (no change)",
	'elgg_menus:menu_class' => "CSS class",
	'elgg_menus:menu_class:details' => "Permet de spécifier des classes CSS précises pour ce menu.<br />Vous pouvez notamment ajouter <strong>elgg-menu-hz</strong> pour obtenir un menu horizontal.",
	'elgg_menus:menu_sort_by' => "Menu sort order",
	'elgg_menus:menu_sort_by:details' => "Permet de préciser l'ordre d'affichage des items du menu : les options possibles sont <b>name</b>, <b>priority</b>, <b>title</b>, <b>register</b> (ordre d'ajout au menu), ou le <b>nom d'un callback PHP</b> (une fonction de comparaison pour usort()).",
	'elgg_menus:menu_handler' => "Actions page handler",
	'elgg_menus:menu_handler:details' => "The page handler used to build URLs for the actions",
	'elgg_menus:menu_show_section_headers' => "Show sections",
	'elgg_menus:menu_show_section_headers:details' => "Disabled by default. Displays the section titles.",
	// Menu item
	'elgg_menus:item:name' => "Id",
	'elgg_menus:item:name:details' => "The identifier is a unique character string  used for a specific menu entry.",
	'elgg_menus:item:text' => "Name",
	'elgg_menus:item:text:details' => "Item title as it will be displayed.",
	'elgg_menus:item:href' => "Link URL",
	'elgg_menus:item:href:details' => "The link URL, if any. Can also be an anchor <strong>#anchor</strong> or a JS command.",
	'elgg_menus:item:title' => "Title (tooltip)",
	'elgg_menus:item:title:details' => "If a title is defined, the link will have a <b>title</b> property, which displays its content on hovering.",
	'elgg_menus:item:confirm' => "Confirm",
	'elgg_menus:item:confirm:details' => "Optional. If a confirmation message is defined, it will be displayed on a click on the link, offering the ability to cancel. Useful especially for actions.",
	'elgg_menus:item:item_class' => "Item CSS classes",
	'elgg_menus:item:item_class:details' => "Classes to add to the menu item (on <b>&lt;li&gt;</b> HTML tag).",
	'elgg_menus:item:link_class' => "Link CSS classes",
	'elgg_menus:item:link_class:details' => "Classes to add to the menu item link (on <b>&lt;a&gt;</b> HTML tag).",
	'elgg_menus:item:section' => "Section",
	'elgg_menus:item:section:details' => "The menu items can be grouped in sections. The default section is named 'default'.",
	'elgg_menus:item:priority' => "Priority",
	'elgg_menus:item:priority:details' => "A weight priority, from 1 to 1000. The entries will be displayed by ascending priority order.",
	'elgg_menus:item:contexts' => "Context(s)",
	'elgg_menus:item:contexts:details' => "The Elgg contexts in which this item will be displayed. For all, type 'all'. For several contexts, use: 'blog,groups,friends...'",
	'elgg_menus:item:parent_name' => "Parent identifier",
	'elgg_menus:item:parent_name:details' => "If this item is a submenu, type here the parent item identifier. If the parent itself is a submenu, the menu tree will have several sub-levels.",
	'elgg_menus:item:selected' => "Sélected?",
	'elgg_menus:item:selected:details' => "Permet de marquer cet item comme sélectionné, c'est-à-dire que la classe CSS <b>elgg-state-selected</b> sera ajoutée à cet item.",
	'elgg_menus:menu_remove' => "Items to remove (\"Merge\" mode)",
	'elgg_menus:menu_remove:details' => "Si le menu est en mode \"fusion\", vous pouvez définir ici une liste d'items qui seront retirés du menu par défaut. Il s'agit généralement d'items dynamiques (définis par une vue) que vous ne souhaitez pas voir appraître. Cela peut être particulièrement utile si vous ne souhaitez pas remplacer intégralement un menu, mais seulement retirer certains de ses items.",
	'elgg_menus:fieldset:menu_options' => "General menu parameters",
	'elgg_menus:fieldset:menu_options:details' => "Ces options s'appliquent avant tout aux menus personnalisés.<br />Elles ne pourront notamment pas être appliquées lorsque vous appelez un menu par la fonction elgg_view_menu(nom_du_menu).<br />A contrario, elles s'appliquent si vous utilisez la vue elgg_menus/menu",
	'elgg_menus:fieldset:menu_items' => "Menu items management",
	'elgg_menus:edit:items' => "Menu items",
	'elgg_menus:edit:items:details' => "Selon le mode choisi, ces items seront soit ajoutés au menu par défaut (fusion), soit remplacent ce menu.",
	'elgg_menus:edit:system_menu:details' => "Aucun menu personnalisé n'est encore défini pour ce menu. Les éléments du menu par défaut ont été pré-chargés.<br />Si vous souhaitez créer un menu personnalisé, éditez les éléments ci-dessous et enregistrez votre menu.",
	'elgg_menus:edit:custom_menu:details' => "Les items du menu personnalisé ci-dessous s'ajoutent à ceux du menu par défaut, ou les remplacent, selon le mode choisi.",
	'elgg_menus:edit:new_menu:details' => "Aucun menu n'est encore défini. Vous pouvez créer ce menu en ajoutant des items ci-dessous, puis en enregistrant vos modifications.",
	'elgg_menus:menu:create' => "Create this menu",
	'elgg_menus:menu:select' => "Display this menu",
	'elgg_menus:menu:save' => "Save changes",
	'elgg_menus:menu:delete' => "Delete custom menu \"%s\"",
	'elgg_menus:backups' => "Save and restore menus",
	'elgg_menus:backups:toggle' => "Click to display options",
	// Import
	'elgg_menus:import' => "Import",
	'elgg_menus:import:title' => "Import custom menu",
	'elgg_menus:import:title:details' => "Vous pouvez restaurer une configuration précédemment enregistrée. Les menus présents dans la sauvegarde s'ajouteront aux menus existants, ou les remplaceront s'ils existent déjà. Les menus personnalisés non présents dans la sauvegarde ne seront pas modifiés.",
	'elgg_menus:import:backup_file' => "Configuration file",
	'elgg_menus:import:filter' => "Name of menu to import",
	'elgg_menus:import:filter:details' => "If empty, imports all menus from file. If a menu name is defined, only this menu will be imported.",
	'elgg_menus:imported:menu' => "Menu \"%s\" successfully imported.",
	// Export
	'elgg_menus:export' => "Export",
	'elgg_menus:export:title' => "Export custom configuration",
	'elgg_menus:export:title:details' => "Vous pouvez exporter la configuration actuelle du menu sélectionné, ou de l'ensemble des menus, en cliquant sur l'un des liens suivants. En cas de besoin, vous pourrez les utiliser pour restaurer tous les menus personnalisés, ou l'un d'entre eux séparemment.<br />Attention, seules les modifications enregistrées seront exportées !",
	'elgg_menus:export:all_menus' => "Export all custom menus",
	'elgg_menus:export:menu' => "Export custom menu \"%s\"",
	'elgg_menus:export:message' => "The %s custom menus have been successfully exported. Keep the file %s to be able to import them later.",
	'elgg_menus:export:error:nomenu' => "Error: no menu to export",
	
	'elgg_menus:edit:newsection:prompt' => "New section name",
	
	'elgg_menus:sortby:text' => "By title",
	'elgg_menus:sortby:name' => "By identifier",
	'elgg_menus:sortby:priority' => "By priority",
	'elgg_menus:sortby:register' => "By add order",
	'elgg_menus:sortby:customcallback' => "Callback => ",
	'elgg_menus:sortby:customcallback:placeholder' => "Fonction for usort",
	
);

