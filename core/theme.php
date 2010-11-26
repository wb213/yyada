<?php

class Theme {
  public $name = "basic";

  public function __construct($name) {
    $this->name = $name;
  }

  public function get_path() {
    chdir(__DIR__);
    chdir('../theme');
    $path = getcwd() . '/' . $this->name;
    chdir(__DIR__);
    return $path;
  }

  public function get_html_path($name) {
    return $this->get_path() . '/' . $name . '.html';
  }

  public static function list_all() {
    chdir(__DIR__);
    chdir('../theme');
    $d = dir(getcwd());
    chdir(__DIR__);
    $ret = array();
    while (false !== ($entry = $d->read())) {
      if (!preg_match('/.*\.theme$/', $entry)) continue;
      if (!is_dir('../theme/' . $entry)) continue;
      array_push($ret, preg_replace('/\.theme$/', '', $entry));
    }
    return $ret;
  }
}

?>
