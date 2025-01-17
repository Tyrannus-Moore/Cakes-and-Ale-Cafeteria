<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta content="yes" name="apple-mobile-web-app-capable"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>积分商城</title>
		<base href="/public/home/" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css" />
		<script src="js/mui.min.js"></script>
		<link rel="stylesheet" href="css/currency.css" />
		<script src="js/jquery-2.2.1.min.js"></script>
		<link rel="stylesheet" href="css/swiper/swiper.css">
		<script src="js/swiper/swiper.min.js"></script>
	</head>
	<style type="text/css">
		.swiper-container {
			width: 100%;
			height: 100%;
		}
		
		.swiper-slide {
			text-align: center;
			font-size: 18px;
			background: #fff;
			display: -webkit-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			-webkit-box-pack: center;
			-ms-flex-pack: center;
			-webkit-justify-content: center;
			justify-content: center;
			-webkit-box-align: center;
			-ms-flex-align: center;
			-webkit-align-items: center;
			align-items: center;
		}
		
		.search {
			width: 100%;
			height: 50px;
			background: #f2f2f2;
			position: relative;
		}
		
		.search input {
			width: 100%;
			height: 30px;
			border-radius: 5px;
			text-align: center;
			color: #ccc;
		}
		
		.search img {
			position: absolute;
			top: 18px;
			left: 150px;
			width: 1rem;
		}
		
		.jifen {
			display: flex;
			flex-direction: row;
			align-items: center;
			font-size: 1rem;
		}
		
		.jifen img {
			width: 0.5rem;
			height: 0.7rem;
			margin-left: 0.5rem;
		}
		
		.up {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
		}
		
		.up img {
			width: 1rem;
		}
		
		.order {
			width: 5rem;
			border-radius: 0.3rem;
			color: #fff;
			height: 2rem;
			border:none;
			line-height: 0.9;
		}
		
		.navBar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			height: 32px;
		}
		
		.navBar .nav {
			width: 86.6%;
			box-sizing: content-box;
			height: 35px;
			overflow: hidden;
			line-height: 30px;
		}
		
		.navBar p {
			width: 13.3%;
		}
		
		.nav li {
			margin: 1px 10px;
		}
		
		.mask .nav {
			width: 100%;
			background: #fff;
			line-height: 32px;
		}
		
		.nav .active {
			color: #e04852;
			border-bottom: 2px solid #e04852;
		}
		
		.nav li {
			display: inline-block;
			text-align: center;
			font-size: 0.9rem;
		}
		
		.more img {
			width: 0.8rem;
			margin-left: 1.5rem;
		}
		
		.content {
			margin-top: 0.8rem;
		}
		
		.mui-table-view:not(:first-child) {
			display: none;
		}
		
		.mui-table-view:before {
			background: transparent;
		}
		
		.mui-table-view-cell:after {
			background: transparent;
		}
		
		.mui-table-view li {
			width: 49%;
			display: inline-block;
			padding: 11px 10px;
		}
		
		.content {
			position: relative;
		}
		
		.mask {
			position: absolute;
			top: 0
		}
		
		.navDiv {
			background: #fff;
		}
		
		.navDiv p {
			height: 30px;
			justify-content: center;
			display: flex;
			align-items: center;
		}
		
		.next {
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		
		.swiper-slide img {
			width: 100%;
		}
		
		.swiper-pagination-bullet {
			width: 25px;
			height: 5px;
			opacity: 1;
			background: #fff;
			border-radius: 3px !important;
		}
		
		.swiper-pagination-bullet-active {
			background: #e04852;
		}
		.mui-search{width: 88%;}
		.mui-search input{margin-bottom: 0px;}
		.mui-search:before{top:24px;}
		
		.searchDiv .searchBtn {
			color: #333;
			width: 13%;
			background: transparent;
			border:none;
		}
		.mui-search .mui-placeholder {
	        pointer-events: none;
	    }
		.all:after{background: transparent;}
		.shopDetail .imgs{text-align: center;margin-top:20px;}
	</style>

	<body>

		<div class="searchDiv p-15">			
			<div class="mui-input-row mui-search">
				<input type="search" name="goods_name" id="goods_name" class="mui-input-clear" placeholder="搜索餐品">
			</div>
			<button type="submit" class="searchBtn" id="search">搜索</button>
		</div>

		
		<input type="hidden" class="totalPage" value="<?php echo ($totalPage); ?>">
		<div class="mui-content">
			<div class="bac-fff contentDiv p-15">
				<div class="swiper-container" id="slider">
					<div class="swiper-wrapper">
						<?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
								<a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo ($vo["image"]); ?>"></a>
							</div><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="swiper-pagination"></div>
				</div>

				<div class="box-shadow p-10" style="margin-top: 20px;">
					<a href="<?php echo U('integralDetail');?>"><p class="jifen">当前积分<img src="images/more_triangle_right.png" alt="" /></p></a>
					<p class="next"><span class="blackColor font40"><?php if(($integral["is_perfect"]) == "1"): echo ($integral["integral"]); endif; ?></span><a href="<?php echo U('record');?>"><button class="redBackground order">订单</button></a></p>
				</div>

				<div class="content">
					<div class="navBar">
						<ul class="nav">
							<li class="active" cat_id="0">全部</li>
							<?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li cat_id="<?php echo ($v["cat_id"]); ?>"><?php echo ($v["cat_name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
						<p class="more"><img src="images/icon_fenl.png" alt="" /></p>
					</div>

					<div class="mask">
						<div class="navDiv">
							<ul class="nav">
								<li class="active" cat_id="0">全部</li>
								<?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li cat_id="<?php echo ($v["cat_id"]); ?>"><?php echo ($v["cat_name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
							<p class="up"><img src="images/more_on.png" alt="" /></p>
						</div>
					</div>
					<div class="shopDetail">
						<ul class="mui-table-view all">
							<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
										<a href="<?php echo U('detail',array('goods_id'=>$vo['goods_id']));?>">
											<div class="img" style="width:100%;background:url(<?php echo ($vo["pic_url"]); ?>) no-repeat center;background-size:cover;"></div>
											<p><?php echo (msubstr($vo["goods_name"],0,8)); ?></p>
											<p class="yellowColor"><?php echo ($vo["intergral"]); ?>积分</p>
										</a>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							<?php else: ?>
								<div class="imgs">
									<p class="greyColor">暂无结果</p>
								</div><?php endif; ?>
							
						</ul>
					</div>
				</div>
			</div>

			<ul class="footer" id="nav">
				<li>
					<a href="<?php echo U('Drink/index');?>" id="order">
						<span class="mui-icon order "></span>
						<span class="mui-tab-label">点餐</span>
					</a>
				</li>
				<li>
					<a href="<?php echo U('Integral/shop');?>" id="shop" class="active">
						<span class="mui-icon shop active"></span>
						<span class="mui-tab-label">积分商城</span>
					</a>
				</li>
				<?php if(($status) == "2"): ?><li>
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
					<a href="<?php echo U('Personal/index');?>" id="mine" >
						<span class="mui-icon mine"></span>
						<span class="mui-tab-label">我的</span>
					</a>
				</li>
			</ul>

			<script>
				var winHeight = $(window).height(); //获取当前页面高度
				var imgHeight = $(".mui-table-view li a").width();
				$(".shopDetail .img").height(imgHeight);
				$(".shopDetail").css("min-height",$(window).height()-50 -30-$("#slider").height()-20-$(".box-shadow").height()-32);//50是搜索栏的高度  30是contentDiv的padding 20是box-shadow的margin-top 32是navBar的高度
				$(window).resize(function() {
					var thisHeight = $(this).height();
					if(winHeight - thisHeight > 50) {
						$(".footer").css("position", "relative"); //当软键盘弹出，在这里面操作
						$(".mui-content").css("padding-bottom", "0");
					} else {
						//当软键盘收起，在此处操作
						$(".footer").css("position", "fixed");
						$(".mui-content").css("padding-bottom", "44px");
					}
				});
			
	
				//轮播图尺寸 2:1
				var swiper = new Swiper('.swiper-container', {
					autoplay: true,
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
						renderBullet: function(index, className) {
							return '<span class="' + className + '"></span>';
						}
					},
					loop: true
				});

				var width = parseInt($(window).width());
				$(".swiper-container").css("height", width + "px");
				$(".swiper-container").height(width / 2 + "px");
				$(".swiper-slide img").css({
					"height": width / 2 + "px",
					"width": width
				});

				$(".more").click(function() {
					$(".nav").css({
						"height": "",
						"overflow": ""
					});
					$(".mask").show();
				})

				$(".nav li").click(function() {
				    var ele = $(this).parent().parent();
				    var cat_id;
				    if (ele.hasClass("navBar")) //外层li的选中
				    {
				        $(this).addClass("active").siblings().removeClass("active");
				        
				        cat_id = $(".nav").find('.active').attr('cat_id');
				    }else if (ele.hasClass("navDiv")) //遮罩层的li选中
				    {
				        $(this).addClass("active").siblings().removeClass("active");
				        cat_id = $(".navDiv .nav").find(".active").attr("cat_id");
				        
				        if ($(this).index() < 5) //让外层的li也有active
				        	$($(".navBar li")[$(this).index()]).addClass("active").siblings().removeClass("active");
				        else {
				            $(".navBar li").removeClass("active");
				        }
				    }
				    search(cat_id);
				})

				//收起遮罩层
				$(".up").click(function() {
					$(".nav").css({
						"height": "35px",
						"overflow": "hidden"
					});
					$(".mask").hide();
				})
				//搜索
				$('#search').click(function(){
					search();				    
				})
				
				$("#search").on('keypress', function(e) {
					search();
				});
				
				//查询数据
				function search(cat_id){
					var goods_name = $("#goods_name").val();
					
				    $.post("<?php echo U('search');?>",{goods_name:goods_name,cat_id:cat_id},function(da){
				    	console.log(da);
				    	var html='';
				    	if(da.totalPage>0){
				    		$.each(da, function(i) {
								if(da[i]['goods_id']){
									html+='<li class="mui-table-view-cell">'
									html+=	'<a href="/index.php?m=Home&c=Integral&a=detail&goods_id='+da[i]['goods_id']+'">'
									html+=      ('<div class="img" style="width:100%;height:'+imgHeight+'px;background:url('+da[i]['pic_url']+') no-repeat center;background-size:cover;"></div>')
									html+=		'<p>'+da[i]['goods_name']+'</p>'
									html+=		'<p class="yellowColor">'+da[i]['intergral']+'积分</p>'
									html+=	'</a>'
									html+='</li>'
								}
								
							});
				    	}else{
							html+='<div class="imgs">'
							html+='	<p class="greyColor">暂无结果</p>'
							html+='</div>'
						}
						
						$(".mui-table-view").empty();
						$(".mui-table-view").append(html);
						$('.totalPage').val(da.totalPage);
				    })
				}
			</script>
			<script type="text/javascript">
				//分页
				p = 2;
				$(window).scroll(function() {
					var winH = $(window).height(); //页面可视区域高度
					var scrollTop = $(window).scrollTop(); //滚动条top
					var bodyHeight = $(document).height();
					if(scrollTop + winH >= bodyHeight) {
						var totalPage = $('.totalPage').val();
						var goods_name = $("#goods_name").val();
						var cat_id = $(".nav").find('.active').attr('cat_id');
						if(p <= totalPage) {
							$.ajax({
								type: "post",
								url: "<?php echo U('ListNext');?>",
								data: {
									p: p,
									goods_name: goods_name,
									cat_id: cat_id
								},
								dataType: 'json',
								success: function(data) {
									if(data) {
										var html='';
										$.each(data, function(i) {
											html+='<li class="mui-table-view-cell">'
											html+=	'<a href="/index.php?m=Home&c=Integral&a=detail&goods_id='+data[i]['goods_id']+'">'
											html+=		'<img src="'+data[i]['pic_url']+'" alt="" />'
											html+=		'<p>'+data[i]['goods_name']+'</p>'
											html+=		'<p class="yellowColor">'+data[i]['intergral']+'积分</p>'
											html+=	'</a>'
											html+='</li>'
										});
										$(".all").append(html);
										
									//	$(".contentDiv").height($(document.body).height()-130);
									}																		 									
								}
							});
							p++;

						}
					}
				});

				$(".mask").click(function(event){
					  var _con = $('.navDiv');   // 设置目标区域
					  if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
						//$('#divTop').slideUp('slow');   //滑动消失
						$('.mask').hide();          //淡出消失
					  }
				});

			</script>
	</body>

</html>