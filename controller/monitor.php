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

  $content['monitor'] = array();
  foreach ($monitor->urls as $name => $key) {
    if ($name == 'mention' || $name == 'direct')
      continue;
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
  $monitor->save();

  header("Location: ".make_path("monitor"));
}

function remove_list($list) {
  global $monitor;

  $name = "list/" . $list;
  if ($monitor->find($name)) {
    $monitor->remove($name);
    $monitor->save();
  }

  header("Location: ".make_path("monitor"));
}

?>
