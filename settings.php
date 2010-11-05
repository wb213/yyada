<?php
require_once('config.php');
require_once('core/twitteroauth.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('util/settings.php');
require_once('util/url.php');
require_once('util/tweet.php');
require_once('util/tag.php');

session_start();
$theme = get_theme();
$access_token = load_access_token();
$content = array();
$settings = get_settings();

include($theme->get_html_path('settings'));

?>
