<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>意见反馈</title>
	<base href="/public/" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	<script src="home/js/mui.min.js"></script>
</head>
<style type="text/css">
	textarea {
		height: 150px;
		margin-bottom: 0;
	}

	.submitDiv button {
		margin-top: 40px;
		border-radius: 6px;
	}

	p {
		display: flex;
		align-items: center;
	}

	span {
		margin-right: 10px;
	}

	input {
		width: 80% !important;
		border: none !important;
		margin-bottom: 0 !important;
	}
</style>
<body>
	<div class="mui-content">
		<form class="form-horizontal feedbackForm" name="form0" method="post" action="<?php echo U('feedback');?>"  enctype="multipart/form-data">
			<textarea class="p-10" rows="" cols="" maxlength="500" name="content" placeholder="详细描述您的问题" required></textarea>
			<p class="p-10 mt-10 whiteBackground">
				<span>联系方式</span>
				<input type="tel" maxlength="11" name="telphone" id="telphone" value="<?php echo ($telphone); ?>" required>
			</p>
			<div class="p-10 submitDiv">
				<button class="redBackground bottomBtn">提交</button>
			</div>
		</form>
	</div>
	<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="others/jquery.form.js"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script src="home/jscript/ajax.js" type="text/javascript"></script>
</body>
</html>