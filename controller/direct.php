<?php

require_once('core/APIcall.php');

function create($user) {
  global $content;

  $content['create-direct']=true;
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    new_direct($user);
    header('Location: /direct/sent');
  }
}

function delete() {
  remove_direct($target);
  header('Location: /direct/inbox');
}

function default_behavior($box) {
  global $content, $theme;

  if (empty($box)) $box = 'inbox';
  $directs = get_direct($box);
  $content = array_merge($content, array('directs' => $directs, 'box' => $box));
  $theme->include_html('direct_list');
}

?>
