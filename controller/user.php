<?php

require_once('core/twitter.php');

$controller_router = array(
  "default" => "show",
  "show" => "show",
  "followers" => "followers",
  "friends" => "friends",
  "follow" => "follow",
  "unfollow" => "unfollow",
  "block" => "block",
  "unblock" => "unblock",
  "spam" => "spam"
);

function show($user) {
  global $content, $conn, $theme, $access_token;

  if (!isset($user) || empty($user))
    $user = $access_token['screen_name'];

  //get user recent tweets
  $request = $_GET;
  $request['screen_name'] = $user;
  $request['include_rts'] = true;
  $tweets = twitter_get('statuses/user_timeline', $request);
  $content['reply_tweet_name'] = '@' . $user . ' ';
  $content['tweets'] = $tweets;

  //get user friendship information
  $request = array('target_screen_name' => $user);
  $friendship = twitter_get('friendships/show', $request);
  $content['friendship'] = $friendship;

  //get block information
  $request = array('screen_name' => $user);
  $is_blocked = $conn->get('blocks/exists', $request);
  $content['is_blocked'] = ! isset($is_blocked->error);

  $theme->include_html('user');
}

function followers($user) {
  global $content, $conn, $theme, $access_token;

  if (empty($user)) $user = $access_token['screen_name'];

  $request = $_GET;
  $request['screen_name'] = $user;
  if (!isset($request['cursor'])) $request['cursor']= -1 ;
  $user_list = twitter_get('statuses/followers', $request);
  $content['user_list'] = $user_list->users;
  $content['next_cursor'] = $user_list->next_cursor;
  $content['previous_cursor'] = $user_list->previous_cursor;
  $theme->include_html('user_list');
}

function friends($user) {
  global $content, $conn, $theme, $access_token;

  if (empty($user)) $user = $access_token['screen_name'];

  $request = $_GET;
  $request['screen_name'] = $user;
  if (!isset($request['cursor'])) $request['cursor']= -1 ;
  $user_list = twitter_get('statuses/friends', $request);
  $content['user_list'] = $user_list->users;
  $content['next_cursor'] = $user_list->next_cursor;
  $content['previous_cursor'] = $user_list->previous_cursor;
  $theme->include_html('user_list');
}

function follow($user) {
  global $conn;

  $request = array('screen_name' => $user);
  twitter_post('friendships/create', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function unfollow($user) {
  global $conn;

  $request = array('screen_name' => $user);
  twitter_post('friendships/destroy', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function block($user) {
  global $conn;

  $request = array('screen_name' => $user);
  twitter_post('blocks/create', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function unblock($user) {
  global $conn;

  $request = array('screen_name' => $user);
  twitter_post('blocks/destroy', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function spam($user) {
  global $conn;

  $request = array('screen_name' => $user);
  twitter_post('report_spam', $request);
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function add($user_id) {
  global $conn;

  if (!isset($_POST['list'])) $_POST = $_GET;
  if (isset($_POST['list'])) {
    $request = array('id' => $user_id);
    twitter_post($access_token['screen_name'].'/'.$_POST['list'].'/members.json', $request);
  }
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function remove($useri_id) {
  global $conn;

  if (!isset($_POST['list'])) $_POST = $_GET;
  if (isset($_POST['list'])) {
    $request = array('id' => $user_id, '_method' => 'DELETE');
    twitter_post($access_token['screen_name'].'/'.$_POST['list'].'/members.json', $request);
  }
  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function manage_list($user) {
  global $access_token, $theme, $content;

  $cur_user = $access_token['screen_name'];

  $lists_json = twitter_get($cur_user.'/lists.json');
  $lists = array();
  foreach ($lists_json as $list_json) {
    list($u, $l) = explode('/', $list_json->full_name);
    $is_member = twitter_get($cur_user./.$l./.'members'./.$user.'.json');
error_log(print_r($is_member, true));
    $lists[$l] = false;
  }

  $theme->include_html('manage_list');
}

?>
