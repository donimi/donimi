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
      $rss = $this->fetch($feed['url']);
      if($feed['title'] == ''){
        $feed['title'] = $rss->get_title();
        $feedmodel->update($feed);
      }
      foreach($rss->get_items() as $item){
        $title = $item->get_title();
        $titlecode = sha1($title);
        $content = html_entity_decode($item->get_content());
        if($feed['is_full'] == 'no'){

        }
        $contentcode = sha1($content);
        if($this->has('title', $titlecode)){
          if($this->has('content', $contentcode)){
            break;
          }
        }
        $per = array();
        $per['fid'] = $id;
        $per['title'] = $title;
        $per['content'] = $content;
        $per['link'] = $item->get_permalink();
        $per['tm'] = $item->get_date('y-m-d h:i:s');
        $per['created'] = time();
        $iid = $this->set($per);
        if($iid > 0){
          $this->sethash('title', $titlecode, $iid);
          $this->sethash('content', $contentcode, $iid);
        }
      }
    }
  }

  private function fetch($url){
    $rss = new SimplePie();
    $rss->set_feed_url($url);
    $rss->enable_order_by_date(false);
    $rss->cache = false;
    $rss->init();
    return $rss;
  }

  private function has($type = 'title', $code){
    if($type != 'title' && $type != 'content'){
      return false;
    }
    $hashmodel = new hash_model();
    if($type == 'content'){
      $hashmodel->table('content_hash');
    }
    return $hashmodel->exist('code', $code);
  }

  private function set($item){
    $itemodel = new item_model();
    return $itemodel->set($item);
  }

  private function sethash($type = 'title', $code, $iid){
    if($type != 'title' && $type != 'content'){
      return false;
    }
    $hashmodel = new hash_model();
    if($type == 'content'){
      $hashmodel->table('content_hash');
    }
    $data = array();
    $data['code'] = $code;
    $data['iid'] = $iid;
    return $hashmodel->set($data);
  }
}