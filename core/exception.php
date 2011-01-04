<?php

class NoCookie extends Exception {
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

class NoInvited extends Exception {
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

class TwitterError extends Exception {
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

class ConnectError extends Exception {
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

?>
