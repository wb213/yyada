<?php

require_once('settings.php');

function url_dispatcher() {
	global $page, $action, $target;

	// pharse URI
	$uri = explode("/" , $_SERVER['REQUEST_URI']);

	$page   = isset($uri_a[0]) ? $uri_a[0] : 'home' ;
	$action = isset($uri_a[1]) ? $uri_a[1] : '' ;
	$target = isset($uri_a[2]) ? $uri_a[2] : '' ;

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

	$theme = get_theme();
	$access_token = load_access_token();
	$settings = get_settings();

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
