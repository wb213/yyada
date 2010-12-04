<?php

function query() {
  global $conn, $content, $theme;

  if (! isset($_GET['q']) || empty($_GET['q'])) return;
  $qry = 'q='.urlencode($_GET['q']);

  if (isset($_GET['page']) && ! empty($_GET['page']))
    $qry .= '&page='.$_GET['page'];

  $content['saved_searches'] = array();
  $ret = $conn->http('https://search.twitter.com/search.json?'.$qry, 'GET', NULL);
  $results = json_decode($ret)->results;
  $content = array_merge($content, array('search_results' => $results));
  $theme->include_html('search_list');
}

function add($query_string) {
  global $conn;

  $post_data = array('query' => urldecode($query_string));
  $conn->post('saved_searches/create', $post_data);
  make_header_location('/search');
}

function remove($saved_search_id) {
  global $conn;

  $conn->post('saved_searches/destroy/'.$saved_search_id);
  make_header_location('/search');
}

function default_behavior() {
  global $content, $theme, $conn;

  $saved_searches = $conn->get('saved_searches');
  $content = array_merge($content, array('saved_searches' => $saved_searches));
  $content['search_results'] = array();
  $theme->include_html('search_list');
}

?>
