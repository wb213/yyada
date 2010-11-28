<?php

require_once('core/tweets.php');

function load_timeline() {
	global $access_token, $content, $conn, $action, $target, $display;

	$display = true;

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
}

?>
