<?php
/**
 * French strings
 */

$footer_default = '<div class="mts clearfloat right"><a href="http://elgg.org"><img src="' . elgg_get_site_url() . '_graphics/powered_by_elgg_badge_drk_bckgnd.gif" alt="Site construit avec Elgg" width="106" height="15" /></a></div>';

$fr = array(
	
	'option:notify:email' => "Activer par email (par défaut)",
	'option:notify:site' => "Activer par le site (messages)",
	'option:notify:all' => "Activer toutes les méthodes",
	'option:notify:no' => "Désactiver toutes les méthodes",
	'option:empty' => "",
	
	'profile_edit' => "Edition du profil",
	'event_calendar:view' => "Voir un événement",
	
	//Theme settings
	'admin:appearance:adf_theme' => "Configuration du thème",
	'admin:appearance:main_theme_config' => "Configuration du thème",
	'adf_platform:configredirect' => "La configuration du thème est gérée via Paramètres > AccEssonne ~ Thème accessible",
	'adf_platform:config:interface' => "ELEMENTS DE L'INTERFACE",
	'adf_platform:config:publichomepage' => "PAGE D'ACCUEIL PUBLIQUE",
	'adf_platform:config:loggedhomepage' => "PAGE D'ACCUEIL CONNECTEE",
	'adf_platform:config:behaviour' => "COMPORTEMENTS ET REGLAGES",
	'adf_platform:config:toolslistings' => "PAGES DE LISTING DES OUTILS",
	'adf_platform:config:toolslistings:details' => "Ce réglage permet de modifier le comportement par défaut des pages de listing des blogs, fichiers, etc. Par défaut seuls les publications <em>personnelles</em> du membre sont listées (pas celles dans ses groupes). Vous pouvez choisir ici de les lister toutes.",
	'adf_platform:config:filters' => "FILTRES",
	'adf_platform:config:widgets' => "CHOIX DES WIDGETS",
	'adf_platform:settings:removeusermenutools' => "(expert) Supprime des menus de l'owner block des membres.<br />Utiliser l'id du lien, par ex: blog,liked_content,photos (SANS espace)",
	'adf_platform:settings:removeusertools' => "(expert) Supprime des liens de création des contenus personnels (hors groupe)).<br />Utiliser le nom interne, par ex: blog,bookmark (SANS espace)",
	'adf_platform:settings:user_exclude_access' => "(expert) Supprime les niveaux d'accès des membres. Indiquer les id, par ex: -2,0,1,2 (sans espace)",
	'adf_platform:settings:admin_exclude_access' => "(expert) Supprime les niveaux d'accès des admins (attention !). Indiquer les id, par ex: -2,0,1,2 (sans espace)",
	'adf_platform:config:groupinvites' => "INVITATIONS DANS LES GROUPES",
	'adf_platform:settings:opengroups:defaultaccess' => "Niveau d'accès par défaut des nouveaux contenus dans les groupes ouverts",
	'adf_platform:settings:closedgroups:defaultaccess' => "Niveau d'accès par défaut des nouveaux contenus dans les groupes restreints",
	'adf_platform:groupdefaultaccess:default' => "Idem que celui par défaut pour le site",
	'adf_platform:groupdefaultaccess:group' => "Membres du groupe",
	'adf_platform:groupdefaultaccess:groupvis' => "Idem que celui du groupe",
	'adf_platform:groupdefaultaccess:members' => "Membres du site",
	'adf_platform:groupdefaultaccess:public' => "Public",
	'adf_platform:settings:groupjoin_enablenotif' => "Activer les notifications lorsqu'un membre rejoint un groupe",
	'adf_platform:config:grouptabs' => "ONGLETS DES GROUPES",
	'adf_platform:settings:groups:alpha' => "Onglet tri alphabétique",
	'adf_platform:settings:groups:newest' => "Onglet liste par date",
	'adf_platform:settings:groups:popular' => "Onglet liste par membres",
	'adf_platform:settings:groups:discussion' => "Onglet des discussions de forum",
	'adf_platform:settings:groups:discussion:always' => "Toujours affiché (après la liste des groupes)",
	'adf_platform:settings:groups:tools_default' => "Activer tous les outils des groupes lors de la création",
	'adf_platform:settings:groups:tools_default:auto' => "Auto (selon les plugins)",
	'groups:alpha' => "Alphabétique",
	'adf_platform:config:memberssearch' => "PAGE DE RECHERCHE DE MEMBRES",
	'adf_platform:settings:members:alpha' => "Onglet tri alphabétique",
	'adf_platform:settings:members:newest' => "Onglet liste par date",
	'adf_platform:settings:members:popular' => "Onglet liste par contacts",
	'adf_platform:settings:members:onlinetab' => "Onglet liste en ligne",
	'members:label:alpha' => "Alphabétique",
	'adf_platform:config:contacts' => "CONTACTS ET COORDONNEES",
	'adf_platform:config:contacts:details' => "Note : ces coordonnées ne sont pas utilisées par tous les thèmes (actuellement : seulement compétences numériques)",
	'adf_platform:config:styles' => "COULEURS & STYLE",
	'adf_platform:config:styles:headerfooter' => "Dégradé du header et du pied de page",
	'adf_platform:config:styles:groupmodules' => "Dégradé des widgets et modules des groupes",
	'adf_platform:config:styles:buttons' => "Dégradé des boutons (normal puis :hover)",
	'adf_platform:config:saverestore' => "SAUVEGARDE ET RESTAURATION - <i>BETA</i>",
	'adf_platform:config:saverestore:details' => "Cette fonctionnalité vous permet de sauvegarder/exporter les paramètres de votre thème, et d'importer les données d'une sauvegarde précédente ou d'un autre thème. Cela peut vous servir à des fins de sauvegarde d'une version particulière de votre thème, mais aussi à tester différents thèmes ou réglages tout en conservant la possibilité de revenir à votre configuration initiale, ou encore à transférer votre thème d'un site à un autre.",
	'adf_platform:config:import' => "Import / restauration",
	'adf_platform:config:import:details' => "Pour importer les paramètres depuis un autre site ou restaurer une sauvegarde précédente, collez-ci-dessous les données, puis enregistrez les paramètres.<br />ATTENTION : les paramètres existants seront remplacés par ceux de la sauvegarde ! Il est vivement conseillé de sauvegarder les anciens paramètres du plugin au préalable...<br />Note importante : seuls les paramètres définis dans la sauvegarde sont remplacés ; si de nouveaux champs ont été ajoutés, ou si certains réglages ne font pas partie de la sauvegarde, les paramètres actuels seront conservés.",
	'adf_platform:config:export' => "Export / sauvegarde",
	'adf_platform:config:export:details' => "Copiez-collez le contenu du bloc ci-dessous et conservez-le dans un fichier texte ou dans un mail. Pour tout sélectionner, cliquez dans la zone texte, puis Ctrl-C (ou Pomme-C) pour copier le texte.",
	
	
	// Overrides plugins translations
	// Note : ces ajouts sont faits ici plutôt que dans les plugins concernés de sorte qu'une mise à jour ait le moins d'incidence possible sur ces traductions 
  'river:comment:object:announcement' => "%s a commenté %s",
  'widgets:profile_completeness:view:tips:link' => "<br />%s&raquo;&nbsp;Compléter mon profil !%s",
	
	'widget:toggle' => "Montrer/masquer le module %s",
	'widget:editmodule' => "Configurer le module %s",
	
	// Announcements: missing translation keys - Annonces : manque des clefs de trad
	'announcements:summary' => "Titre de l'annonce",
	'announcements:body' => "Texte de l'annonce",
	'announcements:post' => "Publier l'annonce",
	'announcements:edit' => "Modifier l'annonce",
	'announcements:delete:nopermission' => "Impossible de supprimer l'annonce : vous n'avez pas les permissions suffisantes",
	'announcements:delete:failure' => "Impossible de supprimer l'annonce.",
	'announcements:delete:sucess' => "Annonce publiée",
	'object:announcement:save:permissiondenied' => "Impossible d'enregistrer l'annonce : vous n'avez pas les permissions suffisantes",
	'object:announcement:save:descriptionrequired' => "Impossible d'enregistrer l'annonce : le texte de l'annonce ne peut être vide.",
	'object:announcement:save:success' => "Annonce enregistrée",
	'item:object:category' => "Thématiques utilisées",
	'item:object:topicreply' => "Réponse dans un forum",
	
	// Theme translation & other customizations
	// Traductions du thème et autres personnalisations
	'adf_platform:groupinvite' => "invitation à rejoindre un groupe à examiner",
 	'adf_platform:groupinvites' => "invitations à rejoindre un groupe à examiner",
	'adf_platform:friendinvite' => "demande de contact à examiner",
	'adf_platform:friendinvites' => "demandes de contact à examiner",
	'adf_platform:gotohomepage' => "Aller sur la page d'accueil",
	'adf_platform:usersettings' => "Mes paramètres",
	'adf_platform:myprofile' => "Mon profil",
	'adf_platform:help' => "Aide",
	'adf_platform:loginregister' => "Connexion / inscription",
	'adf_platform:joinagroup' => "Rejoindre un groupe",
	'adf_platform:categories' => "Thématiques",
	'adf_platform:directory' => "Annuaire",
	'adf_platform:event_calendar' => "Agenda",
	'adf_platform:search' => "Rechercher",
	'adf_platform:search:defaulttext' => "Trouvez dans le site...",
	'adf_platform:groupicon' => "icône du groupe",
	'adf_platform:categories:all' => "Actualité des thématiques",
	'adf_platform:members:online' => "Membres connectés",
	'adf_platform:members:newest' => "Derniers inscrits",
	'adf_platform:groups:featured' => "Groupes à la Une",
	
	// Widgets
	'adf_platform:widget:bookmark:title' => 'Liens web',
	'adf_platform:widget:brainstorm:title' => 'Idées',
	'adf_platform:widget:blog:title' => 'Articles',
	'adf_platform:widget:event_calendar:title' => 'Agenda',
	'adf_platform:widget:file:title' => 'Fichiers',
	'adf_platform:widget:group:title' => 'Groupes',
	'adf_platform:widget:page:title' => 'Wikis',
	'adf_platform:widget:user_activity:title' => "Activité du site",
	'adf_platform:widget:user_activity:description' => "Affiche vos dernières activités",
	'adf_platform:widget:site_activity:title' => "Activité récente",
	'adf_platform:widget:site_activity:description' => "Affiche les dernières activités du site",
	
	'accessibility:sidebar:title' => "Outils",
	//'breadcrumb' => "Fil d'Ariane",
	'breadcrumbs' => "Revenir à ",
	// Demandes en attente
	'decline' => "Décliner",
	'refuse' => "Refuser",
	/* Pagination */
	'previouspage' => "Page précédente",
	'nextpage' => "Page suivante",
	/* Recherche de membres */
	'searchbytag' => "Recherche par mot-clef",
	'searchbyname' => "Recherche par nom",
	// Actions génériques à "typer"
	'delete:message' => "Supprimer le(s) message(s)",
	'markread:message' => "Marquer le(s) message(s)  comme lu(s)",
	'toggle:messages' => "inverser la sélection des messages",
	'messages:send' => "Envoyer le message",
	'save:newgroup' => "Créer le groupe !",
	'save:group' => "Enregistrer les modifications du groupe",
	'upload:avatar' => "Charger la photo",
	'option:force' => "Oui (forcé)",
	'save:settings' => "Enregistrer la configuration",
	'save:usersettings' => "Enregistrer mes paramètres",
	'save:usernotifications' => "Enregistrer mes paramètres de notification pour les membres",
	'save:groupnotifications' => "Enregistrer mes paramètres de notification pour les groupes",
	'save:widgetsettings' => "Enregistrer les réglages du module",
	'groups:join:success' => "Groupe rejoint avec succès",
	// Notifications
	'link:userprofile' => "Page de profil de %s",
	
	// Params widgets
	'onlineusers:numbertodisplay' => "Nombre maximum de membres connectés à afficher",
	'newusers:numbertodisplay' => "Nombre maximum de nouveaux membres à afficher",
	'brainstorm:numbertodisplay' => "Nombre maximum d'idées à afficher",
	'river:numbertodisplay' => "Nombre maximum d'activités à afficher",
	'group:widget:num_display' => "Nombre maximum de groupes à afficher",
	
	'more:friends' => "Plus de contacts", 
	
	// New group
	// @TODO : Ce texte devrait être adapté à votre site !
	// use $CONFIG->url for site install URL, $CONFIG->email for site email
	'groups:newgroup:disclaimer' => "<blockquote><strong>Extrait de la Charte :</strong> <em>toute personne ou groupe de personnes souhaitant créer un groupe - à la condition de <a href=\"mailto:" . $CONFIG->email . "&subject=Demande%20de%20validation%20de%20groupe&body=Contact%20%depuis%20la%20page%20http%3A%2F%2Fdepartements-en-reseaux.fr%2Fgroups%2Fadd%2F129\" title=\"Ecrire au secrétariat de la plateforme\">se déclarer comme animateur de ce groupe auprès du secrétariat de la plateforme</a>, dispose de droits d’administrateur sur les accès à ce groupe et s’engage à y faire respecter les <a href=\"' . $CONFIG->url . 'pages/view/3792/charte-de-dpartements-en-rseaux\">règles d’utilisation et de création de contenus de « Départements-en-réseaux »</a></em></blockquote>",
	'groups:search:regular' => "Recherche de groupe",
	'groups:regularsearch' => "Nom ou mot-clef",
	'search:group:go' => "Rechercher un groupe",
	'members:search' => "Rechercher un membre",
	
	// 
	'accessibility:allfieldsmandatory' => "<sup class=\"required\">*</sup> Tous les champs sont obligatoires",
	'accessibility:requestnewpassword' => "Demander la réinitialisation du mot de passe",
	'accessibility:revert' => "Supprimer",
	
	
	'adf_platform:homepage' => "Accueil",
	'announcements' => "Annonces",
	'event_calendar' => "Agenda",
	
	'adf_platform:access:public' => "Public (accessible hors connexion)",
	
	'brainstorm:widget:description' => "Affiche la liste de vos idées de remue-méninges.",
	'bookmarks:widget:description' => "Affiche la liste de vos liens web.",
	'pages:widget:description' => "Affiche la liste de vos pages wikis.",
	'event_calendar:widget:description' => "Affiche les événements à venir de votre agenda personnel.",
	'event_calendar:num_display' => "Nombre d'événements à afficher",
	'messages:widget:title' => "Messages non lus",
	'messages:widget:description' => "Affiche les derniers messages non lus de votre boîte de réception.",
	'messages:num_display' => "Nombre de messages à afficher",
	
	
	// Layout settings
	'adf_platform:settings:help' => "Les différentes rubriques de configuration vous permettent de configurer de nombreux éléments du thème (éléments graphiques, d'interface, couleurs, feuilles de styles, etc.), ainsi que certains comportements du site.",
	'adf_platform:settings:layout' => "Pour retrouver la configuration initiale, remplacez le contenu par \"RAZ\" (en mode HTML).",
	'adf_platform:faviconurl' => "URL de la favicon",
	'adf_platform:faviconurl:help' => "Indiquez le chemin de l'icône du site : il s'agit généralement d'un fichier favicon.ico ou .png ou .gif, de format carré et de 64px maxi.",
	'adf_platform:headertitle' => "Titre du site (cliquable, dans le bandeau)",
	'adf_platform:headertitle:help' => "Pour agrandir certains caractères, encadrez-les de balises, et utilisez la classe 'minuscule' pour changer la casse&nbsp;: &lt;span&gt;T&lt;/span&gt;itre.&lt;span class=\"minuscule\"&gt;fr&lt;/span&gt;",
	'adf_platform:header:content' => "Contenu de l'entête (code HTML libre). Pour retrouver la configuration initiale avec une image de logo configurable, remplacez le contenu par \"RAZ\" (en mode HTML).",
	'adf_platform:header:default' => '<div id="easylogo"><a href="/"><img src="' . $vars['url'] . '/mod/adf_public_platform/img/logo.gif" alt="Logo du site"  /></a></div>',
	'adf_platform:header:height' => "Hauteur de l'entête du menu (identique à celle de l'image de fond utilisée - ou inférieure)",
	'adf_platform:header:background' => "URL de l'image de fond de l'entête (apparaît également sous le menu)",
	'adf_platform:footer:color' => "Couleur de fond du footer",
	'adf_platform:footer:content' => "Contenu du footer",
	'adf_platform:footer:default' => $footer_default,
	'adf_platform:home:displaystats' => "Afficher les statistiques en page d'accueil",
	'adf_platform:css' => "Ajoutez ici vos styles CSS personnalisés",
	'adf_platform:css:help' => "Les CSS ajoutés ici surchargent la feuille de style (sans la remplacer), et viennent se charger après tous les autres modules. Ajoutez ici vos styles personnalisés",
	'adf_platform:css:default' => "/* Pour modifier le bandeau */\nheader {  }\n\n/* Les liens */\na, a:visited {  }\na:hover, a:active, a:focus {  }\n\n/* Les titres */\nh1, h2, h3, h4, h5 {  }\n/* etc. */\n",
	'adf_platform:dashboardheader' => "Zone configurable en entête du tableau de bord des membres.",
	'adf_platform:index_wire' => "Ajouter Le Fil sur l'accueil.",
	'adf_platform:index_groups' => "Afficher les groupes à la Une",
	'adf_platform:index_members' => "Afficher les membres connectés",
	'adf_platform:index_recent_members' => "Afficher les derniers inscrits",
	'adf_platform:homegroup_guid' => "Choisir le groupe principal / d'aide",
	'adf_platform:homegroup_index' => "Afficher les actualités du groupe ?",
	'adf_platform:homesite_index' => "Afficher les actualités du site ?",
	'adf_platform:homegroup_autojoin' => "Inscrire automatiquement les nouveaux membres dans ce groupe ? (si forcé, réinscrit les membres désinscrits)",
	
	'adf_platform:homeintro' => "Bloc en introduction de la page de connexion / inscription.",
	'adf_platform:settings:colors' => "Couleurs du thème",
	'adf_platform:fonts' => "Polices de caractères",
	'adf_platform:fonts:details' => "Ajoutez de nouvelles polices sur le serveur, ou via les règles CSS @import dans le bloc de règles CSS ci-dessous, puis utilisez-les sur les différents éléments du site via les réglages suivants.",
	'adf_platform:font1' => "Font 1 : Variante de Font 2, utilisée pour les titres de sections",
	'adf_platform:font2' => "Font 2 : La plupart des titres, entêtes des modules et widgets",
	'adf_platform:font3' => "Font 3 : Menu de navigation principal",
	'adf_platform:font4' => "Font 4 : Police par défaut, tous les textes",
	'adf_platform:font5' => "Font 5 : code, adresses.. Privilégiez une police à chasse fixe (monospace)",
	'adf_platform:font6' => "Font 6 : Non utilisée...",
	'adf_platform:colors' => "Couleurs",
	'adf_platform:colors:details' => "Vous pouvez modifier les principales couleurs de l'interface via ces réglages. Pour des ajustements plus fins, utilisez la feuille de style CSS personnalisée ci-dessous.",
	'adf_platform:title:color' => "Couleur des titres",
	'adf_platform:text:color' => "Couleur du texte",
	'adf_platform:link:color' => "Couleur des liens",
	'adf_platform:link:hovercolor' => "Couleur des liens au survol (et inversions de couleurs)",
	'adf_platform:color1:color' => "Haut du dégradé header",
	'adf_platform:color2:color' => "Haut du dégradé widgets/modules",
	'adf_platform:color3:color' => "Bas du dégradé widgets/modules",
	'adf_platform:color4:color' => "Bas du dégradé header",
	'adf_platform:color5:color' => "Haut du dégradé des boutons",
	'adf_platform:color6:color' => "Bas du dégradé des boutons",
	'adf_platform:color7:color' => "Haut du dégradé des boutons (hover)",
	'adf_platform:color8:color' => "Bas du dégradé des boutons (hover)",
	'adf_platform:color9:color' => "Couleur configurable 9",
	'adf_platform:color10:color' => "Couleur configurable 10",
	'adf_platform:color11:color' => "Couleur configurable 11",
	'adf_platform:color12:color' => "Couleur configurable 12",
	'adf_platform:color13:color' => "Couleur de fond du sous-menu déroulant",
	'adf_platform:color14:color' => "Titre des modules",
	'adf_platform:color15:color' => "Titre des boutons",
	'adf_platform:settings:remove_collections' => "Désactiver les collections de contacts",
	'widgets:dashboard:add' => "Personnaliser ma page d'accueil",
	'widgets:profile:add' => "Ajouter des modules à ma page de profil",
	'adf_platform:settings:publicpages' => "Listes des pages publiques (accessibles hors connexion)",
	'adf_platform:settings:publicpages:help' => "Les \"Pages publiques\" sont accessibles à tous, hors connexion. Elles permettent de rendre publics la charte, les mentions légales et autres pages importantes du site.<br />Indiquez une adresse complète de page (URL) par ligne, sans le nom de domaine et le slash initial ('/'), par exemple : pages/view/1234/mentions-legales",
	'adf_platform:home:public_profiles' => "Profil public au choix du membre ? Non = public ; Oui = opt-in (non-public par défaut)",
	'adf_platform:home:public_profiles:help' => "Ce réglage permet de donner la possibilité aux membres du site de choisir de rendre leur profil accessible depuis internet, sans compte sur le site. Par défaut leur profil sera réservé aux membres, jusqu'à-ce qu'ils choisissent de le rendre public. Si ce réglage est désactivé, les profils sont publics.<br />A noter : en mode \"intranet\", aucune page n'est visible de l'extérieur, y compris les pages de profil, et ce réglage n'a aucun effet.",
	'adf_platform:usersettings:public_profiles:title' => "Choisir la visibilité de mon profil",
	'adf_platform:usersettings:public_profile' => "Rendre mon profil public",
	'adf_platform:usersettings:public_profile:help' => "Par défaut votre profil n'est visible que des membres du site, afin de ne pas exposer votre profil publiquement sans votre accord volontaire. Ce réglage vous permet de le rendre accessible de l'extérieur.<br />Veuillez noter que tous vos autres réglages de visibilité des champs et des widgets qui composent votre page du profil restent valables : par exemple si vous avez choisi que votre numéro de téléphone ou la liste de contacts sont réservés à vos contacts, rendre votre profil public ne modifiera pas ce réglage, et cette information restera réservée à vos contacts.<br />Il est conseillé de rendre votre profil public si vous souhaitez présenter vos compétences ou partager certaines informations choisies sur internet.",
	'adf_platform:action:public_profile:error' => "Une erreur s'est lors de la modification de vos paramètres.",
	'adf_platform:action:public_profile:saved' => "La visibilité de votre profil a bien été modifiée.",
	'adf_platform:usersettings:public_profile:public' => "Votre profil est maintenant PUBLIC.",
	'adf_platform:usersettings:public_profile:private' => "Votre profil est maintenant RÉSERVÉ AUX MEMBRES.",
		
	
	// Behaviour settings
	'adf_platform:index:url' => "URL du fichier de la page d'accueil (doit pouvoir être inclus)",
	'adf_platform:settings:redirect' => "URL (relative) de redirection après connexion",
	'adf_platform:settings:replace_public_home' => "URL (relative) pour remplacer la page d'accueil publique (par défaut&nbsp;: laisser vide)",
	'adf_platform:settings:replace_public_homepage' => "Remplacer la page d'accueil publique ?",
	'adf_platform:replacehome:no' => "Non (page d'accueil par défaut)",
	'adf_platform:replacehome:default' => "Oui : utiliser la page par défaut du thème (configurable)",
	'adf_platform:replacehome:cmspages' => "Oui : utiliser une page CMS (homepage-public)",
	'adf_platform:homepage:cmspages:editlink' => "Editer la page d'accueil du site (nouvelle fenêtre)",
	'adf_platform:cmspages:notactivated' => "Attention : le plugin cmspages n'est pas activé. Veuillez l'activer ou changer les réglages du thème.",
	'adf_platform:settings:replace_home' => "Remplacer la page d'accueil connectée par un tableau de bord personnalisable",
	'adf_platform:settings:firststeps' => "GUID de la page des Premiers Pas (ou page d'aide au démarrage)",
	'adf_platform:settings:firststeps:help' => "Cette page s'affichera dans un bloc de la page d'accueil dépliable qui restera ouvert pendant un mois pour les nouveaux membres. Le GUID de la page est le nombre indiqué dans l'adresse de la page à utiliser : <em>" . elgg_get_site_url() . "/pages/<strong>GUID</strong>/premiers-pas</em>",
	'adf_platform:settings:footer' => "Contenu du pied de page",
	'adf_platform:settings:headerimg' => "Image du bandeau supérieur (85px de haut par défaut)",
	'adf_platform:settings:headerimg:help' => "Indiquez l'URL (relative) de l'image qui sera positionnée au centre du bandeau, sous le menu supérieur, et répétée si nécessaire horizontalement (motif). Utilisez une image de 85px de haut, et suffisamment large pour éviter d'être répétée sur un grand écran (2000px minimum). Pour des dimensions différentes, ajoutez dans les ci-dessous (en modifiant la hauteur) : <em>header { height:115px; }</em>",
	'adf_platform:settings:helplink' => "Page d'aide",
	'adf_platform:settings:helplink:help' => "Indiquez l'adresse de la page d'aide du site, correspondant au lien \"Aide\" du menu supérieur. Cette adresse doit être relative à celle du site (pas de lien externe).",
	'adf_platform:settings:backgroundimg' => "Motif de fond",
	'adf_platform:settings:backgroundimg:help' => "Indiquez l'URL (relative) de l'image qui sera répétée horizontalement et verticalement",
	'adf_platform:settings:backgroundcolor' => "Couleur de fond",
	'adf_platform:settings:backgroundcolor:help' => "La couleur de fond est utilisée si aucune image de fond n'est définie, ou le temps que cette image de fond soit chargée.",
	'adf_platform:settings:groups_disclaimer' => "Configuration du message à l'attention du futur responsable lors de la création d'un nouveau groupe. Pour que le message soit vide, laisser un espace seulement dans le champ.",
	'adf_platform:settings:blog_user_listall' => "Lister de tous les articles de blog (personnels + groupes)",
	'adf_platform:settings:bookmarks_user_listall' => "Listing de tous les liens web (personnels + groupes)",
	'adf_platform:settings:brainstorm_user_listall' => "Listing de toutes les idées (personnelles + groupes)",
	'adf_platform:settings:file_user_listall' => "Listing de tous les fichiers (personnels + groupes)",
	'adf_platform:settings:pages_user_listall' => "Listing de toutes les pages wiki (personnelles + groupes)",
	'river:select:all:nofilter' => "Tout (aucun filtre de l'activité)",
	'adf_platform:profile:settings' => "Page de profil des membres",
	'adf_platform:profile:add_profile_activity' => "Ajouter le flux d'activité",
	'adf_platform:profile:remove_profile_widgets' => "Supprimer les widgets du profil",
	'adf_platform:profile:custom_profile_layout' => "Utiliser un layout personnalisé",
	
	
	// Widget settings
	'adf_platform:settings:widget:blog' => "Activer le widget Blog",
	'adf_platform:settings:widget:bookmarks' => "Activer le widget Liens web",
	'adf_platform:settings:widget:brainstorm' => "Activer le widget Boîte à idées",
	'adf_platform:settings:widget:event_calendar' => "Activer le widget Mon agenda",
	'adf_platform:settings:widget:file' => "Activer le widget Mes fichiers",
	'adf_platform:settings:widget:groups' => "Activer le widget Mes groupes",
	'adf_platform:settings:widget:pages' => "Activer le widget Pages wiki",
	'adf_platform:settings:widget:friends' => "Activer le widget Mes contacts",
	'adf_platform:settings:widget:group_activity' => "Activer le widget Activité du groupe",
	'adf_platform:settings:widget:messages' => "Activer le widget Messages non lus",
	'adf_platform:settings:widget:river_widget' => "Activer le widget Activité globale du site",
	'adf_platform:settings:widget:twitter' => "Activer le widget Twitter",
	'adf_platform:settings:widget:tagcloud' => "Activer le widget Nuage de tags",
	'adf_platform:settings:widget:videos' => "Activer le widget Vidéos",
	'adf_platform:settings:widget:profile_completeness' => "Activer le widget Complétion du profil",
	'adf_platform:settings:widget:profile_completeness:help' => "Ce widget peut être activé/désactivé via la configuration du plugin Profile Manager",
	'webprofiles' => "Profils en ligne",
	'webprofiles:description' => "Profils sur divers grands réseaux sociaux, sites web et adresses mail",
	'webprofiles:widget:title' => "Profils en ligne",
	'webprofiles:widget:description' => "Affiche un bloc avec des liens vers vos profils, identités et contacts en ligne : vidadeo, facebook, linkedin, twitter, doyoubuzz, sites web et adresses mails, etc.",
	'webprofiles:widget:help' => "Renseignez vos identifiants sur divers sites pour afficher l'icône du service avec un lien vers votre profil. Vous pouvez également ajouter des sites web, adresses mail et fils RSS.",
	
	'adf_platform:settings:filters:friends' => "Supprimer l'onglet \"Contacts\" dans les listes de publications personnelles ? (par défaut : Non)",
	'adf_platform:settings:filters:mine' => "Supprimer l'onglet \"Moi\" dans les listes de publications personnelles ? (par défaut : Non)",
	'adf_platform:settings:filters:all' => "Supprimer l'onglet \"Tous\" dans les listes de publications personnelles ? (par défaut : Non)",
	'adf_platform:settings:groups:inviteanyone' => "Permettre d'inviter tout membre dans les groupes ? (par défaut : non = contacts seulement)",
	'adf_platform:groups:allowregister' => "Inscrire dans le groupe",
	'adf_platform:settings:groups:allowregister' => "Permettre aux responsables des groupes d'inscrire directement les membres dans le groupe, au lieu de simplement les inviter (il est toujours possible de les inviter).",
	'adf_platform:settings:members:onesearch' => "Ne garder que la recherche générale de membres ? (par défaut : Non)",
	'adf_platform:settings:members:online' => "Afficher les membres connectés dans la barre latérale (défaut : non)",
	
	'adf_platform:settings:contact:contactemail' => "Adresse email de contact",
	'adf_platform:settings:contactemail:help' => "",
	'adf_platform:settings:rss' => "URL du flux RSS",
	'adf_platform:settings:rss:help' => "",
	'adf_platform:settings:twitter' => "URL de la page Twitter (compte ou recherche sur hashtag)",
	'adf_platform:settings:twitter:help' => "",
	'adf_platform:settings:facebook' => "URL de la page/compte Facebook",
	'adf_platform:settings:facebook:help' => "",
	'adf_platform:settings:googleplus' => "URL de la page/compte Google+",
	'adf_platform:settings:googleplus:help' => "",
	'adf_platform:settings:linkedin' => "URL de la page/compte Linkedin",
	'adf_platform:settings:linkedin:help' => "",
	'adf_platform:settings:netvibes' => "URL de la page/compte Netvibes",
	'adf_platform:settings:netvibes:help' => "",
	'adf_platform:settings:flickr' => "URL de la page/compte FlickR",
	'adf_platform:settings:flickr:help' => "",
	'adf_platform:settings:youtube' => "URL de la page/compte Youtube",
	'adf_platform:settings:youtube:help' => "",
	'adf_platform:settings:dailymotion' => "URL de la page/compte Dailymotion",
	'adf_platform:settings:dailymotion:help' => "",
	'adf_platform:settings:pinterest' => "URL de la page/compte Pinterest",
	'adf_platform:settings:pinterest:help' => "",
	'adf_platform:settings:tumblr' => "URL de la page/compte Tumblr",
	'adf_platform:settings:tumblr:help' => "",
	'adf_platform:settings:slideshare' => "URL de la page/compte Slideshare",
	'adf_platform:settings:slideshare:help' => "",
	

	// DATES
	'date:format' => 'D d F Y',
	'date:day:0' => 'Dimanche',
	'date:day:1' => 'Lundi',
	'date:day:2' => 'Mardi',
	'date:day:3' => 'Mercredi',
	'date:day:4' => 'Jeudi',
	'date:day:5' => 'Vendredi',
	'date:day:6' => 'Samedi',
	'date:month:1' => 'Janvier',
	'date:month:2' => 'Février',
	'date:month:3' => 'Mars',
	'date:month:4' => 'Avril',
	'date:month:5' => 'Mai',
	'date:month:6' => 'Juin',
	'date:month:7' => 'Juillet',
	'date:month:8' => 'Août',
	'date:month:9' => 'Septembre',
	'date:month:10' => 'Octobre',
	'date:month:11' => 'Novembre',
	'date:month:12' => 'Décembre',
	
	'adf_platform:dashboard:title' => "Mon Accueil personnalisable",
	'adf_platform:welcome:msg' => "Bienvenue sur votre plateforme collaborative.<br />Administrateurs du site, pensez à éditer ce message !",
	'adf_platform:firststeps:linktitle' => "Premiers pas (cliquer pour afficher / masquer)",
	'adf_platform:site:activity' => "Activité récente",
	'adf_platform:thewire:togglelink' => "Publier sur le Fil &#x25BC;",
	'adf_platform:homewire:title' => "Le Fil de %s",
	'adf_platform:thewire:charleft' => "max",
	'adf_platform:thewire:access' => "Accès : ",
	'adf_platform:homewire:msg' => "Un message ou une info à partager ?",
	'members:search' => "Recherche de membres",
	
	'grouptype:default' => "Génériques",
	'grouptype:thematic' => "Thématiques",
	'grouptype:project' => "Projets",
	'grouptype:industry' => "Secteurs",
	'grouptype:year' => "Années",
	'grouptype:edition' => "Editorial",
	'grouptype:region' => "Géographique",
	'grouptype:publication' => "Publications",
	
	/* ESOPE search */
	'adf_platform:grouptools:priority' => "Ordre des outils dans les groupes",
	'adf_platform:settings:members:searchtab' => "Onglet de recherche multicritère",
	'members:label:search' => "Recherche",
	'esope:search:title' => "Recherche avancée",
	'esope:search:setting:metadata' => "Saisissez une liste de metadata à utiliser pour la recherche multi-critère (meta1, meta2, etc.)",
	'esope:search:details' => "Utiliser plusieurs critières pour affiner votre recherche",
	'esope:fulltextsearch' => "Recherche libre",
	'esope:search:type' => "Type de recherche",
	'esope:search:subtype' => "Type de publication",
	'esope:search:profile_type' => "Type de profil",
	'esope:search:nbresults' => '%s résultats',
	'object' => "Publication",
	'esope:search:morethanmax' => "Trop de résultats, veuillez affiner vos critères de recherche.",
	'esope:search:noresult' => "Aucun résultat. Soit il n'y a pas assez de critères, soit ceux-ci sont au contraire trop restrictifs.",
	
);

add_translation('fr', $fr);
