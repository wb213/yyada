<?php

require_once('util/tweet.php');

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

  echo "<a class='search' href='".join_path(BASE_URL, "tweet/query", $saved->query)."'>". $saved->name ."</a>";
}

?>
