<?php

function get_settings() {
  $s = new Settings(cookie_get('config'));
  return $s;
}

function get_theme() {
  return new Theme(get_settings()->theme);
}

function get_twitter_conn() {
  $access_token = load_access_token();
  if (isset($access_token)) {
    return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  }
  return null;
}

function save_access_token($access_token) {
  $str = join('|', array($access_token['oauth_token'], $access_token['oauth_token_secret'], $access_token['user_id'], $access_token['screen_name']));
  cookie_set_secret('access_token', $str);
}

function load_access_token() {
  $str = cookie_get_secret('access_token', null);
  $ret = null;
  if (isset($str)) {
    list($oauth_token, $oauth_token_secret, $user_id, $screen_name) = explode('|', $str);
    if (isset($oauth_token)  && isset($oauth_token_secret) && isset($user_id) && isset($screen_name) && check_invite($screen_name)) {
      $ret = array('oauth_token' => $oauth_token,
                   'oauth_token_secret' => $oauth_token_secret,
                   'user_id' => $user_id,
                   'screen_name' => $screen_name);
      $_SESSION['status'] = 'verified';
    }
  }
  return $ret;
}

function save_settings() {
  global $settings;
  cookie_set('config', $settings->str());
}

?>
