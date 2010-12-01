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

function delete_status($tweet_id) {
  global $conn;
  $conn->post('statuses/destroy/' . $tweet_id);
}

function retweet_status($tweet_id) {
  global $conn;
  $conn->post('statuses/retweet/' . $tweet_id);
}

function get_fav() {
  global $conn;
  return $conn->get('favorites');
}

function add_fav_tweet($tweet_id) {
  global $conn;
  $conn->post('favorites/create/' . $tweet_id);
}

function remove_fav_tweet($tweet_id) {
  global $conn;
  $conn->post('favorites/destroy/' . $tweet_id);
}

function get_direct($box) {
  global $conn;
  switch ($box) {
    case 'inbox':
      $ret = $conn->get('direct_messages');
      break;
    case 'sent':
      $ret = $conn->get('direct_messages/sent');
      break;
    default:
      $ret = $conn->get('direct_messages');
      break;
  }
  return $ret;
}

function new_direct($target) {
  global $conn, $target;

  if (empty($target)) 
    if (isset($_POST['to']))
      $target = $_POST['to'];
    else
      return;
  
  $post_data = array('text' => $_POST['direct'], 'screen_name' => $target);
  $conn->post('direct_messages/new', $post_data);
}

function remove_direct($target) {
  global $conn;
  $conn->post('direct_messages/destroy/' . $target);
}

function get_timeline() {
  global $conn;
  return $conn->get('statuses/home_timeline');
}

function get_single_tweet($tweet_id) {
	global $conn;
  return $conn->get('statuses/show/'.$tweet_id);
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
  global $conn;
  return $conn->get('statuses/mentions');
}

function get_user($user) {
  global $conn;
 
  $parm = array("screen_name" => $user);
  return $conn->get('statuses/user_timeline', $parm);
}

function get_twitter_conn() {
  global $access_token;
  if (isset($access_token)) {
    return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  }
  return null;
}

?>
