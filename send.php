<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';
require APP_PATH.'vender/wechat/wechatext.class.php';

$options = array(
    'account' => 'cumt.xiaochi@gmail.com',
    'password' => 'x4stcweixin',
    'datapath' => 'saestor://xc222/cookie.txt',
);
$we = new Wechatext($options);
$msg = $we->getMsg();
if ($msg) {
    $id = $msg[0]['fakeId'];
    $we->send($id, 'hello, we push');
}

$thead = array_keys(reset($msg));
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>主动发送消息</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <?php foreach ($thead as $t): ?>
                    <th><?php echo $t ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($msg as $m): ?>
                <tr>
                    <?php foreach ($m as $v): ?>
                        <td><?php echo $v ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>

