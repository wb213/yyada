<?php

require_once('tag/include.php');
require_once('util/tweet.php');

//hardcode in this stage, the deep n means original reply tweet + n maximum thread tweets if exist
define('THREAD_DEFAULT_DEEP', 2);
define('THREAD_PAGE_DEEP', 10);

$controller_router = array(
  "default" => "homeline",
  "show" => "show",
  "update" => "update",
  "remove" => "remove",
  "mention" => "mention",
  "retweet" => "retweet",
  "reply" => "reply",
  "replyall" => "replyall",
);

function show($user = '') {
  global $access_token, $content, $conn, $theme;

  if (empty($user))
    $user = $access_token['screen_name'];
  $content['tweets'] = array($conn->get('statuses/show/' . $user));
  $theme->include_html('tweet_list');
}

function update() {
  global $access_token, $content, $conn, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_data = array("status" => $_POST['status']);
    if (!empty($_POST['in_reply_to_id']))
      $post_data["in_reply_to_status_id"] = $_POST['in_reply_to_id'];
    if (!empty($_POST['location'])) {
      list($lat, $long) = explode(',', $_POST['location']);
      $post_data["lat"] = $lat;
      $post_data["long"] = $long;
    }
    $conn->post('statuses/update', $post_data);
  }
  make_header_location('/');
}

function remove($tweet) {
  global $content, $theme, $conn;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ret = $conn->post('statuses/destroy/' . $tweet);
    make_header_location('/');
  } else {
    $content['tweets'] = array($conn->get('statuses/show/' . $tweet));
    $content['delete'] = $content['tweets'][0]->id_str;
    $theme->include_html('tweet_list');
  }
}

function mention() {
  global $content, $theme, $conn;

  $content['tweets'] = $conn->get('statuses/mentions', $_GET);
  $content['mentioned'] = false;
  $theme->include_html('tweet_list');
}

function retweet($tweet) {
  global $conn, $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->post('statuses/retweet/' . $tweet);
    make_header_location('/');
  } else {
    $tweet_obj = $conn->get('statuses/show/' . $tweet);
    $content['retweet_id'] = $tweet;
    $content['retweet_user'] = '@'.$tweet_obj->user->screen_name;
    $content['retweet_text'] = $tweet_obj->text;
    $theme->include_html('retweet');
  }
}

function reply($tweet) {
  global $access_token, $content, $conn, $theme;

  if (isset($_GET['next']) && ! empty($_GET['next'])) {
    $thread_start_id = $_GET['next'];
    $deep = THREAD_PAGE_DEEP;
  } else {
    $thread_start_id = $tweet;
    $deep = THREAD_DEFAULT_DEEP;
  }
  
  $content['tweets'] = get_reply_thread($thread_start_id, $deep);
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = '@'.$content['tweets'][0]->user->screen_name.' ';
  $theme->include_html('tweet_list');
}

function replyall($tweet) {
  global $access_token, $content, $conn, $theme;

  if (isset($_GET['next']) && ! empty($_GET['next'])) {
    $thread_start_id = $_GET['next'];
    $deep = THREAD_PAGE_DEEP;
  } else {
    $thread_start_id = $tweet;
    $deep = THREAD_DEFAULT_DEEP;
  }

  $content['tweets'] = get_reply_thread($thread_start_id, $deep);
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = get_reply_users($content['tweets'][0]);
  $theme->include_html('tweet_list');
}

function homeline() {
  global $access_token, $content, $conn, $theme;

  $content['tweets'] = $conn->get('statuses/home_timeline', $_GET);
  $theme->include_html('tweet_list');
}

?>
