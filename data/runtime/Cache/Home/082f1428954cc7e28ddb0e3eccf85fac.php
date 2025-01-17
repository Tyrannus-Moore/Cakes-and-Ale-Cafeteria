<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>确认支付</title>
    <base href="/public/home/">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <link rel="stylesheet" type="text/css" href="css/layout.css"/>
</head>
<body class="bac-fff">
<div class="payTop clearfix p-10 bac-fff">
    <div class="fl" style="line-height: 33px;">支付金额</div>
    <div class="fr c-zhu">
        <span class="">￥</span>
        <span class="font25"><?php echo ($infos["real_money"]); ?></span>
    </div>
</div>
<div class="jiangek"></div>
<div class="zffsBox p-10">
    <div class="c99">支付方式</div>
    <div class="clearfix wxzfBox">
        <img class="fl zfImg" src="images/icon_wx.png"/>
        <div class="fl">微信钱包</div>
        <img class="fr zfSelImg" src="images/icon_s.png"/>
    </div>
</div>
<button class="psyTjBtn weiXinButton"onclick="callpay()" style="margin-top: 100px;" type="button">去支付</button>
<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    sessionStorage.clear();
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
    //自适应
    (function (doc, win) {
        var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function () {
                var clientWidth = docEl.clientWidth;
                if (!clientWidth) return;
                docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
            };

        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })
    (document, window);
    function cancel(){
        window.location.href="/index.php/Home/Myorder/index"
    }

    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo ($jsApiParameters); ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                // alert(res);return false
                var state = 0;
                if(res.err_msg=='get_brand_wcpay_request:ok'){
                    state = 1;
                    // alert('支付成功！');
                }
                else
                {
                    //state = 0;
                    // alert('支付失败！');

                }
                if(state === 1)
                {
                    setTimeout("location.href = '<?php echo ($success_returnUrl); ?>'",1);
                }
                else
                {
                    setTimeout("location.href = '<?php echo ($error_returnUrl); ?>'",1);
                }
            }
        );
    }

    function callpay(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }
            else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }
        else
        {
            jsApiCall();
        }
    }
</script>
</body>
</html>