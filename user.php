<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/settings.php');
require_once('util/url.php');
require_once('util/tweet.php');
require_once('util/tag.php');

session_start();
$theme = get_theme();
$access_token = load_access_token();
$content = array();
$settings = get_settings();
$conn = get_twitter_conn();

function get_mentions() {
  global $theme, $content, $conn;

  $tweets = $conn->get('statuses/mentions');
  $content = array_merge($content, array('tweets' => $tweets));
  
  include($theme->get_html_path('tweets'));
}

function get_user() {
  global $theme, $content, $conn;
  
  $parm = array("screen_name" => $_GET['args']);
  $tweets = $conn->get('statuses/user_timeline', $parm);
  $content['reply_tweet_name'] = '@'.$_GET['args'].' ';
  $content = array_merge($content, array('tweets' => $tweets));
  
  include($theme->get_html_path('user'));
}

switch ($_GET['action']) {
  case 'mention':
    get_mentions();
    break;
  case 'show':
    get_user();
    break;
  default:
    get_mentions();
    break;
}
  
?>