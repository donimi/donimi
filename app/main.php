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
    echo $rss->get_title();
    echo '<br />';
    foreach($rss->get_items() as $item){
      echo $item->get_permalink();
      echo '<br />';
      echo $item->get_title();
      echo '<br />';
      echo $item->get_content();
      exit;
    }
    */
    $this->tpl();
    $this->frame();
    $this->tpl->display('main.html');
  }
}