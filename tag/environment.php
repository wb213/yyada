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
  global $access_token, $conn;

  $ret = $conn->get('account/rate_limit_status');
  $api_remain = $ret->remaining_hits;
  $api_total = $ret->hourly_limit;

  echo "
<div class='menu'>
  <a href='".make_path("user/show/".$access_token['screen_name'])."'>Profile</a>
 | <a href='".BASE_URL."'>Home</a>
 | <a href='".make_path("tweet/mention")."'>Mention</a>
 | <a href='".make_path("direct")."'>Directs</a>
 | <a href='".make_path("favor")."'>Favourite</a>
 | <a href='".make_path("search")."'>Search</a>
 | <a href='".make_path("user/followers")."'>Followers</a>
 | <a href='".make_path("user/friends")."'>Friends</a>
 | <a href='".make_path("list")."'>List</a>
 | <a href='".make_path("settings")."'>Settings</a>
 | <a href='".make_path("login/clear")."'>Logout</a>
 | <a href='http://code.google.com/p/yyada/issues/list' style='color:red; font-weight:bold;' >BUG REPORT</a>
    API Limit($api_remain/$api_total)
</div>
";
}

?>
