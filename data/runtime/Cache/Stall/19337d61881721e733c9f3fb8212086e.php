<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no，mininum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>时光餐厅</title>
	<base href="/public/" />
	<link rel="stylesheet" type="text/css" href="home/css/layer/default/layer.css"/>
	<link rel="stylesheet" type="text/css" href="home/css/common.css"/>
	<link rel="stylesheet" type="text/css" href="home/css/layout.css"/>
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
</head>
<body>
	<div class="loginTopTitle">餐厅档口登录</div>
	<form class="form-horizontal ajaxForm" name="form0" method="post" action="<?php echo U('login');?>"  enctype="multipart/form-data">
	<input class="fl loginInp" type="hidden" name="ma_id" value="<?php echo ($ma_id); ?>"/>
	<div class="loginBox">
		<div class="loginInpBox clearfix borf2">
			<div class="fl loginText">账号</div>
			<input class="fl loginInp" type="text" name="account" value="<?php echo ($cook[phone]); ?>" maxlength="20" placeholder="输入登录账号" required/>
		</div>
		<div class="loginInpBox clearfix">
			<div class="fl loginText">密码</div>
			<input class="fl loginInp" type="password" name="pwd" value="<?php echo ($cook[pwd]); ?>" maxlength="20" placeholder="输入密码" required/>
		</div>
	</div>
	<input class="defaultBtn loginBtn" type="submit" value="登录"  style="height:40px;"/>
	</form>
</body>
<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="others/jquery.form.js"></script>
<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="home/jscript/ajax.js" type="text/javascript"></script>
</html>