<?php

require_once('util/tweet.php');

function search_box_html() {
  echo "
<form action='".make_path("/search/query")."' method='get'>
  <input name='q' type='text' value=''/>
  <input type='submit' value='Search' />
</form>";

  if (isset($_GET['q']) && ! empty($_GET['q'])) {
    echo "<a href='".make_path("search/add/".urlencode($_GET['q']))."'> <b>Save this search</b> </a>";
    echo "<hr />";
  }
}

function has_saved_search_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['saved_searches']);
}

function list_saved_search_class() {
  global $content;

  $classes = array();
  $results = $content['saved_searches'][$content['iter']];
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_saved_search_html() {
  global $settings, $content, $access_token;

  $saved = $content['saved_searches'][$content['iter']];

  echo "<a class='search' href='".make_path("search/query?q=".urlencode($saved->query))."'>". $saved->name ."</a>";
  echo "  <a class='search' href='".make_path("search/remove/".$saved->id_str)."'>". "DEL</a>";
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
  echo "<a class='name' href='".make_path("user/show/".$results->from_user)."'>".$results->from_user."</a>";
  echo "<a class='reply' href='".make_path("tweet/reply/".$results->id_str)."'>@</a>";
  if (is_reply_all('@'.$results->from_user.' '.$results->text))
    echo "<a class='replyall' href='".make_path("tweet/replyall/".$results->id_str)."'>@@</a>";
  echo "<a class='direct' href='".make_path("direct/new/".$results->from_user)."'>DM</a>";
  echo "<a class='favor' href='".make_path("favor/add/".$results->id_str)."'>FAV</a>";
  echo "<a class='retweet' href='".make_path("tweet/retweet/".$results->id_str)."'>RT</a>";
  if ($results->from_user == $access_token['screen_name'])
    echo "<a class='del' href='".make_path("tweet/remove/".$results->id_str)."'>DEL</a>";
  if (isset($results->geo)) {
    $lat = $results->geo->coordinates[0];
    $long = $results->geo->coordinates[1];
    $point = "$lat,$long";
    echo "<a class='geo' href='http://maps.google.com/maps/api/staticmap?center=$point&markers=$point&sensor=false&size=400x400&zoom=12'>geo</a>";
  }
  echo "<a class='time' href='".make_path("tweet/show/".$results->id_str)."'>".format_time(strtotime($results->created_at), 0)."</a>";
  echo "</div>";
  echo "<div class='status'>".format_tweet($results->text)." ";
  echo "<span class='via'>via ".html_entity_decode($results->source);
  echo "</span></div></div>";
}

function search_page_menu() {
  global $content;

  if (empty($content['search_results'])) return;

  if (isset($_GET['page']))
    $page = (int)$_GET['page'];
  if (!isset($page))
    $page = 1;
  if ($page > 1) {
    if ($page == 2)
      echo '<a href="'.get_current_path().'?q='.urlencode($_GET['q']).'">PageUp</a>';
    else
      echo '<a href="'.get_current_path().'?q='.urlencode($_GET['q']).'&page='.(string)($page-1).'">PageUp</a>';
    echo '|';
  }
  echo '<a href="'.get_current_path().'?q='.urlencode($_GET['q']).'&page='.(string)($page+1).'">PageDown</a>';
}

?>
