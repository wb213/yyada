<?php

function save_access_token($access_token) {
  $s = new settings();
  $str = join('|', array($access_token['oauth_token'], $access_token['oauth_token_secret'], $access_token['user_id'], $access_token['screen_name']));
  $s->set_secret('access_token', $str);
}

function load_access_token() {
  $s = new settings();
  $str = $s->get_secret('access_token', null);
  $ret = null;
  if (isset($str)) {
    list($oauth_token, $oauth_token_secret, $user_id, $screen_name) = explode('|', $str);
    $ret = array('oauth_token' => $oauth_token,
                 'oauth_token_secret' => $oauth_token_secret,
                 'user_id' => $user_id,
                 'screen_name' => $screen_name);
  }
  return ret;
}

?>
