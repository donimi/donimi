<?php
class cmd_model extends model_core {
  public function __construct(){
    parent::__construct();
    $this->table('cmd');
  }

  public function getone(){
    $now = time();
    $sql = "SELECT * FROM `{$this->table}` WHERE `created` <= $now AND `status` = 'waiting'
      ORDER BY `created` LIMIT 1";
    $query = $this->db->query($sql);
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function getparam($app, $param, $status = null){
    $status = (int)$status;
    $bind = array();
    $sql = "SELECT * FROM `{$this->table}` WHERE `app`=:app
      AND `param`=:param";
    if($status != null){
      $sql .= " AND `status`=:status";
      $bind[':status'] = $status;
    }
    $sql .= " LIMIT 1";
    $sth = $this->db->prepare($sql);
    $bind[':app'] = $app;
    $bind[':param'] = $param;
    $sth->execute($bind);
    return $sth->fetch(PDO::FETCH_ASSOC);
  }
}