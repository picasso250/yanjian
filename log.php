<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';

require APP_PATH.'Keyword.php';

$list = ORM::for_table('weixin_yanjian_log')->order_by_desc('id')->find_many();

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
    <title>言简 Log</title>
</head>
<body>
    <ul>
        <?php foreach ($list as $entry): ?>
            <li>
                <strong><?php echo $entry->created ?></strong>
                <p>
                    <pre><?php echo $entry->content ?></pre>
                </p>
            </li>
        <?php endforeach ?>
    </ul>
</body>
</html>

