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

if (empty($_REQUEST)) {
  $tweets = $conn->get('statuses/home_timeline');
  $content = array_merge($content, array('tweets' => $tweets));

  include($theme->get_html_path('tweets'));
} else {
  $post_data = array("status" => $_REQUEST['status']);
  if (!empty($_REQUEST['in_reply_to_id']))
    $post_data = array_merge($post_data, array("in_reply_to_status_id" => $_REQUEST['in_reply_to_id']));
  if (!empty($_REQUEST['location'])) {
    list($lat, $long) = explode(',', $_REQUEST['location']);
    $post_data = array_merge($post_data, array("lat" => $lat, "long" => "$long"));
  }
  $conn->post('statuses/update', $post_data);
  header('Location: /');
}

