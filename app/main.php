<?php
class main_app extends app_core{
  public function __construct(){
    parent::__construct();
  }

  public function index(){
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
    $this->tpl();
    $this->frame();
    $this->tpl->assign('sitetitle', $sitetitle);
    $this->tpl->assign('items', $items);
    $this->tpl->display('main.html');
  }
}