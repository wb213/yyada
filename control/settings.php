<?php

require_once('core/settings.php');
require_once('core/theme.php');

function load_settings() {
	global $theme, $settings, $page;

	if (empty($_POST)) {
		load_theme($page);
	} else {
		if (isset($_POST['theme'])) $settings->theme = $_POST['theme'];
		$settings->show_avatar = isset($_POST['avatar']);
		$settings->is_reverse_thread = isset($_POST['reverse']);
		$settings->show_img = isset($_POST['img']);
		if (isset($_POST['rt_format'])) $settings->rt_format = $_POST['rt_format'];

		save_settings();
		header('Location: /');
	}
}

?>
