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
db_log('content: '.$content);
        $msg = ORM::for_table('weixin_msg')->create();
        $msg->content = $content;
        $msg->set_expr('created', 'NOW()');
        $msg->username = $username = $weObj->getRevFrom();
db_log('username: '.$username);
        $msg->save();
        exit;
        break;
    case Wechat::MSGTYPE_EVENT:
        $weObj->text("有什么话，你就说吧")->reply();
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
