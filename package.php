<?php
$xmldata = file_get_contents("php://input");

$_GET['m'] = 'Home';
$_GET['c'] = 'Notify';
$_GET['a'] = 'Notify';
// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./App/');
//定义目录
define("ROOT_PATH",str_replace("\\", '/', dirname(__FILE__)));

// 引入入口文件
require './ThinkPHP/ThinkPHP.php';
?>
