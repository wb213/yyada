<?php

require_once('cookie.php');

class Settings {

  public $theme = "basic";
  public $show_avatar = false;
  public $is_reverse_thread = false;
  public $show_img = false;
  public $rt_format = "RT %u: %t";

  public function __construct($s = null) {
    if (isset($s) && $s != "") {
      $this->load($s);
    }
  }

  public function str() {
    return sprintf('%s|%d|%d|%d|%s',
                   $this->theme,
                   $this->show_avatar?1:0,
                   $this->is_reverse_thread?1:0,
                   $this->show_img?1:0,
                   $this->rt_format);
  }

  public function load($s) {
    list($theme, $show_avatar, $is_reverse_thread, $show_img, $rt_format) = explode('|', $s);
    if (isset($theme)) $this->theme = $theme;
    if (isset($show_avatar)) $this->show_avatar = ($show_avatar == '1');
    if (isset($is_reverse_thread)) $this->is_reverse_thread = ($is_reverse_thread == '1');
    if (isset($show_img)) $this->show_img = ($show_img == '1');
    if (isset($rt_format)) $this->rt_format = $rt_format;
  }
}

function purge_settings() {
	session_unset();
	if (isset($_COOKIE[session_name()])) {
    	setcookie(session_name(), '', time()-3600, '/');
	}
	session_destroy();
	cookie_clear();
}

function init_settings() {
	global $settings, $theme, $access_token;
	$settings = new Settings(cookie_get('config'));
	$theme    = new Theme($settings->theme);
	$access_token = load_access_token();
	return $s;
}

function check_invite($user) {
  $invite_file = __DIR__ . '/' . '../invite.txt';
  if (ENABLE_INVITE != 'true') return true;
  if (!is_file($invite_file)) return false;

  $allowed_users = file('invite.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (!in_array(strtolower($user), $allowed_users)) {
    $_SESSION['status'] = 'invite_fail';
    return false;
  }
  return true;
}

?>
