<?php

function has_manage_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['lists']);
}

function list_manage_item_class() {
  global $content, $access_token;

  $classes = array();
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_manage_item_html() {
  global $monitor, $content, $access_token;

  $list_member = $content['lists'][$content['iter']];
  $list = $list_member[0];
  $is_member = $list_member[1];
  $list_name = explode('/', $list->full_name);
  $list_name = $list_name[1];

  echo "<div class='toolbar'>";
  if ($is_member)
    echo "<a class='screen_name' href='".make_path(join_path('user/remove', $content['user_id'])."?list=".$list_name)."'>Remove from</a>";
  else
    echo "<a class='screen_name' href='".make_path(join_path('user/add', $content['user_id'])."?list=".$list_name)."'>Add to</a>";
  echo "<div class='name'>".$list->full_name."</div>";
  echo "<div class='desc'>".$list->description."</div>";
}

?>
