<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>确认订单</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css" />
		<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
		
	</head>
	<style type="text/css">
		.manage{height: 40px;display: flex;justify-content: center;flex-direction: row;background: #f7f7f7;}
		.manage p{width: 50%;display: flex;justify-content: center;align-items: center;color: #949494;font-weight: bold;}
		.manage .active{background: #fff;color: #333;}
		.distribution, .invite{background: #fff;padding: 10px;}
		.addressDiv{padding: 0 10px 10px 10px;}
		.addAddress{display: flex;justify-content: center;flex-direction: column;align-items: center;}
		.addAddress img{width:1.5rem;height: 1.8rem;}
		/*.distribution{display: none;}*/
		

		.jifen{height: 100px;}
		.jifen p{text-align: right;}
		.addressDetail{display: flex;justify-content: space-between;align-items: center;flex-direction: row;}
		.addressDetail span{width:21rem;margin-right: 15px;}
		.addressDetail img{width: 0.5rem;height: 0.8rem;}
    	.send{display: flex;justify-content: space-between;align-items: center;}
	</style>
	<body>
		<div class="mui-content">
			<div class="addressDiv">							
				<div class="manage">
				<input type="hidden" id="is_default" value="2">
					<p <?php if(!empty($address_id)): ?>class="active"<?php endif; ?> >
						捎带
					</p>
					<?php if(!empty($infos["ma_address"])): ?><p <?php if(empty($address_id)): ?>class="active"<?php endif; ?> >
							自取
						</p><?php endif; ?>									
				</div>
								
				<div class="distribution">
					<?php if(!empty($address["address"])): ?><a href="<?php echo U('Stall/address_list',array('goods_id'=>$goods_id,'nums'=>$nums));?>">
							<div class="address">
								<p><span><?php echo ($address["name"]); ?></span>&nbsp;&nbsp;<span><?php echo ($address["phone"]); ?></span></p>
								<p class="addressDetail"><span><?php echo ($address["address"]); ?></span><img src="images/more_right.png" alt=""></p>
							</div>
						</a>
					<?php else: ?>
					<a href="<?php echo U('Stall/address_list',array('goods_id'=>$goods_id,'nums'=>$nums));?>">
						<div class="addAddress">
							<img src="images/icon_site.png" alt="" />
							<p>添加配送地址</p>
						</div>
					</a><?php endif; ?>					
				</div>
			
				<div class="invite">
					<p>自取地址</p>
					<p class="c33"><?php echo ($infos["ma_address"]); ?></p>
					<p><span>预留电话</span>&nbsp;&nbsp;<span><?php echo ($infos["ma_tel"]); ?></span></p>
				</div>
				
				
				
			</div>
			
			<div class="p-10 whiteBackground">
				<p class="c33">商品信息</p>
				<div class="product">
					<div class="left">
						<img src="<?php echo ($goodsInfo["pic_url"]); ?>" alt="" />
					</div>
					<div class="right">
						<p class="c33"><?php echo ($goodsInfo["goods_name"]); ?></p>
						<p><span class="c33"><?php echo ($goodsInfo["intergral"]); ?>积分</span><span>×<?php echo ($nums); ?></span></p>
					</div>					
				</div>
			</div>
			<div class="p-10 mt-10 whiteBackground freight">
				<p class="send"><span class="c33">配送费</span><span class="c33">￥<?php echo ($freight); ?></span></p>
			</div>
			<div class="p-10 mt-10 whiteBackground">
				<p><span class="c33">备注</span><span class="c99">(选填)</span></p>
				<textarea name="remark" id="remark" rows="" cols=""></textarea>
			</div>
			
			<div class="p-10  mt-10 whiteBackground jifen">
				<p><span class="c33">积分合计: </span><span class="yellowColor"><?php echo ($total); ?></span></p>
			</div>
			
			
			
		</div>
		
		<div class="footer">
			<button class="redBackground bottomBtn">确认兑换</button>
		</div>
		<script src="js/jquery-2.2.1.min.js"></script>
		<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/mui.min.js"></script>
		<script>
			var address_id = "<?php echo ($address_id); ?>";
			if(address_id){
				//配送
				$('#is_default').val(1);
				$('.invite').css('display','none');
				$('.distribution').css('display','block');
				$('.freight').css('display','block');
			}else{
				var res = "<?php echo ($infos["ma_address"]); ?>";
				if(res){
					$('.invite').css('display','block');
					$('.distribution').css('display','none');
					$('.freight').css('display','none');
				}
			}
			
			$(".manage p").click(function(){
				$(this).addClass("active").siblings().removeClass("active");
				// alert($(this).index())
				if($(this).index() == 1){
					//配送
					$('#is_default').val(1);

					$(".distribution").show();
					$(".invite").hide();
					$('.freight').show();	
				}else{
					//自取
					$('#is_default').val(2);
					$(".distribution").hide();
					$(".invite").show();
					$('.freight').hide();				
				}
			})
			
			//确认兑换
			$('.bottomBtn').click(function(){
				//判断积分和库存是否充足
				var num = "<?php echo ($goodsInfo["num"]); ?>";
				var intergrals = "<?php echo ($infos["integral"]); ?>";
				var nums = "<?php echo ($nums); ?>";
				if(parseInt(nums)>parseInt(num)){
					//提示
					layer.open({
					    content: '库存不足'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
					});
					return false;
				}
				var total = "<?php echo ($total); ?>";
				// alert(intergrals)
				// alert(total)
				if(parseInt(total)>parseInt(intergrals)){
					//提示
					layer.open({
					    content: '积分不足'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
					});
					return false;
				}
				var goods_id = "<?php echo ($goodsInfo["goods_id"]); ?>";
				var is_default = $('#is_default').val();
				var remark = $('#remark').val();
				if(remark && remark.length>200){
					//提示
					layer.open({
					    content: '备注最多200字'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
					});
					return false;
				}
				if(is_default == 1){
					//配送
					var telphone = "<?php echo ($address["phone"]); ?>";
					var name = "<?php echo ($address["name"]); ?>";
					var address = "<?php echo ($address["address"]); ?>";
					//提示
					if(!telphone){
						layer.open({
						    content: '请添加收货地址'
						    ,skin: 'msg'
						    ,time: 2 //2秒后自动关闭
						});
						return false;
					}
					
					var freight = "<?php echo ($freight); ?>";
					$.post("<?php echo U('createOrder');?>",{goods_id:goods_id,telphone:telphone,address:address,nums:nums,remark:remark,name:name,freight:freight},function(data){
						if(data.status == 1){
							var order_id = data.order_id;
							window.location.href="<?php echo U('payment');?>&order_id="+order_id;
						}
					})
				}else{
					//自提
					var ma_address = "<?php echo ($infos["ma_address"]); ?>";
					var ma_tel = "<?php echo ($infos["ma_tel"]); ?>";
					layer.open({
						type: 2,
					  	shadeClose:false
					})
					$.post("<?php echo U('mention');?>",{goods_id:goods_id,ma_tel:ma_tel,ma_address:ma_address,nums:nums,remark:remark},function(data){
						if(data == 1){
						//提示
							layer.open({
							    content: '兑换成功'
							    ,skin: 'msg'
							    ,time: 2 //2秒后自动关闭
							    ,end:function(){
							    	window.location.href="<?php echo U('shop');?>";
							    }
							});
						}else{
							layer.open({
							    content: '兑换失败'
							    ,skin: 'msg'
							    ,time: 2 //2秒后自动关闭
							   
							});
						}
					})
				}
			})
		</script>
	</body>
</html>