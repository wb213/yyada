<?php

require_once('core/twitter.php');
require_once('tag/include.php');
require_once('util/tweet.php');

$controller_router = array(
  "default" => "show",
  "show" => "show",
  "add" => "add",
  "delete" => "delete",
  "sub" => "sub",
  "subers" => "subers",
);

function show_user_lists($user) {
  global $content, $theme;

  $lists = twitter_get($user."/lists");
  $content['lists'] = $lists;
error_log(print_r($lists, true));

  $theme->include_html('lists_list');
}

function show_list($list) {
  global $content, $theme;

  $user, $id = explode('/', $list);
  $tweets = twitter_get($user.'/lists/'.$id);
  $content['tweets'] = $tweets;
error_log(print_r($tweets, true));

  $theme->include_html('tweet_list');
}

function show($list = null) {
  global $access_token;

  if (!isset($list) 
    $list = $access_token['screen_name'];
  if (strstr($list, '/'))
    show_list($list);
  else
    show_user_lists($list);
}
