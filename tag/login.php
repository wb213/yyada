<?php

require_once('util/url.php');

function login_html($echo = true) {
  $ret =
'<div>
  <h2>Welcome to yyada, Yet Yyada Another DAbr.</h2>
  <a href="'.make_url("login/oauth").'"><img src="'.make_url('twitter_button.gif').'" alt="Sign in with Twitter/OAuth" width="165" height="28" /></a>
</div>';
  if ($echo) echo $ret;
  return $ret;
}

?>
