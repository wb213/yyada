<?php

require_once('core/APIcall.php');

function load_direct() {
  global $access_token, $content, $page, $conn, $action, $target;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' and $action = 'create') {
    new_direct($target);
    $box = 'sent';
	} else {
    switch ($action) {
      case 'create':
        //TODO check if the target user following current user;
      case 'delete':
        remove_direct($target);
        $box = 'inbox';
        break;
      default:
        $box = $action;
        $directs = get_direct($box);
 	      $content = array_merge($content, array('directs' => $directs));
        break;
    }
  }
  load_theme($page);
}

?>
