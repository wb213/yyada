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

function has_user_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['user_list']);
}

function list_user_html() {
  global $settings, $content, $access_token;

  $users = $content['user_list'][$content['iter']];

  $name = $users->name;
  $screen_name = $users->screen_name;
  $img_url = $users->profile_image_url;
  $desc = $users->description;
  $tweets = $users->statuses_count . "tweets";
  $friends = $users->friends_count . "friends";
  $followers = $users->followers_count . "followers";
  $favs = $users->favourites_count . "favs";
  $lists = $users->listed_count . "lists";

  if ($settings->show_avatar) {
        echo "<img class='avatar' src='".$img_url."' alt='".$name."' />";
  }

  echo $name." |<a class='name' href='".join_path(BASE_URL, "user/show", $screen_name)."'>".$screen_name."</a>";
  echo "<br />";
  echo "Bio: " . $desc;
  echo "Info: " . $tweets . ", " . $friends . ", " . $followers . ", " . $favs . ", " . $lists;
}
?>
