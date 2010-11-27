<?php

require_once('core/twitteroauth.php');
require_once('core/url.php');

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

function get_twitter_conn() {
  global $access_token;
  if (isset($access_token)) {
    return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  }
  return null;
}

function array_get($array, $key, $default=null) {
  if (isset($array[$key])) return $array[$key];
  return $default;
}

function pluralise($word, $count, $show) {
  if ($show) $word = "{$count} {$word}";
  return $word . (($count > 1) ? 's' : '');
}

function local_date($format, $timestamp, $offset) {
  $offset = $offset * 3600;
  return gmdate($format, $timestamp + $offset);
}

function format_time($time, $offset) {
  $units = array(
    'sec'  => array(1, 60),
    'min'  => array(60, 3600),
    'hour' => array(3600, 86400),
  );
  $delta = time() - $time;
  $ret = '';
  foreach ($units as $key => $value) {
    if ($delta < $value[1]) {
      $ret = pluralise($key, floor($delta / $value[0]), true) . ' ago';
      break;
    }
  }
  if ($ret == '') {
    $ret = local_date("m-d", $time, $offset);
  }
  return $ret;
}

function format_tweet($tweet) {
  $list_pattern = '/@([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)/';
  $list_replace = '@\1/<a href="/list/\1/\2">\2</a>';
  $user_pattern = '/@([a-zA-Z0-9_]+)/';
  $user_replace = '@<a href="/user/show/\1">\1</a>';
  $tag_pattern = '/#([a-zA-Z0-9_]+)/';
  $tag_replace = '#<a href="/search?data=\1">\1</a>';
  $url_pattern = '/((http|https)\:\/\/[a-zA-Z0-9_\-\+\.\/\?\&\$\@\:\=]+)/';
  $url_replace = '<a href="\1">\1</a>';

  $tweet = preg_replace($list_pattern, $list_replace, $tweet);
  $tweet = preg_replace($user_pattern, $user_replace, $tweet);
  $tweet = preg_replace($tag_pattern, $tag_replace, $tweet);
  $tweet = preg_replace($url_pattern, $url_replace, $tweet);

  return $tweet;
}

function get_mentioned_users($tweet) {
  preg_match_all('/(?P<name>@[a-zA-Z0-9_]+)/', $tweet, $users);
  $ret = array();
  foreach ($users['name'] as $user) {
    if (!in_array($user, $ret))
      array_push($ret, $user);
  }
  return $ret;
}

function init_tweet() {
	global $conn, $content;
	$content = array();
	$conn = get_twitter_conn();
}
