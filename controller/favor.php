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

  twitter_post('favorites/create/' . $tweet_id);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function remove($tweet_id) {
  global $conn;

  twitter_post('favorites/destroy/' . $tweet_id);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function show($user) {
  global $content, $theme, $conn, $access_token;

  if (empty($user)) $user = $access_token['screen_name'];

  $request = $_GET;
  $request['id'] = $user;
  $tweets = twitter_get('favorites', $request);
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
