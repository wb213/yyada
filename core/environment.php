<?php

require_once('core/setting-control.php');
require_once('core/APIcall.php');
require_once('control/include.php');

function url_dispatcher() {
	global $page, $action, $target;

	// pharse URI
	$base  = preg_replace('/^\w+:\/+s*/' , '' , BASE_URL) . '/';
	$url   = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	// remove the query string
	$url   = preg_replace('/\?.*$/','',$url);
	
	// get the relative URI based on the BASE URL
	$r_uri = str_ireplace($base , '' , $url);
	
	$uri = explode('/' , $r_uri);
	
	$page   = isset($uri[0]) ? $uri[0] : '' ;
	$action = isset($uri[1]) ? $uri[1] : '' ;
	$target = isset($uri[2]) ? $uri[2] : '' ;

	//TODO: request URL validation
}

function login_status() {
	if (empty($_SESSION['status'])) $_SESSION['status'] = '';
	return $_SESSION['status'];
}

function load_controller($page) {
	$func_name = 'load_' . $page;
	$func_name();
}

function init_environment() {
	global $theme, $settings, $access_token, $conn, $content;

	session_start();

	$settings = new Settings(cookie_get('config'));
	$theme    = new Theme($settings->theme);
	$access_token = load_access_token();
	$content = array();
	$conn = get_twitter_conn();
}

?>
