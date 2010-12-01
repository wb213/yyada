<?php

function add($tweet_id) {
  $conn->post('favorites/create/' . $tweet_id);
  header('Location: /');
}

function remove($tweet_id) {
  $conn->post('favorites/destroy/' . $tweet_id);
  header('Location: /');
}

function default_behavior() {
  global $content, $theme;

  $tweets = $conn->get('favorites');
 	$content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
