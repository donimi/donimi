<?php
class cmd_core{
  public $id;
  public $cmd;
  public $param;
  public $cmdmodel;
  public function __construct($id){
    $this->id = $id;
    $this->cmdmodel = new cmd_model();
    $this->cmd = $this->cmdmodel->id($id);
    if(empty($this->cmd)){
      func_core::logerror();
    } else {
      $this->param = $this->param();
      $this->start();
    }
  }

  public function start(){
    $this->cmd['status'] = 'processing';
    return $this->cmdmodel->update($this->cmd);
  }

  public function end($result = true){
    if($this->cmd['next'] == 0){
      $this->cmd['status'] = $result == true ? 'finished' : 'failed';
    } else {
      $this->cmd['status'] = 'waiting';
      $this->cmd['start'] = time() + $this->cmd['next'];
    }
    return $this->cmdmodel->update($this->cmd);
  }

  public function param(){
    return $this->cmd['param'] == null ? array() : json_decode($this->cmd['param'], true);
  }
}