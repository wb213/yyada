<?php

require_once('util/url.txt');

function add($tweet_id) {
  global $conn;

  $conn->post('favorites/create/' . $tweet_id);
  make_header_location('/');
}

function remove($tweet_id) {
  global $conn;

  $conn->post('favorites/destroy/' . $tweet_id);
  make_header_location('/');
}

function default_behavior() {
  global $content, $theme, $conn;

  $tweets = $conn->get('favorites');
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
