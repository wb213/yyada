<?php

require_once('core/twitter.php');
require_once('core/cookie.php');
require_once('util/url.php');

class Monitor {

  public $urls = array(
    'mention' => array('twitter' => 'statuses/mentions', 'yyada' => 'tweet/mention'),
    'direct' => array('twitter' => 'direct_messages', 'yyada' => 'direct'),
  );

  private $urls_key = 'monitor';
  private $time_key = 'check';
  private $interval = 3; // 5 * 60 seconds

  public function __construct() {
    $this->load();
  }

  public function save() {
    $save_str = '';
    foreach ($this->urls as $name => $urls) {
      if ($name == 'mention' || $name == 'direct')
        continue;

      $save_str .= '|'.urlencode($name);
    }

    cookie_set('monitor', trim($save_str, '|'));
  }

  public function load() {
    $save_str = cookie_get('monitor');
    if (empty($save_str))
      return;

    foreach (explode("|", $save_str) as $name) {
      $name = urldecode($name);
      list($obj, $user, $list_id) = explode('/', $name);
      $twitter_url = $user."/lists/".$list_id.'/statuses';
      $yyada_url = 'list/show/'.$user.'/'.$list_id;
      $this->add($name, $twitter_url, $yyada_url);
    }
  }

  public __construct() {
    $this->load();
  }

  public function save() {
    $save_str = '';
    foreach ($this->urls as $name => $urls) {
      if ($name == 'mention' || $name == 'direct')
        continue;

      $save_str .= '|'.urlencode($name);
    }

    cookie_set('monitor', trim($save_str, '|'));
  }

  public function load() {
    $save_str = cookie_get('monitor');
    if (empty($save_str))
      return;

    foreach (explode("|", $save_str) as $name) {
      list($obj, $user, $list_id) = explode('/', $name);
      $twitter_url = $user."/lists/".$list_id.'status';
      $yyada_url = 'list/show'.$user.'/'.$list_id;
      $this->add($name, $twitter_url, $yyada_url);
    }
  }

  public function find($name) {
    $keys = array_keys($this->urls);
    for ($i=0; $i<count($this->urls); $i++)
      if ($keys["$i"] == $name)
        return $i;
    return -1;
  }

  public function get($index) {
    $keys = array_keys($this->urls);
    return $this->urls[$keys["$i"]];
  }

  public function add($name, $twitter_url, $yyada_url) {
    $index = $this->find($name);

    if ($index == -1)
      $this->urls[$name] = array('twitter' => $twitter_url, 'yyada' => $yyada_url);
  }

  public function is_new($name) {
    if (!isset($_SESSION['monitor']))
      return false;
    return isset($_SESSION['monitor'][$name]);
  }

  public function remove($name) {
    $index = $this->find($name);

    if ($index != -1)
      unset($this->urls[$name]);
  }

  public function check_new() {
    global $conn;

    if (!isset($_SESSION['monitor']))
      $_SESSION['monitor'] = array();

    foreach ($this->urls as $name => $url) {
error_log($_SERVER['REQUEST_URI']);
error_log(make_path($url['yyada']));
      if ($_SERVER['REQUEST_URI'] == make_path($url['yyada']))
        unset($_SESSION['monitor'][$name]);
    }

    $now = time();
    $last = cookie_get($this->time_key, '0');

    if ((int)($now / $this->interval) <= (int)($last / $this->interval))
      return;

    foreach ($this->urls as $name => $url) {
      if ($_SERVER['REQUEST_URI'] == make_path($url['yyada'])) {
        continue;
      }
      if ($this->is_new($name))
        continue;

      $tweets = twitter_get($url['twitter']);
      $time = 0;
      if (count($tweets) > 0)
        $time = strtotime($tweets[0]->created_at);
      if ($time > $last)
        $_SESSION['monitor'][$name] = true;
    }

    cookie_set($this->time_key, $now);
  }
}

?>
