<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';

require APP_PATH.'logic.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Fdd 测试</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
</head>
<body>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>楼层</th>
                <th>是否中奖</th>
                <th>抽奖时间</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (get_all() as $key => $value): ?>
            <tr>
                <td><?php echo $value->id ?></td>
                <td><?php echo $value->is_hongbao ? '是' : '否' ?></td>
                <td><?php echo $value->create_time ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>

