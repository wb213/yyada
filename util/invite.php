<?php

function check_invite($user, $invite_file) {
  if (ENABLE_INVITE != 'true') return true;
  if (!is_file($invite_file)) return false;

  $allowed_users = file('invite.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (!in_array(strtolower($user), $allowed_users)) {
    return false;
  }
  return true;
}

?>
