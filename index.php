<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

if (!defined('SAE_MYSQL_HOST_M')) {
    define('SAE_MYSQL_HOST_M', 'localhost');
}
if (!defined('SAE_MYSQL_PORT')) {
    define('SAE_MYSQL_PORT', '3306');
}
if (!defined('SAE_MYSQL_DB')) {
    define('SAE_MYSQL_DB', 'test');
}
if (!defined('SAE_MYSQL_USER')) {
    define('SAE_MYSQL_USER', 'root');
}
if (!defined('SAE_MYSQL_PASS')) {
    define('SAE_MYSQL_PASS', '');
}

ORM::configure('mysql:host='.SAE_MYSQL_HOST_M.';port='.SAE_MYSQL_PORT.';dbname='.SAE_MYSQL_DB);
ORM::configure('username', SAE_MYSQL_USER);
ORM::configure('password', SAE_MYSQL_PASS);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$options = array(
    'token'=>'youneverguessxiaochi', //填写你设定的key
);
$weObj = new Wechat($options);
$weObj->valid();
$type = $weObj->getRev()->getRevType();
switch($type) {
    case Wechat::MSGTYPE_TEXT:
        if (preg_match('/help/', $weObj->getRevContent())) {
            $weObj->text('you ask me for help, but I do not know any thing, I am just a newbi');
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