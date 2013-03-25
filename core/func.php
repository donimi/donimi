<?php
class func_core{
  static public function show404(){
    $app = new app_core(false);
    $app->tpl();
    $app->tpl->display('404.html');
    exit;
  }

  static public function showerror(){
    $app = new app_core(false);
    $app->tpl();
    $app->tpl->display('error.html');
    exit;
  }

  static public function logerror(){
    exit;
  }

  static public function debug($var, $func = 'print_r'){
    echo '<pre>';
    $func($var);
    echo '</pre>';
    exit;
  }

  static public function redirect($url, $sec = 0){
    echo '<meta http-equiv="Refresh" content="'.$sec.'; url='.$url.'">';
    exit;
  }

  static public function ispost(){
    return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
  }

  static public function getip(){
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    if($ip == null) {
      $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
    }
    if($ip == null) {
      $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }
    return $ip;
  }

  static public function getpage(){
    return isset($_GET['p']) ? (int)$_GET['p'] : 1;
  }

  /**
   * get page url
   * method = 0 url may like http://xxx.com/a/b/p/1
   * method = 1 url may like http://xxx.com/a/b/?p=1
   * if $_GET['p'] already exists
   * the function defined method will be changed
   **/
  static public function getpageurl($method = 0){
    $url = explode('?', $_SERVER['REQUEST_URI']);
    $url = explode('/p/', $url[0]);
    $method = count($url == 2) ? 0 : $method;
    if($method === 0){
      $urlfix = isset($url[1]) ? $url[1] : '';
      if($urlfix === ''){
        $url = $url[0];
      } else {
        $urlfix = explode('/', $urlfix, 2);
        $url = $url[0];
        $url = count($urlfix) == 2 ? $url . '/' . $urlfix[1] : $url;
      }
      $url .= '/p/';
    } else {
      $url = explode('p=', $_SERVER['REQUEST_URI']);
      $url = $url[0];
      if(substr($url, -1) == '?' || substr($url, -1) == '&'){
        $url .= 'p=';
      } elseif(strpos($url, '?') > 0){
        $url .= '&p=';
      } else {
        if(substr($url, -1) != '/'){
          $url .= '/';
        }
        $url .= '?p=';
      }
    }
    return $url;
  }

  static public function getpass($len){
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for($i=0; $i<$len; $i++){
      $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
  }

  static public function valid($type, $val){
    $valided = true;
    $val = trim($val);
    switch($type){
      case 'null':
        $valided = $val == null ? true : false;
        break;
      case 'notnull':
        $valided = $val != null ? true : false;
        break;
      case 'isemail':
        $filter = '/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
        $valided = preg_match($filter, $val) ? true : false;
        break;
      case 'ispassword':
        $filter = '/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/';
        $valided = preg_match($filter, $val) ? true : false;
        break;
      default:
        $intype = (int)$type;
        if($intype == $type){
          $valided = mb_strlen($val, 'UTF-8') == $intype ? true : false;
        } else {
          $valided = $val == $type ? true : false;
        }
        break;
    }
    return $valided;
  }

  static public function getsys($name){
    $sysconf = conf_core::get('sys');
    return isset($sysconf[$name]) ? $sysconf[$name] : '';
  }
}