<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Saket Saurabh
 *
 */

/**
 * Web service to get profile labels
 *
 * @return string $profile_labels Array of profile labels
 */
function user_get_profile_fields() {	
	$user_fields = elgg_get_config('profile_fields');
	foreach ($user_fields as $key => $type) {
		$profile_labels[$key]['label'] = elgg_echo('profile:'.$key);
		$profile_labels[$key]['type'] = $type;
	}
	return $profile_labels;
}

expose_function('user.get_profile_fields',
	"user_get_profile_fields",
	array(),
	elgg_echo('web_services:user:get_profile_fields'),
	'GET',
	true,
	true
);


/**
 * Web service to get profile information
 *
 * @param string $username username to get profile information
 *
 * @return string $profile_info Array containin 'core', 'profile_fields' and 'avatar_url'
 */
function user_get_profile($username) {
	//if $username is not provided then try and get the loggedin user
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
	}
	
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$user_fields = elgg_get_config('profile_fields');
	
	foreach ($user_fields as $key => $type) {
		if($user->$key){
			$profile_fields[$key]['label'] = elgg_echo('profile:'.$key);
			$profile_fields[$key]['type'] = $type;
			if(is_array($user->$key)){
			$profile_fields[$key]['value'] = $user->$key;

			} else {
			$profile_fields[$key]['value'] = strip_tags($user->$key);
			}
		}
	}
	
	$core['name'] = $user->name;
	$core['username'] = $user->username;
	
	$profile_info['core'] = $core;
	$profile_info['profile_fields'] = $profile_fields;
	$profile_info['avatar_url'] = $user->getIconURL('medium');
	return $profile_info;
}

expose_function('user.get_profile',
	"user_get_profile",
	array('username' => array ('type' => 'string', 'required' => false)
		),
	elgg_echo('web_services:user:get_profile'),
	'GET',
	true,
	true
);


/**
 * Web service to update profile information
 *
 * @param string $username username to update profile information
 *
 * @return bool 
 */
function user_save_profile($username, $profile) {
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	$owner = get_entity($user->guid);
	$profile_fields = elgg_get_config('profile_fields');
	foreach ($profile_fields as $shortname => $valuetype) {
		$value = $profile[$shortname];
		$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');

		if ($valuetype != 'longtext' && elgg_strlen($value) > 250) {
			$error = elgg_echo('profile:field_too_long', array(elgg_echo("profile:{$shortname}")));
			return $error;
		}

		if ($valuetype == 'tags') {
			$value = string_to_tag_array($value);
		}
		$input[$shortname] = $value;
	}
	
	$name = strip_tags($profile['name']);
	if ($name) {
		if (elgg_strlen($name) > 50) {
			return elgg_echo('user:name:fail');
		} elseif ($owner->name != $name) {
			$owner->name = $name;
			return $owner->save();
			if (!$owner->save()) {
				return elgg_echo('user:name:fail');
			}
		}
	}
	
	if (sizeof($input) > 0) {
		foreach ($input as $shortname => $value) {
			$options = array(
				'guid' => $owner->guid,
				'metadata_name' => $shortname
			);
			elgg_delete_metadata($options);
			
			if (isset($accesslevel[$shortname])) {
				$access_id = (int) $accesslevel[$shortname];
			} else {
				// this should never be executed since the access level should always be set
				$access_id = ACCESS_DEFAULT;
			}
			
			if (is_array($value)) {
				$i = 0;
				foreach ($value as $interval) {
					$i++;
					$multiple = ($i > 1) ? TRUE : FALSE;
					create_metadata($owner->guid, $shortname, $interval, 'text', $owner->guid, $access_id, $multiple);
				}
				
			} else {
				create_metadata($owner->guid, $shortname, $value, 'text', $owner->guid, $access_id);
			}
		}
		
	}
	
	return "Success";
}

expose_function('user.save_profile',
	"user_save_profile",
	array('username' => array ('type' => 'string'),
		'profile' => array ('type' => 'array'),
	),
	elgg_echo('web_services:user:save_profile'),
	'POST',
	true,
	true
);


/**
 * Web service to get all users registered with an email ID
 *
 * @param string $email Email ID to check for
 *
 * @return string $foundusers Array of usernames registered with this email ID
 */
function user_get_user_by_email($email) {
	if (!validate_email_address($email)) {
		throw new RegistrationException(elgg_echo('registration:notemail'));
	}

	$user = get_user_by_email($email);
	if (!$user) {
		throw new InvalidParameterException('registration:emailnotvalid');
	}
	foreach ($user as $key => $singleuser) {
		$foundusers[$key] = $singleuser->username;
	}
	return $foundusers;
}

expose_function('user.get_user_by_email',
	"user_get_user_by_email",
	array('email' => array ('type' => 'string'),
		),
	elgg_echo('web_services:user:get_user_by_email'),
	'GET',
	true,
	true
);


/**
 * Web service to check availability of username
 *
 * @param string $username Username to check for availaility 
 *
 * @return bool
 */
function user_check_username_availability($username) {
	$user = get_user_by_username($username);
	if (!$user) {
		return true;
	} else {
		return false;
	}
}

expose_function('user.check_username_availability',
	"user_check_username_availability",
	array(
		'username' => array ('type' => 'string'),
	),
	elgg_echo('web_services:user:check_username_availability'),
	'GET',
	true,
	true
);


/**
 * Web service to register user
 *
 * @param string $name     Display name 
 * @param string $email    Email ID 
 * @param string $username Username
 * @param string $password Password 
 *
 * @return bool
 */
function user_register($name, $email, $username, $password) {
	$user = get_user_by_username($username);
	if (!$user) {
		if (elgg_is_admin_logged_in()) {
			$return['success'] = true;
			$return['guid'] = register_user($username, $password, $name, $email);
		} else {
			$return['success'] = false;
			$return['message'] = elgg_echo('registration:adminonly');
		}
	} else {
		$return['success'] = false;
		$return['message'] = elgg_echo('registration:userexists');
	}
	return $return;
}

expose_function('user.register',
	"user_register",
	array('name' => array ('type' => 'string'),
		'email' => array ('type' => 'string'),
		'username' => array ('type' => 'string'),
		'password' => array ('type' => 'string'),
	),
	elgg_echo('web_services:user:register'),
	'GET',
	true,
	true
);


/**
 * Web service to add as friend
 *
 * @param string $username Username
 * @param string $friend Username to be added as friend
 *
 * @return bool
 */
function user_friend_add($friend, $username) {
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$friend_user = get_user_by_username($friend);
	if (!$friend_user) {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if($friend_user->isFriendOf($user->guid)) {
		$msg = elgg_echo('friends:alreadyadded', array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if ($user->addFriend($friend_user->guid)) {
		// add to river
		add_to_river('river/relationship/friend/create', 'friend', $user->guid, $friend_user->guid);
		$return['success'] = true;
		$return['message'] = elgg_echo('friends:add:successful' , array($friend_user->name));
	} else {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.add',
	"user_friend_add",
	array(
		'friend' => array ('type' => 'string'),
		'username' => array ('type' => 'string', 'required' =>false),
	),
	elgg_echo('web_services:user:friend:add'),
	'POST',
	true,
	true
);


/**
 * Web service to remove friend
 *
 * @param string $username Username
 * @param string $friend Username to be removed from friend
 *
 * @return bool
 */
function user_friend_remove($friend,$username) {
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
	 	throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$friend_user = get_user_by_username($friend);
	if (!$friend_user) {
		$msg = elgg_echo("friends:remove:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if(!$friend_user->isFriendOf($user->guid)) {
		$msg = elgg_echo("friends:remove:notfriend", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if ($user->removeFriend($friend_user->guid)) {
		$return['message'] = elgg_echo("friends:remove:successful", array($friend->name));
		$return['success'] = true;
	} else {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.remove',
	"user_friend_remove",
	array(
			'friend' => array ('type' => 'string'),
			'username' => array ('type' => 'string', 'required' => false),
		),
	elgg_echo('web_services:user:friend:remove'),
	'GET',
	true,
	true
);


/**
 * Web service to remove group join requests
 *
 * @param string $username Username
 * @param string $group_guid target group's guid
 *
 * @return array
 */
function user_remove_join_request($username, $group_guid) {
	if ($username) {
		$user = get_user_by_username($username);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$group = get_entity($group_guid);
	if (!elgg_instanceof($group, 'group')) {
		throw new InvalidParameterException('group:notfound');
	}
	if (check_entity_relationship($user->guid, 'membership_request', $group->guid)) {
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);
		system_message(elgg_echo("groups:joinrequestkilled"));
		$return['message'] = "groups:joinrequestkilled";
	} else {
		$return['message'] = "groups:nojoinrequest";
	}
	return $return;
}

expose_function('user.remove_join_request',
	"user_remove_join_request",
	array(
		'username' => array ('type' => 'string', 'required' => true),
		'group_guid' => array('type' => 'int', 'required' => true),
	),
	elgg_echo('web_services:user:group_join_request:remove'),
	'GET',
	true,
	true
);

/**
 * Web service to remove friend requests
 *
 * @param string $username Username
 *
 * @return array
 */
function user_remove_friend_request($username) {
	$friend = get_user_by_username($username);
	if (!$friend) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$user = elgg_get_logged_in_user_entity();

	if(remove_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())) {
//		$subject = elgg_echo("friend_request:decline:subject", array($user->name));
//		$message = elgg_echo("friend_request:decline:message", array($friend->name, $user->name));
			
//		notify_user($friend->getGUID(), $user->getGUID(), $subject, $message);
		$return['message'] = "friend_request:decline:success";
	} else {
		$return['message'] = "friend_request:decline:fail";
	}
	return $return;
}

expose_function('user.remove_friend_request',
	"user_remove_friend_request",
	array(
		'username' => array ('type' => 'string', 'required' => true),
	),
	elgg_echo('web_services:user:friend_request:remove'),
	'GET',
	true,
	true
);


/**
 * Web service to accept group join requests
 *
 * @param string $username Username
 * @param string $group_guid target group's guid
 *
 * @return array
 */
function user_accept_join_request($username, $group_guid) {
	if ($username) {
		$user = get_user_by_username($username);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$group = get_entity($group_guid);
	if (!elgg_instanceof($group, 'group')) {
		throw new InvalidParameterException('group:notfound');
	}
	if ($user && $group && $group->canEdit()) {
		if (!$group->isMember($user)) {
			if (groups_join_group($group, $user)) {
				// send welcome email to user
				notify_user($user->getGUID(), $group->owner_guid,
						elgg_echo('groups:welcome:subject', array($group->name)),
						elgg_echo('groups:welcome:body', array(
							$user->name,
							$group->name,
							$group->getURL())
				));
				system_message(elgg_echo('groups:addedtogroup'));
				$return['message'] = 'groups:addedtogroup';
			} else {
				$return['message'] = 'groups:joinaccepterror';
			}
		}
	}
	return $return;
}

expose_function('user.accept_join_request',
	"user_accept_join_request",
	array(
		'username' => array ('type' => 'string', 'required' => true),
		'group_guid' => array('type' => 'int', 'required' => true),
	),
	elgg_echo('web_services:user:group_join_request:accept'),
	'GET',
	true,
	true
);

/**
 * Web service to accept friend requests
 *
 * @param string $username Username
 *
 * @return array
 */
function user_accept_friend_request($username) {
	$friend = get_user_by_username($username);
	if (!$friend) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$user = elgg_get_logged_in_user_entity();

	if(remove_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())) {
			
		$user->addFriend($friend->getGUID());
		$friend->addFriend($user->getGUID());			//Friends mean reciprical...
			
		// notify the user about the acceptance
		$subject = elgg_echo("friend_request:approve:subject", array($user->name));
		$message = elgg_echo("friend_request:approve:message", array($friend->name, $user->name));
			
		notify_user($friend->getGUID(), $user->getGUID(), $subject, $message);		
		system_message(elgg_echo("friend_request:approve:successful", array($friend->name)));
			
		// add to river
		add_to_river("river/relationship/friend/create", "friend", $user->getGUID(), $friend->getGUID());
		add_to_river("river/relationship/friend/create", "friend", $friend->getGUID(), $user->getGUID());

		$return['message'] = 'friend_request:approve:successful';
	} else {
		register_error(elgg_echo("friend_request:approve:fail", array($friend->name)));
		$return['message'] = 'friend_request:approve:fail';
	}
	return $return;
}

expose_function('user.accept_friend_request',
	"user_accept_friend_request",
	array(
		'username' => array ('type' => 'string', 'required' => true),
	),
	elgg_echo('web_services:user:friend_request:accept'),
	'GET',
	true,
	true
);


/**
 * Web service to get friend requests and group join requests
 *
 * @param string $username Username
 *
 * @return array
 */  
function user_get_user_requests($username, $group_guid) {
	if ($username) {
		$user = get_user_by_username($username);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$options = array(
		"type" => "user",
		"limit" => false,
		"relationship" => "friendrequest",
		"relationship_guid" => $user->getGUID(),
		"inverse_relationship" => true
	);
	
	// Get all received requests
	$received_requests = elgg_get_entities_from_relationship($options);

	foreach($received_requests as $single) {
		$friend['guid'] = $single->guid;
		$friend['username'] = $single->username;
		$friend['name'] = $single->name;
		$friend['avatar_url'] = $single->getIconURL('small');
		$return['friend'][] = $friend;
	}

	$owned_groups = elgg_get_entities(array(
		'type' => 'group',
		'owner_guid' => $user->guid,
	));

	foreach($owned_groups as $group) {
		$options = array(
			"type" => "user",
			"limit" => false,
			"relationship" => "membership_request",
			"relationship_guid" => $group->guid,
			"inverse_relationship" => true
		);
		$requests = elgg_get_entities_from_relationship($options);

		foreach($requests as $request) {
			$join['group_guid'] = $group->guid;
			$join['group_name'] = $group->name;
			$join['username'] = $request->username;
			$join['name'] = $request->name;
			$join['avatar_url'] = $request->getIconURL('small');
			$return['join'][] = $join;
		}
	}

	return $return;
}

expose_function('user.get_user_requests',
	"user_get_user_requests",
	array(
		'username' => array ('type' => 'string', 'required' => false)
	),
	elgg_echo('web_services:user:requests:list'),
	'GET',
	true,
	true
);


/**
 * Web service to get friends of a user
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */
function user_get_friends($username, $limit = 10, $offset = 0) {
	if($username){
		$user = get_user_by_username($username);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$friends = get_user_friends($user->guid, '' , $limit, $offset);
	
	if($friends){
	foreach($friends as $single) {
		$friend['guid'] = $single->guid;
		$friend['username'] = $single->username;
		$friend['name'] = $single->name;
		$friend['avatar_url'] = $single->getIconURL('small');
		$return[] = $friend;
	}
	} else {
		$msg = elgg_echo('friends:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.get_friends',
	"user_get_friends",
	array('username' => array ('type' => 'string', 'required' => false),
		'limit' => array ('type' => 'int', 'required' => false),
		'offset' => array ('type' => 'int', 'required' => false),
	),
	elgg_echo('web_services:user:get_friends'),
	'GET',
	true,
	true
);


/**
 * Web service to obtains the people who have made a given user a friend
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */
function user_get_friends_of($username, $limit = 10, $offset = 0) {
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$friends = get_user_friends_of($user->guid, '' , $limit, $offset);
	
	$success = false;
	foreach($friends as $friend) {
		$return['guid'] = $friend->guid;
		$return['username'] = $friend->username;
		$return['name'] = $friend->name;
		$return['avatar_url'] = $friend->getIconURL('small');
		$success = true;
	}
	
	if(!$success) {
		$return['error']['message'] = elgg_echo('friends:none');
	}
	return $return;
}

expose_function('user.friend.get_friends_of',
	"user_get_friends_of",
	array('username' => array ('type' => 'string', 'required' => true),
		'limit' => array ('type' => 'int', 'required' => false),
		'offset' => array ('type' => 'int', 'required' => false),
	),
	elgg_echo('web_services:user:friend:get_friends_of'),
	'GET',
	true,
	true
);


/**
 * Web service to retrieve the messageboard for a user
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */
function user_get_messageboard($limit = 10, $offset = 0, $username){
	if(!$username){
		$user = elgg_get_logged_in_user_entity();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	$options = array(
		'annotations_name' => 'messageboard',
		'guid' => $user->guid,
		'limit' => $limit,
		'pagination' => false,
		'reverse_order_by' => true,
	);

	$messageboard = elgg_get_annotations($options);
	
	if($messageboard){
		foreach($messageboard as $single){
			$post['id'] = $single->id;
			$post['description'] = $single->value;
		
			$owner = get_entity($single->owner_guid);
			$post['owner']['guid'] = $owner->guid;
			$post['owner']['name'] = $owner->name;
			$post['owner']['username'] = $owner->username;
			$post['owner']['avatar_url'] = $owner->getIconURL('small');
		
			$post['time_created'] = (int)$single->time_created;
			$return[] = $post;
		}
	} else {
		$msg = elgg_echo('messageboard:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.get_messageboard',
	"user_get_messageboard",
	array(
		'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
		'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
		'username' => array ('type' => 'string', 'required' => false),
	),
	elgg_echo('web_services:user:get_messageboard'),
	'GET',
	true,
	true
);


/**
 * Web service to post to a messageboard
 *
 * @param string $text
 * @param string $to - username
 * @param string $from - username
 *
 * @return array
 */    				
function user_post_messageboard($text, $to, $from){
	if(!$to){
		$to_user = elgg_get_logged_in_user_entity();
	} else {
		$to_user = get_user_by_username($to);
		if (!$to_user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	if(!$from){
		$from_user = elgg_get_logged_in_user_entity();
	} else {
		$from_user = get_user_by_username($from);
		if (!$from_user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	$result = messageboard_add($from_user, $to_user, $text, 2);

	if($result){
		$return['success']['message'] = elgg_echo('messageboard:posted');
	} else {
		$return['error']['message'] = elgg_echo('messageboard:failure');
	}
	return $return;
}

expose_function('user.post_messageboard',
	"user_post_messageboard",
	array(
		'text' => array ('type' => 'string'),
		'to' => array ('type' => 'string', 'required' => false),
		'from' => array ('type' => 'string', 'required' => false),
	),
	elgg_echo('web_services:user:post_messageboard'),
	'POST',
	true,
	true
);


/**
 * Web service to get activity feed for a user
 *
 * @param int $username - the username
 * @param int $limit default 10
 * @param int $offset default 0
 *
 * @return bool
 */
function user_activity($username, $limit = 10, $offset = 0) {
	$user = get_user_by_username($username);		
	if(!$user){
		$msg = elgg_echo('users:notfound');
		throw new InvalidParameterException($msg);
	}

	$db_prefix = elgg_get_config('dbprefix');
	global $jsonexport;
	
	$content = elgg_list_river(array(
		'limit' => $limit,
		'offset' => $offset,
		'joins' => array("JOIN {$db_prefix}entities e1 ON e1.guid = rv.subject_guid"),
		'wheres' => array("(e1.guid = " . $user->guid . ")"),
	));

	return $jsonexport['activity'];
}

expose_function('user.activity',
	"user_activity",
	array(
		'username' => array ('type' => 'string'),
		'limit' => array ('type' => 'int', 'required' => false),
		'offset' => array ('type' => 'int', 'required' => false),
	),
	elgg_echo('web_services:user:activity'),
	'GET',
	true,
	true
);


// Get a user GUID from username (or email)
function user_getguid($username = false) {
	// check if username is an email address
	if (is_email_address($username)) {
		$users = get_user_by_email($username);
		// check if we have a unique user
		if (is_array($users) && (count($users) == 1)) {
			$username = $users[0]->username;
		}
	}
	if ($user = get_user_by_username($username)) { return $user->guid; }
	throw new InvalidParameterException($username);
}

expose_function(
	"user.getguid",
	"user_getguid",
	array(
		'username' => array ('type' => 'string'),
	),
	elgg_echo('web_services:user:get_guid'),
	'POST',
	true,
	false
);


// Get a user username from GUID
function user_getusername($guid = false) {
	// check if guid is a real user and return username if ok
	if (($user = get_entity($guid)) && elgg_instanceof($user, 'user')) {
		return $user->username;
	}
	throw new InvalidParameterException($guid);
}

expose_function(
	"user.getusername",
	"user_getusername",
	array(
		'guid' => array ('type' => 'string'),
	),
	elgg_echo('web_services:user:get_username'),
	'POST',
	true,
	false
);


/* Get a user icon
 * @param int $guid - the guid of the user
 * @param string $size default 'medium' - the size of the wanted icon
 */
function user_get_icon($guid, $size = 'medium') {
	$user = get_entity($guid);
	if (!elgg_instanceof($user, 'user')) {
		header("HTTP/1.1 404 Not Found");
		exit;
	}
	$size = strtolower($size);
	if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar'))) { $size = "medium"; }
	$success = false;
	$data_root = elgg_get_data_path();
	$join_date = $user->time_created;
	$user_path = date('Y/m/d/', $join_date) . $guid;
	$filename = "$data_root$user_path/profile/{$guid}{$size}.jpg";
	$filesize = @filesize($filename);
	if ($filesize) {
		if ($contents = file_get_contents($filename)) { $success = true; }
	}
	// Use default if not found
	if (!$success) {
		$location = elgg_get_site_url() . "_graphics/icons/user/default{$size}.gif";
		$contents = @file_get_contents($location);
	}
	// Send image
	$return['content'] = base64_encode($contents);
	return $return;
}

expose_function('user.get_icon',
	"user_get_icon",
	array(
		'guid' => array ('type'=> 'int', 'required'=>true),
		'size' => array ('type'=> 'string', 'required'=>false, 'default' =>'medium'),
		),
	elgg_echo('web_services:user:get_icon'),
	'POST',
	true,
	true
);


