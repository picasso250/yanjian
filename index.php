<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';
require APP_PATH.'lib.php';
require APP_PATH.'logic.php';

require APP_PATH.'orm_config.php';

$options = array(
    'token'=>'youneverguessxiaochi', //填写你设定的key
);
$weObj = new Wechat($options);
$weObj->valid();
$rev = $weObj->getRev();
$type = $rev->getRevType();
db_log('tyep: '.$type);
switch($type) {
    case Wechat::MSGTYPE_TEXT:
        $content = $weObj->getRevContent();
        if (preg_match('/help/', $content)) {
            $weObj->text('you ask me for help, but I do not know any thing, I am just a newbi');
        } elseif (preg_match('/^q(.+)/', $content, $matches)) {
        } elseif ($content == '抢红包' || $content == '搶紅包') {
            $id = $rev->getRevFrom();
            // db_log(has_hongbao_today($id));
            // exit();
            if (has_hongbao_today($id)) {
                $weObj->text("你今天已经抢过红包了。")->reply();
                exit();
            }

            if (is_hongbao(3)) { // probability
                $id = save_hongbao($id, 1);
                $weObj->text("楼层：".$id."。很遗憾，没有抢中哦，明天再来吧。祝你马年大吉。")->reply();
            } else {
                $id = save_hongbao($id, 00);
                $weObj->text("楼层：".$id."。恭喜你，获得房多多1元红包一个！！！马年吉祥。请加微信: jeasun 凭此消息截图领取。")->reply();
            }
        } else {
            $weObj->text("hello, 房多多");
        }
        $weObj->reply();
        exit;
        break;
    case Wechat::MSGTYPE_EVENT:
        $weObj->text("抢红包活动正在进行中，请回复「抢红包」来试试运气吧。")->reply();
        exit();
        break;
    case Wechat::MSGTYPE_IMAGE:
        break;
    default:
        $weObj->text("help info")->reply();
}
exit;

function db_log($msg){
    $log = ORM::for_table('weixin_yanjian_log')->create();
    $log->content = $msg;
    $log->set_expr('created', 'NOW()');
    $log->save();
}
