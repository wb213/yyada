<?php

class settings {

  public $ignore_keys = array(
    'AUTH',
    'UTC_OFFSET',
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

  public function clean() {
    $duration = time() - 3600;
    foreach (array_keys($_COOKIE) as $key) {
      if (!in_array($key, $ignore_keys)) {
        setcookie($key, NULL, $duration, '/');
        setcookie($key, NULL, $duration);
      }
    }
  }
}
