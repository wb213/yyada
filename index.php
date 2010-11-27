<?php

require_once('config.php');
require_once('core/globalvar.php');
require_once('core/environment.php');
require_once('core/theme.php');

url_dispatcher();
init_environment();

$warning = '';
switch ( login_status() ) {
	case 'logoff' :
		purge_settings();
		$page = 'logoff';
		break;
	case 'login_fail'    :
		purge_settings();
		$warning = 'Sign in failed, please try again.';
		$page = 'info';
		break;
	case 'invite_fail':
		purge_settings();
		$warning = 'You are not invited by administrator.';
		$page = 'info';
		break;
	case 'verified':
		if (empty($page)) $page = 'home';
		break;
	default :
		purge_settings();
		$page = 'login';
		break;
}

load_theme($page);

?>
