<?php

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
