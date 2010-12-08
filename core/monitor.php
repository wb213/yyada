<?php

require_once('core/cookie.php');

class Monitor {

  private $urls = array(
    'statuses/mentions',
    'direct_messages',
  );

  private $urls_key = 'monitor';
  private $time_key = 'check';
  private $interval = 300; // 5 * 60 seconds

  public function __construct($s = null) {
    if (isset($s) && !empty($s)) {
      $args = explode('|', $s);
      foreach ($args as $url) {
        $this->add($url);
      }
    }
  }

  public function find($url) {
    for ($i=0; $i<count($this->urls); $i++)
      if ($url == $this->urls["$i"])
        return $i;
    return -1;
  }

  public function get($index) {
    return $this->urls["$i"];
  }

  public function add($url) {
    $index = $this->find($url);

    if ($index == -1)
      array_push($this->addresses, $url);
  }

  public function is_new($url) {
    return isset($_SESSION[$url]);
  }

  public function remove($url) {
    $index = $this->find($url);

    if ($index != -1)
      unset($this->addresses[$index]);
  }

  public function check_new() {
    global $conn;

    if ($_SESSION['status'] != 'verified')
      return;

    $now = time();
    $last = cookie_get($this->time_key, '0');
    
    if (($now / $this->interval) <= ($last / $this->interval))
      return;

    foreach ($this->urls as $url) {
      if ($this->is_new($url))
        continue;

      $tweets = $conn->get($url);
      $time = strtotime($tweets[0]->created_at);
      if ($time > $last)
        $_SESSION[$url] = true;
    }

    cookie_set($this->time_key, $now);
  }
}

?>
