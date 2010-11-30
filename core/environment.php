<?php

require_once('core/settings.php');
require_once('core/APIcall.php');

// environment
global $content;

// settings
global $settings, $theme, $access_token, $conn;

function dispatch_url() {
  global $access_token;

  // pharse URI
  $base = preg_replace('/^\w+:\/+s*/' , '' , BASE_URL) . '/';
  $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

  // remove the query string
  $url = preg_replace('/\?.*$/','',$url);

  // get the relative URI based on the BASE URL
  $r_uri = str_ireplace($base , '' , $url);

  $uri = explode('/' , $r_uri);

  if (isset($_SESSION['status']) && $_SESSION['status'] == 'verified')
    $controller = 'status.php';
  else
    $controller = 'login.php';
  $action = 'default_behavior';
  $args = $access_token['screen_name'];

  if (isset($uri[0]) && !empty($uri[0]))
    $controller = $uri[0] . ".php";
  if (isset($uri[1]) && !empty($uri[1]))
    $action = $uri[1];
  if (isset($uri[2]) && !empty($uri[2]))
    $args = $uri[2];

  include 'controller/' . $controller;
  $action($args);
}

function init_environment() {
  global $theme, $settings, $access_token, $conn, $content;

  session_start();

  $settings = new Settings(cookie_get('config'));
  $theme = new Theme($settings->theme);
  $access_token = load_access_token();
  $content = array();
  $conn = get_twitter_conn();
}

?>
