<?php

require_once('core/cookie.php');

class Settings {

  private $args = array(
    "theme" => array("string", "basic"),
    "show_avatar" => array("bool", false),
    "show_img" => array("bool", false),
    "rt_format" => array("string", "RT %u: %t"),
    "highlight" => array("string","RT"),
    "filter" => array("string",""),
  );

  private $cookie_key = "config";

  public function __construct($s = null) {
    if (isset($s) && $s != "") {
      $this->load($s);
    }
  }

  public function str() {
    $ret = "";
    foreach ($this->args as $key => $value) {
      $ret .= urlencode((string)$value[1]);
      $ret .= '|';
    }
    return rtrim($ret, '|');
  }

  public function load($s = null) {
    if (!isset($s)) $s = cookie_get($this->cookie_key);

    $args = explode('|', $s);
    if (count($args) != count($this->args))
      return;
    $keys = array_keys($this->args);

    for ($i=0; $i<count($args); $i++) {
      $temp = urldecode($args["$i"]);
      $key = $keys["$i"];
      if (settype($temp, $this->args[$key][0]))
        $this->args[$key][1] = $temp;
    }
  }

  public function __get($name) {
    $keys = array_keys($this->args);
    if (!in_array($name, $keys)) {
      throw new Exception("can't find setting: $name");
      return '';
    }
    return $this->args[$name][1];
  }

  public function __set($name, $value) {
    $keys = array_keys($this->args);
    if (!in_array($name, $keys)) {
      throw new Exception("can't find setting: $name");
      return '';
    }
    $this->args[$name][1] = $value;
    settype($this->args[$name][1], $this->args[$name][0]);
  }

  public function save() {
    cookie_set($this->cookie_key, $this->str());
  }

  public static function purge() {
    session_unset();
    cookie_clear();
  }
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
