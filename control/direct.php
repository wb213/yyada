<?php

require_once('core/APIcall.php');

function load_direct() {
  global $access_token, $content, $page, $conn, $action, $target;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' and $action = 'create') {
    new_direct($target);
    header('Location: /direct/sent');
    return;
	} else {
    switch ($action) {
      case 'create':
        //TODO check if the target user following current user;
      case 'delete':
        remove_direct($target);
        header('Location: /direct/inbox');
        return;
        break;
      default:
        $box = $action;
        $directs = get_direct($box);
 	      $content = array_merge($content, array('directs' => $directs, 'box' => $box));
        break;
    }
  }
  load_theme($page);
}

?>
