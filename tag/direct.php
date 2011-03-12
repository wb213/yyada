<?php

function direct_menu_html() {
  echo "
<div class='direct_menu'>
  <a href='" . make_path("direct/inbox") . "'>Inbox</a>
 | <a href='" . make_path("direct/sent") . "'>Sent</a>
</div>";
}

function is_create_direct() {
  global $content;
  if (isset($content['create-direct']) && ! empty($content['create-direct']))
    return true;
  else
    return false;
}

function create_direct_html() {
  global $content;
  if (isset($content['is_followed_by']) && (false == $content['is_followed_by'])) {
    echo "<div class='warning'>Sorry, Target user not following you, you can't send the DM to him/her.";
    return;
  }

  if (isset($content['create-to']) && ! empty($content['create-to']))
    $user = $content['create-to'];
  else
    $user = '';

  echo "<form class='create-direct' method='post' action='".make_path('/direct/create')."'>";
  echo "To: <input type='text' name='to' value='$user'><br />";
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
  echo 'class='.implode(' ', $classes);
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
  echo "<div class='direct'>";
  echo "<div class='toolbar'>";
  echo "<span class='name'>".$name."</span>|";
  echo "<a class='screen_name' href='".make_path("user/show/".$screen_name)."'>".$screen_name."</a>";
  echo "<a class='reply' href='".make_path("direct/create/".$screen_name)."'>DM</a>";
  echo "<a class='delete' href='".make_path("direct/remove/".$direct->id)."'>DEL</a>";
  echo "|<span class='time'>".format_time(strtotime($direct->created_at), 0)."</span>";
  echo "</div>";
  echo "<div class='direct-text'>".format_tweet($direct->text)."</div>";
  echo "</div>";
}

function direct_page_menu() {
  global $content;

  if (isset($_GET['page']))
    $page = (int)$_GET['page'];
  if (!isset($page))
    $page = 1;
  if ($page > 1) {
    if ($page == 2)
      echo '<a href="'.get_current_path().'">PageUp</a>';
    else
      echo '<a href="'.get_current_path().'?page='.(string)($page-1).'">PageUp</a>';
    echo '|';
  }
  echo '<a href="'.get_current_path().'?page='.(string)($page+1).'">PageDown</a>';
}

?>
