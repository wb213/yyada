<?php

function user_info_html() {
  global $settings, $content;
  if (!isset($content['tweets'])) return;
 
  $tweet = $content['tweets'][0];
  if ($settings->show_avatar)
    echo "<img src='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."' />";
  else
    echo "<a href='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."'>Avatar</a>";
  echo "<a class='name' href='".join_path(BASE_URL, "user/show", $tweet->user->screen_name)."'>".$tweet->user->screen_name."</a>"."(".$tweet->user->name.")<br/>";
  echo "Bio: ". $tweet->user->description."<br/>";
  echo "Link: <a target='_blank' href='".$tweet->user->url."'>".$tweet->user->url."</a><br/>";
  echo "Location: ". $tweet->user->location."<br/>";
  echo "Joined: ". $tweet->user->created_at."<br/>";
}

?>
