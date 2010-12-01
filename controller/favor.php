<?php

require_once('core/APIcall.php');

function add($tweet_id) {
  add_fav_tweet($tweet_id);
  header('Location: /');
}

function remove($tweet_id) {
  remove_fav_tweet($tweet_id);
  header('Location: /');
}

function default_behavior() {
  global $content, $theme;

  $tweets = get_fav();
 	$content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
