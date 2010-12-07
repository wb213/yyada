<?php

$controller_router = array(
  "default" => "show",
  "show" => "show",
  "followers" => "followers",
  "friends" => "friends",
  "follow" => "follow",
  "unfollow" => "unfollow",
);

function show($user) {
  global $content, $conn, $theme, $access_token;

  if (!isset($user) || empty($user))
    $user = $access_token['screen_name'];

  $request = $_GET;
  $request['screen_name'] = $user;
  $tweets = $conn->get('statuses/user_timeline', $request);
  $content['reply_tweet_name'] = '@' . $user . ' ';
  $content['tweets'] = $tweets;

  $request = array('target_screen_name' => $user);
  $friendship = $conn->get('friendships/show', $request);
  $content['friendship'] = $friendship;
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

function follow($user) {
  global $conn;

  $request = array('screen_name' => $user);
  $conn->post('friendships/create', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function unfollow($user) {
  global $conn;

  $request = array('screen_name' => $user);
  $conn->post('friendships/destroy', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

?>
