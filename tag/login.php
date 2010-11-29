<?php

function login_html($echo = true) {
  $ret = <<<HTML
<div>
  <h2>Welcome to yyada, Yet Yyada Another DAbr.</h2>
  <a href="/login/oauth"><img src="/twitter_button.gif" alt="Sign in with Twitter/OAuth" width="165" height="28" /></a>
</div>
HTML;
  if ($echo) echo $ret;
  return $ret;
}

?>
