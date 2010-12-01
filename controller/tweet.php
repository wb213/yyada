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

  $tweets = $conn->get('statuses/home_timeline');
  $content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('tweet_list');
}

function load_tweet() {
	global $access_token, $content, $page, $conn, $action, $target;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  switch ($action) {
		  case 'delete':
		    $ret = $conn->post('statuses/destroy/' . $target);
		    break;
		  default:
		    update_status();
		    break;
	  }
	  header('Location: /');
	} else {
    	switch ($action) {
		  case 'reply':
		    $tweets = get_reply_thread($target);
		    $content['reply_tweet_id'] = $target;
		    $content['reply_tweet_name'] = '@'.$tweets[0]->user->screen_name.' ';
		    break;
		  case 'replyall':
		    $tweets = get_reply_thread($target);
		    $content['reply_tweet_id'] = $target;
		    $content['reply_tweet_name'] = get_reply_users($target);
		    break;
		  case 'show':
		    $tweets = get_single_tweet($target);
		    break;
		  case 'delete':
		    $tweets = get_reply_thread($target);
		    break;
		  default:
		    $tweets = $conn->get('statuses/home_timeline');
		    break;
		}

	  	$content = array_merge($content, array('tweets' => $tweets));
	}
	load_theme($page);
}

?>
