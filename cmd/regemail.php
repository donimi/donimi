<?php
class regmail_cmd extends cmd_core{
  public function __construct($id){
    parent::__construct($id);
    $this->index();
    $this->end();
  }

  public function index(){
    $id = $this->param['id'];
    $waitmodel = new wait_model();
    $wait = $waitmodel->id($id);
    if(isset($wait['email'])){
      $alt = $this->getalt($wait['pass']);
      $html = $this->gethtml($wait['pass']);
      $m = new PHPMailer();
      $m->CharSet = 'UTF-8';
      $m->IsSMTP();
      $m->SMTPAuth = true;
      $m->Host = 'smtp.exmail.qq.com';
      $m->Port = 25;
      $m->Username = 'admin@donimi.com';
      $m->Password = '800224z.';
      $m->SetFrom('admin@donimi.com', 'donimi');
      $m->AddReplyTo('admin@donimi.com', 'donimi');
      $m->Subject = 'donimi register email confirm';
      $m->AltBody = $alt;
      $m->MsgHTML($html);
      $m->AddAddress($wait['email'], '');
      if(!$m->Send()){
        echo 'Mailer Error:'. $m->ErrorInfo . "\n";
      } else {
        echo "Mail Sended!\n";
      }
    }
  }

  private function getalt($pass){
    $content = '';
    $content .= 'fav为您提供了一系列实用工具和个性阅读体验。' . "\n";
    $content .= '您使用本邮箱注册了donimi帐号，' . "\n";
    $content .= '请使用浏览器访问下面一行网址，确认您的注册信息。' . "\n";
    $content .= 'https://donimi.com/register/confirmail/?pass=' . $pass . "\n";
    $content .= '如您本人没有在fav上注册过帐号，请不要打开上面的网址，并忽略本邮件。' . "\n";
    $content .= '谢谢。'. "\n";
    return $content;
  }

  private function gethtml($pass){
    $html = '';
    $html .= '<div style="margin:auto;padding:15px;background-color:#08c;width:80%;min-width:80%;color:#fff;border:3px solid #333;">';
    $html .= '<p>donimi为您提供了最棒的RSS工具和个性化阅读体验。</p>';
    $html .= '<p>您使用本邮箱注册了fav帐号，</p>';
    $html .= '<p>请使用浏览器访问下面一行网址，确认您的注册信息。</p>';
    $html .= '<p><a href="https://donimi.com/register/confirmail/?pass='.$pass.'" target="_blank" style="color:#f0d230;">';
    $html .= 'https://donimi.com/register/confirmail/?pass='.$pass;
    $html .= '</a></p>';
    $html .= '<p>如您本人没有在donimi上注册过帐号，请不要打开上面的网址，并忽略本邮件。</p>';
    $html .= '<p>谢谢。</p>';
    $html .= '</div>';
    return $html;
  }
}