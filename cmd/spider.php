<?php
class spider_cmd extends cmd_core{
  public function __construct($id){
    parent::__construct($id);
    $result = $this->index();
    $this->end($result);
  }

  public function index(){
    $id = $this->param['id'];
    return true;
  }
}