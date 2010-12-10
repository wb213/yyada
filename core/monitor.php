<?php

require_once('core/twitter.php');
require_once('core/cookie.php');
require_once('util/url.php');

class Monitor {

  private $urls = array(
    'mention' => array('twitter' => 'statuses/mentions', 'yyada' => 'tweet/mention'),
    'direct' => array('twitter' => 'direct_messages', 'yyada' => 'direct'),
  );

  private $urls_key = 'monitor';
  private $time_key = 'check';
  private $interval = 300; // 5 * 60 seconds

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
    return isset($_SESSION[$name]);
  }

  public function remove($name) {
    $index = $this->find($name);

    if ($index != -1)
      unset($this->urls[$name]);
  }

  public function check_new() {
    global $conn;

    foreach ($this->urls as $name => $url)
      if ($_SERVER['REQUEST_URI'] == make_path($url['yyada']))
        unset($_SESSION[$name]);

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
        $_SESSION[$name] = true;
    }

    cookie_set($this->time_key, $now);
  }
}

?>
