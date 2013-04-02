<?php
//system init
error_reporting(E_ALL);

//defined dir
define('WWW_DIR',    dirname(__FILE__)   . DIRECTORY_SEPARATOR);
//define('UPLOAD_DIR', WWW_DIR  . 'upload' . DIRECTORY_SEPARATOR);
define('ROOT_DIR',   substr(WWW_DIR, 0, -4));
define('API_DIR',    ROOT_DIR . 'api'    . DIRECTORY_SEPARATOR);
define('APP_DIR',    ROOT_DIR . 'app'    . DIRECTORY_SEPARATOR);
define('CMD_DIR',    ROOT_DIR . 'cmd'    . DIRECTORY_SEPARATOR);
define('CONF_DIR',   ROOT_DIR . 'conf'   . DIRECTORY_SEPARATOR);
define('CORE_DIR',   ROOT_DIR . 'core'   . DIRECTORY_SEPARATOR);
define('LIB_DIR',    ROOT_DIR . 'lib'    . DIRECTORY_SEPARATOR);
define('LOG_DIR',    ROOT_DIR . 'log'    . DIRECTORY_SEPARATOR);
define('MODEL_DIR',  ROOT_DIR . 'model'  . DIRECTORY_SEPARATOR);
define('TEST_DIR',   ROOT_DIR . 'test'   . DIRECTORY_SEPARATOR);
define('TPL_DIR',    ROOT_DIR . 'tpl'    . DIRECTORY_SEPARATOR);

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
    if(count($classarr) > 1){
      $classtype = $classarr[count($classarr) - 1];
      unset($classarr[count($classarr) - 1]);
      $filename = '';
      $classdir = ROOT_DIR . $classtype . DIRECTORY_SEPARATOR;
      if(file_exists($classdir)){
        $filename = $classdir . implode('_', $classarr) . '.php';
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
        func_core::showerror();
      }
    }
  }
});

//parse url
$uri = $_SERVER['REQUEST_URI'];
$uriarr = explode('?', $uri);
$uriarr = array_filter(explode('/', $uriarr[0]));
if(isset($uriarr[1])){
  if($uriarr[1] == 'test'){
    $app = isset($uriarr[2]) ? $uriarr[2] : 'main';
    $appclass = $app . '_test';
    $i = 3;
  } else {
    $app = $uriarr[1];
    $appclass = $app . '_app';
    $i = 2;
  }
  $get = array();
  for($i;;$i+=2){
    if(isset($uriarr[$i])){
      $j = $i + 1;
      $per[$uriarr[$i]] = isset($uriarr[$j]) ? $uriarr[$j] : '';
      $get = array_merge($get, $per);
    } else {
      break;
    }
  }
  $_GET = array_merge($get, $_GET);
} else {
  $app = 'main';
  $appclass = 'main_app';
}
define('APP', $app);
//start app
$app = new $appclass();
$app->index();