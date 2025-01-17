<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>评价</title>
    <link rel="stylesheet" type="text/css" href="/public/home/css/common.css" />
    <link rel="stylesheet" type="text/css" href="/public/home/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/public/layer/layermobile/need/layer.css"/>
</head>
<body>
<form class="ajaxForm" name="form0" method="post"  action="<?php echo U('evaluationSub');?>">
<input type="hidden" id="order_id" value="<?php echo ($list["order_id"]); ?>" name="order_id">
<input type="hidden" id="ca_id" value="<?php echo ($list["ma_id"]); ?>" name="ma_id">
<input type="hidden" id="stall_id" value="<?php echo ($list["stall_id"]); ?>" name="stall_id">
<input type="hidden" id="productsXing" value="1" name="productsXing">
<input type="hidden" id="serviceXing" value="1" name="serviceXing">
<input type="hidden" id="files" value="" name="files">
<?php if($type == 1): ?><input type="hidden" id="ps_id" value="<?php echo ($list["ps_id"]); ?>" name="ps_id">
<input type="hidden" id="eveSenderEveXing" value="1" name="eveSenderEveXing">
<div class="eveSenderEveBox bac-fff">
    <div class="eveSenderEveTop clearfix">
        <div class="eveSenderEveHeadImgBox fl">
            <img src="<?php echo ($list["member_list_headpic"]); ?>" />
        </div>
        <div class="fl eveSenderEveName">
            <span class="fontw font16 c00"><?php echo ($list["member_name"]); ?></span>
            <span class="font12">(配送员)</span>
        </div>
    </div>
    <div>
        <div class="eveSenderEveXingBox">
            <img src="/public/home/images/star_icon24.png" />
            <img src="/public/home/images/star_icon25.png" />
            <img src="/public/home/images/star_icon25.png" />
            <img src="/public/home/images/star_icon25.png" />
            <img src="/public/home/images/star_icon25.png" />
        </div>
        <div class="text-c c99 font12 mt-10">点星评分</div>
    </div>
    <div class="eveSenderEveTextBox mt-20">
        <textarea class="eveSenderEveText" name="markiText" rows="" cols="" placeholder="说点什么吧~"></textarea>
    </div>
</div><?php endif; ?>
<div class="eveShopEveBox bac-fff mt-15 p-10">
    <div class="eveShopEveTop clearfix">
        <img class="eveShopEveTopImg fl" src="<?php echo ($list["image"]); ?>" />
        <div class="fl eveShopEveName">
            <span class="fontw font16 c00"><?php echo ($list["stall_name"]); ?></span>
        </div>
    </div>
    <div class="xingBox clearfix mt-15">
        <div class="fl">餐品评价</div>
        <div class="clearfix fr ml-10 productsXing">
            <img class="fl" src="/public/home/images/star_icon24.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
        </div>
    </div>
    <div class="xingBox clearfix mt-5">
        <div class="fl">服务评价</div>
        <div class="clearfix fr ml-10 serviceXing">
            <img class="fl" src="/public/home/images/star_icon24.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
            <img class="fl" src="/public/home/images/star_icon25.png" />
        </div>
    </div>
    <div class="eveSenderEveTextBox mt-20">
        <textarea class="eveSenderEveText" name="stallText" rows="" cols="" placeholder="说点什么吧~"></textarea>
    </div>

    <div class="uploadImgBox mt-10">
        <div class="uploadImgImgBox hideUploadImgBox">
            <img class="uploadImgImg" src="/public/home/images/icon_img.png"/>
            <input class="uploadImgImgFile" onchange="xmTanUploadImg1(this)" type="file" value="" name="" id="" />
        </div>
    </div>
    <input class="defaultBtn loginBtn" style="margin: 20px auto;width: 100%;height:40px;" id="loginSum" type="submit" value="提交" />

</div>
</form>
<script src="/public/home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/home/js/mainfile.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/others/jquery.form.js"></script>
<script src="/public/layer/layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/home/jscript/pass.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        //清空浏览器历史记录
        pushHistory();
        //监听浏览器后退事件
        window.addEventListener("popstate",
            function(e) {
                //转向指定的URL
                location.href = '/index.php/Home/Myorder/index';
            }, false);
        //清空浏览器历史记录
        function pushHistory() {
            var url = "#";
            var state = {
                title: "title",
                url: "#"
            };
            window.history.pushState(state, "title", "#");
        }
    });
 var width = $(".uploadImgImgBox").width();
 $(".uploadImgImgBox ").height(width + "px")
</script>
</body>

</html>