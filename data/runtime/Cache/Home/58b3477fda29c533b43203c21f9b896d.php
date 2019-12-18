<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>积分明细</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css" />
		<script src="js/jquery-2.2.1.min.js"></script>
		<script src="js/mui.min.js"></script>
	</head>
	<style>
		.manage{height: 40px;display: flex;justify-content: center;flex-direction: row;}
		.manage p{width: 50%;display: flex;justify-content: center;align-items: center;}
		.manage span.active{color: #333;}
		
		.comeIn, .out{margin-top: 0.1rem;}
		.item{display: flex;flex-direction: row;justify-content: space-between;width: 100%;}
		.left,{display: flex;flex-direction: column;}
		.right{display: flex;align-items: center;}
		.out{display: none;}
	</style>
	<body>
		<div class="mui-content">
			<div class="manage whiteBackground box-shadow">
				<p class="font20">
					<span class="active">收入</span>
				</p>
				<p class="font20">
					<span>支出</span>
				</p>
				
			</div>
			
			<div class="comeIn whiteBackground">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["type"]) == "1"): ?><div class="item p-10">
							<div class="left">
								<p class="c33">购买菜品</p>
								<p class="greyColor"><?php echo (date('Y-m-d H:i',$vo["creattime"])); ?></p>
							</div>
							<div class="right yellowColor">
								+<?php echo ($vo["integral"]); ?>
							</div>
						</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>				
			</div>
			
			<div class="out whiteBackground">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["type"]) == "2"): ?><div class="item p-10">
							<div class="left">
								<p class="c33">兑换商品</p>
								<p class="greyColor"><?php echo (date('Y-m-d H:i',$vo["creattime"])); ?></p>
							</div>
							<div class="right yellowColor">
								-<?php echo ($vo["integral"]); ?>
							</div>
						</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<script type="text/javascript">
			$(".manage span").click(function(){
				$(this).addClass("active").parent().siblings().find("span").removeClass("active");
				
				if($(this).parent().index() == 0){
					$(".comeIn").show();
					$(".out").hide();
				}else{
					$(".comeIn").hide();
					$(".out").show();				
				}
			})
		</script>
	</body>
</html>