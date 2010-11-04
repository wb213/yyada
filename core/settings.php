<?php

class Settings {

  public $theme = "basic";
  public $show_avatar = true;
  public $is_reverse_thread = false;
  public $show_img = false;
  public $rt_format = "RT %u: %t";

  public function __construct($s = null) {
    if (isset($s) && $s != "") {
      error_log("s: ".$s);
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

?>
