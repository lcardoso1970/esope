<?php
/** Elgg notification_messages plugin language
 * @author Florian DANIEL - Facyla
 * @copyright Florian DANIEL - Facyla 2015
 * @link http://id.facyla.net/
 */

$url = elgg_get_site_url();

return array(
	'notification_messages' => "Message de notification",
	
	// Actions
	'notification_messages:create' => "a publié",
	'notification_messages:delete' => "a supprimé",
	'notification_messages:update' => "a mis à jour",
	
	// Explanations
	'notification_messages:process' => "Comment fonctionnent les notifications ?",
	'notification_messages:process:details' => "2 modes de fonctionnement :
		<ol>
			<li>Direct : les notifications sont envoyées immédiatement, par une action du système, via notify_user. C'est le cas en particulier pour toutes les notifications telles que les emails d'inscription, de validation, d'adhésion, ou les messages directs. Cette méthode est adaptée lorsque le nombre de destinataires est réduit.</li>
			<li>File d'attente : les notifications sont envoyées lorsque se produit un événement qui a été enregistré pour générérer des notifications. Les messages sont ensuite envoyés par le cron \"minute\", afin de ne pas ralentir l'action en cours.
				<ul>
					<li>Les événements qui génèrent des notifications sont enregistrés via <code>elgg_register_notification_event('object', 'photo', array('create'))</code><br />D'autres gestionnaires d'événements peuvent prendre en charge et/ou affecter l'envoi de notifications, par ex. en les bloquant, etc.</li>
					<li>Le titre et le contenu du message de notification peuvent être modifiés par le hook <code>elgg_register_plugin_hook_handler('prepare', 'notification:create:object:photo', 'photos_prepare_notification')</code></li>
					<li>Enfin les destinataires peuvent être modifiés par le hook <code>elgg_register_plugin_hook_handler('get', 'subscriptions', 'discussion_get_subscriptions')</code></li>
				</ul>
			</li>
		</ol>",
	
	// Settings
	'notification_messages:settings:objects' => "Sujet des nouvelles publications (objets)",
	'notification_messages:settings:details' => "En activant les messages de notification détaillés pour chacun des types de contenus suivants, vous pouvez remplacer le titre du mail par défaut par un titre explicite composé sous la forme : [Type de publication Nom du groupe ou du membre] Titre du contenu<br />Cette forme facilite également l'identification de conversations par les messageries.",
	'notification_messages:object:subtype' => "Type d'objet",
	'notification_messages:setting' => "Réglage",
	'notification_messages:events' => "Notification pour les types d'événements",
	'notification_messages:prepare:setting' => "Sujet et message",
	'notification_messages:recipients:setting' => "Destinataires",
	'notification_messages:register:default' => "Par défaut",
	'notification_messages:recipients:default' => "Par défaut",
	'notification_messages:subject:default' => "Par défaut",
	'notification_messages:subject:allow' => "Amélioré",
	'notification_messages:subject:deny' => "Bloqué (pas de notification)",
	'notification_messages:message:default' => "Message par défaut",
	'notification_messages:message:allow' => "Message amélioré",
	'notification_messages:settings:group_topic_post' => "Activer pour les réponses dans les forums",
	'notification_messages:settings:comments' => "Sujet des commentaires",
	'notification_messages:settings:messages' => "Messages",
	'notification_messages:settings:comments:details' => "Si vous avez activé ce plugin, vous souhaitez probablement activer ce réglage, de manière à utiliser le même titre pour les commentaires que pour les nouvelles publications.",
	'notification_messages:settings:generic_comment' => "Activer pour les commentaires génériques",
	'notification_messages:settings:notify_user' => "Notifier également l'auteur des commentaires ?",
	'notification_messages:settings:notify_user:details' => "Par défaut l'auteur d'un commentaire n'est pas notifié. Vous pouvez choisir de le notifier également, ce qui est particulièrement utile si vous utilisez des réponses par email.",
	'notification_messages:settings:notify_user:comment_tracker' => "Lorsque le plugin comment_tracker est utilisé, un réglage identique est proposé, ce réglage n'est pas disponible et doit être modifié directement dans la <a href=\"" . $url . "admin/plugin_settings/comment_tracker\">configuration de comment_tracker</a>.",
	'notification_messages:settings:expert' => "Expert",
	'notification_messages:settings:messagehandledby' => "OUI, géré par : ",
	'notification_messages:settings:nomessage' => "NON",
	'notification_messages:settings:recipients' => "Destinataires : ",
	
	'notification_messages:notify:create' => "create",
	'notification_messages:notify:publish' => "publish",
	'notification_messages:notify:update' => "update",
	'notification_messages:notify:delete' => "delete",
	
	
	// Notification message content
	'notification_messages:settings:objects:message' => "Contenu des messages de notification",
	'notification_messages:message:default:blog' => "Par défaut les messages de notification des blogs ne contiennent que l'extrait.",
	
	// Notification subject
	'notification_messages:objects:subject' => "[%s | %s] %s",
	'notification_messages:objects:subject:nocontainer' => "[%s] %s",
	'notification_messages:untitled' => "(sans titre)",
	'notification_messages:objects:body' => "%s a publié %s dans %s :

%s

Voir et commenter en ligne :
%s
",
	'notification_messages:objects:body:nocontainer' => "%s a publié %s :

%s

Voir et commenter en ligne :
%s
",
	// Messages
	'notification_messages:email:subject' => "[%s] Message de %s : %s",
	
	
	// Object:notifications hook control
	'notification_messages:settings:object_notifications_hook' => "Activer le hook sur object:notifications",
	'notification_messages:settings:object_notifications_hook:subtext' => "Ce hook permet à d'autres plugins d'ajouter facilement des pièces jointes aux emails envoyés, de la même manière qu'ils peuvent modifier le contenu des messages. Attention car il peut causer des problèmes de compatibilité dans certains cas, en bloquant l'utilisation du hook par d'autres plugins -notamment advanced_notifications- car il prend en charge le processus d'envoi et répond donc \"true\" au hook.<br />Si vous ne savez pas quoi faire, laissez le réglage par défaut.",
	
	'notification_messages:settings:messages_send' => "Envoyer les messages directs en HTML",
	'notification_messages:settings:messages_send:subtext' => "Par défaut, les messages direct envoyés par la messagerie interne sont envoyés par email en texte seulement. Ce réglage permet de conserver la mise en forme HTML de ces messages.",
	
	'notification_messages:subject:comment' => "%s | Commentaire",
	'notification_messages:subject:discussion_reply' => "%s | Réponse",
	'notification_messages:subject:reply' => "Re: %s",
	
	'notification_messages:summary:wrapper' => "%s

%s

%s",
	
	'notification_messages:body:inreplyto' => "%s


En réponse à :
<blockquote>
%s
</blockquote>",
	
	
);


