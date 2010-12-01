<?php

require_once('core/APIcall.php');
require_once('tag/include.php');

function show($user = '') {
  global $access_token, $content, $conn, $theme;

  if (empty($user)) $user = $access_token['screen_name'];
  $tweets = get_single_tweet($user);
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

function update() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') update_status();
  header('Location: /');
}

function mention() {
  global $content, $theme;
  
  $tweets = get_mentions();
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

function delete($tweet) {
  global $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    delete_status($tweet);
    header('Location: /');
  } else {
    $tweets = get_single_tweet($tweet);
    $content = array_merge($content, array('tweets' => $tweets));
    $content['delete'] = $tweet;
    $theme->include_html('tweet_list');
  }
}

function retweet($tweet) {
  global $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    retweet_status($tweet);
    header('Location: /');
  } else {
    $tweets = get_single_tweet($tweet);
    $content['retweet_id'] = $tweet;  
    $content['retweet_user'] = '@'.$tweets[0]->user->screen_name;
    $content['retweet_text'] = $tweets[0]->text;
    $theme->include_html('retweet');
  }
}

function reply($tweet) {
  global $access_token, $content, $conn, $theme;

  $tweets = get_reply_thread($tweet);
  $content = array_merge($content, array('tweets' => $tweets));
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = '@'.$tweets[0]->user->screen_name.' ';
  $theme->include_html('tweet_list');
}

function replyall($tweet) {
  global $access_token, $content, $conn, $theme;

  $tweets = get_reply_thread($tweet);
  $content = array_merge($content, array('tweets' => $tweets));
  $content['reply_tweet_id'] = $tweet;
  $content['reply_tweet_name'] = get_reply_users($tweet);
  $theme->include_html('tweet_list');
}

function default_behavior() {
  global $access_token, $content, $conn, $theme;

  $tweets = get_timeline();
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

?>
