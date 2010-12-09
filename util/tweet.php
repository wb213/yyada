<?php

function array_get($array, $key, $default=null) {
  if (isset($array[$key])) return $array[$key];
  return $default;
}

function echo_global_var($varname) {
  return $GLOBALS[$varname];
}

function pluralise($word, $count, $show) {
  if ($show) $word = "{$count} {$word}";
  return $word . (($count > 1) ? 's' : '');
}

function local_date($format, $timestamp, $offset) {
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
    $ret = local_date("d F Y", $time, $offset);
  }
  return $ret;
}

function format_tweet($tweet) {
  global $settings;

  $list_pattern = '/@([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)/';
  $list_replace = '@\1/<a href="'.make_path('/list').'/\1/\2">\2</a>';
  $user_pattern = '/@([a-zA-Z0-9_]+)/';
  $user_replace = '@<a href="'.make_path('/user/show').'/\1">\1</a>';
  $url_pattern = '/((http|https)\:\/\/[a-zA-Z0-9_\-\+\.\/\?\&\$\@\:\=]+)/';
  $url_replace = '<a target="_blank" href="\1">\1</a>';
  $tag_pattern = '/(#[a-zA-Z0-9_]+)/';

  $highlight_pattern = $settings->highlight;
  if (! empty($highlight_pattern))
    $tweet = keyword_highlight($highlight_pattern, $tweet);

  $tweet = preg_replace($list_pattern, $list_replace, $tweet);
  $tweet = preg_replace($user_pattern, $user_replace, $tweet);
  $tweet = preg_replace($url_pattern, $url_replace, $tweet);
  $tweet = preg_replace_callback($tag_pattern, 'hashtag_encode', $tweet);

  return $tweet;
}

function hashtag_encode($match) {
  return "<a href='".make_path("/search/query")."?q=" . urlencode($match[1]) . "'>". $match[1] . "</a>";
}

function keyword_highlight($pattern, $tweet) {
  preg_match_all("/$pattern/", $tweet, $match);
  $match = array_unique($match[0]);
  if (! empty($match)) {
    foreach ($match as $value) {
      $tweet = str_replace($value, "<span class='highlight'>$value</span>", $tweet);
    }
  }
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

function is_mentioned($tweet) {
  global $access_token;

  $users = get_mentioned_users($tweet);
  return in_array('@'.$access_token['screen_name'], $users);
}

function is_reply_all($tweet) {
  global $access_token;

  $users = get_mentioned_users($tweet);
  $num = count($users);
  if (in_array('@'.$access_token['screen_name'], $users)) $num-- ;
  return $num > 1;
}

function get_reply_users($tweet_obj) {
  global $access_token;

  $tweet_user = '@'.$tweet_obj->user->screen_name;
  $tweet = $tweet_obj->text;
  $users = get_mentioned_users($tweet_user.' '.$tweet);
  $self = array_search('@'.$access_token['screen_name'], $users);
  if ($self !== false) // must !== here for same type.
    unset($users[$self]);
  return implode($users, ' ').' ';
}

function get_reply_thread($tweet_id, $deep=0) {
  global $conn, $content;
  $ret = array();

  for ($i = 1; $i <= $deep+1; $i++) {
    $t = twitter_get('statuses/show/'.$tweet_id);
    array_push($ret, $t);

    if (isset($t->error)) {
      unset($tweet_id);
      break;
    }

    $tweet_id = $t->in_reply_to_status_id_str;
    if (empty($tweet_id)) break;
  }
  if (! empty($tweet_id)) $content['thread-next-id'] = $tweet_id;
  return $ret;
}

?>
