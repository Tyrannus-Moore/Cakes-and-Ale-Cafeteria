<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>点餐</title>
	<base href="/public/" />
	<link rel="stylesheet" type="text/css" href="home/css/common.css" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	<script src="home/js/mui.min.js"></script>
	<link rel="stylesheet" href="home/css/currency.css" />
	<script src="home/js/jquery-2.2.1.min.js"></script>
	<link rel="stylesheet" href="home/css/swiper/swiper.css">
	<script src="home/js/swiper/swiper.min.js"></script>
	<link type="text/css" media="all" rel="stylesheet" href="home/jiaoben/css/dropload.css">
</head>
<style type="text/css">
	.swiper-container {
		width: 100%;
		height: 100%;
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

	.dinner {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		margin-top: 10px;
	}

	.dinner .left {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}

	.dinner .left img {
		width: 1.5rem;
		height: 1.5rem;
	}
	
	.dinner .left img {
		margin-right: 15px;
	}

	.dinner .right {
		flex-direction: row;
	}

	.tuijian {
		margin-top: 20px;
		display: flex;
		justify-content: space-between;
		margin-bottom: 10px;
	}

	.tuijian .left {
		display: flex;
		align-items: center;
	}

	.tuijian .right {
		display: flex;
		flex-direction: row;
		align-items: center;
	}

	.tuijian img {
		height: 1rem;
	}

	.line {
		width: 5px;
		height: 20px;
		background: #e04852;
		margin-right: 15px;
		display: inline-block;
	}

	.tuijianContent{display: flex;flex-direction: row;overflow: scroll;width: auto;}
	.tuijianContent .picDiv{width: 32%;min-width: 32%;position: relative;background-size:cover;display: flex;justify-content: center;}
	.tuijianContent p{position: absolute;color: #fff;bottom: 15px;}
	.tuijianContent .picDiv:not(:last-child){margin-right: 2%;}
	.list li{margin-bottom: 15px;}
	.list .img{margin-right: 15px;}
	.cantingDiv{display: flex;flex-direction: column;justify-content: space-around;}
	.starDiv{display: flex;flex-direction: row;}
	.starDiv img{width: 1rem;height: 1rem;}
	.priceDiv{display: flex;}
	.picDiv a{    width: 100%;
		display: flex;
		justify-content: center;}
	.mui-search:before{top:25px;}
	.count{text-decoration:line-through;}
	.countMoney {
		text-decoration: line-through;
	}
	.mui-search {
		width: 83%;
	}
	.searchDiv .searchBtn {
		font-size:16px;
		color: #333;
		width: 13%;
		background: transparent;
		border:none;
	}
	.name{font-size: 20px;}
	
	.mui-search .mui-placeholder {
        pointer-events: none;
    }
    .list .img {
	    border-radius: 5px;
	}
    .jifen{color: #333;font-size:14px;}
    .distance{color: #ccc;font-size:12px}
</style>

<body>
<div class="mui-content">
	<!--搜索-->
	<form method="post" action="<?php echo U('search');?>" enctype="multipart/form-data" onsubmit="return abce();">
	<div class="searchDiv p-15">
		<div class="mui-input-row mui-search">
			<input type="search" name="search" id="search" value="" class="mui-input-clear" placeholder="搜索餐品">
		</div>
		<button type="submit" class="searchBtn">搜索</button>
	</div>
	</form>
	<div class="bac-fff p-15">
		<!--轮播图-->
		<div class="swiper-container" id="slider">
			<div class="swiper-wrapper">
				<?php if(is_array($banner_list)): $i = 0; $__LIST__ = $banner_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="swiper-slide" data-swiper-autoplay="1000">
					<a href="<?php echo ($v["url"]); ?>"><img src="<?php echo ($v["image"]); ?>"></a>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
		<!--距离-->
		<div class="box-shadow p-10" style="margin-top: 20px;">
			<p class="jifen">餐厅用餐人数</p>
			<div class="dinner">
				<div class="left">
					<?php if(($shopData["saturation"]) == "1"): ?><img src="home/images/home_man_s.png" alt=""/><?php else: ?><img src="home/images/home_man_uns.png" alt=""/><?php endif; ?>
					<?php if(($shopData["saturation"]) == "2"): ?><img src="home/images/home_shao_s.png" alt=""/><?php else: ?><img src="home/images/home_shao_uns.png" alt=""/><?php endif; ?>
					<?php if(($shopData["saturation"]) == "3"): ?><img src="home/images/home_kong_s.png" alt=""/><?php else: ?><img src="home/images/home_kong_uns.png" alt=""/><?php endif; ?>
				</div>
				<div class="right">
					<span class="distance">距餐厅&nbsp;-km</span>
				</div>
			</div>
		</div>
		<!--推荐-->
		<div class="tuijian">
			<div class="left">
				<span class="line"></span>
				<span class="c33 font20">为您推荐</span>
			</div>
			<div class="right">
				<?php if(empty($recom)): ?><span class="greyColor" style="width:35px;/margin-right: 5px;color:#999;">
						更多
					</span>
				<?php else: ?>
					<a href="<?php echo U('recommend');?>">
					<span class="greyColor" style="width:35px;margin-right: 5px;color:#999;">
						更多
					</span>
					</a><?php endif; ?>
				<img src="home/images/more_right.png" alt="" />
			</div>
		</div>
		<div class="tuijianContent">
			<?php if(is_array($recom)): $i = 0; $__LIST__ = $recom;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="picDiv dishesDetails" data-id="<?php echo ($v["dishes_id"]); ?>" data-type="<?php echo ($v["type"]); ?>">
				<img src="<?php echo ($v["pic_url"]); ?>" style="width: 100%;height: 100%"/>
				<p style="background-color: rgba(0,0,0,0.4);padding: 2px 5px"><?php echo (msubstr($v["dishes_name"],0,5)); ?></p>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<!--档口-->
		<p style="margin-top: 10px;margin-bottom:10px !important;display: flex;align-items: center;">
			<span class="line"></span>
			<span class="c33 font20">餐厅档口</span>
		</p>

		<article class="khfxWarp" style="padding-top:0;">
			<ul class="list">
			</ul>
		</article>

	</div>
</div>
<!--菜单-->
<ul class="footer" id="nav">
	<li>
		<a href="<?php echo U('Drink/index');?>" id="order" class="active">
			<span class="mui-icon order active"></span>
			<span class="mui-tab-label">点餐</span>
		</a>
	</li>
	<li>
		<a href="<?php echo U('Integral/shop');?>" id="shop">
			<span class="mui-icon shop"></span>
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
		<a href="<?php echo U('Personal/index');?>" id="mine">
			<span class="mui-icon mine"></span>
			<span class="mui-tab-label">我的</span>
		</a>
	</li>
</ul>
<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="home/jiaoben/lib/dropload.js"></script>
<script type="text/JavaScript">
	function abce() {
        var search = $('#search').val();
        if(search == ''){
            layer.open({
                content: '请填写菜品名称'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
    }
    //配置信息验证接口
    wx.config({
        debug: false,
        appId: '<?PHP echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            //'checkJsApi',
            //'openLocation',
            'getLocation'
        ]
    });
    //验证之后进入该函数，所有需要加载页面时调用的接口都必须写在该里面
    wx.ready(function () {
	//基础接口判断当前客户端版本是否支持指定JS接口
        wx.checkJsApi({
            jsApiList: [
                'getLocation'
            ],
            success: function (res) {
                if (res.checkResult.getLocation == false) {
                    alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                    return;
                }
            }
        });
		//微信获取地理位置并拉取用户列表（用户允许获取用户的经纬度）
        wx.getLocation({
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                console.log(res);
                //去数据库查询获取附近的门店
                $.ajax({
                    type: 'post',
                    url: "<?php echo U('distance');?>",
                    dataType: 'json',
                    data: {"latitude": latitude,"longitude":longitude},
                    success:function(shopInfo){
                        //index是下表，el是值
                        $(".distance").text(shopInfo);
                    }
                });
            },
            cancel: function (res) {
                //$(".distance").text('3km');
            }
        });
    });
    
	var winHeight = $(window).height(); //获取当前页面高度
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
	
	$(".tuijianContent .picDiv").height($(".tuijianContent .picDiv").width());
	
	/*苹果手机调起键盘底部导航栏抬起的解决方法*/
	function isIphone() {
		var u = navigator.userAgent,
			app = navigator.appVersion;
		var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
		var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端	
		if(isIOS)
			return true;
		else
			return false;
	}

		
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
		loop: 1,
		// 自动播放时间
       // autoplay:1000,
		speed:1000,
	});

	var width = parseInt($(window).width());
	$(".swiper-container").css("height", width + "px");
	$(".swiper-container").height(width / 2 + "px");
	$(".swiper-slide img").css({
		"height": width / 2 + "px",
		"width": width
	});
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
    //档口详情
    $( ".khfxWarp" ).on( "click", ".stallDetail", function() {
        var stall_id = $(this).attr("data-id");
        var stall_type = $(this).attr("data-type");
        if(stall_type == '1'){
            window.location.href = "<?php echo U('Stall/stallDetail');?>&stall_id="+stall_id;
        }else{
            window.location.href = "<?php echo U('Package/index');?>&stall_id="+stall_id;
        }
    })
</script>
<script>
    $(function () {
        var page = 1;
        var psize = 10;
        var tabLoadEndArray = false;
        var tabLenghtArray = '<?php echo ($stall); ?>';
        var tabScroolTopArray = [0, 0, 0];

        // dropload
        var dropload = $('.khfxWarp').dropload({
            scrollArea: window,
            domDown: {
                domClass: 'dropload-down',
                domRefresh: '<div class="dropload-refresh">上拉加载更多</div>',
                domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
                domNoData: '<div class="dropload-noData">没有更多了</div>'
            },
            loadDownFn: function (me) {
                if (tabLenghtArray > 0) {
                    //console.log('i');
                    $.ajax({
                        type: 'GET',
                        data: {page:page,psize:psize},
                        url: '/index.php?m=Home&c=Drink&a=indexLists',
                        //dataType: 'json',
                        success: function (data) {
                            //console.log(data);
                            if(data == 1) {
                                me.lock();
                                me.noData();
                                me.resetload();
                                return;
                            }else{
                                setTimeout(function () {
                                    if (tabLoadEndArray) {
                                        me.resetload();
                                        me.lock();
                                        me.noData();
                                        me.resetload();
                                        return;
                                    }
                                    page++;
                                    $('.list').append(data);
                                //    $(".stallDetail .img").css({"width":$(".stallDetail").width(), "height":$(".stallDetail").width()});
                                    $(".stallDetail .img").css({"width":$(".dishesDetails").width(), "height":$(".dishesDetails").height()});
                                    me.resetload();
                                },0);
                            }
                        },
                        error: function(xhr, type){
                            //alert('error!');
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                } else {
                    me.lock();
                    me.noData();
                    me.resetload();
                    return false;
                }
            }
        });
    });
</script>
</body>
</html>