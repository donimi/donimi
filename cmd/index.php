<?php
//system init
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
ini_set('memory_limit', '128M');
set_time_limit(0);

//is windows system?
//define('IS_WIN', !strncasecmp(PHP_OS, 'win', 3));
define('IS_WIN', DIRECTORY_SEPARATOR == '\\');

//define dir
define('CMD_DIR',    dirname(__FILE__)   . DIRECTORY_SEPARATOR);
define('ROOT_DIR',   substr(CMD_DIR, 0, -4));
define('API_DIR',    ROOT_DIR . 'api'    . DIRECTORY_SEPARATOR);
define('APP_DIR',    ROOT_DIR . 'app'    . DIRECTORY_SEPARATOR);
define('CONF_DIR',   ROOT_DIR . 'conf'   . DIRECTORY_SEPARATOR);
define('CORE_DIR',   ROOT_DIR . 'core'   . DIRECTORY_SEPARATOR);
define('LIB_DIR',    ROOT_DIR . 'lib'    . DIRECTORY_SEPARATOR);
define('LOG_DIR',    ROOT_DIR . 'log'    . DIRECTORY_SEPARATOR);
define('MODEL_DIR',  ROOT_DIR . 'model'  . DIRECTORY_SEPARATOR);
define('TEST_DIR',   ROOT_DIR . 'test'   . DIRECTORY_SEPARATOR);
define('TPL_DIR',    ROOT_DIR . 'tpl'    . DIRECTORY_SEPARATOR);
define('WWW_DIR',    ROOT_DIR . 'www'    . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', WWW_DIR  . 'upload' . DIRECTORY_SEPARATOR);

//autoload function
spl_autoload_register(function($classname){
  if($classname == 'idna_convert'){ //idna
    $filename = LIB_DIR . 'idna' . DIRECTORY_SEPARATOR . 'idna.php';
  } elseif($classname == 'uctc'){ //idna uctc
    $filename = LIB_DIR . 'idna' . DIRECTORY_SEPARATOR . 'uctc.php';
  } elseif(strpos($classname, 'SimplePie') === 0){ //simplepie
    $filename = LIB_DIR . 'simplepie' . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $classname) . '.php';
  } elseif($classname == 'Savant3'){ //savant3
    $filename = LIB_DIR . 'savant3' . DIRECTORY_SEPARATOR . 'Savant3.php';
  } else {
    $classarr = explode('_', $classname);
    $classtype = isset($classarr[1]) ? $classarr[1] : '';
    $filename = '';
    if($classtype != ''){
      $classdir = ROOT_DIR . $classtype . DIRECTORY_SEPARATOR;
      if(file_exists($classdir)){
        $filename = $classdir . $classarr[0] . '.php';
      } else {
        $filename = APP_DIR   . $classarr[0] . '.php';
      }
    }
  }
  if(class_exists($classname)){
    return true;
  } else {
    if(file_exists($filename)){
      require($filename);
      return true;
    } else {
      if(isset($classtype) && $classtype == 'app'){
        func_core::show404();
      } else {
        func_core::logerror();
      }
    }
  }
});

class index_cmd {
  public $num = 0;
  public function __construct(){
    $this->start();
  }

  public function index(){
    $cmdmodel = new cmd_model();
    $cmd = $cmdmodel->getone();
    if(!empty($cmd)){
      $app = $cmd['app'] . '_cmd';
      $app = new $app($cmd['id']);
      sleep(1);
      $this->index();
    } else {
      if($this->num < 6){
        $this->num++;
        sleep(10);
        $this->index();
      } else {
        exit;
      }
    }
  }

  public function start(){
    if(IS_WIN == true){
      $this->index();
    } else {
      $cmd = 'ps aux | grep /cmd/index.php$ | wc -l';
      $process = exec($cmd);
      if($process > 10){
        exit;
      } else {
        $this->index();
      }
    }
  }
}
$cmd = new index_cmd();