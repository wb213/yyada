<?php

require_once('config.php');
require_once('core/environment.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('tag/include.php');

init_environment();

dispatch_url();

?>
