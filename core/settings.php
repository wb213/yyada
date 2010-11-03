<?php

class settings {

  public static $ignore_keys = array(
    'PHPSESSID',
  );

  public function get($key, $default = NULL) {
    if (array_key_exists($key, $_COOKIE)) {
      return $_COOKIE[$key];
    }
    return $default;
  }

  public function set($key, $value) {
    $duration = time() + (3600 * 24 * 30); // one month
    setcookie($key, $value, $duration, '/');
  }

  public function set_secret($key, $value) {
    $td = mcrypt_module_open('blowfish', '', 'cfb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, SECRET_KEY, $iv);
    $crypt_text = mcrypt_generic($td, $value);
    mcrypt_generic_deinit($td);
    $this->set($key, base64_encode($iv . $crypt_text));
  }

  public function get_secret($key, $default = NULL) {
    $value = $this->get($key, $default);
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

  public static function clear() {
    $duration = time() - 3600;
    foreach (array_keys($_COOKIE) as $key) {
      if (in_array($key, self::$ignore_keys)) continue;
      setcookie($key, NULL, $duration, '/');
      setcookie($key, NULL, $duration);
    }
  }
}

?>
