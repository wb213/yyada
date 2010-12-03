<?php

function show($user) {
  global $content, $conn, $theme;

  $request = $_GET;
  $request['screen_name'] = $user;
  $tweets = $conn->get('statuses/user_timeline', $request);
  $content['reply_tweet_name'] = '@' . $user . ' ';
  $content['tweets'] = $tweets;
  $theme->include_html('user');
}

function followers($user) {
  global $content, $conn, $theme, $access_token;

  if (empty($user)) $user = $access_token['screen_name'];

  $request = $_GET;
  $request['screen_name'] = $user;
  if (!isset($request['cursor'])) $request['cursor']= -1 ;
  $user_list = $conn->get('statuses/followers', $request);
  $content['user_list'] = $user_list->users;
  $content['next_cursor'] = $user_list->next_cursor;
  $content['previous_cursor'] = $user_list->previous_cursor;
  $theme->include_html('user_list');
}

function friends($user) {
  global $content, $conn, $theme, $access_token;

  if (empty($user)) $user = $access_token['screen_name'];

  $request = $_GET;
  $request['screen_name'] = $user;
  if (!isset($request['cursor'])) $request['cursor']= -1 ;
  $user_list = $conn->get('statuses/friends', $request);
  $content['user_list'] = $user_list->users;
  $content['next_cursor'] = $user_list->next_cursor;
  $content['previous_cursor'] = $user_list->previous_cursor;
  $theme->include_html('user_list');
}

function default_behavior() {
  global $access_token;

  show($access_token['screen_name']);
} 

?>
