<?php

require_once('core/twitter.php');
require_once('tag/include.php');
require_once('util/tweet.php');

$controller_router = array(
  "default" => "show",
  "show" => "show",
  "edit" => "edit",
  "add" => "add",
  "remove" => "remove",
  "sub" => "sub",
  "subers" => "subers",
);

function show_user_lists($user) {
  global $content, $theme;

  $lists = twitter_get($user."/lists");
  $content['lists'] = $lists->lists;

  $lists = twitter_get($user."/lists/subscriptions");
  $content['lists'] = array_merge($content['lists'], $lists->lists);

  $theme->include_html('lists_list');
}

function show_list($list) {
  global $content, $theme;

  list($user, $id) = explode('/', $list, 2);
  $tweets = twitter_get($user.'/lists/'.$id.'/statuses', $_GET);
  $content['tweets'] = $tweets;

  $theme->include_html('tweet_list');
}

function show($list = null) {
  global $access_token;

  if (empty($list))
    $list = $access_token['screen_name'];

  if (strstr($list, '/'))
    show_list($list);
  else
    show_user_lists($list);
}

function edit($list) {
  global $access_token, $content, $theme;

  list($user, $id) = explode('/', $list, 2);
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    twitter_post($user.'/lists/'.$id, $_POST);
    make_header_location('/list');
    return;
  } else {
    $list = twitter_get($user.'/lists/'.$id);
    $content['list'] = $list;
    $theme->include_html('list_edit');
  }
}

function add() {
  global $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    twitter_post($user.'/lists', $_POST);
    make_header_location('/list');
    return;
  } else {
    $theme->include_html('list_new');
  }
}

function remove($list) {
  global $theme, $access_token, $content;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = array('_method' => 'DELETE');
    twitter_post($user.'/lists/'.$list, $request);
    make_header_location('/list');
    return;
  } else {
    $content['list'] = $list;
    $theme->include_html('list_remove');
  }
}

?>
