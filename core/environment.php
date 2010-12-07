<?php

require_once('core/twitteroauth.php');
require_once('core/settings.php');
require_once('util/url.php');
require_once('util/tweet.php');

// environment
global $content;

// settings
global $settings, $theme, $access_token, $conn;

function dispatch_url() {
  global $access_token;

  // pharse URI
  $base = join_path(preg_replace('/^\w+:\/+s*/' , '' , BASE_URL), '/');
  $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

  // remove the query string
  $url = preg_replace('/\?.*$/','',$url);

  // get the relative URI based on the BASE URL
  $r_uri = str_ireplace($base , '' , $url);

  $uri = explode('/' , $r_uri);

  if (!isset($_SESSION['status']) || $_SESSION['status'] != 'verified') {
    $controller = 'login.php';
  } else {
    $controller = 'tweet.php';

    if (isset($uri[0]) && !empty($uri[0]))
      $controller = $uri[0] . ".php";
  }
  include 'controller/' . $controller;

  $action = 'default';
  if (isset($uri[1]) && !empty($uri[1]))
    $action = $uri[1];

  $args = '';
  if (isset($uri[2]) && !empty($uri[2]))
    $args = $uri[2];

  $func = array_get($controller_router, $action, 'default');
  $func($args);
}

function init_environment() {
  global $theme, $settings, $access_token, $conn, $content;

  session_start();

  $settings = new Settings(cookie_get('config'));
  $theme = new Theme($settings->theme);
  $access_token = load_access_token();
  $content = array();
  if (isset($access_token))
    return $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  else
    $conn = null;
}

function check_new() {
  global $conn;

  $last = cookie_get('check', '0');
  $url = preg_replace('/\?.*$/', '', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
  $path = str_ireplace($base , '' , $url);

  if (!isset($_SESSION['home_new']) && ($path != '/') {
    $tweets = $conn->get('statuses/home_timeline');
    $last_tweet_time = strtotime($tweets[0]->created_at);
    if ($last_tweet_time > $last)
      $_SESSION['home_new'] = true;
  }
  if (!isset($_SESSION['mentioned_new']) && ($path != '/tweet/mention') {
    $tweets = $conn->get('statuses/mentions');
    $last_tweet_time = strtotime($tweets[0]->created_at);
    if ($last_tweet_time > $last)
      $_SESSION['mentioned_new'] = true;
  }
  if (!isset($_SESSION['direct_new']) && ($path != '/direct') {
    $tweets = $conn->get('direct_messages');
    $last_tweet_time = strtotime($tweets[0]->created_at);
    if ($last_tweet_time > $last)
      $_SESSION['direct_new'] = true;
  }
}

?>
