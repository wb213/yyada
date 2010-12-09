<?php

require_once('core/environment.php');
require_once('util/url.php');
require_once('tag/include.php');

try {
  init_environment();
} catch (NoCookie $e) {
  // do nothing
} catch (Exception $e) {
  error_log($e->getMessage());
  make_header_location('/login/clear');
  return;
}

try {
  if ($_SESSION['status'] == 'verified') {
    $monitor->check_new();
  }
} catch (Exception $e) {
  // do nothing
}

dispatch_url();

?>
