<?php

function echo_theme_path() {
  $t = get_theme();
  echo $t->get_path();
}

function echo_settings() {
  global $settings;
  $s = $settings;

  echo '<form action="settings" method="post">';
  echo '<p>Theme:<select name="theme">';
  foreach (Theme::list_all() as $theme_name) {
    echo '<option value="' . $theme_name . '"';
    if ($theme_name == $s->theme) {
      echo ' selected="selected"';
    }
    echo '>' . $theme_name . '</option>';
  }
  echo '</p>';

  echo '<p><input type="checkbox" name="avatar" value="';
  echo $s->show_avatar?'yes':'no';
  echo '" />Show avatar.';
  echo '</p>';

  echo '<p><input type="checkbox" name="reverse" value="';
  echo $s->is_reverse_thread?'yes':'no';
  echo '" />Reverse the conversation thread.';
  echo '</p>';

  echo '<p><input type="checkbox" name="img" value="';
  echo $s->show_img?'yes':'no';
  echo '" />Show image.';
  echo '</p>';

  echo '<p>RT format: <input type="text" name="rt_format" maxlength="140" value="';
  echo $s->rt_format;
  echo '" />';
  echo '</p>';
}

function echo_menu() {
  global $access_token;
  echo "<div class='menu'><a href='".path_join(BASE_URL, "user/show", $access_token['screen_name'])."'>Profile</a> | <a href='".BASE_URL."'>Home</a> | <a href='".path_join(BASE_URL, "user/mention")."'>Mention</a> | <a href='".path_join(BASE_URL, "direct")."'>Directs</a> | <a href='".path_join(BASE_URL, "favor")."'>Favourite</a> | <a href='".path_join(BASE_URL, "search")."'>Search</a> | <a href='".path_join(BASE_URL, "list")."'>List</a></div>";
}

function parse_tweet($tweet) {
  global $settings;
  $ret = "<p class='tweet'>";
  if ($settings->show_avatar) {
    $ret .= "<img src='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."' />";
  }
  $ret .= "<a class='name' href='".path_join(BASE_URL, "user/show", $tweet->user->id_str)."'>".$tweet->user->name."</a>";
  $ret .= "<a class='reply' href='".path_join(BASE_URL, "tweet/reply", $tweet->id_str)."'>reply</a>";
  $ret .= "<a class='direct' href='".path_join(BASE_URL, "direct/new", $tweet->user->id_str)."'>direct</a>";
  if ($tweet->favorited)
    $ret .= "<a class='unfavor' href='".path_join(BASE_URL, "favor/remove", $tweet->id_str)."'>unfavor</a>";
  else
    $ret .= "<a class='favor' href='".path_join(BASE_URL, "direct/new", $tweet->id_str)."'>favor</a>";
  $ret .= "<a class='retweet' href='".path_join(BASE_URL, "tweet/retweet", $tweet->id_str)."'>retweet</a>";
  $ret .= "<a class='time' href='".path_join(BASE_URL, "tweet/show", $tweet->id_str)."'>".format_time(strtotime($tweet->created_at), 0)."</a>";
  $ret .= "<span class='content'>".format_tweet($tweet->text)."</span>";
  $ret .= "<span class='via'>via ".$tweet->source."</span>";
  if (isset($tweet->in_reply_to_status_id_str))
    $ret .= "<a class='reply' href='".path_join(BASE_URL, "tweet/show_reply", $tweet->id_str)."'>in reply to ".$tweet->in_reply_to_screen_name."</a>";
  $ret .= "</p>";
  return $ret;
}

function echo_tweets() {
  global $content, $access_token;
  echo "<ul class='tweets'>";
  foreach ($content['tweets'] as $tweet) {
    echo "<li";
    if (is_user_mention_in_tweet($access_token['screen_name'], $tweet->text))
      echo " class='mentioned'";
    echo ">";
    echo parse_tweet($tweet);
    echo "</li>";
  }
  echo "</ul>";
}

function echo_tweet() {
  global $content;
  echo parse_tweet($content['tweet']);
}

function echo_users() {
}

function echo_user() {
}

function echo_lists() {
}

function echo_list() {
}

?>
