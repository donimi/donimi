<?php
//system init
error_reporting(E_ALL);

//defined dir
define('WWW_DIR',    dirname(__FILE__)   . DIRECTORY_SEPARATOR);
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
  $classlower = strtolower($classname);
  if($classname == ucfirst($classname)){ //lib
    $filename = LIB_DIR . $classlower . DIRECTORY_SEPARATOR . $classname . '.php';
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
        func_core::showerror();
      }
    }
  }
});

//parse url
$uri = $_SERVER['REQUEST_URI'];
$uriarr = explode('?', $uri);
$uriarr = array_filter(explode('/', $uriarr[0]));
if(!isset($uriarr[1])){
  $folder = 'app';
  $app = 'main';
  $appclass = 'main_app';
  $act = 'index';
  $getnum = 0;
} else {
  $dirname = ROOT_DIR . $uriarr[1] . DIRECTORY_SEPARATOR;
  if(file_exists($dirname)){
    $folder = $uriarr[1];
    $app = isset($uriarr[2]) ? $uriarr[2] : 'main';
    $appclass = $app . '_' . $folder;
    $act = isset($uriarr[3]) && strlen($uriarr[3]) > 1 ? $uriarr[3] : 'index';
    $getnum = isset($uriarr[3]) && strlen($uriarr[3]) > 1 ? 4 : 3;
  } else {
    $folder = 'app';
    $app = $uriarr[1];
    $appclass = $app . '_app';
    $act = isset($uriarr[2]) && strlen($uriarr[2]) > 1 ? $uriarr[2] : 'index';
    $getnum = isset($uriarr[2]) && strlen($uriarr[2]) > 1 ? 3 : 2;
  }
}

//defined folder app and act
define('FOLDER', $folder);
define('APP', $app);
define('ACT', $act);

//set get and param
$get = array();
$param = array();
if($getnum > 0){
  for(;;$getnum++){
    if(isset($uriarr[$getnum])){
      $param[] = $uriarr[$getnum];
    } else {
      break;
    }
  }
  for($i=0;;$i+=2){
    if(isset($param[$i])){
      $k = $param[$i];
      $get[$k] = '';
    } else {
      break;
    }
    if(isset($param[$i+1])){
      $get[$k] = $param[$i+1];
    }
  }
}
$_GET = array_merge($get, $_GET);
define('PARAM', json_encode($param));

//start app and act
$app = new $appclass();
$app->$act();