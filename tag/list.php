<?php

function has_lists_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['lists']);
}

function list_lists_item_class() {
  global $content, $access_token;

  $classes = array();
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_lists_item_html() {
  global $content, $access_token;

  $list = $content['lists'][$content['iter']];

  
}

?>
