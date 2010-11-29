<?php

function join_path() {
  $ret = '';
  foreach (func_get_args() as $arg) {
    if ($ret == '') {
      $ret = $arg;
    } else {
      $ret = rtrim($ret, '/') . '/' . $arg;
    }
  }
  return $ret;
}

function get_base_path($url) {
  $ret = preg_replace('/[^:\/]+:\/\/[^:\/]+/', '', $url);
  if (empty($ret)) $ret = '/';
  return $ret;
}

?>
