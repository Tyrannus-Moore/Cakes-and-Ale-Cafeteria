<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>常见问题</title>
		<base href="/public/" />
		<link rel="stylesheet" type="text/css" href="home/css/common.css" />
		<link rel="stylesheet" href="home/css/mui.min.css" />
		<script src="home/js/mui.min.js"></script>
		<script src="home/js/jquery-2.2.1.min.js"></script>
	</head>
	<body>
		<div class="mui-content">
			<ul class="mui-table-view">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
					 <a href="<?php echo U('problem_info',array('baise_id'=>$v['baise_id']));?>"><?php echo ($v["baise_name"]); ?></a>
				 </li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	</body>
</html>