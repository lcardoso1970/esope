<?php
/**
 * Iris v2 profile header
 */

$user = elgg_get_page_owner_entity();
$own = elgg_get_logged_in_user_entity();

?>
<div class="iris-profile-icon" style="background:url('<?php echo $user->getIconUrl(array('size' => 'large')); ?>') no-repeat center/contain;" />
	<?php

	if ($user->canEdit()) {
		echo '<a href="' . elgg_get_site_url() . 'avatar/edit/' . $user->username . '" class="iris-profile-editavatar"><i class="fa fa-camera"></i><br />' . elgg_echo('edit') . '</a>';
	}
	if ($user->guid != $own->guid) {
		// Friendship and friend request
		if (!$user->isFriendOf($own->guid)) {
			// no, check if pending request
			if (check_entity_relationship($own->guid, 'friendrequest', $user->guid)) {
				// pending request
				echo elgg_view('output/url', array(
						'href' => "friend_request/{$own->username}#friend_request_sent_listing",
						'text' => '<i class=" fa fa-user-plus"></i>',
						'title' => elgg_echo('friend_request:friend:add:pending'),
						'class' => 'iris-profile-pendingfriend', 'is_action' => true
					));
			} else {
				// add as friend
				echo elgg_view('output/url', array(
						'href' => "action/friends/add?friend={$user->guid}",
						'text' => '<i class=" fa fa-user-plus"></i>',
						'title' => elgg_echo('friend:add'),
						'class' => 'iris-profile-addfriend', 'is_action' => true
					));
			}
		} else {
			// is friend, so remove friend link
			echo elgg_view('output/url', array(
					'href' => "action/friends/remove?friend={$user->guid}",
					'text' => '<i class=" fa fa-user-times"></i>',
					'title' => elgg_echo('friend:remove'),
					'class' => 'iris-profile-removefriend', 'is_action' => true
				));
		}
		// Send message
		echo '<a href="' . elgg_get_site_url() . 'messages/compose?send_to=' . $user->guid . '" class="iris-profile-sendmessage"><i class=" fa fa-envelope"></i></a>';
	}

	?>
</div>

<div class="iris-profile-title">
	<h2><?php echo $user->name; ?></h2>
	<?php echo strip_tags($user->briefdescription); ?>
</div>

