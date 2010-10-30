<?php

function join_path() {
  $ret = join(func_get_args(), '/');
  return str_replace('//', '/', $ret);
}

?>
