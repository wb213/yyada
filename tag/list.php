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

  echo "<div class='toolbar'>";
  echo "<a class='screen_name' href='".make_path(join_path('list/show', $list->uri))."'>".$list->full_name."</a>";
  echo "<a class='edit' href=''>Edit</a>";
  echo "<a class='member' href=''>Members(".$list->member_count.")</a>";
  echo "<a class='suber' href=''>Subers(".$list->subscriber_count.")</a>";
  echo "<a class='monitor' href=''>Monitor</a>";
  echo "</div>";
  echo "<div class='desc'>".$list->description."</div>";
}

?>
