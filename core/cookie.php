<?php

require_once('core/exception.php');
require_once('util/url.php');
require_once('config.php');

function cookie_get($key, $default = NULL) {
  if (array_key_exists($key, $_COOKIE)) {
    return $_COOKIE[$key];
  }
  return $default;
}

function cookie_set($key, $value) {
  $duration = time() + (3600 * 24 * 30); // one month
  setcookie($key, $value, $duration, make_path('/'));
}

function cookie_set_secret($key, $value) {
  $td = mcrypt_module_open('blowfish', '', 'cfb', '');
  $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
  mcrypt_generic_init($td, SECRET_KEY, $iv);
  $crypt_text = mcrypt_generic($td, $value);
  mcrypt_generic_deinit($td);
  cookie_set($key, base64_encode($iv . $crypt_text));
}

function cookie_get_secret($key, $default = NULL) {
  $value = cookie_get($key, $default);
  if (empty($value)) return null;

  $crypt_text = base64_decode($value);
  $td = mcrypt_module_open('blowfish', '', 'cfb', '');
  $ivsize = mcrypt_enc_get_iv_size($td);
  $iv = substr($crypt_text, 0, $ivsize);
  $crypt_text = substr($crypt_text, $ivsize);
  mcrypt_generic_init($td, SECRET_KEY, $iv);
  $ret = mdecrypt_generic($td, $crypt_text);
  mcrypt_generic_deinit($td);

  return $ret;
}

function cookie_clear() {
  $ignore_keys = array(
    'PHPSESSID',
  );

  $duration = time() - 3600;
  foreach (array_keys($_COOKIE) as $key) {
    if (in_array($key, $ignore_keys)) continue;
    setcookie($key, NULL, $duration, make_path('/'));
    setcookie($key, NULL, $duration);
  }
}

function load_access_token() {
  $str = cookie_get_secret('access_token', null);
  $ret = null;
  if (!isset($_SESSION['status'])) $_SESSION['status'] = 'logoff';
  if (!isset($str)) {
    $_SESSION['status'] = 'logoff';
    throw new NoCookie('No cookie');
  }

  $args = explode('|', $str);
  if (count($args) != 4) {
    cookie_clear();
    $_SESSION['status'] = 'logoff';
    throw new Exception('fail cookie');
  } 
  list($oauth_token, $oauth_token_secret, $user_id, $screen_name) = $args;
  if (!check_invite($screen_name)) {
    $_SESSION['status'] = 'invite_fail';
    throw new NoInvited('Not invited');
  }
  if (!isset($oauth_token) || !isset($oauth_token_secret) || !isset($user_id) || !isset($screen_name)) {
    cookie_clear();
    $_SESSION['status'] = 'logoff';
    throw new Exception('fail cookie');
  }
  // should check if token still valid here
  $ret = array('oauth_token' => $oauth_token,
               'oauth_token_secret' => $oauth_token_secret,
               'user_id' => $user_id,
               'screen_name' => $screen_name);
  $_SESSION['status'] = 'verified';

  return $ret;
}

function save_access_token($access_token) {
  $str = join('|', array($access_token['oauth_token'], $access_token['oauth_token_secret'], $access_token['user_id'], $access_token['screen_name']));
  cookie_set_secret('access_token', $str);
}

?>
