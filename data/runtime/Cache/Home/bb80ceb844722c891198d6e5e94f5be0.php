<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>我的收藏</title>
	<base href="/public/"/>
	<link rel="stylesheet" type="text/css" href="home/css/common.css" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
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
	
	.name{font-size:18px;color: #333;font-weight: bold;}
</style>
<style type="text/css">
	.mui-content {
		padding-bottom: 0px;
	}
	.mui-content{background: #fff;height: 100%;}
	.list li {
		margin-bottom: 15px;
	}

	.list .img {
		margin-right: 15px;
	}

	.cantingDiv {
		flex: 1;
		display: flex;
		flex-direction: column;
	}

	.starDiv {
		display: flex;
		flex-direction: row;
		align-items: center;
		margin-top: 10px;
	}

	.starDiv img {
		width: 1rem;
		height: 1rem;
	}

	.sale {
		margin-top:2.2rem;
	}

	.sale img {
		width: 0.8rem;
		height: 0.8rem;
	}

	.list .delete{display:flex;align-items: flex-end;}
	.delete img{height:15px;}
</style>
<body>
	<div class="mui-content">
		<ul class="list whiteBackground padd-15">
			<?php if(empty($list)): ?><div class="imgs">
					<img src="home/images/icon_wjg.png" alt="" />
					<p class="greyColor">暂无收藏</p>
				</div>
			<?php else: ?>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="stallDetail" data-id="<?php echo ($v["stall_id"]); ?>" data-type="<?php echo ($v["stall_type"]); ?>">
					<div class="img" style="width:7rem;height:7rem;background:url('<?php echo ($v["image"]); ?>') no-repeat center;background-size:cover;"></div>
					<div class="cantingDiv">
						<p class="name"><?php echo ($v["stall_name"]); ?></p>
						<p class="starDiv">
							<?php $__FOR_START_23253__=0;$__FOR_END_23253__=$v["score"];for($i=$__FOR_START_23253__;$i < $__FOR_END_23253__;$i+=1){ ?><img src="home/images/star_icon24.png" alt="" /><?php } ?>
							<?php
 for ($i=0;$i<5-$v[score];$i+=1){ echo '<img class="ptdkXing" src="home/images/star_icon25.png" />'; } ?>
							<span><?php echo (sprintf('%.1f',$v["score"])); ?></span>
						</p>
						<p class="sale  greyColor">
							<span>月销<span><?php echo ($v["on_the_pin"]); ?></span></span>
						</p>
					</div>

					<div class="delete">
						<img src="home/images/icon_shanc.png" class="edit" data-id="<?php echo ($v["collection_id"]); ?>"/>
					</div>
				</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</ul>
	</div>
	<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="others/jquery.form.js"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
        $(".imgs").height($(window).height() - 50);
	</script>
	<script type="text/javascript">
		$(".mui-content").css("min-height",$(window).height());
		$(".list").on("click",".edit",function(){
            var collection_id = $(this).attr('data-id');
            layer.open({
                content: '确定取消收藏吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'post',
                        url: "<?php echo U('mycollection_del');?>",
                        data: {collection_id:collection_id},
                        success: function (data) {
                            if(data == 200){
                                layer.open({
                                    content: '取消成功！'
                                    ,skin: 'msg'
                                    ,time: 2 //2秒后自动关闭
                                    ,end: function() {
                                        location.reload();
                                    }
                                });
                            }else{
                                layer.open({
                                    content: '取消失败！'
                                    ,skin: 'msg'
                                    ,time: 2 //2秒后自动关闭
                                    ,end: function() {
                                        location.reload();
                                    }
                                });
                            }
                        }
                    })
                }
            });
            return false;
		})
        //档口详情
        $( ".list" ).on( "click", ".stallDetail", function() {
            var stall_id = $(this).attr("data-id");
            var stall_type = $(this).attr("data-type");
            if(stall_type == '1'){
                window.location.href = "<?php echo U('Stall/stallDetail');?>&stall_id="+stall_id;
            }else{
                window.location.href = "<?php echo U('Package/index');?>&stall_id="+stall_id;
            }
        })
	</script>
</body>
</html>