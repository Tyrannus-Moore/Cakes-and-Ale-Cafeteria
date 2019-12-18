<?php
$xmldata = file_get_contents("php://input");

$filename = date("Y-m-d").'notify.txt';
file_put_contents($filename, "支付时间：".date("Y-m-d H:i:s").PHP_EOL,FILE_APPEND);

file_put_contents($filename, $xmldata.PHP_EOL,FILE_APPEND);
//file_put_contents($filename, var_export($notify->data["out_trade_no"],TRUE),FILE_APPEND);
// if(empty($xmldata))
// {
// 	// file_put_contents($filename, '没有数据         '.PHP_EOL,FILE_APPEND);
//     exit();
// }
// echo "SUCCESS";
// exit;
$_GET['m'] = 'Small';
$_GET['c'] = 'Wechat';
$_GET['a'] = 'notify_url';
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