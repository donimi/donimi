<?php
class feed_model extends model_core {
  public function __construct(){
    parent::__construct();
    $this->table('feed');
  }
}