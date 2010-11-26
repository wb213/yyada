<?php

require_once('settings.php');

function url_dispatcher() {
	global $page, $action, $target;

	// pharse URI
	$uri = explode("/" , $_SERVER['REQUEST_URI']);
	array_shift($uri);

	$page   = isset($uri[0]) ? $uri[0] : 'home' ;
	$action = isset($uri[1]) ? $uri[1] : '' ;
	$target = isset($uri[2]) ? $uri[2] : '' ;

	//TODO: can't handle the request correct if install under folder
	//TODO: request URL validation

}

function login_status() {
	if (empty($_SESSION['status'])) $_SESSION['status'] = '';
	return $_SESSION['status'];
}

function init_environment() {
	global $theme, $settings, $access_token;

	session_start();
	url_dispatcher();
	init_settings();

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
		default :
			purge_settings();
			$page = 'login';
			break;
	}
}

?>
