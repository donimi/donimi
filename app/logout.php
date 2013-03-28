<?php
class logout_app extends app_core{
  public function __construct(){
    parent::__construct(false);
  }

  public function index(){
    $this->_logout();
    $this->res();
  }

  private function _logout(){
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