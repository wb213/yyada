<?php

require_once('core/APIcall.php');

function load_tweet() {
	global $access_token, $content, $page, $conn, $action, $target;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  switch ($action) {
		  case 'delete':
		    delete_status($target);
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
		    $tweets = get_timeline();
		    break;
		}

	  	$content = array_merge($content, array('tweets' => $tweets));
	}
	load_theme($page);
}

?>
