<?php
// Integrate theme CSS - default wrapper
$titlecolor = elgg_get_plugin_setting('titlecolor', 'esope');
$textcolor = elgg_get_plugin_setting('textcolor', 'esope');
$linkcolor = elgg_get_plugin_setting('linkcolor', 'esope');
$linkhovercolor = elgg_get_plugin_setting('linkhovercolor', 'esope');
$color2 = elgg_get_plugin_setting('color2', 'esope');
$color3 = elgg_get_plugin_setting('color3', 'esope');
$font2 = elgg_get_plugin_setting('font2', 'esope'); // Title font
$font4 = elgg_get_plugin_setting('font4', 'esope'); // Main font
?>

/* Iris Newsletter Style */

body {
	background: #f6f6f6;
	color: #333333;
	font: 80%/1.4 "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
	word-wrap: break-word;
	-moz-hyphens: auto;
	-webkit-hyphens: auto;
	-ms-hyphens: auto;
	-o-hyphens: auto;
	hyphens: auto;
}

a {
	/* color: <?php echo $linkcolor; ?>; */
	color: #6D2D4F;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
	color: <?php echo $linkhovercolor; ?>;
}

img {
	border: none;
}
h1, h2, h3, h4 {
	/* olor: <?php echo $titlecolor; ?>; */
	color: #6D2D4F;
	margin: 0;
}
h2 {
	color: #EF783E;
}

h1 {
	font-size: 20px;
}

h2 {
	font-size: 18px;
}

h3 {
	font-size: 16px;
}

h4 {
	font-size: 14px;
}

#newsletter_online {
	font-size: 11px;
	color: #999999;
	text-align: center;
	padding: 10px 20px 0px;
	margin: 0 auto;
	width: 800px;
}

#newsletter_header {
	padding: 10px 30px;
	min-height: 20px;
	
	background: #6D2D4F;
	
	border: 1px solid #6D2D4F;
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}

#newsletter_header h1{
	color: #FFFFFF;
}

#newsletter_container {
	padding: 20px 0;
	width: 800px;
	margin: 0 auto;
}

#newsletter_content_wrapper {
	display: inline-block;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
}

#newsletter_sidebar {
	width: 179px;
	float: left;
	padding: 30px 10px;
}

#newsletter_content {
	width: 600px;
	float: right;
}

#newsletter_unsubscribe {
	font-size: 11px;
	/* 	color: <?php echo $linkcolor; ?>; */
	color: #999999;
	padding: 20px;
	text-align: center;
}

#newsletter_footer {
	background: #6D2D4F;
	color: white;
	
	border-top: 1px solid #FFFFFF;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
	border-right: 1px solid #dbdbdb;
	
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
	
	padding: 5px;
	text-align: right;
}
#newsletter_footer img { height:35px; }

.elgg-module-newsletter {
	background: #FFFFFF;
	padding: 30px;
	
	border-top: 1px solid #FFFFFF;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
	border-right: 1px solid #dbdbdb;
}

.elgg-module-newsletter .elgg-head {
	padding-bottom: 5px;
	border-bottom: 1px solid #dbdbdb;
}

.elgg-module-newsletter h1 a,
.elgg-module-newsletter h2 a,
.elgg-module-newsletter h3 a {
	text-decoration: none;
}
