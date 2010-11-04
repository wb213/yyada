<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/parser.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/settings.php');

session_start();

function show_login() {
  switch ($_SESSION['status']) {
  case 'login_fail':
    $content = 'Sign in failed, please try again.';
    break;
  case 'invite_fail':
    $content = 'You are not invited by administrator.';
    break;
  default:
    $content = 'Please sign in.';
    break;
  }
  
  include(theme_get('sign'));
}

function show_timeline() {
  $conn = get_twitter_conn();
  $tweets = $conn->get('statuses/home_timeline');
  $parser = new Parser(settings_get_configue());
  $content = $parser->parse_tweets($tweets);

  include(theme_get('tweets'));
}

if (isset(load_access_token())) {
  show_timeline();
} else {
  show_login();
}

?>
