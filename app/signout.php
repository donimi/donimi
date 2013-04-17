<?php
class signout_app extends app_core{
  public function __construct(){
    parent::__construct();
  }

  public function index(){
    $this->_signout();
    $this->res();
  }

  private function _signout(){
    if(isset($_SESSION)){
      if(isset($_COOKIE[session_name()])){
        setcookie(session_name(), '', time()-36000, '/');
      }
      unset($_SESSION);
      session_destroy();
    }
    return true;
  }
}