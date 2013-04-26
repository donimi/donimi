<?php
class model_core{
  public $table;

  public function __construct(){}

  public function table($name){
    $this->table = $name;
    return true;
  }

  public function id($id, $col = '*'){
    $id = (int)$id;
    $bind = array();
    if(is_array($col)){
      $col = implode('`, `', $col);
      $col = '`' . $col . '`';
    }
    $bind[':col'] = $col;
    $sql = "SELECT :col FROM `{$this->table}` WHERE `id` = $id LIMIT 1";
    $sth = db_core::getinstance()->prepare($sql);
    $sth->execute($bind);
    return $sth->fetch(PDO::FETCH_ASSOC);
  }

  public function delete($id){
    $id = (int)$id;
    $sql = "DELETE FROM `{$this->table}` WHERE `id` = $id LIMIT 1";
    return db_core::getinstance()->query($sql);
  }

  public function put(array $data){
    if(!is_array($data) || empty($data)){
      $res = false;
    } else {
      $col = array();
      $val = array();
      $bind = array();
      foreach($data as $k => $v){
        $col[] = '`' . trim($k) . '`';
        $key = ':' . trim($k);
        $val[] = $key;
        $bind[$key] = trim($v);
      }
      $col = implode(',', $col);
      $val = implode(',', $val);
      $sql = "INSERT INTO `{$this->table}` ($col) VALUES ($val)";
      $sth = db_core::getinstance()->prepare($sql);
      $res = $sth->execute($bind);
    }
    return $res;
  }

  public function set(array $data){
    if(!is_array($data) || empty($data)){
      $res = false;
    } else {
      $col = array();
      $val = array();
      $bind = array();
      foreach($data as $k => $v){
        $col[] = '`' . trim($k) . '`';
        $key = ':' . trim($k);
        $val[] = $key;
        $bind[$key] = trim($v);
      }
      $col = implode(',', $col);
      $val = implode(',', $val);
      $sql = "INSERT INTO `{$this->table}` ($col) VALUES ($val)";
      $sth = db_core::getinstance()->prepare($sql);
      if($sth->execute($bind)){
        $res = (int)db_core::getinstance()->lastInsertId();
      } else {
        $res = false;
      }
    }
    return $res;
  }

  public function get($page = 0, $per =10, $col = '*', $where = '1', $order = ''){
    $bind = array();
    if(is_array($col)){
      $col = implode('`, `', $col);
      $col = '`' . $col . '`';
    }
    $bind[':col'] = $col;
    $sql = "SELECT :col FROM `{$this->table}` WHERE :where";
    $bind[':where'] = $where;
    if($order != ''){
      $sql .= " ORDER BY :order";
      $bind[':order'] = $order;
    }
    $page = (int)$page;
    $per = (int)$per;
    if($page > 0 && $per > 0){
      $first = ($page - 1) * $per;
      $sql .= " LIMIT $first, $per";
    }
    $sth = db_core::getinstance()->prepare($sql);
    if($sth->execute($bind)){
      $res = $per == 1 ? $sth->fetch(PDO::FETCH_ASSOC) : $sth->fetchAll(PDO::FETCH_ASSOC);
    } else {
      $res = false;
    }
    return $res;
  }

  public function exist($col, $val){
    $sql = "SELECT * FROM `{$this->table}` WHERE `$col` = :val LIMIT 1";
    $sth = db_core::getinstance()->prepare($sql);
    $sth->execute(array(':val' => $val));
    $res = $sth->fetch(PDO::FETCH_ASSOC);
    if(empty($res)){
      return false;
    } else {
      return true;
    }
  }

  public function update(array $data, $where = '1', $limit = 0){
    if(!is_array($data) || empty($data)){
      $res = false;
    } else {
      $col = array();
      $bind = array();
      if(isset($data['id'])){
        $id = (int)$data['id'];
        $where = " `id` = $id AND " . $where;
      }
      $bind[':where'] = $where;
      foreach($data as $k => $v){
        if($k != 'id'){
          $col[] = "`$k`=:$k";
          $k = ':'.$k;
          $bind[$k] = $v;
        }
      }
      $sql = "UPDATE `{$this->table}` SET " . implode(',', $col);
      $sql .= " WHERE :where";
      $limit = (int)$limit;
      if($limit > 0){
        $sql .= " LIMIT $limit";
      }
      $sth = db_core::getinstance()->prepare($sql);
      $res = $sth->execute($bind);
    }
    return $res;
  }

  public function rep(array $data){
    if(!is_array($data) || empty($data)){
      $res = false;
    } else {
      $col = array();
      $val = array();
      $bind = array();
      foreach($data as $k => $v){
        $col[] = '`' . trim($k) . '`';
        $key = ':' . trim($k);
        $val[] = $key;
        $bind[$key] = trim($v);
      }
      $col = implode(',', $col);
      $val = implode(',', $val);
      $sql = "REPLACE INTO `{$this->table}` ($col) VALUES ($val)";
      $sth = db_core::getinstance()->prepare($sql);
      $res = $sth->execute($bind);
    }
    return $res;
  }

  public function count($where = '1'){
    $bind = array();
    $sql = "SELECT COUNT(*) AS `cnt` FROM `{$this->table}` WHERE :where";
    $bind[':where'] = $where;
    $sth = db_core::getinstance()->prepare($sql);
    if($sth->execute($bind)){
      $res = (int)$sth->fetchColumn();
    } else {
      $res = false;
    }
    return $res;
  }
}