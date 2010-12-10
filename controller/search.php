<?php

require_once('core/twitter.php');

$controller_router = array(
  "default" => "show",
  "query" => "query",
  "add" => "add",
  "remove" => "remove",
);

function query() {
  global $conn, $content, $theme;

  if (! isset($_GET['q']) || empty($_GET['q'])) return;

  $utf8_lead_pattern = "/([^ ])([\xc0-\xdf\xe0-\xef\xf0-\xf7])/";
  $utf8_lead_replace = '\1 \2';
  $qry = preg_replace($utf8_lead_pattern, $utf8_lead_replace, $_GET['q']);
  $qry = 'q='.urlencode($qry);

  if (isset($_GET['page']) && ! empty($_GET['page']))
    $qry .= '&page='.$_GET['page'];

  $content['saved_searches'] = array();
  $ret = twitter_http('https://search.twitter.com/search.json?'.$qry, 'GET', NULL);
  $results = $ret->results;
  $content = array_merge($content, array('search_results' => $results));
  $theme->include_html('search_list');
}

function add($query_string) {
  global $conn;

  $post_data = array('query' => urldecode($query_string));
  twitter_post('saved_searches/create', $post_data);
  make_header_location('/search');
}

function remove($saved_search_id) {
  global $conn;

  twitter_post('saved_searches/destroy/'.$saved_search_id);
  make_header_location('/search');
}

function show() {
  global $content, $theme, $conn;

  $saved_searches = twitter_get('saved_searches');
  $content = array_merge($content, array('saved_searches' => $saved_searches));
  $content['search_results'] = array();
  $theme->include_html('search_list');
}

?>
