<?php

require_once('core/APIcall.php');

function load_user() {
  global $action,$page;

  switch ($action) {
    case 'mention':
      get_mentions();
      $page = 'tweet';
      break;
    case 'show':
      get_user();
      break;
    default:
      get_mentions();
      $page = 'tweet';
      break;
  }
  load_theme($page);
} 

?>
