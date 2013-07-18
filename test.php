<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';

$q = _get('q');
if ($q) {
    $list = search($q);
}

function search($q)
{
    $list = get_list_from_db($q);
    if ($list) {
        return $list;
    }
    $response = get_json_response_from_bing($q);
    $obj = json_decode($response);
    $list = $obj->d->results;
    return $list;
}
function get_json_response_from_bing($q)
{
    $acctKey = 'tTWCKOHsb0tbGkFjaIvsr1Bba15057Hj2hPloJgu0p4';
    $rootUri = 'https://api.datamarket.azure.com/Bing/Search';
    $query = urlencode("'{$q}'");
    $serviceOp = 'Web';
    $requestUri = "$rootUri/$serviceOp?\$format=json&Query=$query";

    $auth = base64_encode("$acctKey:$acctKey");
    $data = array(
        'http' => array(
        'request_fulluri' => true,
        // ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
        'ignore_errors' => true,
        'header' => "Authorization: Basic $auth")
    );

    $context = stream_context_create($data);

    // Get the response from Bing.
    $response = file_get_contents($requestUri, 0, $context);
    return $response;
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
    <title>主动发送消息</title>
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
                    <h4><?php echo $entry->Title ?></h4>
                    <p><?php echo $entry->Description ?></p>
                    <a href="<?php echo $entry->Url ?>"><?php echo $entry->DisplayUrl ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</body>
</html>
