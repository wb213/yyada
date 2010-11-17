<?php

function echo_theme_path() {
  $t = get_theme();
  echo '/theme/' . $t->name . '.theme';
}

function echo_settings() {
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

function echo_menu() {
  global $access_token;
  echo "<div class='menu'><a href='".path_join(BASE_URL, "user/show", $access_token['screen_name'])."'>Profile</a> | <a href='".BASE_URL."'>Home</a> | <a href='".path_join(BASE_URL, "user/mention")."'>Mention</a> | <a href='".path_join(BASE_URL, "direct")."'>Directs</a> | <a href='".path_join(BASE_URL, "favor")."'>Favourite</a> | <a href='".path_join(BASE_URL, "search")."'>Search</a> | <a href='".path_join(BASE_URL, "list")."'>List</a> | <a href='".path_join(BASE_URL, "settings")."'>Settings</a> | <a href='".path_join(BASE_URL, "login/clear")."'>Logout</a></div>";
}

function echo_info() {
  global $content;

  if (isset($content['information'])) {
    echo $content['information'];
  }
}

function echo_remove_tweet() {
  global $content;

  echo "<p>Are you really sure you want to delete your tweet?<br />There is no way to undo this action.</p>";
  echo "<form action='/tweet/delete/".$_GET['args']."' method='post'>";
  echo "<input type='submit' value='Yes please' />";
  echo "</form>";
}

function echo_update() {
  global $content;

  $reply_tweet_id = "";
  $reply_tweet_name = "";
  if (isset($content['reply_tweet_id'])) {
    $reply_tweet_id = $content['reply_tweet_id'];
    $reply_tweet_name = $content['reply_tweet_name'];
  }

  echo "
<form class='update' method='post' action='/tweet'>
  <textarea id='status' name='status' rows='3'>$reply_tweet_name</textarea>
  <input name='in_reply_to_id' value='$reply_tweet_id' type='hidden' />
  <input type='submit' value='Update' />
  <span id='remaining'>140</span> 
  <span id='geo'>
    <input onclick='goGeo()' type='checkbox' id='geoloc' name='location' />
    <label for='geoloc' id='lblGeo'></label>
  </span> 
  <script type='text/javascript'> 
started = false;
chkbox = document.getElementById('geoloc');
if (navigator.geolocation) {
  geoStatus('Tweet my location');
  if ('N'=='Y') {
    chkbox.checked = true;
    goGeo();
  }
}
function goGeo(node) {
  if (started) return;
  started = true;
  geoStatus('Locating...');
  navigator.geolocation.getCurrentPosition(geoSuccess, geoStatus);
} 
function geoStatus(msg) {
  document.getElementById('geo').style.display = 'inline';
  document.getElementById('lblGeo').innerHTML = msg;
}
function geoSuccess(position) {
  geoStatus('Tweet my location');
  chkbox.value = position.coords.latitude + ',' + position.coords.longitude;
}
function updateCount() {
  document.getElementById('remaining').innerHTML = 140 - document.getElementById('status').value.length;
  setTimeout(updateCount, 400);
}
updateCount();
  </script> 
</form>";
}

function echo_tweet($tweet=null) {
  global $settings, $content, $access_token;
  if (empty($tweet))
    if (isset($content['tweet']))
      $tweet = $content['tweet'];
    else
      return;

  if ($settings->show_avatar) {
    echo "<img class='avatar' src='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."' />";
  }
  echo "<div class='tweet'>";
  echo "<div class='toolbar'>";
  echo $tweet->user->name." |<a class='name' href='".path_join(BASE_URL, "user/show", $tweet->user->id_str)."'>".$tweet->user->screen_name."</a>";
  echo "<a class='reply' href='".path_join(BASE_URL, "tweet/reply", $tweet->id_str)."'>@</a>";
  if (count(get_mentioned_users('@'.$tweet->user->screen_name.' '.$tweet->text)) > 1)
    echo "<a class='replyall' href='".path_join(BASE_URL, "tweet/replyall", $tweet->id_str)."'>@@</a>";
  echo "<a class='direct' href='".path_join(BASE_URL, "direct/new", $tweet->user->id_str)."'>DM</a>";
  if ($tweet->favorited)
    echo "<a class='unfavor' href='".path_join(BASE_URL, "favor/remove", $tweet->id_str)."'>unFAV</a>";
  else
    echo "<a class='favor' href='".path_join(BASE_URL, "direct/new", $tweet->id_str)."'>FAV</a>";
  echo "<a class='retweet' href='".path_join(BASE_URL, "tweet/retweet", $tweet->id_str)."'>RT</a>";
  if ($tweet->user->screen_name == $access_token['screen_name'])
    echo "<a class='del' href='".path_join(BASE_URL, "tweet/delete", $tweet->id_str)."'>DEL</a>";
  if (isset($tweet->geo)) {
    $lat = $tweet->geo->coordinates[0];
    $long = $tweet->geo->coordinates[1];
    $point = "$lat,$long";
    echo "<a class='geo' href='http://maps.google.com/maps/api/staticmap?center=$point&markers=$point&sensor=false&size=400x400&zoom=12'>geo</a>";
  }
  echo "<a class='time' href='".path_join(BASE_URL, "tweet/show", $tweet->id_str)."'>".format_time(strtotime($tweet->created_at), 0)."</a>";
  echo "</div>";
  echo "<div class='status'>".format_tweet($tweet->text)." ";
  echo "<span class='via'>via ".$tweet->source;
  if (isset($tweet->in_reply_to_status_id_str))
    echo " <a class='reply' href='".path_join(BASE_URL, "tweet/reply", $tweet->id_str)."'>in reply to ".$tweet->in_reply_to_screen_name."</a>";
  echo "</span></div></div>";
}

function echo_tweets() {
  global $content, $access_token;
  if (!isset($content['tweets'])) return;

  echo "<ul class='tweets'>";
  $current_user = strtolower($access_token['screen_name']);
  $count = 0;
  foreach ($content['tweets'] as $tweet) {
    echo "<li class='";
    if ((++$count & 1) == 0) echo ' even';
    if (in_array('@'.$current_user, get_mentioned_users($tweet->text)))
      echo " mentioned";
    echo "'>";
    echo_tweet($tweet);
    echo "</li>";
  }
  echo "</ul>";
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
