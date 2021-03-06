<?php
/**
 * Action for adding a wire post
 * 
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);
// Iris : add our emoji conversion before unicode caracters are stripped off
if (elgg_is_active_plugin('emojis')) {
	$body = emojis_to_html($body);
} else if (function_exists('theme_inria_emoji_to_html')) {
 $body = theme_inria_emoji_to_html($body);
}

$access_id = ACCESS_PUBLIC;
$method = 'site';
$parent_guid = (int) get_input('parent_guid');

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
	forward(REFERER);
}

$guid = thewire_save_post($body, elgg_get_logged_in_user_guid(), $access_id, $parent_guid, $method);
if (!$guid) {
	register_error(elgg_echo("thewire:notsaved"));
	forward(REFERER);
}

// if reply, forward to thread display page
if ($parent_guid) {
	$parent = get_entity($parent_guid);
	forward("thewire/thread/$parent->wire_thread");
}

system_message(elgg_echo("thewire:posted"));
forward(REFERER);

