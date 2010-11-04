<?php

class Theme {
  public $path = "";

  public function __construct($name) {
    chdir(__DIR__);
    chdir('../theme');
    $this->path = implode('/', array(getcwd(), $name . '.theme'));
    chdir(__DIR__);
  }

  public function get_html_path($name) {
    return implode('/', array($this->path, $name . '.html'));
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
