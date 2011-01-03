<?php

function has_monitor_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['monitor']);
}

function list_monitor_item_class() {
  global $content, $access_token;

  $classes = array();
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_monitor_item_html() {
  global $content, $access_token;

  $monitor = $content['monitor'][$content['iter']];

  echo "<div class='toolbar'>";
  echo "<a class='remove' href='".make_path('monitor/remove_'.$monitor)."'>RM</a>";
  echo "</div>";
  echo "<div class='name'>".$monitor."</div>";
}


