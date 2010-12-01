<?php

require_once('tag/include.php');
require_once('util/tweet.php');

function show($user = '') {
  global $access_token, $content, $conn, $theme;

  if (empty($user))
    $user = $access_token['screen_name'];
  $content['tweets'] = $conn->get('statuses/show/' . $user);
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
  header('Location: /');
}

function remove($tweet) {
  global $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ret = $conn->post('statuses/destroy/' . $tweet);
    header('Location: /');
  } else {
    $content['tweets'] = array($conn->get('statuses/show/' . $tweet));
    $theme->include_html('tweet_list');
  }
}

function mention() {
  global $content, $theme, $conn;
  
  $content['tweets'] = $conn->get('statuses/mentions');
  $content['mentioned'] = false;
  $theme->include_html('tweet_list');
}

function retweet($tweet) {
  global $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->post('statuses/retweet/' . $tweet);
    header('Location: /');
  } else {
    $content['retweet_id'] = $conn->get('statuses/show/' . $tweet);  
    $content['retweet_user'] = '@'.$tweets[0]->user->screen_name;
    $content['retweet_text'] = $tweets[0]->text;
    $theme->include_html('retweet');
  }
}

function reply($tweet) {
  global $access_token, $content, $conn, $theme;

  $content['tweets'] = get_reply_thread($tweet);
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = '@'.$tweets[0]->user->screen_name.' ';
  $theme->include_html('tweet_list');
}

function replyall($tweet) {
  global $access_token, $content, $conn, $theme;

  $content['tweets'] = get_reply_thread($tweet);
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = get_reply_users($content['tweets'][0]);
  $theme->include_html('tweet_list');
}

function default_behavior() {
  global $access_token, $content, $conn, $theme;

  $tweets = $conn->get('statuses/home_timeline');
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
