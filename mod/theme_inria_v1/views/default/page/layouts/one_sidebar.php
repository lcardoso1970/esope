<?php
/**
 * Layout for main column with one sidebar
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['content'] Content HTML for the main column
 * @uses $vars['sidebar'] Optional content that is added to the sidebar
 * @uses $vars['nav']     Optional override of the page nav (default: breadcrumbs)
 * @uses $vars['header']  Optional override for the header
 * @uses $vars['footer']  Optional footer
 * @uses $vars['class']   Additional class to apply to layout
 */

// Iris v2 : switch to custom layout if group page owner
$page_owner = elgg_get_page_owner_entity();
if (elgg_in_context('groups_add')) {
	echo elgg_view('page/layouts/iris_group_add', $vars);
	return;
} else if (elgg_instanceof($page_owner, 'group')) {
	echo elgg_view('page/layouts/iris_group', $vars);
	return;
}



$class = 'elgg-layout elgg-layout-one-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// Utiliser l'espace blanc pour menu ? => non
if (elgg_instanceof($page_owner, 'user')) {
	$class .= " elgg-layout-user-owner";
	$user_header = '';
	$user_header .= '<div class="iris-profile-header" style="background: ' . $banner_css . ';">' . elgg_view('profile/iris_owner_header', $vars) . '</div>';
	//$user_header .= '<div class="iris-profile-info">' . elgg_view('profile/iris_profile_info', $vars) . '</div>';
	//$user_header .= '<div class="iris-profile-info">' . '<br />' . '</div>';
	//$user_header .= '<div class="iris-profile-submenu">' . elgg_view_menu('owner_block', array('entity' => $page_owner)) . '</div>';
	$user_header .= '<div class="iris-profile-submenu"></div>';
	//if (!empty($vars['nav'])) $user_header .= '<div class="iris-profile-info">' . $vars['nav'] . '</div>';
	//echo elgg_view('page/layouts/iris_user', $vars);
	//return;
}

// Iris v2 : remove sidebar for specific context (profile edit of another user) => NO !   same layout but different header (based on owner)
// @TODO Iris profile layout ?
//echo print_r(elgg_get_context_stack(), true);
// Back button
// Contextes : settings = params perso mais avec sidebar
//if (elgg_instanceof($page_owner, 'user') || elgg_in_context('profile_edit')) {
if (elgg_in_context('profile_edit') || elgg_in_context('settings')) {
	$profile_back = '<div class="iris-back iris-profile-back"><a href="' . $page_owner->getURL() . '"><i class="fa fa-angle-left"></i> &nbsp; ' . elgg_echo('theme_inria:profile:back') . '</a></div>';
}
/*
if (elgg_in_context('profile_edit') && ($page_owner->guid != elgg_get_logged_in_user_guid())) {
	//$vars['nav'] = $profile_back;
	$vars['title'] .= $profile_back;
	$vars['content'] = $user_header . $vars['content'];
	echo elgg_view('page/layouts/one_column', $vars);
	return;
}
*/



// ESOPE : Add context class, for page differenciation
foreach(elgg_get_context_stack() as $context) {
	$class .= ' elgg-context-' . $context;
}

// navigation defaults to breadcrumbs
//$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));
$nav = elgg_extract('nav', $vars, '');

// Change layout for groups - but only show if group can be seen
/*
$page_owner = elgg_get_page_owner_entity();
if (elgg_instanceof($page_owner, 'group') && has_access_to_entity($page_owner)) {
	$topmenu = elgg_view('group/topmenu', array('entity' => $page_owner));
}
*/

$sidebar = elgg_view('page/elements/sidebar', $vars);
// @TODO Switch layout if no real content in sidebar
//if (empty($sidebar)) { return elgg_view_layout('one_column', $vars); }

// Iris v2 User banner instead of owner block
if (elgg_instanceof($page_owner, 'user')) { echo $user_header; }
?>

<div class="<?php echo $class; ?>">
	
	<?php
	// Inria : show nav and topmenu together here, and not above
	if ($topmenu) {
		echo $nav . '<br >';
		echo $topmenu;
	}
	?>
	
	<?php
	//echo elgg_view('page/layouts/elements/header', $vars);
	//if (!empty($vars['title'])) { echo '<h2>' . $vars['title'] . '</h2>'; }
	?>
	<h2 class="invisible"><?php echo elgg_echo('accessibility:sidebar:title'); ?></h2>
	<div class="menu-sidebar-toggle" title="<?php echo elgg_echo('esope:menu:sidebar'); ?>"><i class="fa fa-th-large"></i></div>
	<div class="elgg-sidebar">
		<div class="menu-sidebar-toggle hidden" style=""><i class="fa fa-compress"></i> <?php echo elgg_echo('hide') . ' ' . elgg_echo('esope:menu:sidebar'); ?></div>
		<?php
		// Bouton de retour depuis les params perso
		if (elgg_in_context('settings')) { echo $profile_back; }
		
		if (!empty($vars['title'])) { echo '<h2>' . $vars['title'] . '</h2>'; }
		//echo elgg_view('page/elements/sidebar', $vars);
		echo $sidebar;
		?>
	</div>

	<div class="elgg-main elgg-body">
		<?php
			if (!$topmenu) { echo $nav; }
			
			//echo elgg_view('page/layouts/elements/header', $vars);
			// Display only title menu
			echo elgg_view_menu('title');
			
			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
			echo elgg_view('page/layouts/elements/footer', $vars);
		?>
	</div>


</div>

