<?php

require_once('core/exception.php');

function twitter_get($url, $args = array()) {
  global $conn;

  $ret = $conn->get($url, args);
  if ($conn->http_code != 200) {
    throw ConnectError($conn->http_info['Status']);
  }
  if (isset($ret->error)) {
    throw TwitterError($ret->error);
  }
  return $ret;
}

function twitter_post($url, $args = array()) {
  global $conn;
  
  $ret = $conn->post($url, args);
  if ($conn->http_code != 200) {
    throw ConnectError($conn->http_info['Status']);
  }
  if (isset($ret->error)) {
    throw TwitterError($ret->error);
  }
  return $ret;
}

?>
