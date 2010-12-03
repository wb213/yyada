<?php

function user_info_html() {
  global $settings, $content;
  if (!isset($content['tweets'])) return;
 
  $user = $content['tweets'][0]->user;

  $name = $user->name;
  $screen_name = $user->screen_name;
  $img_url = $user->profile_image_url;
  $desc = $user->description;
  $url = $user->url;
  $loc = $user->location;
  $join = format_time(strtotime($user->created_at), $user->utc_offset);
  $tweets = $user->statuses_count . " tweets";
  $friends = $user->friends_count . " friends";
  $followers = $user->followers_count . " followers";
  $favs = $user->favourites_count . " favs";
  $protected = $user->protected;

  if ($settings->show_avatar)
    echo "<img src='".$img_url."' alt='".$name."' />";
  else
    echo "<a href='".$img_url."' alt='".$name."'>Avatar</a>";
  if ($protected) echo "PROTECTED USER";
  echo "<br />";
  echo "<a class='name' href='".make_path("user/show/".$screen_name)."'>".$screen_name."</a>"."(".$name.")";
  echo "Bio: ". $desc."<br/>";
  echo "Link: <a target='_blank' href='".$url."'>".$url."</a><br/>";
  echo "Location: ". $loc."<br/>";
  echo "Joined: ". $join ."<br/>";
  echo "Info: " . $tweets . ", " . $friends . ", " . $followers . ", " . $favs;
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
  $tweets = $users->statuses_count . " tweets";
  $friends = $users->friends_count . " friends";
  $followers = $users->followers_count . " followers";
  $favs = $users->favourites_count . " favs";
  $lists = $users->listed_count . " lists";

  if ($settings->show_avatar) {
        echo "<img class='avatar' src='".$img_url."' alt='".$name."' />";
  }

  echo $name." |<a class='name' href='".make_path("user/show/".$screen_name)."'>".$screen_name."</a>";
  echo "<br />";
  echo "Bio: " . $desc;
  echo "<br />";
  echo "Info: " . $tweets . ", " . $friends . ", " . $followers . ", " . $favs . ", " . $lists;
}
?>
