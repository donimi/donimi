<?php
class db_core{
  private static $_instance = null;
  private function __construct(){
    $dbconf = conf_core::get('db');
    self::$_instance = new PDO('mysql:host='.$dbconf['host'].';dbname='.$dbconf['name'],
    $dbconf['user'], $dbconf['pass']);
    self::$_instance->query('SET NAMES UTF8');
  }

  public function __clone(){
    exit;
  }

  public static function getinstance(){
    if(is_null(self::$_instance)){
      new db_core();
    }
    return self::$_instance;
  }
}