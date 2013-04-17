<?php
class register_app extends app_core{
  public $model;
  public function __construct(){
    parent::__construct();
    $this->model = new waiting_model();
  }

  public function index(){
    if(func_core::ispost()){
      $email = filter_var($this->post('email'), FILTER_VALIDATE_EMAIL);
      if($email == null){
        $this->res(10);
      }
      $usermodel = new user_model();
      if($usermodel->exist('email', $email) > 0){
        $this->res(11);
      }
      $id = $this->model->exist('email', $email);
      if($id > 0){
        $this->res(11);
      } else {
        $waiting = array();
        $waiting['email'] = $email;
        $waiting['pass'] = $this->_getpass();
        $waiting['ip'] = func_core::getip();
        $waiting['created'] = time();
        $id = $this->model->set($waiting);
      }
      $this->res();
    }
  }

  private function _getpass(){
    $pass = func_core::getpass(16) . func_core::getconfuse();
    $pass = sha1($pass);
    if($this->model->exist('pass', $pass)){
      return $pass;
    } else {
      $this->_getpass();
    }
  }

  private function _regmail($id){
    $id = (int)$id;
    $waitmodel = new wait_model();
    $wait = $waitmodel->id($id);
    if(!empty($wait)){
      $param = array('id'=>$id);
      $param = json_encode($param);
      $cmdmodel = new cmd_model();
      $cmd = $cmdmodel->getparam('regmail', $param, -1);
      if(empty($cmd)){
        $cmd = array();
        $cmd['app'] = 'regmail';
        $cmd['param'] = $param;
        $cmd['created'] = time();
        $cmdmodel->set($cmd);
      } else {
        $wait['tms'] += 1;
        $wait['created'] = time();
        if((time() - $wait['created']) >= 60*60*24*10){
          $wait['ip'] = func_core::getip();
          $wait['pass'] = $this->_getpass();
          $cmd['created'] = time();
        } else {
          $cmd['created'] = $wait['tms'] >= 5 ? time() + $wait['tms'] * 60 : time();
          $cmd['created'] = (time() - $cmd['created']) < 60 ? $cmd['created'] + 60 : $cmd['created'];
        }
        $waitmodel->update($wait);
        $cmd['status'] = 0;
        $cmdmodel->update($cmd);
      }
    }
    return true;
  }
}