<?php

require_once('util/tweet.php');

function search_box_html() {
  echo <<<HTML
<form action='search/query' method='get'>
  <input name='q' type='text' value=''/>"
  <input type='submit' value='Search' />
</form>
HTML;
}

function has_saved_search_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['saved_searches']);
}

function list_saved_search_html() {
  global $settings, $content, $access_token;

  $saved = $content['saved_searches'][$content['iter']];

  echo "<a class='search' href='".join_path(BASE_URL, "search/query", "?q=".urlencode($saved->query))."'>". $saved->name ."</a>";
}

function has_search_result_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['search_results']);
}

function list_search_result_class() {
  global $content, $access_token;

  $classes = array();
  $results = $content['search_results'][$content['iter']];
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (!isset($content['mentioned']) || $content['mentioned'])
    if (is_mentioned($results->text))
      array_push($classes, 'mentioned');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_search_result_html() {
  global $settings, $content, $access_token;

  $results = $content['search_results'][$content['iter']];

  if ($settings->show_avatar) {
    echo "<img class='avatar' src='".$results->profile_image_url."' alt='".$results->from_user."' />";
  }
  echo "<div class='search_results'>";
  echo "<div class='toolbar'>";
  echo "<a class='name' href='".join_path(BASE_URL, "user/show", $results->from_user)."'>".$results->from_user."</a>";
  echo "<a class='reply' href='".join_path(BASE_URL, "tweet/reply", $results->id_str)."'>@</a>";
  if (is_reply_all('@'.$results->from_user.' '.$results->text))
    echo "<a class='replyall' href='".join_path(BASE_URL, "tweet/replyall", $results->id_str)."'>@@</a>";
  echo "<a class='direct' href='".join_path(BASE_URL, "direct/new", $results->from_user)."'>DM</a>";
  echo "<a class='favor' href='".join_path(BASE_URL, "favor/add", $results->id_str)."'>FAV</a>";
  echo "<a class='retweet' href='".join_path(BASE_URL, "tweet/retweet", $results->id_str)."'>RT</a>";
  if ($results->from_user == $access_token['screen_name'])
    echo "<a class='del' href='".join_path(BASE_URL, "tweet/remove", $results->id_str)."'>DEL</a>";
  if (isset($results->geo)) {
    $lat = $results->geo->coordinates[0];
    $long = $results->geo->coordinates[1];
    $point = "$lat,$long";
    echo "<a class='geo' href='http://maps.google.com/maps/api/staticmap?center=$point&markers=$point&sensor=false&size=400x400&zoom=12'>geo</a>";
  }
  echo "<a class='time' href='".join_path(BASE_URL, "tweet/show", $results->id_str)."'>".format_time(strtotime($results->created_at), 0)."</a>";
  echo "</div>";
  echo "<div class='status'>".format_tweet($results->text)." ";
  echo "<span class='via'>via ".html_entity_decode($results->source);
  echo "</span></div></div>";
}

?>
