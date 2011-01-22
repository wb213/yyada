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
  global $monitor, $content, $access_token;

  $list = $content['lists'][$content['iter']];

  echo "<div class='toolbar'>";
  echo "<a class='screen_name' href='".make_path(join_path('list/show', $list->uri))."'>".$list->full_name."</a>";
  if ($list->user->screen_name == $access_token['screen_name']) {
    echo "<a class='edit' href='".make_path(join_path('list/remove', $list->uri))."'>Remove</a>";
    echo "<a class='edit' href='".make_path(join_path('list/edit', $list->uri))."'>Edit</a>";
  }
  echo "<a class='member' href='".make_path(join_path('list/member', $list->uri))."'>Members(".$list->member_count.")</a>";
  echo "<a class='suber' href='".make_path(join_path('list/suber', $list->uri))."'>Subers(".$list->subscriber_count.")</a>";
  $monitor_name = 'list'.$list->uri;
  if ($monitor->find($monitor_name) != -1)
    echo "<a class='monitor' href='".make_path(join_path('monitor/remove_list', $list->uri))."'>UnMonitor</a>";
  else
    echo "<a class='monitor' href='".make_path(join_path('monitor/add_list', $list->uri))."'>Monitor</a>";
  echo "</div>";
  echo "<div class='desc'>".$list->description."</div>";
}

function list_edit_html() {
  global $content;

  $list = $content['list'];
  echo "
<form class='update' method='post' action='".make_path('list/edit'.$list->uri)."'>
  <div class='name'>
    <span class='title'>Name: </span><textarea class='input' name='name' rows='1'>".$list->slug."</textarea>
  </div>
  <div class='desciption'>
    <span class=title'>Description: </span><textarea class='input' name='description' rows='1'>".$list->description."</textarea>
  <div class='mode'>
    <input class='input' type='checkbox' name='mode'".($list->mode=='private'?" checked='checked'":"")." /><span class='title'> Is private?</span>
  </div>
  <input type='submit' value='Submit' />
</form>";
}

function list_new_html() {
  echo "
<form class='update' method='post' action='".make_path('list/add')."'>
  <div class='name'>
    <span class='title'>Name: </span><textarea class='input' name='name' rows='1'></textarea>
  </div>
  <div class='desciption'>
    <span class=title'>Description: </span><textarea class='input' name='description' rows='1'></textarea>
  <div class='mode'>
    <input class='input' type='checkbox' name='mode' /><span class='title'> Is private?</span>
  </div>
  <input type='submit' value='Submit' />
</form>";
}

function list_new() {
  echo "
<div classs='add'>
  <a href='".make_path('list/add')."'>Create new list</a>
</div>";
}

function list_remove_html() {
  global $content;

  echo "
<form class='update' method='post' action='".make_path('list/remove/'.$content['list'])."'>
  <input type='submit' value='Yes please' />
</form>";
}

?>
