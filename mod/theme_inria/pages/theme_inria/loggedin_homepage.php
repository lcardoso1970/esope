<?php
global $CONFIG;

gatekeeper();

$own = elgg_get_logged_in_user_entity();
inria_check_and_update_user_status('login', 'user', $own);

// Premiers pas : s'affiche au début, peut être désactivé
$firststeps = '';
// Masqué ou réaffiché sur demande
$hide_firststeps = get_input('firststeps', false);
if ($hide_firststeps == 'hide') { $own->hide_firststeps = 'yes'; } 
else if ($hide_firststeps == 'show') { $own->hide_firststeps = 'no'; }
// Affiché jusqu'à ce qu'on demande à le masquer
if ($own->hide_firststeps != 'yes') {
	$firststeps = elgg_view('cmspages/view', array('pagetype' => 'premiers-pas'));
	if (!empty($firststeps)) {
		$firststeps = '<header><div class="iris-firststeps">
				<div class="firststeps">
					' . $firststeps . '
					<a href="?firststeps=hide" class="firststeps-disable">Ne plus afficher</a>
				</div>
			</div></header>
			<div class="clearfloat"></div>';
	}
}


// Slider
$slider_params = array(
		'sliderparams' => "theme:'cs-portfolio', buildStartStop:false, resizeContents:false, ", 
		'slidercss_main' => "width:100%; height:400px;", 
		'width' => '100%',
		'height' => '400px', 
	);
$slider = elgg_view('slider/slider', $slider_params);

// Texte intro configurable
//$intro = elgg_get_plugin_setting('dashboardheader', 'adf_public_platform');
$intro = '<div class="home-news">' . elgg_view('cmspages/view', array('pagetype' => 'home-loggedin-news')) . '</div>';


// Le Fil
$thewire = '<h3><a href="' . $CONFIG->url . 'thewire/all">Inria, le Fil</a></h3>' . elgg_view_form('thewire/add', array('class' => 'thewire-form')) . elgg_view('input/urlshortener');
//elgg_push_context('widgets');
$thewire .= elgg_list_entities(array('type' => 'object', 'subtype' => 'thewire', 'limit' => 3, 'pagination' => false));
//elgg_pop_context('widgets');

// Activité du site
//if ($homesite_index == 'yes') {
	$site_activity = '<h3><a href="' . $CONFIG->url . 'activity">' . elgg_echo('adf_platform:site:activity') . '</a></h3>';
	elgg_push_context('widgets');
	$db_prefix = elgg_get_config('dbprefix');
	$site_activity .= elgg_list_river(array('limit' => 3, 'pagination' => false));
	elgg_pop_context();
//}

// Tableau de bord
// Note : il peut être intéressant de reprendre le layout des widgets si on veut séparer les colonnes et les intégrer dans l'interface
elgg_set_context('dashboard');
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
// Pour afficher un bloc sur 2 colonnes, on utilise la partie content
$params = array( 'content' => '', 'num_columns' => 3, 'show_access' => false);
$widget_body = elgg_view_layout('widgets', $params);


$firststeps = $_SERVER["HTTPS"];
if ($_SERVER["HTTPS"]) $firststeps .= "HTTPS -  ";
else $firststeps .= "HTTP sans S -  ";
$firststeps .= $_SERVER['SERVER_NAME'];


// Composition de la page
$body = $firststeps . '
	<div style="width:30%; float:left;">
		<div class="home-box home-wire">' . $thewire . '</div>
		<div class="clearfloat"></div>
		<div class="home-box home-activity">' . $site_activity . '</div>
	</div>
	
	<div style="width:50%; float:left; margin-left:2%;" class="iris-news">
		' . $slider . '
		<div class="clearfloat"></div>
		' . $intro . '
	</div>
	
	<div style="width:16%; float:right;">
		<div class="clearfloat"></div>
		<div class="home-box">' . elgg_view('theme_inria/sidebar_groups') . '</div>
		<div class="clearfloat"></div><br />
		<div class="home-box">' . elgg_view('theme_inria/users/online') . '</div>
		<div class="clearfloat"></div><br />
		<div class="home-box">' . elgg_view('theme_inria/users/newest') . '</div>
	</div>
	
	<div class="clearfloat"></div><br />
	' . $widget_body;

// Note : si on utilise la sidebar, il faut impérativement y placer un bloc de widgets
// sinon on déséquilibre trop la page
/*
$sidebar = elgg_view('theme_inria/sidebar_groups') . '
		<div class="clearfloat"></div><br />
		' . elgg_view('theme_inria/users/online') . '
		<div class="clearfloat"></div><br />
		' . elgg_view('theme_inria/users/newest');
*/

// Supprime le lien "main" (inexistant) de l'accueil
elgg_pop_breadcrumb();

$params = array( 'content' => $body, 'sidebar' => $sidebar, 'filter' => false);
$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);

