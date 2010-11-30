<?php

require_once('util/url.php');

function theme_name($echo = true) {
  global $theme;
  $ret = $theme->name;
  if ($echo) echo $ret;
  return $ret;
}

function menu() {
  global $access_token;
  echo "
<div class='menu'>
  <a href='".join_path(BASE_URL, "user/show", $access_token['screen_name'])."'>Profile</a> | <a href='".BASE_URL."'>Home</a> | <a href='".join_path(BASE_URL, "user/mention")."'>Mention</a> | <a href='".join_path(BASE_URL, "direct")."'>Directs</a> | <a href='".join_path(BASE_URL, "favor")."'>Favourite</a> | <a href='".join_path(BASE_URL, "search")."'>Search</a> | <a href='".join_path(BASE_URL, "list")."'>List</a> | <a href='".join_path(BASE_URL, "settings")."'>Settings</a> | <a href='".join_path(BASE_URL, "login/clear")."'>Logout</a>
</div>
";
}

?>
