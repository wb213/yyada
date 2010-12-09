<?php

require_once('util/url.php');
require_once('config.php');

function base_url() {
  echo BASE_URL;
}

function base_path() {
  echo make_path('/');
}

function theme_name($echo = true) {
  global $theme;
  $ret = $theme->name;
  if ($echo) echo $ret;
  return $ret;
}

function menu() {
  global $monitor, $access_token, $conn;

  if (array_get($_SESSION, 'status', '') != 'verified') {
    echo "<div class='menu'><a href='".make_path("/")."'>Home</a></div>";
    return;
  }

  $ret = twitter_get('account/rate_limit_status');
  $api_remain = $ret->remaining_hits;

  echo "
<div class='menu'>
  <a href='".make_path("user/show/".$access_token['screen_name'])."'>Profile</a>
 | <a href='".make_path("/")."'>Home</a>
 | <a ".($monitor->is_new('mention')?"class='important' ":"")."href='".make_path("tweet/mention")."'>Mention</a>
 | <a ".($monitor->is_new('direct')?"class='important' ":"")."href='".make_path("direct")."'>Directs</a>
 | <a href='".make_path("favor")."'>Favourite</a>
 | <a href='".make_path("search")."'>Search</a>
 | <a href='".make_path("user/followers")."'>Followers</a>
 | <a href='".make_path("user/friends")."'>Friends</a>
 | <a href='".make_path("list")."'>List</a>
 | <a href='".make_path("settings")."'>Settings</a>
 | <a href='".make_path("login/clear")."'>Logout</a>
 | <a class='important' href='http://code.google.com/p/yyada/issues/list' target='_blank' >BUG REPORT</a>
    API Remain: $api_remain
</div>
";
}

?>
