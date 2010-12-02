<?php

function settings_html() {
  global $settings;
  $s = $settings;

  echo '<form action="/settings" method="post">';
  echo '<p>Theme:<select name="theme">';
  foreach (Theme::list_all() as $theme_name) {
    echo '<option value="' . $theme_name . '"';
    if ($theme_name == $s->theme) {
      echo ' selected="selected"';
    }
    echo '>' . $theme_name . '</option>';
  }
  echo '</select></p>';

  echo '<p><input type="checkbox" name="avatar" value="yes"';
  echo $s->show_avatar?' checked="checked"':'';
  echo '" />Show avatar.';
  echo '</p>';

  echo '<p><input type="checkbox" name="reverse" value="yes"';
  echo $s->is_reverse_thread?' checked="checked"':'';
  echo '" />Reverse the conversation thread.';
  echo '</p>';

  echo '<p><input type="checkbox" name="img" value="yes"';
  echo $s->show_img?' checked="checked"':'';
  echo '" />Show image.';
  echo '</p>';

  echo '<p>RT format: <input type="text" name="rt_format" maxlength="140" value="';
  echo $s->rt_format;
  echo '" />';
  echo '</p>';

  echo '<input type="submit" value="Submit">';
  echo '</form>';
}

?>
