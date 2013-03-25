<?php
class conf_core{
  static public function get($name){
    $filename = CONF_DIR . $name . '.json';
    if(file_exists($filename)){
      $conf = file_get_contents($filename);
      return json_decode($conf, true);
    } else {
      return false;
    }
  }

  static public function set($name, $val = array()){
    $filename = CONF_DIR . $name . '.json';
    if(file_exists($filename)){
      $conf = file_get_contents($filename);
      $conf = json_decode($conf, true);
    } else {
      $conf = array();
    }
    foreach($val as $k => $v){
      $conf[$k] = $v;
    }
    $conf = json_encode($conf);
    return file_put_contents($filename, $conf);
  }
}