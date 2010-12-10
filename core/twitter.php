<?php

require_once('core/exception.php');

function check_conn($ret) {
  global $conn;

  switch ($conn->http_code) {
  case 200:
  case 201:
    break;;
  default:
    if (isset($ret->error))
      throw new TwitterError($ret->error);
    else
      throw new TwitterError('Unknow error in twitter server.');
    break;
  }
}

function twitter_get($url, $args = array()) {
  global $conn;

  $ret = $conn->get($url, $args);
  check_conn($ret);

  return $ret;
}

function twitter_post($url, $args = array()) {
  global $conn;
  
  $ret = $conn->post($url, $args);
  check_conn($ret);

  return $ret;
}

function twitter_http($url, $args = array()) {
  global $conn;
 
  $ret = json_decode($conn->http($url, $args));
  check_conn($ret);

  return $ret;
}

?>
