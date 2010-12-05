<?php

require_once('config.php');
require_once('core/environment.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('tag/include.php');

try {
  init_environment();
} catch (NoCookie $e) {
  error_log($e->getMessage());
} catch (Exception $e) {
  error_log($e->getMessage());
  $_SERVER['REQUEST_URI'] = make_path('/login/clear');
}

dispatch_url();

?>
