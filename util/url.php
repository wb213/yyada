<?php

function path_join() {
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

?>
