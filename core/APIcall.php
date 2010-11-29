<?php

require_once('config.php');
require_once('core/twitteroauth.php');
require_once('util/tweet_func.php');

function update_status() {
  global $conn;
  $post_data = array("status" => $_POST['status']);
  if (!empty($_POST['in_reply_to_id']))
    $post_data = array_merge($post_data, array("in_reply_to_status_id" => $_POST['in_reply_to_id']));
  if (!empty($_POST['location'])) {
    list($lat, $long) = explode(',', $_POST['location']);
    $post_data = array_merge($post_data, array("lat" => $lat, "long" => "$long"));
  }
  $conn->post('statuses/update', $post_data);
}

function delete_status() {
  global $conn;
  $ret = $conn->post('statuses/destroy/' . $target);
}

function get_timeline() {
  global $conn;
  return $conn->get('statuses/home_timeline');
}

function get_single_tweet($tweet_id) {
	global $conn;
	$ret = array();
    $t = $conn->get('statuses/show/'.$tweet_id);
    array_push($ret,$t);
    return $ret;
}

function get_reply_thread($tweet_id) {
  global $conn;
  $ret = array();
  do {
    $t = $conn->get('statuses/show/'.$tweet_id);
    array_push($ret, $t);
    $tweet_id = $t->in_reply_to_status_id_str;
  } while (!empty($tweet_id));
  return $ret;
}

function get_reply_users($tweet_id) {
  global $conn;
  $t = $conn->get('statuses/show/'.$tweet_id);
  $users = get_mentioned_users('@'.$t->user->screen_name.' '.$t->text);
  return implode($users, ' ').' ';
}

function get_mentions() {
  global $content, $conn;

  $tweets = $conn->get('statuses/mentions');
  $content = array_merge($content, array('tweets' => $tweets));
}

function get_user() {
  global $content, $conn, $target;
 
  $parm = array("screen_name" => $target);
  $tweets = $conn->get('statuses/user_timeline', $parm);
  $content['reply_tweet_name'] = '@' . $target . ' ';
  $content = array_merge($content, array('tweets' => $tweets));
}

function get_twitter_conn() {
  global $access_token;
  if (isset($access_token)) {
    return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  }
  return null;
}

?>
