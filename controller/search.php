<?php

function query($query_string) {
  global $conn;

  $tweets = $conn->http('https://search.twitter.com/search.json?q=' . urlencode($query_string , 'GET', NULL);
 	$content = array_merge($content, array('tweets' => $tweets));
  $theme->include_html('search_list');
}

function add($saved_search) {
  global $conn;

}
function remove($saved_search) {
  global $conn;
}

function default_behavior() {
  global $content, $theme, $conn;

  $saved_searches = $conn->get('saved_searches');
 	$content = array_merge($content, array('saved_searches' => $saved_searches));
  $theme->include_html('search_list');
}

?>
