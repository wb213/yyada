<?php

class Theme {
  public $name = "basic";

  public function __construct($name) {
    $this->name = $name;
  }

  public function get_path() {
    $path = getcwd() . '/theme/' . $this->name;
    return $path;
  }

  public function get_html_path($name) {
    return $this->get_path() . '/' . $name . '.html';
  }

  public static function list_all() {
    $d = dir(getcwd().'/theme');
    $ret = array();
    while (false !== ($entry = $d->read())) {
	if (preg_match('/\.+$/', $entry)) continue;
    	array_push($ret, preg_replace('/\.theme$/', '', $entry));
    }
    return $ret;
  }

  public function include_html($page) {
    include($this->get_html_path($page));
  }
}

?>
