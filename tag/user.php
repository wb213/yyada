<?php

function user_info_html() {
  global $content;
  if (!isset($content['tweets'])) return;
 
  $tweet = $content['tweets'][0];
  echo "<img src='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."' />";
  echo "<a class='name' href='".join_path(BASE_URL, "user/show", $tweet->user->screen_name)."'>".$tweet->user->screen_name."</a>"."(".$tweet->user->name.")<br/>";
  echo "Bio: ". $tweet->user->description."<br/>";
  echo "Link: <a target='_blank' href='".$tweet->user->url."'>".$tweet->user->url."</a><br/>";
  echo "Location: ". $tweet->user->location."<br/>";
  echo "Joined: ". $tweet->user->created_at."<br/>";
}

?>
