<?php

function show($user) {
  global $content, $conn, $theme;

  $tweets = $conn->get('statuses/user_timeline', array("screen_name" => $user));
  $content['reply_tweet_name'] = '@' . $user . ' ';
  $content['tweets'] = $tweets;
  $theme->include_html('user');
}

function default_behavior() {
  global $access_token;

  show($access_token['screen_name']);
} 

?>
