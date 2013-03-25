<?php
class main_app extends app_core{
  public function __construct(){
    parent::__construct(false);
  }

  public function index(){
    $this->tpl();
    $this->frame();
    $this->tpl->display('main.html');
  }
}