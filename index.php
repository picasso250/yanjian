<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';
require APP_PATH.'Keyword.php';

require APP_PATH.'orm_config.php';

$options = array(
    'token'=>'youneverguessxiaochi', //填写你设定的key
);
$weObj = new Wechat($options);
$weObj->valid();
$type = $weObj->getRev()->getRevType();
switch($type) {
    case Wechat::MSGTYPE_TEXT:
        $content = $weObj->getRevContent();
        if (preg_match('/help/', $content)) {
            $weObj->text('you ask me for help, but I do not know any thing, I am just a newbi');
        } elseif (preg_match('/^q(.+)/', $content, $matches)) {
            $q = $matches[1];

            $kw = new KeywordModel();
            $list = $kw->search($q);
            $text = implode("\n", array_map(function ($e) {
                return $e->description;
            }, $list));
            $weObj->text($text);
        } else {
            $weObj->text("hello, 房多多");
        }
        $weObj->reply();
        exit;
        break;
    case Wechat::MSGTYPE_EVENT:
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