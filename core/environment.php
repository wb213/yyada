<?php

require_once('core/settings.php');
require_once('core/APIcall.php');
require_once('control/include.php');

// environment
global $controller, $action, $args, $content;

// settings
global $settings, $theme, $access_token, $conn;

function url_dispatcher() {
	// pharse URI
	$base = preg_replace('/^\w+:\/+s*/' , '' , BASE_URL) . '/';
	$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	// remove the query string
	$url = preg_replace('/\?.*$/','',$url);
	
	// get the relative URI based on the BASE URL
	$r_uri = str_ireplace($base , '' , $url);
	
	$uri = explode('/' , $r_uri);
	
	$controller = isset($uri[0]) ? ($uri[0].'.php') : '' ;
	$action = isset($uri[1]) ? $uri[1] : '' ;
	$args = isset($uri[2]) ? $uri[2] : '' ;

	if ($controller == '') {
		$controller = 'status.php';
		$action = 'home';
	} else if ($action == '') {
		$action = 'def';
		$args = $access_token['screen_name'];
	} else if ($args == '') {
		$args = $access_token['screen_name'];
	}

	include 'controller/' . $controller;
	$action($args);
}

function init_environment() {
	global $theme, $settings, $access_token, $conn, $content;

	session_start();

	$settings = new Settings(cookie_get('config'));
	$theme = new Theme($settings->theme);
	$access_token = load_access_token();
	$content = array();
	$conn = get_twitter_conn();
}

?>
