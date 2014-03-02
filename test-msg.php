<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';


$content = _post('content');
if ($content) {
    # code...
        $msg = ORM::for_table('weixin_msg')->create();
        $msg->content = $content;
        $msg->set_expr('created', 'NOW()');
        $msg->username = _post('username');
        $msg->save();
}

function _get($key = null)
{
    if ($key === null) {
        return $_GET;
    }
    return isset($_GET[$key]) ? trim($_GET[$key]) : null;
}
function _post($key = null)
{
    if ($key === null) {
        return $_POST;
    }
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>言简 测试</title>
</head>
<body>
    <form method="post">
        <input name="content" value="">
        <input name="username" value="">
        <input type="submit">
    </form>
</body>
</html>

