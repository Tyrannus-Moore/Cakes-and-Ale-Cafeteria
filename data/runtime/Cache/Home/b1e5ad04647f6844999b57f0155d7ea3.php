<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>个人中心</title>
	<base href="/public/" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<script src="home/js/mui.min.js"></script>
</head>
<style type="text/css">
	.mui-content{background: #fff;}
	.headDiv{border-radius: 6px;margin-bottom: 30px;}
	.content{display: flex;flex-direction: row;justify-content: space-between;}
	.content .left{display: flex;flex-direction: row;align-items: center;}
	.content .left p{color: #fff;}
	.content .left p:first-child{margin-bottom: 15px;}
	.content .right{color: #fff;display: flex;align-items: center;}
	.right{display: flex;flex-direction: row;}
	.right img{width: 1rem;height: 1rem;margin-right: 0.3rem;}
	.mui-table-view a{display: flex !important;flex-direction: row;}
	.mui-table-view img{margin-right: 5px;}
	.mui-table-view-cell a{display: flex;align-items: center;}
	.mui-table-view:before, .mui-table-view-cell:after{background: transparent;}
	.mui-table-view-cell img{width: 1rem;height: 1rem;}
	.mui-table-view:after{background: transparent;}

	.left img{border-radius:50%;margin-right: 10px;}
	.send a{display: flex;justify-content:space-between;}
	.send div:first-child{display: flex;}
	.send div:last-child{margin-right: 25px;}
</style>
<body>
	<div class="mui-content p-10">
		<div class="headDiv redBackground p-15">
			<div class="content">
				<div class="left">
					<div><img src="<?php echo ($infos["member_list_headpic"]); ?>"></div>
					<div>
						<p class="font16"><?php echo ($infos["member_list_nickname"]); ?></p>
						<p class="font14"><?php echo ($infos["telphone"]); ?></p>
					</div>
				</div>
				<a href="<?php echo U('personal');?>">
				<p class="right">
					<img src="home/images/mine_bj.png" alt="" />
					<!--<span class="mui-icon mui-icon-compose"></span>-->
					<span>编辑</span>
				</p>
				</a>
			</div>
		</div>

		<ul class="mui-table-view">
			<li class="mui-table-view-cell">
				<a class="mui-navigate-right" href="<?php echo U('mycollection');?>">
					<img src="home/images/mine_wdsc.png" alt="" /> 我的收藏
				</a>
			</li>
			<li class="mui-table-view-cell">
				<a class="mui-navigate-right" href="<?php echo U('address_list');?>">
					<img src="home/images/mine_wddz.png" alt="" /> 我的地址
				</a>
			</li>
			<li class="mui-table-view-cell send">
				<?php if($infos['status'] == 2 AND $infos['state'] == 2): ?><a class="mui-navigate-right" href="<?php echo U('deliverys');?>">
						<div><img src="home/images/enter.png" alt="" /> 配送员入驻</div><div style="color:green;">已通过</div>
					</a>
				<?php elseif($infos['status'] == 1 AND $infos['state'] == 3): ?>
					<a class="mui-navigate-right" href="<?php echo U('delivery');?>">
						<div><img src="home/images/enter.png" alt="" /> 配送员入驻</div><div style="color:red;">已驳回</div>
					</a>
				<?php elseif($infos['status'] == 1 AND $infos['state'] == 1): ?>
					<a class="mui-navigate-right" href="<?php echo U('deliverys');?>">
						<div><img src="home/images/enter.png" alt="" /> 配送员入驻</div><div style="color:#999;">审核中</div>
					</a>
				<?php else: ?>
					<a class="mui-navigate-right" href="<?php echo U('delivery');?>">
						<div><img src="home/images/enter.png" alt="" /> 配送员入驻</div><div></div>
					</a><?php endif; ?>
			</li>
			<li class="mui-table-view-cell">
				<a class="mui-navigate-right" href="<?php echo U('help');?>">
					<img src="home/images/help.png" alt="" /> 帮助中心
				</a>
			</li>
		</ul>
	</div>
	<!--菜单-->
	<ul class="footer" id="nav">
		<li>
			<a href="<?php echo U('Drink/index');?>" id="order">
				<span class="mui-icon order"></span>
				<span class="mui-tab-label">点餐</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('Integral/shop');?>" id="shop">
				<span class="mui-icon shop"></span>
				<span class="mui-tab-label">积分商城</span>
			</a>
		</li>
		<?php if(($infos["status"]) == "2"): ?><li>
				<a href="<?php echo U('Clerk/task');?>" id="task">
					<span class="mui-icon task"></span>
					<span class="mui-tab-label">我的任务</span>
				</a>
			</li><?php endif; ?>
		<li>
			<a href="<?php echo U('Myorder/index');?>" id="myOrder">
				<span class="mui-icon myOrder"></span>
				<span class="mui-tab-label">我的订单</span>
			</a>
		</li>
		<li>
			<a href="<?php echo U('Personal/index');?>" id="mine" class="active">
				<span class="mui-icon mine active"></span>
				<span class="mui-tab-label">我的</span>
			</a>
		</li>
	</ul>
	

</body>
<script src="/public/home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>

</html>