<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />	
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>配送员入驻</title>
	<base href="/public/" />
	<link rel="stylesheet" type="text/css" href="home/css/common.css" />
	<link rel="stylesheet" type="text/css" href="home/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
</head>
<body>
<form class="form-horizontal deliveryForm" name="form0" method="post" action="<?php echo U('delivery');?>"  enctype="multipart/form-data">
	<div class="psyrzTop clearfix">
		<div class="fl psyTypeText">配送类型</div>
		<div class="psySelTypeBox clearfix fl">
			<div class="psyTypeBox clearfix fl typeBox1">
				<?php if(($infos["type"]) == "1"): ?><img class="fl psySelTypeImg" src="home/images/check_button_uns.png" />
					<?php else: ?>
					<img class="fl psySelTypeImg" src="home/images/check_button_s.png" /><?php endif; ?>
				<div class="fl">专职</div>
			</div>
			<div class="psyTypeBox clearfix fl typeBox2">
				<?php if(($infos["type"]) == "1"): ?><img class="fl psySelTypeImg" src="home/images/check_button_s.png" />
					<?php else: ?>
					<img class="fl psySelTypeImg" src="home/images/check_button_uns.png" /><?php endif; ?>
				<div class="fl">兼职</div>
			</div>
			<input type="hidden" name="type" id="type" <?php if(empty($infos["type"])): ?>value="2"<?php else: ?>value="<?php echo ($infos["type"]); ?>"<?php endif; ?> >
		</div>
	</div>

	<div class="bac-fff mt-15">
		<div class="psyrzList clearfix">
			<div class="fl psyTypeText">姓名</div>
			<input class="fl psyrzInp" maxlength="10" type="text" name="member_name" id="member_name" value="<?php echo ($infos["member_name"]); ?>" placeholder="输入真实姓名"/>
		</div>
		<div class="psyrzList clearfix">
			<div class="fl psyTypeText">性别</div>
			<div class="psySelTypeBox clearfix fl">
				<div class="psyTypeBox clearfix fl typeSex1">
					<?php if(($infos["member_list_sex"]) == "1"): ?><img class="fl psySelTypeImg" src="home/images/check_button_s.png" />
						<?php else: ?>
						<img class="fl psySelTypeImg" src="home/images/check_button_uns.png" /><?php endif; ?>
					<div class="fl">男</div>
				</div>

				<div class="psyTypeBox clearfix fl typeSex2">
					<?php if(($infos["member_list_sex"]) == "2"): ?><img class="fl psySelTypeImg" src="home/images/check_button_s.png" />
						<?php else: ?>
						<img class="fl psySelTypeImg" src="home/images/check_button_uns.png" /><?php endif; ?>
					<div class="fl">女</div>
				</div>
				<input type="hidden" name="member_list_sex" id="member_list_sex" value="<?php echo ($infos["member_list_sex"]); ?>">
			</div>
		</div>
		<div class="psyrzList clearfix">
			<div class="fl psyTypeText">手机号</div>
			<input class="fl psyrzInp" type="tel" maxlength="11" name="telphone" id="telphone" value="<?php echo ($infos["telphone"]); ?>" placeholder="输入手机号"/>
		</div>
	</div>

	<div class="bac-fff mt-15">
		<div class="psyrzList clearfix">
			<div class="fl psyTypeText">身份证号</div>
			<input class="fl psyrzInp" type="text" maxlength="18" name="id_card" id="id_card" value="<?php echo ($infos["id_card"]); ?>" placeholder="输入身份证号"/>
		</div>
	</div>

	<div class="bac-fff mt-15 p-10">
		<div>身份证正面照片</div>
		<div class="po-rela">
			<img class="sfzzp" src="<?php if($infos["card_zheng"] != ''): ?><?php echo ($infos["card_zheng"]); else: ?>/public/home/images/icon_sfzzm.png<?php endif; ?>"/>
			<input class="unloadFile" onchange="xmTanUploadImg(this)" id="card_zheng" type="file" name="card_zheng" value="" />
		</div>

	</div>

	<div class="bac-fff mt-15 p-10">
		<div>身份证反面照片</div>
		<div class="po-rela">
			<img class="sfzzp" src="<?php if($infos["card_fan"] != ''): ?><?php echo ($infos["card_fan"]); else: ?>/public/home/images/icon_sfzfm.png<?php endif; ?>"/>
			<input class="unloadFile" onchange="xmTanUploadImg(this)" id="card_fan" type="file" name="card_fan" value=""/>
		</div>
	</div>
	<input type="hidden" id='aaa' value="<?php if($infos["card_zheng"] != ''): ?>1<?php else: ?>2<?php endif; ?>"/>
	<?php if(($infos["state"]) == "3"): ?><div class="bac-fff mt-15 p-10">
			<div>驳回原因:</div>
			<div class="po-rela">
				<?php echo ($infos["refusal_reason"]); ?>
			</div>
		</div><?php endif; ?>

	<input class="psyTjBtn" type="submit" value="提交" style="height:40px;" />
</form>
<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="others/jquery.form.js"></script>
<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="home/jscript/ajax.js" type="text/javascript"></script>
<script>
	/*配送员 类型和性别切换*/
	$(".typeBox1").click(function(){
		$(this).find("img").attr("src","home/images/check_button_s.png");
		$(this).siblings(".typeBox2").find("img").attr("src","home/images/check_button_uns.png");
		$("#type").val(2);
	});
	$(".typeBox2").click(function(){
		$(this).find("img").attr("src","home/images/check_button_s.png");
		$(this).siblings(".typeBox1").find("img").attr("src","home/images/check_button_uns.png");
		$("#type").val(1);
	});
	$(".typeSex1").click(function(){
		$(this).find("img").attr("src","home/images/check_button_s.png");
		$(this).siblings(".typeSex2").find("img").attr("src","home/images/check_button_uns.png");
		$("#member_list_sex").val(1);
	});
	$(".typeSex2").click(function(){
		$(this).find("img").attr("src","home/images/check_button_s.png");
		$(this).siblings(".typeSex1").find("img").attr("src","home/images/check_button_uns.png");
		$("#member_list_sex").val(2);
	});

	/*配送员入驻上传图片*/
	//选择图片，马上预览
	function xmTanUploadImg(obj) {
		var file = obj.files[0];

		console.log(obj);
		console.log(file);
		console.log("file.size = " + file.size);  //file.size 单位为byte

		var reader = new FileReader();

		//读取文件过程方法
		reader.onloadstart = function (e) {
			console.log("开始读取....");
		};
		reader.onprogress = function (e) {
			console.log("正在读取中....");
		};
		reader.onabort = function (e) {
			console.log("中断读取....");
		};
		reader.onerror = function (e) {
			console.log("读取异常....");
		};
		reader.onload = function (e) {
			console.log("成功读取....");

			//var img = document.getElementById("uploadImg");
			$(obj).siblings("img").attr("src",e.target.result)
			//img.src = e.target.result;
			//或者 img.src = this.result;  //e.target == this
		};

		reader.readAsDataURL(file);
		console.log(file);
	}
</script>
</body>
</html>