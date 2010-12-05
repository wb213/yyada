<?php

require_once('config.php');
require_once('core/environment.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('tag/include.php');

try {
  init_environment();
} catch (NoCookie $e) {
  if ($_SERVER['REQUEST_URI'] != make_path('/')) {
    make_header_location('/');
    return;
  }
} catch (Exception $e) {
  error_log($e->getMessage());
  make_header_location('/login/clear');
  return;
}

dispatch_url();

?>
