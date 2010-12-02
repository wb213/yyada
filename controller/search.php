<?php

function query() {
  global $conn, $content, $theme;

  if (! isset[$_GET['q'] || empty([$_GET['q'])) return;

 	$content['saved_searches'] = '';
  $tweets = $conn->http('https://search.twitter.com/search.json?q='.$_GET['q'], 'GET', NULL);
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
 	$content['tweets'] = '';
  $theme->include_html('search_list');
}

?>
