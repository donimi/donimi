<?php
class spider_cmd extends cmd_core{
  public function __construct($id){
    parent::__construct($id);
    $result = $this->index();
    $this->end($result);
  }

  public function index(){
    $id = $this->param['id'];
    $feedmodel = new feed_model();
    $feed = $feedmodel->id($id);
    if(isset($feed['url'])){
      $rss = new SimplePie();
      $rss->set_feed_url($feed['url']);
      $rss->enable_order_by_date(false);
      $rss->cache = false;
      $rss->init();
      if($feed['title'] == ''){
        $feed['title'] = $rss->get_title();
        $feedmodel->update($feed);
      }
      $itemodel = new item_model();
      foreach($rss->get_items() as $v){
        $item = array();
        $item['fid'] = $id;
        $item['title'] = $v->get_title();
        //$item['content'] = $v->get_content();
        $item['content'] = html_entity_decode($v->get_content());
        if($item['is_full'] == 'no'){
          //fetch content
        }
        $item['link'] = $v->get_permalink();
        $item['tm'] = $v->get_date('y-m-d h:i:s');
        $item['created'] = time();
        $itemodel->set($item);
      }
    }
  }
}