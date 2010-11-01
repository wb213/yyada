<?php

/* Start session and load library. */
require_once('core/twitteroauth.php');
require_once('util/url.php');
require_once('config.php');

function handle_callback() {
  session_start();

  if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: ./login.php?clear=1');
  }

  /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'], OAUTH_PROXY);

  /* Request access tokens from twitter */
  $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

  /* Save the access tokens. Normally these would be saved in a database for future use. */
  $_SESSION['access_token'] = $access_token;

  /* Remove no longer needed request tokens */
  unset($_SESSION['oauth_token']);
  unset($_SESSION['oauth_token_secret']);

  /* If HTTP response is 200 continue otherwise send to connect page to retry */
  if (200 == $connection->http_code) {
    /* The user has been verified and the access tokens can be saved for future use */
    $_SESSION['status'] = 'verified';
    header('Location: ./index.php');
  } else {
    /* Save HTTP status for error dialog on connnect page.*/
    header('Location: ./login.php?clear=1');
  }
}

function handle_login() {
  session_start();

  /* Build TwitterOAuth object with client credentials. */
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, NULL, NULL, OAUTH_PROXY);
 
  /* Get temporary credentials. */
  $request_token = $connection->getRequestToken(path_join(BASE_URL, 'login.php?callback=1'));

  /* Save temporary credentials to session. */
  $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
  $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

  /* If last connection failed don't display authorization link. */
  switch ($connection->http_code) {
    case 200:
      /* Build authorize URL and redirect user to Twitter. */
      $url = $connection->getAuthorizeURL($token);
      header('Location: ' . $url); 
      break;
    default:
      /* Show notification if something went wrong. */
      echo 'Could not connect to Twitter. Refresh the page or try again later.';
  }
}

function handle_clear() {
  session_start();
  session_destroy();
 
  /* Redirect to page with the connect to Twitter option. */
  header('Location: ./index.php');
}

if (isset($_REQUEST['callback'])) {
  handle_callback();
} elseif (isset($_REQUEST['clear'])) {
  handle_clear();
} else {
  handle_login();
}

?>
