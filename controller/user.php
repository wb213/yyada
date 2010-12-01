<?php

require_once('core/APIcall.php');

function show($user) {
  global $content, $theme;

  $tweets = get_user($user);
  $content['reply_tweet_name'] = '@' . $user . ' ';
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('user');
}

function default_behavior() {
  global $access_token;

  show($access_token['screen_name']);
} 

?>
