<?php

require_once('config.php');
require_once('core/environment.php');
require_once('core/theme.php');

init_environment();

switch ($_SESSION['status']) {
  case 'verified':
    dispatch_url();
    break;
  case 'login_fail':
    purge_settings();
    $content['info'] = '<div class="warning">Sign in failed, please try again.</div>';
    $content['info'] .= login_html($echo=false);
    $theme->include_html('info');
    break;
  case 'invite_fail':
    purge_settings();
    $content['info'] = '<div class="warning">You are not invited by administrator.</div>';
    $content['info'] .= login_html($echo=false);
    $theme->include_html('info');
    break;
  case 'logoff':
  default:
    purge_settings();
    $content['info'] = login_html($echo=false);
    $theme->include_html('info');
    break;
}

?>
