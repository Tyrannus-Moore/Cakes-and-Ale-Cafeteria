<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>兑换记录</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css" />
		<link rel="stylesheet" href="css/common.css" />
		<script src="js/jquery-2.2.1.min.js"></script>
		<script src="js/mui.min.js"></script>
	</head>
	<style type="text/css">
		.for-b-grey{display: flex;justify-content: space-between;height: 40px;line-height: 40px;}
		.product{margin-top: 1rem;}
		
		.imgs{display: flex;justify-content: center;flex-direction: column;align-items: center;background: #fff;}
		.imgs img{width:10rem;height: 6rem;}
	</style>
	<body>
		<div class="mui-content">	
			<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('orderDetail',array('order_id'=>$vo['order_id']));?>">
						<div class="mb-15 p-15 whiteBackground">
							<p class="for-b-grey font15 c33"><span>订单编号<span><?php echo ($vo["ordersn"]); ?></span></span> <span><?php switch($vo["status"]): case "1": ?>待发货<?php break; case "2": ?>已发货<?php break; case "3": ?>已完成<?php break; case "4": ?>待取货<?php break; endswitch;?></span></p>
							<div class="product">
								<div class="left">
									<img src="<?php echo ($vo["pic_url"]); ?>" alt="" />
								</div>
								<div class="right">
									<p class="c33 font15 c33"><?php echo ($vo["goods_name"]); ?></p>
									<p><span class="yellowColor"><?php echo ($vo['use_integral']/$vo['pay_num']); ?>积分</span><span>×<?php echo ($vo["pay_num"]); ?></span></p>
								</div>					
							</div>
						</div>
					</a><?php endforeach; endif; else: echo "" ;endif; ?>	
			<?php else: ?>
				<div class="imgs">
		            <img src="/public/home/images/icon_wjg.png" alt="" />
		            <p style="text-align:center">暂无订单</p>
	            </div><?php endif; ?>
									
		</div>
		<script>
			$(".imgs").css("height",$(window).height());
		</script>
		
	</body>	
</html>