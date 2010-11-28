<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/cookie.php');
require_once('util/factory.php');
require_once('util/url.php');
require_once('util/tweet.php');
require_once('util/tag.php');

function update() {
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

function load_timeline() {
	global $access_token, $content, $conn, $action, $target;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  switch ($action) {
		  case 'delete':
		    $ret = $conn->post('statuses/destroy/' . $target);
		    break;
		  default:
		    update();
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
		  case 'delete':
		    $tweets = get_reply_thread($target);
		    break;
		  default:
		    $tweets = $conn->get('statuses/home_timeline');
		    break;
		}
		
	  	$content = array_merge($content, array('tweets' => $tweets));
}

?>
