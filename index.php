<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/parser.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/settings.php');

session_start();
$theme = get_theme();
$access_token = load_access_token();

function show_login() {
  global $theme;

  if (empty($_SESSION['status']) $_SESSION['status'] = '';

  switch ($_SESSION['status']) {
  case 'login_fail':
    $content = 'Sign in failed, please try again.';
    break;
  case 'invite_fail':
    $content = 'You are not invited by administrator.';
    break;
  default:
    $content = null;
    break;
  }
  
  include($theme->get_html_path('sign'));
}

function show_timeline() {
  global $theme;

  $conn = get_twitter_conn();
  $tweets = $conn->get('statuses/home_timeline');
  $parser = new Parser(settings_get_configue());
  #$content = $parser->parse_tweets($tweets);

  print_r($tweets);
  #include($theme->get_html_path('tweets'));
}

if (isset($access_token)) {
  show_timeline();
} else {
  show_login();
}

?>
