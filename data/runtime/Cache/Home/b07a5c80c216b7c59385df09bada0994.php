<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>帮助中心</title>
		<base href="/public/" />
		<link rel="stylesheet" href="home/css/mui.min.css" />
		<script src="home/js/mui.min.js"></script>
		<script src="home/js/jquery-2.2.1.min.js"></script>
	</head>
	<style>
		.mui-content>.mui-table-view:first-child{margin-top: 0;}
	</style>
	<body>
		<div class="mui-content">
			<ul class="mui-table-view">
				 <li class="mui-table-view-cell">
				 	<a class="mui-navigate-right" href="<?php echo U('help_illustrate');?>">帮助说明</a>
				 </li>
		         <li class="mui-table-view-cell">
		         	<a class="mui-navigate-right" href="<?php echo U('feedback');?>">意见反馈</a>
		         </li>
		         <li class="mui-table-view-cell">
		         	<a class="mui-navigate-right" href="<?php echo U('problem_list');?>">常见问题</a>
		         </li>
			</ul>
		</div>
	</body>
</html>