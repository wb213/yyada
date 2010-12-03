<?php

require_once('config.php');

function join_path() {
  $ret = '';
  foreach (func_get_args() as $arg) {
    if ($ret == '') {
      $ret = $arg;
    } else {
      $ret = rtrim($ret, '/') . '/' . ltrim($arg, '/');
    }
  }
  return $ret;
}

function make_url($path) {
  return join_path(BASE_URL, $path);
}

function make_path($path) {
  $url = make_url($path);
  $ret = preg_replace('/[^:\/]+:\/\/[^:\/]+/', '', $url);
  if (empty($ret)) $ret = '/';
  return $ret;
}

function make_header_location($path) {
  header('Location: '.make_path($path));
}

?>
