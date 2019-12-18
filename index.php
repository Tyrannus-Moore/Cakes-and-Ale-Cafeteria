<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
if (file_exists('./data/conf/debug.lock') || !file_exists('./data/install.lock')) {
    define ('APP_DEBUG', false);
    define ('DB_DEBUG', false);
} else {
    define ('APP_DEBUG', true);
    define ('DB_DEBUG', false);
}
defined('APP_DEBUG') or define('APP_DEBUG', true); // 是否调试模式
define('APP_PATH','./app/');
//定义目录
define("ROOT_PATH",str_replace("\\", '/', dirname(__FILE__)));
define("RUNTIME_PATH", "./data/runtime/");
require './ThinkPHP/ThinkPHP.php';
