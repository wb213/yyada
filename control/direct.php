<?php

require_once('core/APIcall.php');

function load_direct() {
  global $access_token, $content, $page, $conn, $action, $target;

  if ($_SERVER['REQUEST_METHOD'] == 'POST' and $action = 'new') {
    new_direct($target);
    $box = 'sent';
	} else {
    switch ($action) {
      case 'new';
        $box = 'sent';
        break;
      case 'remove';
        remove_direct($target);
        $box = 'in';
        break;
    }
  }

  $tweet = get_direct($box);
 	$content = array_merge($content, array('tweets' => $tweets));
  load_theme($page);
}

?>
