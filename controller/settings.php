<?php

require_once('core/settings.php');
require_once('core/theme.php');
require_once('tag/setting.php');

$controller_router = array(
  "default" => "show",
);

function show() {
  global $content, $theme, $settings;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['theme'])) $settings->theme = $_POST['theme'];
    $settings->show_avatar = isset($_POST['avatar']);
    $settings->show_img = isset($_POST['img']);
    $settings->url_expand = isset($_POST['url_expand']);
    if (isset($_POST['rt_format'])) $settings->rt_format = $_POST['rt_format'];
    if (isset($_POST['highlight'])) $settings->highlight = $_POST['highlight'];
    if (isset($_POST['filter'])) $settings->filter = $_POST['filter'];

    $settings->save();
    make_header_location('/');
  } else {
    $content['info'] = settings_html($echo=false);
    $theme->include_html('info');
  }
}

?>
