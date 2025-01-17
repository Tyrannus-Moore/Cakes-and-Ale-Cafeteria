<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>添加地址</title>
	<base href="/public/"/>
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="home/css/common.css" />
	<link href="home/css/mui.picker.min.css" rel="stylesheet" />
	<link href="home/css/mui.poppicker.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
	<script src="home/js/rem.js"></script>
	<script src="home/js/mui.min.js"></script>
	<script src="home/js/mui.picker.min.js"></script>
	<script src="home/js/mui.poppicker.js"></script>
	<script src="home/js/jquery-2.2.1.min.js"></script>
	<script src="home/js/city.data-3.js" type="text/javascript" charset="utf-8"></script>
</head>
<style>
	.mui-input-group {
		background: transparent;
	}
			.mui-input-row,
		.addressDiv {
			background: #fff;
		}
		
		.selectDiv {
			display: flex;
			justify-content: space-between;
			padding-right: 15px;
		}
		
		.selectEle {
			flex: 1;
			height: 40px;
			display: inline-block;
			line-height: 40px;
			text-align: right;
		}
		
		.addressDiv p {
			padding: 0.11rem 15px;font-size: 0.3rem;
			color: #333;
		}
		.mui-input-group .mui-input-row:after{left:0px;}
		.mui-input-group label{
			font-size: 0.3rem;
			color: #333;
		}
		
		input::-webkit-input-placeholder {
			/* WebKit browsers */
			color: #ccc;
			font-size: 0.3rem;
		}
		
		input:-moz-placeholder {
			/* Mozilla Firefox 4 to 18 */
			color: #ccc;
			font-size: 0.3rem;
		}
		
		input::-moz-placeholder {
			/* Mozilla Firefox 19+ */
			color: #ccc;
			font-size: 0.3rem;
		}
		
		:-ms-input-placeholder {
			/* Internet Explorer 10+ */
			color: #ccc;
			font-size: 0.3rem;
		}
		/*底部按钮*/
		
		.bottomDiv {
			margin: 2rem auto 0;
			border-radius: 0.2rem;
			background-color: #e04852;
			color: #fff;
			width: 90%;
			height: 0.88rem;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		
		textarea{padding: 5px 15px;font-size:0.3rem;}
		.mui-input-row input{padding:11px 15px;font-size:0.3rem;}

</style>
<style type="text/css">
#container{
	width: 100%;
    height: 300px;
}
</style>
<body onload="init()">
	<div class="mui-content">
		<form class="mui-input-group addressForm" name="form0" method="post" action="<?php echo U('address_add');?>"  enctype="multipart/form-data">
			<input type="hidden" name="stall_id" value="<?php echo ($stall_id); ?>" required="required"/>
			<div class="mui-input-row">
				<label>姓名</label>
				<input type="text" maxlength="4" name="name" id="name" class="mui-input-clear" placeholder="输入姓名">
			</div>

			<div class="mui-input-row">
				<label>手机号</label>
				<input type="tel" maxlength="11" class="mui-input-clear" name="phone" id="phone" placeholder="输入手机号">
			</div>

			<div class="mui-input-row selectDiv">
				<label>服务地址</label>
				<span class="selectEle" id="city"></span>
				<input type="hidden" name="proviceid" id="proviceid" required="required"/>
				<input type="hidden" name="cityid" id="cityid" required="required"/>
				<input type="hidden" name="countyid" id="countyid" required="required"/>
				<input type="hidden" name="lat" id="lat" required="required"/>
				<input type="hidden" name="lng" id="lng" required="required"/>
			</div>

			<div class="addressDiv">
				<p>详细地址</p>
				<textarea name="address" rows="" cols="20" maxlength="30" placeholder="街道/小区/门牌号" id="addressBind" required></textarea>
			</div>
			<div id="container"></div>
			<button type="submit" class="bottomDiv save">
				保存
			</button>
		</form>
	</div>
	<script src="others/jquery.form.js"></script>
	<script src="layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
	<script src="home/jscript/ajax.js" type="text/javascript"></script>
	<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=233BZ-4T5W4-3NSUR-XRSVQ-YNYXQ-6ZF4D"></script>
	<script type="text/javascript">
		
		var latitude = "<?php echo ($infos["latitude"]); ?>";
		var longitude = "<?php echo ($infos["longitude"]); ?>";
		//经纬度
		function init() {
		    var map = new qq.maps.Map(document.getElementById("container"),{
		        center: new qq.maps.LatLng(latitude,longitude),
		        zoom: 13,
		        zoomControl: false,
		    });
			
		    //添加监听事件  获取鼠标点击事件
		    var listener = qq.maps.event.addListener(
			    map,
			    'click',
			    function() {

			    }
			);

			 //添加监听事件   获取鼠标单击事件
		    qq.maps.event.addListener(map, 'click', function(event) {
		    	var lat = event.latLng.getLat();
		    	var lng = event.latLng.getLng();
		    	$('#lat').val(lat);
		    	$('#lng').val(lng);
		        var marker=new qq.maps.Marker({
		                position:event.latLng, 
		                map:map
		        });    
		      	qq.maps.event.addListener(map, 'click', function(event) {
		            marker.setMap(null);      
		    	});
		    });

		}
		
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};

		var cityPicker3 = new mui.PopPicker({
			layer: 3
		});
		$.ajax({
			type:"get",
			url:"<?php echo U('get_address');?>",
			success:function (res) {
				cityPicker3.setData(res.data);
			}
		})
		// cityPicker3.setData(cityData3);
		var showCityPickerButton = document.getElementById('city');

		showCityPickerButton.addEventListener('tap', function(event) {
			cityPicker3.show(function(items) {
				console.log(items);
				$("#city").text(_getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text'));
				$("#proviceid").val(_getParam(items[0], 'value'));
				$("#cityid").val(_getParam(items[1], 'value'));
				$("#countyid").val(_getParam(items[2], 'value'));

				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		}, false);
	</script>
</body>
</html>