<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>编辑地址</title>
	<base href="/public/"/>
	<link rel="stylesheet" href="home/css/mui.min.css" />
	<link rel="stylesheet" type="text/css" href="home/css/common.css" />
	<link href="home/css/mui.picker.min.css" rel="stylesheet" />
	<link href="home/css/mui.poppicker.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="layer/layermobile/need/layer.css"/>
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
	.mui-input-row,.addressDiv{background: #fff;}

	.selectDiv {
		display: flex;
		justify-content: space-between;
		padding-right: 15px;
	}

	.selectEle {
		flex:1;
		height: 40px;
		display: inline-block;
		line-height: 40px;
		text-align: right;
	}

	.addressDiv p{padding: 11px 15px;}
	.addressDiv textarea{padding: 5px 15px !important;}
	/*底部按钮*/
	.bottomDiv{margin:2rem auto 0;border-radius: 0.2rem;background-color:#e04852;color: #fff;width: 90%;height: 2.5rem;display: flex;justify-content: center;align-items: center;}
</style>
<style type="text/css">

#container{
	width: 100%;
    height: 300px;
}
</style>
<body onload="init()">
	<div class="mui-content">
		<form class="mui-input-group addressForm" name="form0" method="post" action="<?php echo U('address_edit');?>"  enctype="multipart/form-data">
			<input type="hidden" name="address_id" id="address_id" value="<?php echo ($infos["address_id"]); ?>" required="required"/>
			<div class="mui-input-row">
				<label>姓名</label>
				<input type="text" maxlength="4" name="name" id="name" class="mui-input-clear" value="<?php echo ($infos["name"]); ?>" placeholder="请输入姓名">
			</div>

			<div class="mui-input-row">
				<label>手机号</label>
				<input type="tel" maxlength="11" class="mui-input-clear" name="phone" id="phone" value="<?php echo ($infos["phone"]); ?>" placeholder="请输入手机号">
			</div>

			<div class="mui-input-row selectDiv">
				<label>服务地址</label>
				<span class="selectEle" id="city"><?php echo ($infos["proviceid"]); ?>&nbsp;<?php echo ($infos["cityid"]); ?>&nbsp;<?php echo ($infos["countyid"]); ?></span>
				<input type="hidden" name="cityInfo" id="cityInfo" value="<?php echo ($infos["proviceid"]); echo ($infos["cityid"]); echo ($infos["countyid"]); ?>" required="required"/>
				<input type="hidden" name="proviceid" id="proviceid" value="<?php echo ($infos["proviceid"]); ?>" required="required"/>
				<input type="hidden" name="cityid" id="cityid" value="<?php echo ($infos["cityid"]); ?>" required="required"/>
				<input type="hidden" name="countyid" id="countyid" value="<?php echo ($infos["countyid"]); ?>" required="required"/>
				<input type="hidden" name="lat" id="lat" value="<?php echo ($infos["latitude"]); ?>" required="required"/>
				<input type="hidden" name="lng" id="lng" value="<?php echo ($infos["longitude"]); ?>" required="required"/>
			</div>
			<div class="addressDiv">
				<p>详细地址</p>
				<textarea name="address" rows="" cols="20" maxlength="30" placeholder="街道/小区/门牌号" id="addressBind" required><?php echo ($infos["address"]); ?></textarea>
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
        //获取经纬度
        var latitude = "<?php echo ($infos["latitude"]); ?>";
		var longitude = "<?php echo ($infos["longitude"]); ?>";
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
  //       $('#addressBind').bind('input propertychange', function() {
  //           var address = $("#cityInfo").val();
  //           var address1 = $("#addressBind").val();
  //           geocoder.getLocation(address+address1);
  //       });

		// /*地图*/
  //       var init = function() {
  //           var callbacks = {
  //               //若服务请求成功，则运行以下函数，并将结果传入
  //               complete: function(result) {
  //                   var latlng = result.detail.location;         //这里就能拿到经纬度了
  //                   $("#lat").val(latlng.lat);
  //                   $("#lng").val(latlng.lng);
  //                   //alert(latlng);
  //                   //console.log(latlng.lat);
  //               },
  //               //若服务请求失败，则运行以下函数
  //               error: function() {
  //                   console.log(latlng);
  //                   // alert("服务器出错了");
  //               }
  //           };
  //           //创建类实例
  //           geocoder = new qq.maps.Geocoder(callbacks);           //body加载的时候实例化对象
  //       };
		/*地图结束*/


		var _getParam = function(obj, param) {
			return obj[param] || '';
		};

		var cityPicker3 = new mui.PopPicker({
			layer: 3
		});
		// cityPicker3.setData(cityData3);
		$.ajax({
			type:"get",
			url:"<?php echo U('get_address');?>",
			success:function (res) {
				cityPicker3.setData(res.data);
			}
		})
		var showCityPickerButton = document.getElementById('city');

		showCityPickerButton.addEventListener('tap', function(event) {
			cityPicker3.show(function(items) {
				$("#city").text(_getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text'));
                //$("#cityInfo").val(_getParam(items[0], 'text')+_getParam(items[1], 'text')+_getParam(items[2], 'text'));
				$("#proviceid").val(_getParam(items[0], 'value'));
				$("#cityid").val(_getParam(items[1], 'value'));
				$("#countyid").val(_getParam(items[2], 'value'));
				// var address = $("#cityInfo").val();
                // var address1 = $("#addressBind").val();
                // geocoder.getLocation(address+address1);
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		}, false);
	</script>
</body>
</html>