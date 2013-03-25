<?php
class app_core extends io_core{
  public $tpl;
  public $user;
  public function __construct($islogin = true){
    if(!isset($_SESSION)){
      session_start();
    }
    if($islogin){
      if(!$this->islogin()){
        func_core::redirect('/login');
      }
    }
    $this->user = $this->user();
  }

  public function __call($act, $var){
    func_core::show404();
  }

  public function tpl(){
    $this->tpl = new Savant3();
    $tpldir = array();
    $tpldir[] = TPL_DIR;
    $dir = TPL_DIR . APP . DIRECTORY_SEPARATOR;
    if(file_exists($dir)){
      $tpldir[] = $dir;
    }
    $this->tpl->setPath('template', $tpldir);
    return true;
  }

  public function frame(){
    $this->tpl->assign('user', $this->user);
    $header = $this->header();
    $this->tpl->assign('header', $header);
    $footer = $this->footer();
    $this->tpl->assign('footer', $footer);
    return true;
  }

  public function head(){
    return $this->tpl->fetch('head.html');
  }

  public function header(){
    return $this->tpl->fetch('header.html');
  }

  public function footer(){
    return $this->tpl->fetch('footer.html');
  }

  public function pager($url, $count = 0, $page = 1){
    $this->tpl->assign('url', $url);
    $this->tpl->assign('count', $count);
    $this->tpl->assign('total', ceil($count / 10));
    $this->tpl->assign('page', $page);
    return $this->tpl->fetch('pager.html');
  }

  public function user(){
    return isset($_SESSION['user']) ? $_SESSION['user'] : array();
  }

  public function islogin(){
    return isset($_SESSION['user']);
  }
}