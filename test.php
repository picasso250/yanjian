<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';

require APP_PATH.'Keyword.php';

$q = _get('q');
if ($q) {
    $kw = new KeywordModel();
    $list = $kw->search($q);
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
    <form>
        <input name="q" value="<?php echo _get('q') ?>">
        <input type="submit">
    </form>
    <?php if (isset($list) && $list): ?>
        <ul>
            <?php foreach ($list as $entry): ?>
                <li>
                    <p><?php echo $entry->description ?></p>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</body>
</html>

