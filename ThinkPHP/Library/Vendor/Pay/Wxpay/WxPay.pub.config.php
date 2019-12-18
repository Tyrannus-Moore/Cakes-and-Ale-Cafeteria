<?php
/**
* 	配置账号信息
*/

class WxPayConf_pub
{
	/*session_start();*/
	//公司的账号
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
//	const APPID = 'wxb26fde05cc4c8cfd';
	/*const APPID = $_SESSION['appid'];*/
	//受理商ID，身份标识
//	const MCHID = '1463800002';
	/*const MCHID = $_SESSION['appid'];*/
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'mackj1978qicai2017GAOwPENGHgCfs8';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = '5c7e4aa0d0ca7a5c8198b74a4a2b874b';
	const JS_API_CALL_URL = 'http://ncds.zhengbang666.com/paytest/index' ;
	
	
	
	const SSLCERT_PATH = '{$path}/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = '{$path}/cacert/apiclient_key.pem';

	const NOTIFY_URL = 'http://ncds.zhengbang666.com/Wxpay.php';
	const CURL_TIMEOUT = 60;

  
    
}

	
?>