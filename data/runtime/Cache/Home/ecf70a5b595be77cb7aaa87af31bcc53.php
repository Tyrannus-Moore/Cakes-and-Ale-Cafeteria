<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>推荐餐品</title>
	<base href="/public/" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<link rel="stylesheet" href="home/css/currency.css" />
	<script src="home/js/mui.min.js"></script>
	<script src="home/js/jquery-2.2.1.min.js"></script>
</head>
<style type="text/css">
	.filter {
		height: 50px;
		line-height: 50px;
		width: 100%;
		background: #fff;
	}

	.filter li {
		width: 30%;
		display: inline-block;
		text-align: center;
	}

	.filter .active {
		font-weight: bold;
	}

	.filter img {
		display: inline-block;
		width: 0.6rem;
		margin-left: 0.3rem;
		height: 0.4rem;
	}

	.meal .img {
		width: 80px;
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
	.evaluate{color: #8f8f94;flex-direction: row;}
	.evaluate{
		display: flex;
	}
	.count{color: #999;display: flex;align-items: center;font-size:12px;}
	.count img{width:14px;height:14px;}
	.evaluate div {
		margin-right: 7px;
	}

	.evaluate img {
		width: 1rem;
		height: 1rem;
	}

	.mask {
		top: 50px;
	}

	.priceDiv {
		display: flex;
		align-items: center;
	}

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
</style>

<body>
	<div class="mui-content">
		<input type="hidden" name="sort" id="sort" value="<?php echo ($sort); ?>">
		<div class="filterDiv">
			<ul class="filter">
				<li class="s6 c33 composite">综合排序<img src="home/images/sort_icon_down.png" /></li>
				<li class="s4">好评优先</li>
				<li class="s5">人气最高</li>
			</ul>
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
		</div>

		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="meal mt-15 p-15 whiteBackground dishesDetails" data-id="<?php echo ($v["dishes_id"]); ?>" data-type="<?php echo ($v["type"]); ?>">
				<p class="font18 c33"><?php echo ($v["stall_name"]); ?></p>
				<div class="mealContent mt-15">
					<div>
						<img class="img" src="<?php echo ($v["pic_url"]); ?>"/>
					</div>
					<div class="info">
						<p class="c33 font16" style="line-height: 13px;"><?php echo ($v["dishes_name"]); ?></p>
						
						<div class="evaluate font12" style="margin-top:8px;">
							
							<div>评分<span><?php echo ($v["score"]); ?></span></div>
							<div>月销<span><?php echo ($v["on_the_pin"]); ?></span></div>
						</div>
                                                               
						<?php if(($v["discount"]) != "10"): ?><div class="count" style=""><img src="home/images/icon_zhe.png" alt="" /> <span><?php echo ($v["discount"]); ?></span>折</div><?php endif; ?>
						<p class="c99 font12"><?php echo ($v["hot"]); ?>卡路里</p>
						<p class="priceDiv">
							<?php if($v['discount'] != 10 ): ?><span class="redColor font16">￥<?php echo sprintf("%.2f",$v['price']*$v['discount']/10) ?></span>&nbsp;&nbsp;
								<span class="countMoney font16">￥<?php echo ($v["price"]); ?></span>
							<?php else: ?>
								<span class="redColor font16">￥<?php echo ($v["price"]); ?></span>&nbsp;&nbsp;<?php endif; ?>
							
						</p>
					</div>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>


	<script type="text/javascript">
		document.querySelector('.mui-table-view.mui-table-view-radio').addEventListener('selected', function(e) {
			//当前选中的为 e.detail.el.innerText
			$(".mask").hide();
            var sort = $(".mui-selected").attr("data-id");
            $("#sort").val(sort);
            formsearch(sort);
           $(".dishesDetails").on('click');
		});
        function formsearch(sort) {
            // 创建Form
            var form = $('<form></form>');
            // 设置属性
            form.attr('action', '<?php echo U(recommend);?>');
            form.attr('method', 'post');

            // 创建Input
            var my_input2 = $('<input  name="sort" type="hidden" />');
            my_input2.attr('value',sort);
            // 附加到Form
            form.append(my_input2);
            $(document.body).append(form);
            // 提交表单
            form.submit();
            return false;
        }

		$(".filter li").click(function(e) {
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
                    $(".mask").show();
                }else{
                    $(".mask").hide();
                }
            }
            $(".dishesDetails").off('click');
		})
        $(function () {
            var sort = '<?php echo ($sort); ?>';
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
                    $('.composite').html('价格最低'+'<img src="home/images/sort_icon_down.png" />');
                    break;
                case '2':
                    $('.s6').addClass("active").siblings().removeClass("active");
                    $('.s2').addClass('mui-selected');
                    $('.composite').html('价格最高'+'<img src="home/images/sort_icon_down.png" />');
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