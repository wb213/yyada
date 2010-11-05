<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/settings.php');
require_once('util/tag.php');

session_start();
$theme = get_theme();
$access_token = load_access_token();
$content = array();

function show_login() {
  global $theme;

  if (empty($_SESSION['status'])) $_SESSION['status'] = '';

  switch ($_SESSION['status']) {
  case 'login_fail':
    $warning = 'Sign in failed, please try again.';
    break;
  case 'invite_fail':
    $warning = 'You are not invited by administrator.';
    break;
  default:
    $warning = null;
    break;
  }
  logout();
  
  include($theme->get_html_path('sign'));
}

function show_timeline() {
  global $theme, $content;

  $conn = get_twitter_conn();
  $tweets = $conn->get('statuses/home_timeline');
  array_push($content, 'tweets' => $tweets);

  include($theme->get_html_path('tweets'));
}

if (isset($access_token)) {
  show_timeline();
} else {
  show_login();
}

?>
