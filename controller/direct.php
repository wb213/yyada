<?php

function create($user) {
  global $conn, $content, $theme;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($user) && isset($_POST['to']))
      $user = $_POST['to'];

    $post_data = array('text' => $_POST['direct'], 'screen_name' => $user);
    $conn->post('direct_messages/new', $post_data);
    header('Location: /direct/sent');
  } else {
    $content['create-direct'] = true;
    $content['create-to'] = $user;
    $theme->include_html('direct_list');
  }
}

function remove($direct) {
  global $conn;

  $conn->post('direct_messages/destroy/' . $direct);
  header('Location: /direct/inbox');
}

function inbox() {
  global $conn, $content, $theme;

  $directs = $conn->get('direct_messages');
  $content = array_merge($content, array('directs' => $directs, 'box' => 'inbox'));
  $theme->include_html('direct_list');
}

function sent() {
  global $conn, $content, $theme;

  $directs = $conn->get('direct_messages/sent');
  $content = array_merge($content, array('directs' => $directs, 'box' => 'sent'));
  $theme->include_html('direct_list');
}

function default_behavior($box) {
  inbox();
}

?>
