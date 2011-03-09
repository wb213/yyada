<?php

require_once('util/url.php');

function login_html($echo = true) {
  $ret =
'<div>
  <h2>Welcome to yyada, Yet Yyada Another DAbr.</h2>
  <a href="'.make_path("login/oauth").'">Login with oauth proxy</a><p>or</p>
  <a href="https://api.twitter.com/oauth"><img src="'.make_path('twitter_button.gif').'" alt="Sign in with Twitter/OAuth" width="165" height="28" /></a>
</div>';
  if ($echo) echo $ret;
  return $ret;
}

?>
