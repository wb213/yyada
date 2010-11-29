<?php

require_once('config.php');
require_once('core/environment.php');
require_once('core/settings.php');
require_once('core/theme.php');
require_once('tag/include.php');

init_environment();

switch ($_SESSION['status']) {
  case 'login_fail':
    Settings::purge();
    $content['info'] = '<div class="warning">Sign in failed, please try again.</div>';
    $content['info'] .= login_html($echo=false);
    break;
  case 'invite_fail':
    Settings::purge();
    $content['info'] = '<div class="warning">You are not invited by administrator.</div>';
    $content['info'] .= login_html($echo=false);
    break;
  case 'logoff':
    Settings::purge();
    $content['info'] = login_html($echo=false);
    break;
}

dispatch_url();

?>
