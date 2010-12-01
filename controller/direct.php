<?php

require_once('core/APIcall.php');

function create($user) {
  global $content;


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    new_direct($user);
    header('Location: /direct/sent');
  } else {
    $content['create-direct'] = true;
    $theme->include_html('direct_list');
}

function delete() {
  remove_direct($target);
  header('Location: /direct/inbox');
}

function inbox() {
  global $content, $theme;

  $directs = get_direct('inbox');
  $content = array_merge($content, array('directs' => $directs, 'box' => 'inbox'));
  $theme->include_html('direct_list');
}

function sent() {
  global $content, $theme;

  $directs = get_direct('sent');
  $content = array_merge($content, array('directs' => $directs, 'box' => 'sent'));
  $theme->include_html('direct_list');
}

function default_behavior($box) {
  inbox();
}

?>
