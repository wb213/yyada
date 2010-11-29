<?php

require_once('core/APIcall.php');

function load_favor() {
  global $access_token, $content, $page, $conn, $action, $target;

  switch ($action) {
      case 'add';
        add_fav_tweet($target);
        header('Location: /');
        break;
      case 'remove';
        remove_fav_tweet($target);
        header('Location: /');
        break;
		  default:
		    $tweets = get_fav();
      	$content = array_merge($content, array('tweets' => $tweets));
        load_theme($page);
		    break;
  }
}

?>
