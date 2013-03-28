<?php
class spider_cmd extends cmd_core{
  public function __construct($id){
    parent::__construct($id);
    $this->index();
    $this->end();
  }

  public function index(){

  }
}