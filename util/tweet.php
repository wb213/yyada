<?php

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
  $user_pattern = '@([a-zA-Z0-9_]*)(/[a-zA-Z0-9_]*){0,1}';
  $user_replace = '@<a href="/user/\1\2">\1\2</a>';
  $tag_pattern = '#([a-zA-Z0-9_]*)';
  $tag_replace = '#<a href="/search?data=\1">\1</a>';

  $tweet = ereg_replace($user_pattern, $user_replace, $tweet);
  $tweet = ereg_replace($tag_pattern, $tag_replace, $tweet);

  return $tweet;
}

function is_user_mention_in_tweet($user, $tweet) {
  return eregi('@' . $user . '([^a-zA-Z0-9_]|$)', $tweet);
}

?>
