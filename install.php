<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APP_PATH', __DIR__.'/');

require APP_PATH.'vender/idiorm.php';
require APP_PATH.'vender/wechat/wechat.class.php';

require APP_PATH.'orm_config.php';

$db = ORM::get_db();
$db->query("
CREATE TABLE `keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT '关键字',
  `created` timestamp NULL DEFAULT NULL COMMENT '第一次搜索的时间',
  `hit` int(10) unsigned DEFAULT NULL COMMENT '搜索次数',
  PRIMARY KEY (`id`),
  KEY `index_name` (`name`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='关键字表'
");

$db->query("
CREATE TABLE `description` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword_id` int(10) unsigned DEFAULT NULL COMMENT '关键字id',
  `description` varchar(255) DEFAULT NULL COMMENT '描述 支持markdown格式',
  `updated` timestamp NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`),
  KEY `index_keyword` (`keyword_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='对关键字的描述'
");

die('ok');