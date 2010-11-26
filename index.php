<?php

require_once('config.php');
require_once('core/globalvar.php');
require_once('core/environment.php');

init_environment();
init_tweets();

require_once('core/theme.php');
theme_load($page);

?>
