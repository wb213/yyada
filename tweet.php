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

if (empty($_REQUEST)) {
  global $theme, $content;

  $conn = get_twitter_conn();
  $tweets = $conn->get('statuses/home_timeline');
  $content = array_merge($content, array('tweets' => $tweets));

  include($theme->get_html_path('tweets'));
} else {
  include($theme->get_html_path('show_debug'));
}

