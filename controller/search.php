<?php

function query() {
  global $conn, $content, $theme;

  if (! isset($_GET['q']) || empty($_GET['q'])) return;

 	$content['saved_searches'] = array();
  $ret = $conn->http('https://search.twitter.com/search.json?q='.urlencode($_GET['q']), 'GET', NULL);
  $results = json_decode($ret)->results;
 	$content = array_merge($content, array('search_results' => $results));
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
 	$content['search_results'] = array();
  $theme->include_html('search_list');
}

?>
