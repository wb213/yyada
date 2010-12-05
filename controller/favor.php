<?php

require_once('util/url.php');

$controller_router = array(
  "default" => "show",
  "show" => "show",
  "add" => "add",
  "remove" => "remove",
);

function add($tweet_id) {
  global $conn;

  $conn->post('favorites/create/' . $tweet_id);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function remove($tweet_id) {
  global $conn;

  $conn->post('favorites/destroy/' . $tweet_id);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function show() {
  global $content, $theme, $conn;

  $tweets = $conn->get('favorites', $_GET);
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
