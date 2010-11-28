<?php

require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/cookie.php');
require_once('core/settings-control.php');
require_once('util/url.php');

function handle_callback() {
  if (empty($_SESSION['oauth_token']) ||
      empty($_REQUEST['oauth_token']) ||
      $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    purge_settings();
    return;
  }

  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'], OAUTH_PROXY);
  $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
  $_SESSION['access_token'] = $access_token;

  unset($_SESSION['oauth_token']);
  unset($_SESSION['oauth_token_secret']);

  if (200 != $connection->http_code) {
    purge_settings();
    $_SESSION['status'] = 'login_fail';
    return;
  }
  save_access_token($access_token);
}

function handle_login() {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, NULL, NULL, OAUTH_PROXY);
  $request_token = $connection->getRequestToken(path_join(BASE_URL, 'login/callback'));

  $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
  $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

  switch ($connection->http_code) {
  case 200:
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    $_SESSION['status'] = 'login_fail';
    purge_settings();
    header('Location: /');
    break;
  }
}

function load_login() {
	global $page, $action;

	if (empty($action)) $action = 'show';

	switch ($action) {
		case 'show':
		  load_theme($page);
		  break;
		case 'callback':
		  handle_callback();
		  header('Location: /');
		  break;
		case 'clear':
		  purge_settings();
		  header('Location: /');
		  break;
		default:
		  handle_login();
		  break;
	}
}

?>
