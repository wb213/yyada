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

if (empty($_POST)) {
  include($theme->get_html_path('settings'));
} else {
  if (isset($_POST['theme'])) $settings->theme = $_POST['theme'];
  $settings->show_avatar = isset($_POST['avatar']);
  $settings->is_reverse_thread = isset($_POST['reverse']);
  $settings->show_img = isset($_POST['img']);
  if (isset($_POST['rt_format'])) $settings->rt_format = $_POST['rt_format'];

  save_settings();
  header('Location: /');
}

?>
