<?php
$title = elgg_echo('elgg_cas:title');
$content = '';

// Allow to forward to asked URL after successful login, or last forward if not explicitely set
$forward = get_input('forward', $_SESSION['last_forward_from']);

// Initialise phpCAS
$client_loaded = elgg_cas_load_client();

// logout from CAS if asked to
if (isset($_REQUEST['logout'])) { phpCAS::logout(); forward($forward); exit; }


// Break if already logged in in Elgg
if (elgg_is_logged_in()) {
	// Si on est déjà identifié sans CAS, inutile de se re-logguer
	$logged = elgg_get_logged_in_user_entity();
	if (phpCAS::isAuthenticated()) {
		$elgg_username = phpCAS::getUser();
		$user = get_user_by_username($elgg_username);
		if ($user->guid == $logged->guid) { $user->is_cas_logged = true; }
		$content .= '<p>' . elgg_echo('elgg_cas:logged:cas', array($user->username)) . '</p>';
		$content .= '<p>' . elgg_echo('elgg_cas:confirmchangecaslogin', array($logged->username, $logged->name)) . '</p>';
	} else {
		$content .= '<p>' . elgg_echo('elgg_cas:logged:nocas', array($user->username)) . '</p>';
		$content .= '<p>' . elgg_echo('elgg_cas:confirmcaslogin', array($logged->username, $logged->name)) . '</p>';
	}
	// Affichage
	$content = elgg_view_layout('one_column', array('content' => $content, 'sidebar' => false));
	echo elgg_view_page($title, $content);
	exit;
}



// force CAS authentication
// Note : will fail with OpenSSL v0.9.8
// Patch : must be applied in Request/CurlRequest and CurlMultiRequest :
// => add curl_setopt($ch, CURLOPT_SSLVERSION,3); before calling curl
//curl_setopt($handle, CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1_2);
try{
	phpCAS::forceAuthentication();
} catch(Exception $e){
	$debug =  print_r($e, true);
	//echo '<pre>' . print_r($e, true) . '</pre>';
	echo "<p>An authentication error has occured. Despites the message above, you should probably be authenticated, but something went wrong while checking your credentials on server side.<br />This error is probably caused by some configuration or communication error, and should be reported to the site admin.<br />Please try another login method, if available.</p>";
	exit;
}

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().


// A ce stade, l'identification via CAS est OK
$elgg_username = phpCAS::getUser();
$content .= '<p>' . elgg_echo('elgg_cas:login:validcas') . '</p>';

// Récupération du compte associé, s'il existe
$user = get_user_by_username($elgg_username);

// Si on est identifié avec un autre compte avant de passer par CAS, on prévient et on arrête la procédure
if (elgg_is_logged_in()) {
	$logged = elgg_get_logged_in_user_entity();
	if ($user->guid != $logged->guid) {
		register_error(elgg_echo('elgg_cas:alreadylogged', array($user->username, $user->name, $logged->username, $logged->name)));
		forward($forward);
	}
}

// Si on est bien authentifié via CAS, login
if (elgg_instanceof($user, 'user')) {
	if (!$user->isBanned()) {
		// CAS is valid, update metadata and finally log user in !
		$user->is_cas_logged = true;
		system_message(elgg_echo('elgg_cas:login:success'));
		// MAJ profil via LDAP
		if (elgg_is_active_plugin('ldap_auth')) {
			elgg_load_library("elgg:ldap_auth");
			ldap_auth_check_profile($user);
		}
		if (login($user)) {
			// Get back to asked page
			forward($forward);
			// Ou on peut aussi afficher un message...
			$content .= '<p>' . elgg_echo('elgg_cas:login:success') . '</p>';
		} else { $content .= elgg_echo('elgg_cas:loginfailed'); }
	} else { $content .= elgg_echo('elgg_cas:user:banned'); }
} else {
	//$content .= '<p>' . elgg_echo('elgg_cas:noaccountyet') . '</p>';
	error_log("No Elgg account yet for CAS login : $elgg_username");
	// No existing account : CAS registration if enabled
	// Si le compte n'existe pas encore : création
	if (elgg_is_active_plugin('ldap_auth')) {
		$casregister = elgg_get_plugin_setting('casregister', 'elgg_cas', false);
		if ($casregister == 'yes') {
				elgg_load_library("elgg:ldap_auth");
				if (ldap_auth_is_active($elgg_username)) {
					$elgg_password = generate_random_cleartext_password();
					// Création du compte puis MAJ avec les infos du LDAP
					$user = ldap_auth_create_profile($elgg_username, $elgg_password);
					if (elgg_instanceof($user, 'user') && login($user)) {
						forward($forward);
					} else { error_log("Could not create account for : $elgg_username"); }
				} else {
					error_log("Not active account");
				}
		} else {
			$content .= elgg_echo('elgg_cas:user:notexist');
		}
	} else {
		error_log("LDAP plugin disabled, please enable it (contact site administrator with this message).");
		$content .= elgg_echo('elgg_cas:user:notexist');
	}
}

$content = elgg_view_layout('one_column', array('content' => $content, 'sidebar' => false));
// Pas de rendu dans la page en cas d'inclusion du script (autologin)
if (!$cas_login_included) echo elgg_view_page($title, $content);

