<?php
$urlfonts = elgg_get_site_url() . 'mod/esope/fonts/';
$urlicon = elgg_get_site_url() . 'mod/esope/img/theme/';
// Dégradés
// Couleur 1 : #0050BF - haut du dégradé header et pied de page
$color1 = elgg_get_plugin_setting('color1', 'esope');
// Couleur 4 : #002E6E - bas du dégradé header et pied de page
$color4 = elgg_get_plugin_setting('color4', 'esope');
?>

header {
	background: <?php echo $color4; ?> url("<?php echo $urlicon; ?>header.jpg") left 6px repeat-x scroll;
}
#transverse {
	background: #F6F6F6 url("<?php echo $urlicon; ?>fond-menu.jpg") left top repeat-x scroll;
}
#transverse form {
	width: 215px;
}
#transverse form input#search-input {
	padding-top: 6px;
	height: 19px;
}
footer {
	background: url("<?php echo $urlicon; ?>fond-footer.jpg") repeat-x scroll left top #626262;
}
footer ul {
	float: left;
	margin-left: 270px;
	width: 500px;
}

