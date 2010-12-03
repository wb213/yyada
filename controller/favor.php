<?php

require_once('util/url.php');

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

function default_behavior() {
  global $content, $theme, $conn;

  $tweets = $conn->get('favorites');
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
