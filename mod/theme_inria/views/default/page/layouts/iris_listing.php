<?php
/**
 * Iris v2 layout for river and content listing
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

$class = 'elgg-layout elgg-layout-one-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// ESOPE : Add context class, for page differenciation
global $CONFIG;
if ($CONFIG->context) foreach ($CONFIG->context as $context) {
	$class .= ' elgg-context-' . $context;
}

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

?>

<div class="iris-listing">
	
	<div class="<?php echo $class; ?>">
		<?php if ($vars['sidebar']) { ?>
			<div class="menu-sidebar-toggle"><i class="fa fa-th-large"></i> <?php echo elgg_echo('esope:menu:sidebar'); ?></div>
			<div class="elgg-sidebar iris-search-sidebar">
				<h2 class="hidden"><?php echo elgg_echo('accessibility:sidebar:title'); ?></h2>
				<?php
					echo $vars['sidebar'];
				?>
			</div>
		<?php } ?>

		<div class="elgg-main elgg-body">
			<?php
				if (!$topmenu) { echo $nav; }
			
				echo elgg_view('page/layouts/elements/header', $vars);
			
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
	
</div>