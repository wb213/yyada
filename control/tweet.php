<?php

require_once('core/APIcall.php');

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
		    $users = str_ireplace('@'.$access_token['screen_name'] , '' , get_reply_users($target));
		    $content['reply_tweet_name'] = $users;
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
