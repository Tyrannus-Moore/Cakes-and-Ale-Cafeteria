<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<base href="/public/" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<link rel="stylesheet" href="home/css/currency.css" />
	<script src="home/js/mui.min.js"></script>
	<script src="home/js/jquery-2.2.1.min.js"></script>
</head>
<style type="text/css">
	.mui-content {
		padding-bottom: 0;
	}

	.imgs {
		width: 100%;
		background: #fff;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}

	.imgs img {
		width: 10rem;
		height: 6rem;
	}

	.filter {
		height: 50px;
		line-height: 50px;
		width: 100%;
		background: #fff;
	}

	.filter li {
		width: 24%;
		display: inline-block;
		text-align: center;
	}

	.filter .active{font-weight: bold;}
	.filter .xianshi{font-weight: bold;}

	.filter img {
		display: inline-block;
		width: 0.6rem;
		margin-left: 0.3rem;
		height: 0.4rem;
	}

	.meal .img {
		width:80px;
		height: 80px;
		background: url(home/images/a.jpg) no-repeat center;
		background-size: cover;
		margin-right: 15px;
	}

	.mealContent {
		display: flex;
		flex-direction: row;
	}

	.info {
		flex: 1;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.evaluate{color: #8f8f94;}
	.evaluate{
		display: flex;
		align-items: center;
	}
	
	.count{color: #999;display: flex;align-items: center;font-size:12px;}
	.count img{width:14px;height:14px;}
	
	.evaluate div {
		margin-right: 6px;
	}

	.evaluate img {
		width: 1rem;
		height: 1rem;
	}
	.mask{top: 100px;}
	.priceDiv{display: flex;align-items: center;}
	
	.countMoney {
		text-decoration: line-through;
	}
	.mui-search {
		width: 83%;
	}
	.searchDiv .searchBtn {
		color: #333;
		width: 15%;
		background: transparent;
	}
		
	.mui-table-view-cell:after{background: transparent;}
</style> 

<body>
	<div class="mui-content">
		<input type="hidden" name="sort" id="sort" value="<?php echo ($sort); ?>">
		<input type="hidden" name="discount" id="discount" value="<?php echo ($discounts); ?>">
		<form method="post" action="<?php echo U('search');?>">
		<div class="searchDiv p-15">
			<div class="mui-input-row mui-search">
				<input type="search" name="search" value="<?php echo ($search); ?>" class="mui-input-clear" placeholder="">
			</div>
			<button type="submit" class="searchBtn">搜索</button>
		</div>
		</form>
		<?php if(empty($list) AND empty($kong)): ?><div class="imgs">
				<img src="home/images/icon_wjg.png" alt="" />
				<p class="greyColor">暂无结果</p>
			</div>
		<?php else: ?>
			<ul class="filter">
				<li class="s6 c33 composite paixu"><span>综合排序</span><img src="home/images/sort_icon_down.png" /></li>
				<li	class="discount" >限时折扣</li>
				<li class="s4 paixu">好评优先</li>
				<li class="s5 paixu">人气最高</li>
			</ul>
			<?php if(($kong) == "1"): ?><div class="imgs">
					<img src="home/images/icon_wjg.png" alt="" />
					<p class="greyColor">暂无结果</p>
				</div>
				<?php else: ?>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="meal mt-15 p-15 whiteBackground dishesDetails" data-id="<?php echo ($v["dishes_id"]); ?>" data-type="<?php echo ($v["type"]); ?>">
						<p class="font18 c33"><?php echo ($v["stall_name"]); ?></p>
						<div class="mealContent mt-15">
							<div>
								<img class="img" src="<?php echo ($v["pic_url"]); ?>"/>
							</div>
							<div class="info">
								<p class="c33 font16" style="line-height: 13px;"><?php echo ($v["dishes_name"]); ?></p>
								<div class="evaluate font12" style="margin-top:5px;">
									<div>评分<span><?php echo ($v["score"]); ?></span></div>
									<div>月销<span><?php echo ($v["on_the_pin"]); ?></span></div>
								</div>
								
								<div>
									<?php if(($v["statue"]) == "3"): if(($disc["discount"]) != "10"): ?><div class="count"><img src="home/images/icon_zhe.png" alt="" /> <span><?php echo ($disc["discount"]); ?></span>折 <?php echo ($v["start_time"]); ?>~<?php echo ($v["end_time"]); ?></div><?php endif; endif; ?>
									<?php if($v["statue"] == 2 && $v["discount"] != 10): ?><div class="count"><img src="home/images/icon_zhe.png" alt="" /> <span><?php echo ($v["discount"]); ?></span>折</div><?php endif; ?>
									
								</div>
								<p class="c99 font12"><?php echo ($v["hot"]); ?>卡路里</p>
								<p class="priceDiv">
									<?php if($v['statue'] == 3 AND $disc['discount'] != 10): ?><span class="redColor font16">￥<?php echo ($v["kill_price"]); ?></span>&nbsp;&nbsp;
										<span class="countMoney font16">￥<?php echo ($v["price"]); ?></span>
									<?php elseif($v['statue'] == 2 AND $v['discount'] != 10 ): ?>
										<span class="redColor font16">￥<?php echo ($v["kill_price"]); ?></span>&nbsp;&nbsp;
										<span class="countMoney font16">￥<?php echo ($v["price"]); ?></span>
									<?php else: ?>
										<span class="redColor font16">￥<?php echo ($v["price"]); ?></span>&nbsp;&nbsp;<?php endif; ?>
								</p>
							</div>
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; endif; endif; ?>
	</div>

	<div class="mask">
		<ul class="mui-table-view mui-table-view-radio">
			<li class="mui-table-view-cell s1" data-id="1">
				<a class="mui-navigate-right">
					综合排序
				</a>
			</li>
			<li class="mui-table-view-cell s2" data-id="2">
				<a class="mui-navigate-right" >
					价格最高
				</a>
			</li>
			<li class="mui-table-view-cell s3" data-id="3">
				<a class="mui-navigate-right" >
					价格最低
				</a>
			</li>
		</ul>
	</div>
	<script type="text/javascript">
		
		$(".imgs").height($(window).height() - $("form").height());
		//排序
        $(".paixu").click(function() {
            //$(this).addClass("active").siblings().removeClass("active");
			if($(this).hasClass("s4")){
                $("#sort").val(4);
                var discount = $("#discount").val();
                formsearch(4,discount);
			}else if($(this).hasClass("s5")){
                $("#sort").val(5);
                var discount = $("#discount").val();
                formsearch(5,discount);
			}else{
                if ($(this).hasClass("composite")) {
                	//调整排序项的顺序
                	var compositeLeft = $(".composite").find("span").offset().left;
                	$(".mask .mui-table-view-cell").css("padding","11px 15px 11px "+compositeLeft+"px");
                	$(".mask .mui-table-view-cell>a:not(.mui-btn)").css("margin","-11px 0 -11px -"+compositeLeft+"px");
                	
                    if($(".mask").is(':hidden'))
						$(".mask").show();
					else
						$(".mask").hide();
                }else{
                    $(".mask").hide();
                }
			}
            $(".dishesDetails").off('click');  //移除click事件
        })
        
		//综合
		document.querySelector('.mui-table-view.mui-table-view-radio').addEventListener('click',function(e){
		//当前选中的为 e.detail.el.innerText
			$(".mask").hide();
			
			var sort = $(".mui-selected").attr("data-id");
			$("#sort").val(sort);
			var discount = $("#discount").val();
			console.log(discount);
            formsearch(sort,discount);
            $(".dishesDetails").on('click');  //添加click事件
		});

        //限时折扣
        $(".discount").click(function(){
            var discount = $("#discount").val();
            if(discount){
                $(this).removeClass("xianshi");
                $("#discount").val('');
                var sort = $("#sort").val();
                formsearch(sort,'');
			}else{
                $(this).addClass("xianshi");
                $("#discount").val(1);
                var sort = $("#sort").val();
                formsearch(sort,1);
			}
        });
		function formsearch(sort,discount) {
            // 创建Form
            var form = $('<form></form>');
            // 设置属性
            form.attr('action','<?php echo U(search);?>');
            form.attr('method', 'post');

            // 创建Input
            var my_input = $('<input  name="search" type="hidden" />');
            my_input.attr('value','<?php echo ($search); ?>');
            var my_input2 = $('<input  name="sort" type="hidden" />');
            my_input2.attr('value',sort);
            var my_input3 = $('<input  name="discount" type="hidden" />');
            my_input3.attr('value',discount);
            // 附加到Form
            form.append(my_input);
            form.append(my_input2);
            form.append(my_input3);
            $(document.body).append(form);
            // 提交表单
            form.submit();
        }
		$(function () {
			var sort = '<?php echo ($sort); ?>';
            var discount = '<?php echo ($discounts); ?>';
            if(discount){
                $(".discount").addClass("xianshi");
            }
            switch (sort) {
				case '5':
                	$('.s5').addClass("active");
                    break;
				case '4':
                    $('.s4').addClass("active");
                    break;
				case '3':
                    $('.s6').addClass("active").siblings().removeClass("active");
                    $('.s3').addClass('mui-selected');
                    $('.composite').html('<span>价格最低</span>'+'<img src="home/images/sort_icon_down.png" />');
                    break;
				case '2':
                    $('.s6').addClass("active").siblings().removeClass("active");
                    $('.s2').addClass('mui-selected');
                    $('.composite').html('<span>价格最高</span>'+'<img src="home/images/sort_icon_down.png" />');
                    break;
                default:
                    $('.s6').addClass("active").siblings().removeClass("active");
                    $('.s1').addClass('mui-selected');
			}
        })
		//菜品详情
		$(".dishesDetails").click(function () {
			var dishes_id = $(this).attr("data-id");
            var dishes_type = $(this).attr("data-type");
            if(dishes_type == '1'){
                window.location.href = "<?php echo U('Stall/dishesDetail');?>&dishes_id="+dishes_id;
			}else{
                window.location.href = "<?php echo U('Package/dishesDetails');?>&dishes_id="+dishes_id;
			}
        })
	</script>
</body>
</html>