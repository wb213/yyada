<?php

$controller_router = array(
  "default" => "show",
  "add_list" => "add_list",
  "remove_list" =>"remove_list",
  "add_search" => "add_search",
  "remove_search" => "remove_search",
);

function show() {
  global $content, $monitor, $theme;

  $content['monitors'] = array();
  foreach ($monitor->urls as $name => $key) {
    array_push($content['monitor'], $name);
  }

  $theme->include_html('monitor');
}

function add_list($list) {
  global $monitor;

  $name = "list/" . $list;
  list($user, $id) = explode('/', $list, 2);
  $twitter_url = $user.'/lists/'.$id.'/statuses';
  $yyada_url = 'list/show/'.$list;

  $monitor->add($name, $twitter_url, $yyada_url);

  header("Location: {$_SERVER['HTTP_REFERER']}");
}

function remove_list($list) {
  global $monitor;

  $name = "list/" . $list;
  if ($monitor->find($name)) {
    $monitor->remove($name);
  ]

  header("Location: {$_SERVER['HTTP_REFERER']}");
}

?>
