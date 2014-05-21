<?php
global $CONFIG;
$imgurl = $CONFIG->url . 'mod/theme_cocon/graphics/';
$fonturl = $CONFIG->url . 'mod/theme_cocon/fonts/';
?>

/* Add some fonts */
@font-face {
	font-family: 'Montserrat';
	src: url('<?php echo $fonturl; ?>Montserrat/Montserrat-Regular.ttf') format('truetype');
}
@font-face {
	font-family: 'Montserrat';
	font-weight: bold;
	src: url('<?php echo $fonturl; ?>Montserrat/Montserrat-Bold.ttf') format('truetype');
}
@font-face {
	font-family: 'MontserratBold';
	src: url('<?php echo $fonturl; ?>Montserrat/Montserrat-Bold.ttf') format('truetype');
}


/* Interface */
html, body { background: url(<?php echo $imgurl; ?>background.jpg) top left no-repeat; background-size: cover; background-attachment:fixed; border-top:0; }
header { background: transparent; border-top:0; height:120px; }
header h1 { margin-top: 17px; }
header h1:hover { text-decoration:none; }
header nav { background: #00668c; top: 0; padding: 7px 4px; border-radius: 0 0 8px 8px; }
header nav ul li { margin-left:3px; margin-right:3px; }
header nav ul li a { line-height: 20px; text-align: center; font-size: 10px; font-weight: normal; color:white; text-shadow:none; padding: 0; display: inline-block; vertical-align: top; }
header nav ul li a img { width: 18px; height: 18px; background: white; border-radius: 10px; border:1px solid white; }
header nav a .fa { margin-right: 0; width: 18px; height: 18px; line-height: 18px; background: white; border-radius: 10px; border:1px solid white; font-size:13px; color: #00658d; }
header nav ul li.invites a, header nav ul li.group-invites a { width: auto; height: auto; background: red !important; line-height: initial; padding: 2px 5px 2px 6px !important; font-size:8px; }
.interne nav ul li.invites,.interne nav ul li.group-invites { margin: -4px 0 0 0; }

#transverse { box-shadow:none; border-bottom:0; background:white; height:40px; }
#transverse nav ul li { border-right:0; }
#transverse nav ul li:first-child { border-left:0; }
#transverse nav ul li a { color:#2a7d9f; line-height: 40px; padding: 0 10px; font-size: 17px; }
#transverse nav ul li ul li a { line-height: 2em; font-weight:normal; }
#transverse nav ul li a.active, #transverse nav ul li a.elgg-state-selected, #transverse nav ul li a:hover, #transverse nav ul li a:focus, #transverse nav ul li a:active { background-color: #02658e; color: white; }
#transverse nav ul li ul li a:hover, #transverse nav ul li ul li a:focus, #transverse nav ul li ul li a:active { background: #02658e; }
/* Search form */
#transverse form { border: 0; }
::-webkit-input-placeholder { color:#2a7d9f; }
:-moz-placeholder { color:#2a7d9f; }
::-moz-placeholder { color:#2a7d9f; }
:-ms-input-placeholder { color:#2a7d9f; }
form input#adf-search-input, form input#adf-search-input:active, form input#adf-search-input:focus { color:#2a7d9f; border-radius:0; background:#e4ecf5; }
form input#adf-search-submit-button, form input#adf-search-submit-button:active, form input#adf-search-submit-button:focus { background:white !important; border:0 !important; }

/* Footer */
footer.footer-cocon { height: 37px; background:#2a7d9f; }
footer.footer-cocon ul { width:auto; }
footer.footer-cocon ul li { margin: 10px 7px 6px 0; }
footer.footer-cocon ul li:first-child { padding-left:0; }
footer.footer-cocon ul li a { font-size: 14px; font-family: Montserrat, sans-serif; }
footer.footer-cocon ul li { background: transparent url("<?php echo $imgurl; ?>puce_footer.png") left 5px no-repeat; }

.elgg-sidebar { background: rgba(255,255,255,0.9); }


/* Boutons @TODO */
.elgg-button {
	border: 1px solid white !important;
	/* background: transparent !important; */
	box-shadow: 0 1px 3px #000 !important;
	border-radius: 16px !important;
}
.elgg-button-action, .elgg-menu .elgg-button-action, .elgg-button-submit, 	.elgg-button-action:hover, .elgg-button-action:focus, .elgg-button-action:active, .elgg-menu .elgg-button-action:hover, .elgg-menu .elgg-button-action:focus, .elgg-menu .elgg-button-action:active, .elgg-button-submit:hover, .elgg-button-submit:focus, .elgg-button-submit:active { color:#00658f; }

.home-box .viewall, span.groups-widget-viewall { border: 1px solid white; border-radius: 20px; display: inline-block; padding: 3px; max-width: 24px; text-align: center; text-transform: uppercase; margin: 3px 3px 0 0; background: rgba(255,255,255,0.2); font-size: 8px; }
.home-box .viewall a, module span.groups-widget-viewall a { font-size: 7px; color:white; text-decoration:none; }

/* Messages */
.elgg-state-succes { color:#78ad45; }
.elgg-state-error { color:#c34840; }
.elgg-state-notice { color:#397188; }

/* Accueil déconnecté */
#adf-loginbox { padding-top:6px; }

/* Accueil */
.elgg-context-dashboard .elgg-main, .elgg-context-dashboard #slider1 { background: transparent; }
.elgg-context-dashboard .anythingSlider { min-height: 300px; background: #397188; font-family: Montserrat; }
.elgg-context-dashboard .anythingSlider * { color: white; }
.anythingSlider h3, .anythingSlider p { padding: 12px 0 0 10px; }
.anythingSlider ul ul, .anythingSlider li li { padding-left: 10px; font-size:14px; }
#adf-homepage .anythingSlider li li { /* list-style-type: circle; */ }
.elgg-context-dashboard .anythingControls { position: absolute; bottom: 20px; left: 20px; display: none; }
.elgg-context-dashboard .anythingControls li { width: 40px; border-radius: 20px; border: 1px solid white; height: 40px; text-align: center; line-height: 40px; color: white; }
.elgg-context-dashboard .anythingSlider .arrow span { visibility:initial; }
.elgg-context-dashboard span.arrow { position: absolute; bottom: 20px; width: 40px; border-radius: 20px; border: 1px solid white; height: 40px; text-align: center; line-height: 40px; color: white; z-index: 11; background:rgba(0,0,0,0.5); }
.elgg-context-dashboard span.back { left: 20px; content: "<"; }
.elgg-context-dashboard span.forward { right: 20px; content: ">"; }

.home-box { background:white; }
.home-box .sidebarBox, .home-box #sidebar-featured-groups { margin: 0 0 30px 0; }
.home-box h2, .home-box h3, .home-box h2 a, .home-box h3 a { color: white; text-decoration:none; font-family: Montserrat; font-weight: normal; font-size: 17px; }
.sidebarBox h3, #sidebar-featured-groups h3 { background: #c5dc1c; min-height: 35px; padding: 12px 4px 0 10px; line-height:1.2; }
.home-box h2 img { float:left; margin-right:15px; }
/* Activity */
.home-box.home-activity { background:white; }
.home-box.home-activity h2 { padding: 12px 10px; line-height:34px; background:#bc9a33; min-height: 45px; }
.home-box.home-activity .elgg-river-item { padding: 12px 10px; }
.elgg-context-dashboard .elgg-list-river > li:hover { background-color: transparent; }
/* Members */
.membersWrapper { padding: 0 10px; }

/* The Wire */
.home-box.home-wire { background:white; }
.home-box.home-wire h2 { background: #e45833; padding: 12px 0 0 0px; width: 100%; line-height:34px; min-height: 45px; }
.home-box.home-wire .elgg-item { padding: 0 10px; }

/* Widgets */
section .elgg-layout-one-column div.module { border-radius:0; border:0; }
section .elgg-layout-one-column div.module header { border-radius:0; }
.cocon-widget-add-control { width: 22%; float: right; background: #00668c; padding: 8px 10px; margin-bottom: 10px; text-align: left; }
.cocon-widget-add-button { background: white; color: #00668c; border-radius: 14px; padding: 5px 8px; font-size: 8px; text-transform: uppercase; font-weight: bold; display: inline-block; }
#widgets-add-panel { background: white; border: 0; }
#widgets-add-panel li { border: 0; color: white; background-color: #00668c; }

/* Couleurs associées aux outils */
li#elgg-widget-type-group_activity { background-color: #98519d; }
li#elgg-widget-type-a_users_groups { background-color: #c5dc1c; }
li#elgg-widget-type-messages { background-color: #c8596a; }
li#elgg-widget-type-event_calendar { background-color: #78ad45; }
li#elgg-widget-type-thewire { background-color: #f23a32; }
li#elgg-widget-type-profile_completeness { background-color: #feb743; }
li#elgg-widget-type-blog { background-color: #7e88c3; }
li#elgg-widget-type-bookmarks { background-color: #c07a9e; }
li#elgg-widget-type-pages { background-color: #c34840; }
li#elgg-widget-type-filerepo { background-color: #80aa84; }
li#elgg-widget-type-friends { background-color: #397188; }
li#elgg-widget-type-points_left {  }


/* Boutons */
a.elgg-button { border-width: 2px; border-radius: 12px; }

/* More button */
.elgg-widget-more { line-height: 40px; /* color: white; */ background: transparent; }
.elgg-widget-more:before { text-shadow: none; border: 1px solid white; border-radius: 20px; text-align: center; width: 40px; height: 40px; line-height: 40px; /* color: white; */ display: inline-block; margin: 2px 6px; }

/* Users and avatars */
.elgg-avatar-tiny > a > img { border-radius: 10px; border: 1px solid white; }
.elgg-avatar-small > a > img { border-radius: 20px; border: 1px solid white; }

/* Feedback */
#feedbackWrapper { top: 170px; }

/* Chat */
.elgg-page #groupchat-sitelink { height: 30px; width:28px; line-height:30px; border: 0; border-radius:15px; padding: 0px 16px 0px 6px; top:7px; }
.elgg-page #groupchat-sitelink i.fa { font-size: 30px; }
.elgg-page #groupchat-grouplink { height: 30px; width:28px; line-height:30px; border: 0; border-radius:15px; padding: 0px 16px 0px 6px; color: white; background: #c5dc1c; }
.elgg-page #groupchat-grouplink i.fa { font-size: 30px; }


/* Agenda */
.elgg-context-event_calendar .elgg-sidebar { background: transparent; }

