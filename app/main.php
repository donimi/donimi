<?php
class main_app extends app_core{
  public function __construct(){
    parent::__construct(false);
  }

  public function index(){
    /*
    $url = 'http://www.ruanyifeng.com/blog/atom.xml';
    $rss = new SimplePie();
    $rss->set_feed_url($url);
    $rss->enable_order_by_date(false);
    $rss->cache = false;
    //$rss->set_cache_location(CACHE_DIR);
    $rss->init();
    $sitetitle = $rss->get_title();
    $items = array();
    foreach($rss->get_items() as $v){
      $item = array();
      $item['link'] = $v->get_permalink();
      $item['title'] = $v->get_title();
      $item['content'] = $v->get_content();
      $item['date'] = $v->get_date('y-m-d h:i:s');
      $items[] = $item;
    }
    */
    $sitetitle = 'site title';
    $items = array();
    for($i=1;$i<100;$i++){
      $item = array();
      $item['link'] = $i . '-link';
      $item['title'] = $i . '-title';
      $item['content'] = $i . '-content';
      $item['date'] = date('Y-m-d H:i:s', time());
      $items[] = $item;
    }
    //print_r($items);exit;
    $this->tpl();
    $this->frame();
    $this->tpl->assign('sitetitle', $sitetitle);
    $this->tpl->assign('items', $items);
    $this->tpl->display('main.html');
  }
}