<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>商品详情</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css" />
		<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
		
	</head>
	<style>
		.mui-content{padding-bottom: 45px;}
		.img img{width: 100%;}
		.numDiv{display: flex;justify-content: space-between;align-items: center;}
		.detailDiv{overflow: hidden;}
		.detailDiv .text{width:100%;overflow: hidden;}
		.detailDiv .text img{max-width:100%;width:100%;}
		.footer{position: fixed;bottom: 0;height: 45px;width: 100%;display:flex;flex-direction: row;}
		.jifen{width:70%;text-align: right;}
		.duihuan{width:30%;}
		.duihuan button{width: 100%;height: 100%;color: #fff;}
		.inputNum {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
		}

		.inputNum {
			width: 8rem;
			border: 1px solid #ccc;
		}

		.inputNum button {
			height: 1.8rem;
			line-height: 0.8rem;
			border: none;
    		border-radius: 0;
		}

		.inputNum input {
			border: none !important;
			text-align: center;
			height: 1.8rem;
			margin-bottom: 0px !important;
			width:60px;
		}

	</style>
	<body>
		<div class="mui-content">
			<div class="img">
				<img src="<?php echo ($goodsInfo["pic_url"]); ?>" alt="" />
			</div>
			<div class="p-10 whiteBackground">
				<p><?php echo ($goodsInfo["goods_name"]); ?></p>
				<p class="yellowColor"><?php echo ($goodsInfo["intergral"]); ?>积分</p>
			</div>
			
			<div class="mt-10 p-10 whiteBackground">
				<div class="numDiv">
					兑换数量
					<div class="inputNum">
						<button onclick="jian()" style="border-right: 1px solid #ccc;">-</button>
						<input type="number" value="1"  id="number" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"/>
						<button onclick="jia()" style="border-left: 1px solid #ccc;">+</button>
					</div>	
					<!-- <div class="mui-numbox" data-numbox-min='1'>
						<button class="mui-btn mui-btn-numbox-minus jian" type="button">-</button>
						<input class="mui-input-numbox" type="number" id="number" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"/>
						<button class="mui-btn mui-btn-numbox-plus" type="button" onclick="jia()">+</button>
					</div> -->
				</div>
			</div>
			
			<div class="mt-10 p-10 detailDiv whiteBackground">
				<p>商品描述</p>
				<div class="text">
					<?php echo (htmlspecialchars_decode($goodsInfo["content"])); ?>
				</div>
			</div>
			
			<div class="footer whiteBackground">
				<div class="jifen p-5-10">
					<p class="content">已选择1件商品,需要积分</p>
					<p class="yellowColor total"><?php echo ($goodsInfo["intergral"]); ?></p>
				</div>
				<div class="duihuan">
					<button class="redBackground">兑换</button>
				</div>
			</div>
		</div>
	</body>
	<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/mui.min.js"></script>

	<script type="text/javascript">
		var intergral = "<?php echo ($goodsInfo["intergral"]); ?>";
		var num = "<?php echo ($goodsInfo["num"]); ?>";
		var intergrals = "<?php echo ($integral["integral"]); ?>";

		//加减
		function jian(){
			var number = parseInt($('#number').val());
			if(number!=1){
				$('#number').val(number-1);
				setTimeout(function(){
					amount()    
				},500);
			}
		}
	
		function jia(){
			var number = parseInt($('#number').val());
			$('#number').val(number+1);
			setTimeout(function(){
				amount()    
			},500);
		}
		$('#number').blur(function(){
			amount()
		})
		function amount(){
			var number = $('#number').val();
			if(number!=0){
				var total = intergral*parseInt(number);
				$('.content').text('已选择'+number+'件商品,需要积分')
				$('.total').text(total);
			}
			
		}
		// function amount(){
		// 	setTimeout(function(){
		// 	    var number = $('#number').val();
		// 		var total = intergral*number;
		// 		$('.content').text('已选择'+number+'件商品,需要积分')
		// 		$('.total').text(total);
		// 	},500);
			
		// }
		$('.redBackground').click(function(){
			var goods_id = "<?php echo ($goodsInfo["goods_id"]); ?>";
			var is_perfect = "<?php echo ($integral["is_perfect"]); ?>";
			if(is_perfect==2){
				//提示
				layer.open({
				    content: '请完善信息'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
				    ,end:function(){
				    	window.location.href="<?php echo U('Personal/personal_add');?>&href="+"/index.php/Home/Integral/detail/goods_id/"+goods_id;
				    }
				});
				return false;
			}
			var nums = $('#number').val();
			if(parseInt(nums)>parseInt(num)){
				//提示
				layer.open({
				    content: '库存不足'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
				});
				return false;
			}
			var total = $('.total').text();
			if(parseInt(total)>parseInt(intergrals)){
				//提示
				layer.open({
				    content: '积分不足'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
				});
				return false;
			}
			
			window.location.href="<?php echo U('confirmOrder');?>&goods_id="+goods_id+"&nums="+nums;
		})
	</script>
</html>