<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title></title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<!-- bootstrap & fontawesome必须的css -->
	<link rel="stylesheet" href="/public/ace/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/public/font-awesome/css/font-awesome.min.css" />
	<!-- 此页插件css -->
	<!-- ace的css -->
	<link rel="stylesheet" href="/public/ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<!-- IE版本小于9的ace的css -->
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="/public/ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="/public/ace/css/ace-ie.css" />
	<![endif]-->
	<link rel="stylesheet" href="/public/yfcmf/yfcmf.css" />
	<!-- 此页相关css -->
	<!-- ace设置处理的css -->
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
	<!--[if lte IE 8]>
	<script src="/public/others/html5shiv.min.js"></script>
	<script src="/public/others/respond.min.js"></script>
	<![endif]-->
    <!-- 引入基本的js -->
    <script type="text/javascript">
        var admin_ueditor_handle = "<?php echo U('Admin/Sys/upload');?>";
        var admin_ueditor_lang ='zh-cn';
    </script>
    <!--[if !IE]> -->
    <script src="/public/others/jquery.min-2.2.1.js"></script>
    <!-- <![endif]-->
    <!-- 如果为IE,则引入jq1.12.1 -->
    <!--[if IE]>
    <script src="/public/others/jquery.min-1.12.1.js"></script>
    <![endif]-->
    <!-- 如果为触屏,则引入jquery.mobile -->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='/public/others/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="/public/others/bootstrap.min.js"></script>
</head>
<body style="background-color: #fff;">
<div class="page-content">
	<div class="page-header">
		<h1>您当前操作
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>列表
			</small>
		</h1>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div>
				<!--<form name="admin_list_sea" class="form-search" method="post" action="<?php echo U('shopList');?>">
					<div class="row maintop">
						<div class="col-xs-12 col-sm-12 btn-sespan">
							<div class="input-group">
								<input name="m_id" type="hidden" value="<?php echo ($m_id); ?>">
								<span class="input-group-addon">商家：</span>
								<input name="search[name]" class="form-control" type="text"  value="<?php echo ($search["name"]); ?>" style="width:200px;">
								<span class="input-group-btn btn-group-sm" style="padding-left:0px;">
								<button type="submit" class="btn btn-xs  btn-purple">
									<span class="ace-icon fa fa-search"></span>
									搜索
								</button>
								</span>
								<span class="input-group-btn btn-group-sm" style="padding-left:10px;">
									<a href=<?php echo U('shopList');?>>
										<button type="button" class="btn btn-sm  btn-purple">
											<span class="ace-icon fa fa-globe icon-on-right bigger-110"></span>
											显示全部
										</button>
									</a>
								</span>
							</div>
						</div>
					</div>
				</form>-->
				<!-- <form id="alldel" class="ajaxForm" name="alldel" method="post" action="<?php echo U('fitmentBatchDel');?>"> -->
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th class="hidden-xs">配送员姓名</th>
							<th class="hidden-xs">手机号</th>
							<th class="hidden-xs">学校名称</th>
							<th class="hidden-xs">商家名称</th>
							<th class="hidden-xs">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
							<td class="hidden-xs"><?php echo ($v["member_name"]); ?></td>
							<td class="hidden-xs"><?php echo ($v["telphone"]); ?></td>
							<td class="hidden-xs"><?php echo ($v["name"]); ?></td>
							<td class="hidden-xs"><?php echo ($v["ma_merchantname"]); ?></td>
							<td class="hidden-xs">
								<a class="red confirm-rst-url-btn btn btn-minier btn-yellow chooseJs" data-id="<?php echo ($v["member_list_id"]); ?>" data-oid="<?php echo ($order_id); ?>" title="分配">
									派单
								</a>
							</td>
						</tr><?php endforeach; endif; ?>
					</tbody>
				</table>
						<!-- <input hidden="hidden" name="oid" id="oid" value="<?php echo ($o_id); ?>"> -->
					<!-- </form> -->
			</div>
		</div>
	</div>
</div><!-- /.page-content -->
<script type="text/javascript" src="/public/others/jquery.min-2.2.1.js"></script>
<script src="/public/others/jquery.form.js"></script>
<script src="/public/layer/layer.js"></script>
<script type="text/javascript">
	$(".chooseJs").click(function(){
		var id = $(this).attr("data-id");
		var order_id = $(this).attr("data-oid");
		$.ajax({
			url:"<?php echo U('paidan');?>",
			data:{
                member_list_id:id,
                order_id:order_id,
			},
			success:function(data){
				if(data == 1){
					layer.alert('派单成功！', {
					  skin: 'layui-layer-molv' //样式类名
					  ,closeBtn: 0
					}, function(){
						//关闭ifram框
						var index = parent.layer.getFrameIndex(window.name);
						parent.location.reload();
						parent.layer.close(index);//关闭当前页
					});
				}else{
					layer.alert('派单失败！', {
					  skin: 'layui-layer-molv' //样式类名
					  ,closeBtn: 0
					}, function(){
						//关闭ifram框
						var index = parent.layer.getFrameIndex(window.name);
						parent.location.reload();
						parent.layer.close(index);//关闭当前页
					});
				}
			}
		})
	})
</script>
</body>
</html>