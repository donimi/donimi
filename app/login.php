<?php
class login_app extends app_core{
  public function __construct(){
    parent::__construct(false);
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
      if($this->_login($user)){
        $this->res();
      } else {
        $this->res(1);
      }
    }
  }

  private function _login($user){
    $_SESSION['user'] = $user;
    return true;
  }
}