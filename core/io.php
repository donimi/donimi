<?php
class io_core{
  public function get($name = null, $default = null){
    if($name == null){
      $get = array();
      foreach($_GET as $k => $v){
        $get[$k] = trim($v);
      }
      return $get;
    } else {
      return isset($_GET[$name]) ? $_GET[$name] : $default;
    }
  }

  public function post($name = null, $default = null){
    if($name == null) {
      $post = array();
      if(isset($_POST)){
        foreach($_POST as $k => $v){
          $post[$k] = is_array($v) ? $v : trim($v);
        }
      }
      return $post;
    } else {
      if(isset($_POST[$name])){
        return is_array($_POST[$name]) ? $_POST[$name] : trim($_POST[$name]);
      } else {
        return $default;
      }
    }
  }

  public function param($name){
    $param = json_decode(PARAM, true);
    return isset($param[$name]) ? $param[$name] : null;
  }

  public function res($state = 0, $data = array()){
    $out = array();
    $out['state'] = $state;
    if(!empty($data)){
      $out['data'] = $data;
    }
    echo json_encode($out);
    exit;
  }
}