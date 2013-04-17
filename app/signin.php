<?php
class signin_app extends app_core{
  public function __construct(){
    parent::__construct();
  }

  public function index(){
    if(func_core::ispost()){
      $email = $this->post('email');
      if($email == ''){
        $this->res(11);
      }
      $pass = sha1($this->post('pass'));
      $usermodel = new user_model();
      $user = $usermodel->login($email, $pass);
      if(empty($user)){
        $this->res(10);
      }
      if($this->_signin($user)){
        $this->res();
      } else {
        $this->res(1);
      }
    }
  }

  private function _signin($user){
    $_SESSION['user'] = $user;
    return true;
  }
}