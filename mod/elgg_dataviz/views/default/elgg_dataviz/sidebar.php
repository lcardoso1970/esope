<?php
$library = get_input('library');
//$viztype = get_input('viztype');

$view_url = elgg_get_site_url() . 'dataviz/view/';

$content = '';

// Main menu (library)
$content .= '<h3>' . elgg_echo('elgg_dataviz:libraries') . '</h3>';

$libraries = array(
	'chartjs' => array('bar', 'doughnut', 'line', 'line-customTooltips', 'pie', 'pie-customTooltips', 'polar-area', 'radar'), 
	'd3' => array('d3js_cfl', 'd3js_bubble', 'd3js_circle', 'd3js_scatter', 'd3js_line', 'd3js_sdg', 'd3js_radar', 'd3js_pie'), 
	'nvd3' => array('pie_chart', 'bar_chart', 'multibar_chart'), 
	'vega' => array('bar_chart'), 
	'dygraphs' => array('line_chart'), 
	'crossfilter' => array(), 
	'raphael' => array('sample', 'sample_clock'), 
);

$content .= '<ul>';
foreach ($libraries as $lib => $viztypes) {
	$content .= '<li><a href="' . $view_url . $lib . '">' . elgg_echo("elgg_dataviz:library:$lib") . '</a><br /><em>' . elgg_echo("elgg_dataviz:library:$lib:details") . '</em></li>';
}
$content .= '</ul>';


// Visualisations menu (secondary)
if (!empty($library)) {
$content .= '<br />';
	$content .= '<h3>' . elgg_echo('elgg_dataviz:library:' . $library) . '</h3>';
	$content .= '<ul>';
	foreach ($libraries[$library] as $viz) {
		$content .= '<li><a href="' . $view_url . $library . '/' . $viz . '">' . elgg_echo("elgg_dataviz:viztype:$viz") . '</a></li>';
	}
	$content .= '</ul>';
}


echo $content;

