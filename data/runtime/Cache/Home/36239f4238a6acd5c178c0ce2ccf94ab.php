<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>我的地址</title>
	<base href="/public/"/>
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	<script src="home/js/mui.min.js"></script>
	<script src="home/js/jquery-2.2.1.min.js"></script>
</head>
<style>
	body{font-size: 18px;}
	.bottomDiv {
		position: fixed;
		bottom: 0;
		width: 100%;
		border-radius: 0.2rem;
		background: #fff;
		color: #e04852;
		height: 2.5rem;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.address p, .address .operate{line-height: 35px;}
	.operate{display: flex;justify-content: space-between;border-top: 1px solid #e0e0e0;}
	.operate p{display: flex;justify-content: space-between;align-items: center;}
	.operate p img{width: 1rem;height: 1rem;}
	.delete{margin-right: 20px;}
</style>
<body>
	<div class="mui-content">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="whiteBackground address mb-15">
			<p class="bold c33 plr-10"><span><?php echo ($v["name"]); ?></span>&nbsp;&nbsp;<span><?php echo ($v["phone"]); ?></span></p>
			<p class="border-bottom c33 plr-10">
				<span><?php echo ($v["proviceid"]); ?></span>&nbsp;&nbsp;<span><?php echo ($v["cityid"]); ?></span>&nbsp;&nbsp;<span><?php echo ($v["countyid"]); ?></span>&nbsp;&nbsp;
				<span><?php echo ($v["address"]); ?></span>
			</p>
			<div class="operate plr-10" >
				<p class="is_default" data-id="<?php echo ($v["address_id"]); ?>">
					<?php if(($v["is_default"]) == "1"): ?><img src="home/images/check_button_s.png" style="margin-right: 10px;" />默认地址
						<?php else: ?>
						<img src="home/images/check_button_uns.png" style="margin-right: 10px;" />默认地址<?php endif; ?>
				</p>
				<p>
					<span class="delete" data-id="<?php echo ($v["address_id"]); ?>">删除</span>
					<span class="edit" data-id="<?php echo ($v["address_id"]); ?>" data-id="<?php echo ($v["address_id"]); ?>">编辑</span>
				</p>
			</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>

		<div class="bottomDiv save">
			+新地址
		</div>
	</div>

	<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="others/jquery.form.js"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		//删除
		$(".delete").click(function(){
            var address_id = $(this).attr('data-id');
            layer.open({
                content: '您确定要删除该地址吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'post',
                        url: "<?php echo U('address_del');?>",
                        data: {address_id:address_id},
                        success: function (data) {
                            if(data == 200){
                                layer.open({
                                    content: '删除成功！'
                                    ,skin: 'msg'
                                    ,time: 2 //2秒后自动关闭
                                    ,end: function() {
                                        location.reload();
                                    }
                                });
                            }else{
                                layer.open({
                                    content: '删除失败！'
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
		});
		//默认
		$(".is_default").click(function () {
            var address_id = $(this).attr('data-id');
            $.ajax({
                type: 'post',
                url: "<?php echo U('address_default');?>",
                data: {address_id:address_id},
                success: function (data) {
                    if(data == 200){
                        layer.open({
                            content: '设置成功！'
                            ,skin: 'msg'
                            ,time: 1 //2秒后自动关闭
                            ,end: function() {
                                location.reload();
                            }
                        });
                    }else{
                        layer.open({
                            content: '设置失败！'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                            ,end: function() {
                                location.reload();
                            }
                        });
                    }
                }
            })
        })
		//添加
		$(".save").click(function(){
			window.location.href = "<?php echo U('address_add');?>";
		})

		$(".edit").click(function(){
            var address_id = $(this).attr('data-id');
			window.location.href = "<?php echo U('address_edit');?>&address_id="+address_id;
		})
	</script>
</body>
</html>