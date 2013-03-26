<?php
class model_core{
  public $db;
  public $table;

  public function __construct(){
    $dbconf = conf_core::get('db');
    $this->db = new PDO('mysql:host='.$dbconf['host'].';dbname='.$dbconf['name'],
      $dbconf['user'], $dbconf['pass']);
    $this->db->query('SET NAMES UTF8');
  }

  public function table($name){
    $this->table = $name;
    return true;
  }

  public function id($id, $col = '*'){
    $id = (int)$id;
    if(is_array($col)){
      $col = implode('`, `', $col);
      $col = '`'.$col.'`';
    }
    $sql = "SELECT $col FROM `{$this->table}` WHERE `id` = $id LIMIT 1";
    $query = $this->db->query($sql);
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function delete($id){
    $id = (int)$id;
    $sql = "DELETE FROM `{$this->table}` WHERE `id` = $id LIMIT 1";
    return $this->db->query($sql);
  }

  public function get($col, $val, $page = 0, $per = 10, $order = ''){
    $sql = "SELECT * FROM {$this->table} WHERE `$col` = :val";
    if($order != ''){
      $sql .= " ORDER BY $order";
    }
    $page = (int)$page;
    $per = (int)$per;
    if($page > 0 && $per > 0){
      $first = ($page - 1) * $per;
      $sql .= " LIMIT $first, $per";
    }
    $sth = $this->db->prepare($sql);
    $sth->execute(array(':val' => $val));
    return $per == 1 ? $sth->fetch(PDO::FETCH_ASSOC) : $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function set($data){
    if(!is_array($data) || empty($data)){
      $res = 0;
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
      $sth = $this->db->prepare($sql);
      if($sth->execute($bind)){
        $id = $this->db->lastInsertId();
        $res = $id > 0 ? (int)$id : 0;
      } else {
        $res = 0;
      }
    }
    return $res;
  }

  public function fetch($page = 0, $per =10, $col = '*', $where = '1', $order = ''){
    if(is_array($col)){
      $col = implode('`, `', $col);
      $col = '`' . $col . '`';
    }
    $sql = "SELECT $col FROM `{$this->table}` WHERE $where";
    if($order != ''){
      $sql .= "  ORDER BY $order";
    }
    $page = (int)$page;
    $per = (int)$per;
    if($page > 0 && $per > 0){
      $first = ($page - 1) * $per;
      $sql .= " LIMIT $first, $per";
    }
    $query = $this->db->query($sql);
    return $per == 1 ? $query->fetch(PDO::FETCH_ASSOC) : $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function exist($col, $val){
    $sql = "SELECT * FROM `{$this->table}` WHERE `$col` = :val LIMIT 1";
    $sth = $this->db->prepare($sql);
    $sth->execute(array(':val' => $val));
    $res = $sth->fetch(PDO::FETCH_ASSOC);
    $res = isset($res['id']) ? $res['id'] : 0;
    return $res;
  }

  public function check($col, $val, $id){
    $id = (int)$id;
    $sql = "SELECT * FROM `{$this->table}` WHERE `$col` = :val AND `id` <> $id LIMIT 1";
    $sth = $this->db->prepare($sql);
    $sth->execute(array(':val' => $val));
    $res = $sth->fetch(PDO::FETCH_ASSOC);
    return isset($res['id']) ? $res['id'] : 0;
  }

  public function update(array $data){
    if(isset($data['id'])){
      $col = array();
      $bind = array();
      foreach($data as $k => $v){
        if($k != 'id'){
          $col[] = "`$k`=:$k";
          $k = ':'.$k;
          $bind[$k] = $v;
        }
      }
      $sql = "UPDATE `{$this->table}` SET " . implode(',', $col);
      $sql .= " WHERE `id` = {$data['id']} LIMIT 1";
      $sth = $this->db->prepare($sql);
      return $sth->execute($bind);
    } else {
      return false;
    }
  }

  public function count($where = '1'){
    $sql = "SELECT COUNT(*) AS `cnt` FROM `{$this->table}` WHERE $where";
    $query = $this->db->query($sql);
    return (int)$query->fetchColumn();
  }

  public function rep($data, $returnid = true){
    if(!is_array($data) || empty($data)){
      $res = 0;
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
      $sth = $this->db->prepare($sql);
      if($sth->execute($bind)){
        if($returnid){
          $id = $this->db->lastInsertId();
          $res = $id > 0 ? (int)$id : 0;
        } else {
          $res = 1;
        }
      } else {
        $res = 0;
      }
    }
    return $res;
  }
}