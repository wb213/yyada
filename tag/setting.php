<?php

function settings_html($echo = true) {
  global $settings;
  $s = $settings;

  $ret = '<form action="/settings" method="post">';
  $ret .= '<p>Theme:<select name="theme">';
  foreach (Theme::list_all() as $theme_name) {
    $ret .= '<option value="' . $theme_name . '"';
    if ($theme_name == $s->theme) {
      $ret .= ' selected="selected"';
    }
    $ret .= '>' . $theme_name . '</option>';
  }
  $ret .= '</select></p>';

  $ret .= '<p><input type="checkbox" name="avatar" value="yes"';
  $ret .= $s->show_avatar?' checked="checked"':'';
  $ret .= '" />Show avatar.';
  $ret .= '</p>';

  $ret .= '<p><input type="checkbox" name="reverse" value="yes"';
  $ret .= $s->is_reverse_thread?' checked="checked"':'';
  $ret .= '" />Reverse the conversation thread.';
  $ret .= '</p>';

  $ret .= '<p><input type="checkbox" name="img" value="yes"';
  $ret .= $s->show_img?' checked="checked"':'';
  $ret .= '" />Show image.';
  $ret .= '</p>';

  $ret .= '<p>RT format: <input type="text" name="rt_format" maxlength="140" value="';
  $ret .= $s->rt_format;
  $ret .= '" />';
  $ret .= '</p>';

  $ret .= '<input type="submit" value="Submit">';
  $ret .= '</form>';

  if ($echo) echo $ret;
  return $ret;
}

?>
