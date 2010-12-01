<?php

function direct_menu_html() {
  echo "
<div class='direct_menu'>
  <a href='" . join_path(BASE_URL, "direct/create") . "'>Create</a> | 
  <a href='" . join_path(BASE_URL, "direct/inbox") . "'>Inbox</a> | 
  <a href='" . join_path(BASE_URL, "direct/sent") . "'>Sent</a>
</div>";
}

function is_create_direct() {
  global $content;
  if (isset($content['create-direct']) && ! empty($content['create-direct']))
    return true;
  else
    return false;
}

function create_direct_html($user) {
  global $content;

  if (empty($user)) {
    $post_action = '/direct/create';
  } else {
    $post_action = '/direct/create/'.$user;
  }

  echo "<form class='create-direct' method='post' action='$post_action'>";
  if (empty($user)) {
    echo "To: <input type='text' name='to'> <br />";
  } else {
    echo "Sending direct message to <b>$user</b><br />";
  }

  echo "
  Direct Message: <br />
  <textarea id='direct' name='direct' rows='3'></textarea>
  <div>
    <input type='submit' value='Send Direct' />
    <span id='remaining'>140</span>
  </div>
</form>
<script type='text/javascript'> 
  function updateCount() {
    document.getElementById('remaining').innerHTML = 140 - document.getElementById('direct').value.length;
    setTimeout(updateCount, 400);
  }
  updateCount();
</script>";
}

function has_direct_list() {
  global $content;

  if (!isset($content['iter']))
    $content['iter'] = 0;
  else
    $content['iter']++;
  return $content['iter'] < count($content['directs']);
}

function list_direct_item_class() {
  global $content;

  $classes = array();
  $direct = $content['directs'][$content['iter']];
  if (($content['iter'] % 2) == 0) array_push($classes, 'even');
  if (count($classes) == 0) return '';
  return 'class='.implode(' ', $classes);
}

function list_direct_item_html() {
  global $settings, $content, $access_token;

  $direct = $content['directs'][$content['iter']];

  if (isset($content['box']))
    $box = $content['box'];
  else
    $box = 'inbox';

  switch ($box) {
    case 'inbox':
      $name = $direct->sender->name;
      $screen_name = $direct->sender_screen_name;
      $img_url = $direct->sender->profile_image_url;
      break;
    case 'sent':
      $name = $direct->recipient->name;
      $screen_name = $direct->recipient_screen_name;
      $img_url = $direct->recipient->profile_image_url;
      break;
  }

  if ($settings->show_avatar) {
        echo "<img class='avatar' src='".$img_url."' alt='".$name."' />";
  }
  echo "<div class='direct-message'>";
  echo "<div class='direct-toolbar'>";
  echo $name." |<a class='name' href='".join_path(BASE_URL, "user/show", $screen_name)."'>".$screen_name."</a>";
  echo "<a class='direct-reply' href='".join_path(BASE_URL, "direct/create", $screen_name)."'>DM</a>";
  echo "<a class='direct-delete' href='".join_path(BASE_URL, "direct/delete", $direct->id)."'>DEL</a>";
  echo " | <span class='direct-time'>".format_time(strtotime($direct->created_at), 0)."</span>";
  echo "</div>";
  echo "<div class='direct-text'>".format_tweet($direct->text)."</div>";
  echo "</div>";
}

?>
