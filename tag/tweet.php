<?php

require_once('util/tweet.php');

function update_html() {
  global $content;

  $reply_tweet_id = "";
  $reply_tweet_name = "";
  if (isset($content['reply_tweet_id'])) {
    $reply_tweet_id = $content['reply_tweet_id'];
  }
  if (isset($content['reply_tweet_name'])) {
    $reply_tweet_name = $content['reply_tweet_name'];
  }

  echo "
<form class='update' method='post' action='".make_path('tweet/update')."'>
  <textarea id='status' name='status' rows='3'>$reply_tweet_name</textarea>
  <div>
    <input name='in_reply_to_id' value='$reply_tweet_id' type='hidden' />
    <input type='submit' value='Update' />
    <span id='remaining'>140</span>
    <span id='geo'>
      <input onclick='goGeo()' type='checkbox' id='geoloc' name='location' />
      <label for='geoloc' id='lblGeo'></label>
    </span>
  </div>
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

function has_tweet_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['tweets']);
}

function list_tweet_item_class() {
  global $content, $access_token;

  $classes = array();
  $current_user = $access_token['screen_name'];
  $tweet = $content['tweets'][$content['iter']];
  if (($content['iter'] % 2) == 0)
    array_push($classes, 'even');
  if (!isset($content['mentioned']) || $content['mentioned'])
    if (is_mentioned($tweet->text))
      array_push($classes, 'mentioned');
  if (count($classes) == 0) return '';
  echo "class='" . implode(' ', $classes) . "'";
}

function list_tweet_item_html() {
  global $settings, $content, $access_token;

  $tweet = $content['tweets'][$content['iter']];

  if ($settings->show_avatar) {
    echo "<img class='avatar' src='".$tweet->user->profile_image_url."' alt='".$tweet->user->name."' />";
  }
  echo "<div class='tweet'>";
  echo "<div class='toolbar'>";
  echo $tweet->user->name." |<a class='name' href='".make_path("user/show/".$tweet->user->screen_name)."'>".$tweet->user->screen_name."</a>";
  echo "<a class='reply' href='".make_path("tweet/reply/".$tweet->id_str)."'>@</a>";
  if (is_reply_all('@'.$tweet->user->screen_name.' '.$tweet->text))
    echo "<a class='replyall' href='".make_path("tweet/replyall/".$tweet->id_str)."'>@@</a>";
  echo "<a class='direct' href='".make_path("direct/create/".$tweet->user->screen_name)."'>DM</a>";
  if ($tweet->favorited)
    echo "<a class='unfavor' href='".make_path("favor/remove/".$tweet->id_str)."'>unFAV</a>";
  else
    echo "<a class='favor' href='".make_path("favor/add/".$tweet->id_str)."'>FAV</a>";
  echo "<a class='retweet' href='".make_path("tweet/retweet/".$tweet->id_str)."'>RT</a>";
  if ($tweet->user->screen_name == $access_token['screen_name'])
    echo "<a class='del' href='".make_path("tweet/remove/".$tweet->id_str)."'>DEL</a>";
  if (isset($tweet->geo)) {
    $lat = $tweet->geo->coordinates[0];
    $long = $tweet->geo->coordinates[1];
    $point = "$lat,$long";
    echo "<a class='geo' href='http://maps.google.com/maps/api/staticmap?center=$point&markers=$point&sensor=false&size=400x400&zoom=12'>geo</a>";
  }
  echo "<a class='time' href='".make_path("tweet/show/".$tweet->id_str)."'>".format_time(strtotime($tweet->created_at), 0)."</a>";
  echo "</div>";
  echo "<div class='status'>".format_tweet($tweet->text)." ";
  echo "<span class='via'>via ".$tweet->source;
  if (isset($tweet->in_reply_to_status_id_str))
    echo " <a class='reply' href='".make_path("tweet/reply/".$tweet->id_str)."'>in reply to ".$tweet->in_reply_to_screen_name."</a>";
  echo "</span></div></div>";
}

function is_delete_tweet() {
  global $content;

  if (isset($content['delete']) && ! empty($content['delete']))
    return true;
  else
    return false;
}

function delete_html() {
  global $content;

  echo "<form action='".make_path('tweet/remove/'.$content['delete'])."' method='post'>";
  echo "<input type='submit' value='Yes please' />";
  echo "</form>";
}

function new_retweet_html() {
  global $content;

  echo "<form action='".make_path("tweet/retweet/".$content['retweet_id'])."' method='post'>";
  echo "<input type='submit' value='Twitter Retweet' />";
  echo "</form>";
}

function old_retweet_html() {
  global $content, $settings;

  $retweet = $settings->rt_format;
  $retweet = str_ireplace("%u", $content['retweet_user'], $retweet);
  $retweet = str_ireplace("%t", $content['retweet_text'], $retweet);
  $content['reply_tweet_name'] = $retweet;
  update_html();
}

function tweet_page_menu() {
  global $content;

  if (isset($_GET['page']))
    $page = (int)$_GET['page'];
  if (!isset($page))
    $page = 1;
  if ($page > 1) {
    if ($page == 2)
      echo '<a href="'.get_current_path().'">PageUp</a>';
    else
      echo '<a href="'.get_current_path().'?page='.(string)($page-1).'">PageUp</a>';
    echo '|';
  }
  echo '<a href="'.get_current_path().'?page='.(string)($page+1).'">PageDown</a>';
}

?>
