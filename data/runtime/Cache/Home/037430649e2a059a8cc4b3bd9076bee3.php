<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>我的任务</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/layer/default/layer.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/layout.css" />
		<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	</head>

	<body>
		<div class="orderCenterTopTab clearfix">
			<a href="<?php echo U('task');?>">
				<div class="fl psyTopTabBox">
					<span class="c-zhu">待抢单</span>
					<div class="orderCenterTopTabLine"></div>
				</div>
			</a>
			<a href="<?php echo U('waitOrder');?>">
				<div class="fl psyTopTabBox">
					<span class="">待取餐</span>
					<div class="orderCenterTopTabLine dis-n"></div>
				</div>
			</a>
			<a href="<?php echo U('deliveryOrder');?>">
				<div class="fl psyTopTabBox">
					<span class="">配送中</span>
					<div class="orderCenterTopTabLine dis-n"></div>
				</div>
			</a>
			<a href="<?php echo U('finishOrder');?>">
				<div class="fl psyTopTabBox">
					<span class="">已完成</span>
					<div class="orderCenterTopTabLine dis-n"></div>
				</div>
			</a>
		</div>

		<!--待抢单-->
		<div class="psyConBox mt-15 mb-50">
			<?php if(is_array($sheets)): $i = 0; $__LIST__ = $sheets;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="orderCenterListBox bac-fff mb-15" >
					<div class="orderCenterListTime p-10">
						<span>订单时间</span>
						<span><?php echo (date('Y-m-d H:i',$vo["payment_time"])); ?></span>
					</div>
					<div class="orderCenterListContent" order_id="<?php echo ($vo['order_id']); ?>">
						<div class="orderCenterProListBox clearfix">
							<img class="orderCenterProImg fl" src="<?php echo ($vo["image"]); ?>" />
							<span class="orderCenterProTitle fl"><?php echo ($vo["stall_name"]); ?></span>
						</div>
						<div class="c99 mb-5">
							<span>送至：</span>
							<span><?php echo ($vo["address"]); ?></span>
						</div>
					</div>
					<div class="orderCenterListBtnBox clearfix">
						<input class="fr orderCenterListBtn bcwcBtn" type="button" onclick="sheet(<?php echo ($vo["order_id"]); ?>)" value="抢单" />
					</div>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<ul class="footer" id="nav">
			<li>
				<a href="<?php echo U('Drink/index');?>" id="order">
					<span class="mui-icon order "></span>
					<span class="mui-tab-label">点餐</span>
				</a>
			</li>
			<li>
				<a href="<?php echo U('Integral/shop');?>" id="shop" >
					<span class="mui-icon shop"></span>
					<span class="mui-tab-label">积分商城</span>
				</a>
			</li>
			<?php if(($status) == "2"): ?><li>
					<a href="<?php echo U('Clerk/task');?>" id="task" class="active">
						<span class="mui-icon task active"></span>
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
				<a href="<?php echo U('Personal/index');?>" id="mine" >
					<span class="mui-icon mine"></span>
					<span class="mui-tab-label">我的</span>
				</a>
			</li>
		</ul>
		<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/mainfile.js" type="text/javascript" charset="utf-8"></script>
		<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			//抢单
			function sheet(order_id){
				var zzz = layer.open({
					type: 2,
				  	shadeClose:false
				})
				$.post("<?php echo U('sheet');?>",{order_id:order_id},function(da){
					if(da == 1){
					//提示
						layer.open({
						    content: '抢单成功'
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						    ,end:function(){
						    	window.location.reload()
						    }
						});
					}else if(da == 2){
						layer.open({
						    content: '抢单失败'
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						    ,end:function(){
						    	layer.close(zzz);
						    }
						   
						});
					}else if(da == 3){
						layer.open({
						    content: '订单已被抢'
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						    ,end:function(){
						    	layer.close(zzz);
						    }
						   
						});
					}else if(da == 4){
						layer.open({
						    content: '您已经变成了用户'
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						    ,end:function(){
						    	window.location.href="<?php echo U('Home/Personal/index');?>"
						    }
						   
						});
					}
				})
			}
			$('.orderCenterListContent').click(function(){
				var order_id = $(this).attr('order_id');
				window.location.href="<?php echo U('taskDetail');?>&order_id="+order_id;
			})
		</script>
	</body>

</html>