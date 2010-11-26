<?php

require_once('config.php');
require_once('core/settings.php');

settings_init();
tweets_init();

require_once('core/theme.php');
switch ( login_status() ) {
	case 'logoff' :
		settings_purge();
		theme_purge();
		theme_load('logoff');
	case 'login' :
		theme_load('home');
	case 'nologin' :
		theme_load('login');
}

?>
