<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>完善资料</title>
	<base href="/public/" />
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" href="home/css/common.css" />
	<link rel="stylesheet" type="text/css" href="home/css/layout.css"/>
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	<script src="home/js/mui.min.js"></script>
	<script src="home/js/jquery-2.2.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="home/css/mui.picker.min.css" />
	<script src="home/js/mui.picker.min.js"></script>
	<link href="home/css/mui.poppicker.css" rel="stylesheet" />
	<script src="home/js/mui.poppicker.js"></script>
</head>
<style type="text/css">
	.mui-input-group {
		background: transparent;
	}

	.mui-input-row input {
		text-align: right;
	}

	.mui-input-row span {
		font-size: 17px;
		color: #666;
	}

	.mui-input-group .mui-input-row .last:after {
		background: transparent !important;
	}

	.selectDiv {
		display: flex;
		justify-content: space-between;
		padding-right: 15px;
		/*background: #fff;*/
	}

	.selectEle {
		width: 6rem;
		height: 40px;
		display: inline-block;
		line-height: 40px;
		text-align: right;
	}

	.sex {
		display: flex;
		justify-content: flex-end;
		align-items: center;
		height: 100%;
		padding-right: 15px;
	}

	.sex img {
		width: 1rem;
		height: 1rem;
	}

	.bottomDiv {
		margin: 2rem auto 0;
		border-radius: 0.2rem;
		background-color: #e04852;
		color: #fff;
		height: 2.5rem;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.male, .female{width: 40px;
display: flex;}
</style>

<body>
	<div class="mui-content p-15">
		<form class="mui-input-group personalForm" name="form0" method="post" action="<?php echo U('personal_add');?>"  enctype="multipart/form-data">
			<div class="whiteBackground">
				<input type="hidden" name="href" value="<?php echo ($href); ?>"/>
				<div class="mui-input-row">
					<label>昵称</label>
					<input type="text" name="member_list_nickname" id="member_list_nickname" value="<?php echo ($infos["member_list_nickname"]); ?>">
				</div>
				<div class="mui-input-row">
					<label>性别</label>
					<div class="sex">
						<p class="male">
							<img src="home/images/check_button_s.png" />男
						</p>&nbsp;&nbsp;
						<p class="female">
							<img src="home/images/check_button_uns.png" />&nbsp;&nbsp;女
						</p>
						<input type="hidden" name="member_list_sex" id="member_list_sex" value="1">
					</div>
				</div>
				<div class="mui-input-row selectDiv">
					<label>出生日期</label>
					<span class="selectEle date" data-options='{"type":"date","beginYear":"1900"}'><?php echo (date('Y-m-d',$infos["birthary_time"])); ?></span>
					<input type="hidden" name="birthary_time" id="birthary_time" value="<?php echo (date('Y-m-d',$infos["birthary_time"])); ?>"/>
				</div>
				<div class="mui-input-row">
					<label>手机号</label>
					<input type="tel" name="telphone" id="telphone" value="<?php echo ($infos["telphone"]); ?>">
				</div>
			</div>

		<div class="whiteBackground mt-15">
			<div class="mui-input-row">
				<label>学校</label>
				<input type="text" value="<?php echo ($school_info["name"]); ?>" readonly>
				<input type="hidden" name="school_id" value="<?php echo ($school_info["school_id"]); ?>">
			</div>
			<div class="mui-input-row selectDiv">
				<label>专业</label>
				<span class="selectEle special"><?php echo ($infos["faculty"]); ?></span>
				<input type="hidden" name="faculty" id="faculty" value="<?php echo ($infos["faculty"]); ?>">
			</div>
			<div class="mui-input-row selectDiv">
				<label>班级</label>
				<span class="selectEle classes"><?php echo ($infos["member_class"]); ?></span>
				<input type="hidden" name="member_class" id="member_class" value="<?php echo ($infos["member_class"]); ?>">
			</div>
		</div>

		<div class="whiteBackground mt-15">
			<div class="mui-input-row">
				<label>身高cm</label>
				<input type="number" id="stature" name="stature" value="<?php echo ($infos["stature"]); ?>">
			</div>
			<div class="mui-input-row">
				<label>体重kg</label>
				<input type="number" id="weight" name="weight" value="<?php echo ($infos["weight"]); ?>">
			</div>
		</div>
		<button type="submit" class="bottomDiv save" style="width: 100%;">保存</button>
	</form>
	</div>
	<script src="home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="others/jquery.form.js"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script src="home/jscript/ajax.js" type="text/javascript"></script>
	<script type="text/javascript">
        var dateEle = $('.date')[0];
        dateEle.addEventListener('tap', function() {
            var _self = this;
            if(_self.picker) {
                _self.picker.show(function(rs) {
                    $(".date").text(rs.text);
                    $("#birthary_time").val(rs.text);
                    _self.picker.dispose();
                    _self.picker = null;
                });
            } else {
                var optionsJson = this.getAttribute('data-options') || '{}';
                var options = JSON.parse(optionsJson);

                _self.picker = new mui.DtPicker(options);
                _self.picker.show(function(rs) {
                    $(".date").text(rs.text);
                    $("#birthary_time").val(rs.text);
                    _self.picker.dispose();
                    _self.picker = null;
                });
            }
        })
        //班级
        var classes = new mui.PopPicker();
        var ban = '<?php echo ($ban); ?>';
        classes.setData(JSON.parse(ban));
        var classesButton = $('.classes')[0];
        classesButton.addEventListener('tap', function(event) {
            classes.show(function(items) {
                $(classesButton).text(items[0].text); //items[0].value
                $("#member_class").val(items[0].text)
            });
        }, false);
        //专业
        var special = new mui.PopPicker();
        var faculty = '<?php echo ($faculty); ?>';
        special.setData(JSON.parse(faculty));

        var specialButton = $('.special')[0];
        specialButton.addEventListener('tap', function(event) {
            special.show(function(items) {
                $(specialButton).text(items[0].text); //items[0].value
                $("#faculty").val(items[0].text);
                $('.classes').text('');
                $("#member_class").val('');

                var fac = items[0].value;
                $.ajax({
                    type:'post',
                    url:"<?php echo U('ban');?>",
                    data:{fac:fac},
                    success:function(data){
                        console.log(data);
                        //班级
                        //var classes = new mui.PopPicker();
                        ban = data;
                        classes.setData(ban);
                        var classesButton = $('.classes')[0];
                        classesButton.addEventListener('tap', function(event) {
                            classes.show(function(items) {
                                $(classesButton).text(items[0].text); //items[0].value
                                $("#member_class").val(items[0].text)
                            });
                        }, false);
                    }
                })
            });
        }, false);


		//改变性别
		$(".male").click(function(){
			$(this).find("img").attr("src","home/images/check_button_s.png");
			$(this).siblings("p").find("img").attr("src","home/images/check_button_uns.png");
			$("#member_list_sex").val(1);
		})
		$(".female").click(function(){
			$(this).find("img").attr("src","home/images/check_button_s.png");
			$(this).siblings("p").find("img").attr("src","home/images/check_button_uns.png");
            $("#member_list_sex").val(2);
		})
	</script>
</body>
</html>