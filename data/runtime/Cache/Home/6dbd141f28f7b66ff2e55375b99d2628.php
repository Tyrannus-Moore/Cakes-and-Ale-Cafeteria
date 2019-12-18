<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <base href="/public/home/" />
    <title>订单详情</title>
    <link rel="stylesheet" type="text/css" href="css/layer/default/layer.css" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <script src="js/jquery-2.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/public/newLayer/mobile/need/layer.css"/>
    <style>
        .sqtkBtn{
            color: #666666;
            border: 1px solid #666666;
        }

        .popuo-refund{width:70% !important;}
        .popuo-refund .layui-m-layercont{padding:20px 10px 10px;}
        .popuo-refund textarea{width:240px;}
		.orderDetailOrderInfoTitle{display: flex;justify-content: space-between;padding-right: 10px;}
    </style>
</head>

<body>
<div class="orderDetailMainBox">
    <div class="orderDetailTop bac-fff p-10">
        <div class="clearfix">
            <div class="orderDetailTopTitle fl"><?php echo ($infos["stall_name"]); ?></div>
            <?php if($infos["order_status"] == 1): ?><div class="c-zhu fr">待付款</div><?php endif; ?>
            <?php if($infos["order_status"] == 2 && $infos["refund"] == 1): ?><div class="c-zhu fr">退款申请中</div><?php endif; ?>
            <?php if($infos["order_status"] == 2 && $infos["refund"] == 0): ?><div class="c-zhu fr">备餐中</div><?php endif; ?>
            <?php if($infos["order_status"] == 3): ?><div class="c-zhu fr">待取餐</div><?php endif; ?>
            <?php if($infos["order_status"] == 4): ?><div class="c-zhu fr">配送中</div><?php endif; ?>
            <?php if($infos["order_status"] == 7): ?><div class="c-zhu fr">已取消</div><?php endif; ?>
            <?php if($infos["order_status"] == 5 or $infos["order_status"] == 6): ?><div class="c-zhu fr">已完成</div><?php endif; ?>
            <?php if($infos["order_status"] == 8): ?><div class="c-zhu fr">退款成功</div><?php endif; ?>
        </div>
        <a href="<?php echo U('Home/Stall/stallDetail',array('stall_id'=>$infos['stall_id']));?>"><div class="c99 font13">档口主页>></div></a>
        <div class="mt-20">
            <?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="orderDetailProList clearfix">
                    <img class="orderDetailProImg fl" src="<?php echo ($v["pic_url"]); ?>" />
                    <span class="psyorderCenterProTitle fl"><?php echo ($v["dishes_name"]); ?></span>
                    <div class="fl">
                        <span>×</span>
                        <span><?php echo ($v["dishes_nums"]); ?></span>
                    </div>
                    <div class="fr">
                        <span>￥</span>
                        <span><?php echo ($v["discount_price"]); ?></span>
                    </div>
                </div><?php endforeach; endif; ?>
        </div>
    </div>

    <div class="orderDetailPriceBox mt-15 bac-fff">
        <div class="orderDetailPriceList clearfix">
            <div class="fl">订单金额</div>
            <div class="fr">
                <span>￥</span>
                <span><?php echo ($infos["total_money"]); ?></span>
            </div>
        </div>
        <?php if(($infos["deliver_type"]) == "1"): ?><div class="orderDetailPriceList clearfix">
                <div class="fl">配送费</div>
                <div class="fr">
                    <span>￥</span>
                    <span><?php echo ($infos["express_money"]); ?></span>
                </div>
            </div><?php endif; ?>
        
        <div class="orderDetailPriceList clearfix">
            <div class="fl">赠送积分</div>
            <div class="fr"><?php echo ($infos["integral"]); ?></div>
        </div>
        <div class="orderDetailPriceList clearfix">
            <div class="fl">实付金额</div>
            <div class="fr c-zhu">
                <span>￥</span>
                <span><?php echo ($infos["real_money"]); ?></span>
            </div>
        </div>
        <div class="orderDetailPriceList clearfix" style="border-bottom: none">
            <div class="fl">备注:</div>
        </div>
        <?php if(empty($infos["order_note"])): ?><div class="clearfix" style="border-bottom: none; padding: 0px 0px 15px 10px;line-height: 25px">
                未填写
            </div>
            <?php else: ?>
            <div class="clearfix" style="border-bottom: none; padding: 0px 0px 15px 10px;line-height: 25px">
                <?php echo ($infos["order_note"]); ?>
            </div><?php endif; ?>
    </div>

     <!--配送-->
     <?php if($infos["deliver_type"] == 1): ?><!--配送信息-->
        <div class="sendInfoBox mt-15">
            <div class="orderDetailOrderInfoTitle">配送信息</div>
            <div class="orderDetailOrderInfoList clearfix bac-fff">
                <div class="fl c99 orderDetailOrderInfoText">配送时间</div>
                <div class="fl"><?php echo (date("Y-m-d H:i",$infos["express_time"])); ?></div>
            </div>
            <div class="orderDetailOrderInfoList clearfix bac-fff">
                <div class="c99 orderDetailOrderInfoText">配送地址</div>
                <div class="addrInfo">
                    <p><?php echo ($infos["username"]); ?> &nbsp;&nbsp;&nbsp; <?php echo ($infos["phone"]); ?></p>
                    <p><?php echo ($infos["address"]); ?></p>
                </div>
            </div>
        </div>
        <!--配送员-->
        <?php if($infos["order_status"] == 4 or $infos["order_status"] == 5 or $infos["order_status"] == 6): ?><div class="senderBox mt-15">
            <div class="orderDetailOrderInfoTitle">配送员</div>
            <div class="senderInfo clearfix">
                <div class="senderHeadImgBox fl">
                    <img src="<?php echo ($ps["member_list_headpic"]); ?>"/>
                </div>
                <div class="fl">
                    <p><?php echo ($ps["member_name"]); ?></p>
                    <p><?php echo ($ps["telphone"]); ?></p>
                </div>
                <a href="tel:<?php echo ($ps["telphone"]); ?>"><img class="call" src="images/icon_phone.png"/></a>
            </div>
        </div><?php endif; endif; ?>
     <!--自取-->
     <?php if($infos["deliver_type"] == 2): ?><!--取餐信息-->
         <div class="qcInfoBox mt-15">
             <div class="orderDetailOrderInfoTitle">
             	取餐信息          
	             <?php if($infos["order_status"] == 3): ?><span style="color: red;">请于<?php echo ($endTimes); ?>前取餐</span><?php endif; ?>
             </div>
             <?php if($infos["order_status"] == 3): ?><div class="orderDetailOrderInfoList clearfix bac-fff" style="color:blue;font-weight: bold;">
                 <div class="fl orderDetailOrderInfoText">取餐码</div>
                 <div class="fl"><?php echo ($list[0][meal_code]); ?></div>
             </div><?php endif; ?>
             <div class="orderDetailOrderInfoList clearfix bac-fff">
                 <div class="fl c99 orderDetailOrderInfoText">档口电话</div>
                 <div class="fl"><?php echo ($infos["stall_tel"]); ?></div>
             </div>
             <div class="orderDetailOrderInfoList clearfix bac-fff">
                 <div class="c99 orderDetailOrderInfoText">档口地址</div>
                 <div class="addrInfo">
                     <p><?php echo ($infos["stall_address"]); ?></p>
                 </div>
             </div>
         </div><?php endif; ?>

    <!--订单信息-->
    <div class="orderDetailOrderInfo mt-15">
        <div class="orderDetailOrderInfoTitle">订单信息</div>
        <!--div class="orderDetailOrderInfoList clearfix bac-fff" style="color:#333;font-weight: bold;"-->
        <div class="orderDetailOrderInfoList clearfix bac-fff">
            <div class="fl  orderDetailOrderInfoText">订单编号</div>
            <div class="fl" ><?php echo ($infos["order_no"]); ?></div>
        </div>
        <div class="orderDetailOrderInfoList clearfix bac-fff">
            <div class="fl c99 orderDetailOrderInfoText">下单时间</div>
            <div class="fl"><?php echo (date("Y-m-d H:i",$infos["create_time"])); ?></div>
        </div>
        <?php if($infos["order_status"] == 2 or $infos["order_status"] == 3 or $infos["order_status"] == 4 or $infos["order_status"] == 5 or $infos["order_status"] == 6): ?><div class="orderDetailOrderInfoList clearfix bac-fff">
                <div class="fl c99 orderDetailOrderInfoText">付款时间</div>
                <div class="fl"><?php echo (date("Y-m-d H:i",$infos["payment_time"])); ?></div>
            </div><?php endif; ?>
        <?php if($infos["order_status"] == 4): ?><div class="orderDetailOrderInfoList clearfix bac-fff">
            <div class="fl c99 orderDetailOrderInfoText">送达时间</div>
            <div class="fl"><?php echo (date("Y-m-d H:i",$infos["meals_time"])); ?></div>
        </div><?php endif; ?>
        <!--用户评价-->
        <?php if(($infos["order_status"]) == "6"): ?><div class="userEveBox mt-15">
                <div class="orderDetailOrderInfoTitle">用户评价</div>
                <div class="userEveListBox p-10 bac-fff">
                    <div class="userEveListTop clearfix">
                        <div class="userHeadImgBox fl">
                            <img class="userHeadImg" src="<?php echo ($eval["member_list_headpic"]); ?>"/>
                        </div>
                        <div class="userEveUserName fl"><?php echo ($eval["member_list_nickname"]); ?></div>
                        <div class="userEveTime c99 fr"><?php echo (date('Y-m-d H:i',$eval["addtime"])); ?></div>
                    </div>
                    <div class="xingBox clearfix mt-15">
                        <div class="fl c99">餐品评价</div>
                        <div class="clearfix fl ml-10">
                            <?php $__FOR_START_26601__=0;$__FOR_END_26601__=$eval["dish_score"];for($i=$__FOR_START_26601__;$i < $__FOR_END_26601__;$i+=1){ ?><img class="fl" src="images/star_icon24.png"/><?php } ?>
                            <?php $__FOR_START_27758__=0;$__FOR_END_27758__=$eval["edish_score"];for($i=$__FOR_START_27758__;$i < $__FOR_END_27758__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon25.png" /><?php } ?>
                        </div>
                    </div>
                    <div class="xingBox clearfix mt-5">
                        <div class="fl c99">服务评价</div>
                        <div class="clearfix fl ml-10">
                            <?php $__FOR_START_24335__=0;$__FOR_END_24335__=$eval["service_score"];for($i=$__FOR_START_24335__;$i < $__FOR_END_24335__;$i+=1){ ?><img class="fl" src="images/star_icon24.png"/><?php } ?>
                            <?php $__FOR_START_15260__=0;$__FOR_END_15260__=$eval["eservice_score"];for($i=$__FOR_START_15260__;$i < $__FOR_END_15260__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon25.png" /><?php } ?>
                        </div>
                    </div>
                    <div class="userEveCon mt-15"><?php echo ($eval["content"]); ?></div>
                    <div class="userEveImgBox mt-5">
                        <?php if(!empty($eval["image"])): if(is_array($eval["image"])): foreach($eval["image"] as $key=>$vo): ?><img class="userEveImg" src="<?php echo ($vo); ?>" /><?php endforeach; endif; endif; ?>
                    </div>
                    <?php if($infos['deliver_type'] == 1): ?><div class="xingBox clearfix mt-15">
                        <div class="fl c99">骑手评价</div>
                        <div class="clearfix fl ml-10">
                            <?php $__FOR_START_6565__=0;$__FOR_END_6565__=$eval["marki_score"];for($i=$__FOR_START_6565__;$i < $__FOR_END_6565__;$i+=1){ ?><img class="fl" src="images/star_icon24.png"/><?php } ?>
                            <?php $__FOR_START_24954__=0;$__FOR_END_24954__=$eval["emarki_score"];for($i=$__FOR_START_24954__;$i < $__FOR_END_24954__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon25.png" /><?php } ?>
                        </div>
                    </div>
                    <div class="userEveCon mt-15"><?php echo ($eval["marki_content"]); ?></div><?php endif; ?>
                </div>
            </div><?php endif; ?>
    </div>
</div>


<input type="hidden" id="orderIds" value="<?php echo ($infos["order_id"]); ?>" />
<?php if($infos["order_status"] == 1 or $infos["order_status"] == 2 or $infos["order_status"] == 4 or $infos["order_status"] == 5 or $infos["order_status"] == 6): if($infos["order_status"] == 1): ?><div class="orderDetailBottBtnBox">
        <div class="fl c99 qcmBox font12">
            <span>支付剩余</span>
            <!--<h1><strong id="RemainH">XX</strong>:<strong id="RemainM">XX</strong>:<strong id="RemainS">XX</strong></h1>-->
            <input type="hidden" id="timeT" value="<?php echo ($timeT); ?>" />
            <input type="hidden" id="oOrder" value="<?php echo ($order_id); ?>" />
            <span><strong id="RemainM"></strong>分<strong id="RemainS"></strong>秒</span>
        </div>
        <button class="fr orderCenterListBtn paynow" type="button" maInfo="<?php echo ($maInfo['is_yy']); ?>" style="background: white;color: #e04852;border: 1px solid #e04852;margin-left: 10px;">去付款</button>
        <input type="hidden" class="startTime" value="<?php echo (date('H:i',$maInfo['start_time'])); ?>">
        <input type="hidden" class="endTime" value="<?php echo (date('H:i',$maInfo['end_time'])); ?>">
        <button class="fr orderCenterListBtn lxkhBtn" type="button" style="background: white">取消</button>
        </div><?php endif; ?>
    <?php if($infos["order_status"] == 4): ?><div class="orderDetailBottBtnBox">
        <button class="fr orderCenterListBtn bcwcBtn" type="button" style="background: white">已送达</button>
        </div><?php endif; ?>
    <?php if($infos["order_status"] == 2 && $infos["refund"] == 0): ?><div class="orderDetailBottBtnBox">
        <button class="fr orderCenterListBtn sqtkBtn" type="button" style="background: white">申请退款</button>
        </div><?php endif; ?>
    <?php if($infos["order_status"] == 5): ?><div class="orderDetailBottBtnBox">
        <a href="<?php echo U('Home/Myorder/evaluation',array('order_id'=>$infos['order_id'],'type'=>1));?>">
            <button class="fr orderCenterListBtn" type="button" style="background: white;color: #e04852;border: 1px solid #e04852;margin-left: 10px;">去评价</button>
        </a>
        </div><?php endif; ?>
    <?php if($infos["order_status"] == 6): ?><div class="orderDetailBottBtnBox">
            <button class="fr orderCenterListBtn" type="button" style="background: white;color: #e04852;border: 1px solid #e04852;margin-left: 10px;">已评价</button>
        </div><?php endif; endif; ?>
<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/newLayer/mobile/layer.js" type="text/javascript" charset="utf-8"></script>
<script>
    // $(function(){
    //     //清空浏览器历史记录
    //     //pushHistory();
    //     //监听浏览器后退事件
    //     window.addEventListener("popstate",
    //         function(e) {
    //             //转向指定的URL
    //             location.href = '/index.php/Home/Myorder/index';
    //         }, false);
    //     //清空浏览器历史记录
    //     /*function pushHistory() {
    //         var url = "#";
    //         var state = {
    //             title: "title",
    //             url: "#"
    //         };
    //         window.history.pushState(state, "title", "#");
    //     }*/
    // });
    var userEveImgBox = $(".userEveImgBox");
    for(var i = 0; i < userEveImgBox.length; i++){
        var size = $(userEveImgBox[i]).find("Img").size();
        var s = 6 - size%6
        var str = '';
        for(var j = 0; j < s; j++){
            str += '<div style="width:50px;margin-right:5px"></div>'
        }
        $(userEveImgBox[i]).append(str)
    };
</script>
<script>
    var runtimes = 0;
    function GetRTime(){
        var timeT = $("#timeT").val();
        if(timeT !== undefined){
            var order_id = $("#oOrder").val();
            var nMS = timeT*1000-runtimes*1000;
            var nM=Math.floor(nMS/(1000*60)) % 60;
            var nS=Math.floor(nMS/1000) % 60;
            document.getElementById("RemainM").innerHTML=nM;
            document.getElementById("RemainS").innerHTML=nS;
            if(nMS==0)
            {
                $.ajax({
                    type:'post',
                    url:"<?php echo U('qxOrder');?>",
                    data:{order_id:order_id},
                    success:function(data){
                        window.location.reload();
                    }
                });
                return false;
            }
            runtimes++;
            setTimeout("GetRTime()",1000);
        }
    }
    window.onload=GetRTime;
</script>
<script>
    //申请退款
    $(".sqtkBtn").click(function () {
        var orderIds = $("#orderIds").val();
        layer.open({
            className: 'popuo-refund',
            content: '<p>申请退款,请填写原因</p><textarea id="textareaText" style="width: 100%;height: 100px"></textarea>'
            //content:str
            ,btn: ['确定', '取消']
            ,yes: function(index){
                if($("#textareaText").val() == ''){
                    layer.open({
                        content: '退款原因不能为空'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                        ,type:1
                    });
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: "<?php echo U('Home/Myorder/refundOrder');?>",
                    data: {order_id:orderIds,user_refuse_reason:$("#textareaText").val()},
                    success: function (data) {
                        if(data.status == 1){
                            layer.open({
                                content: data.info
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                                ,end:function () {
                                    window.location.href = data.url;
                                }
                            });
                        }else{
                            layer.open({
                                content: data.info
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                                ,type:1
                                ,end: function() {
                                    //$(this).attr('disable','false');
                                    //layer.close(index);
                                }
                            });
                        }
                    }
                });
                layer.close(index);
            }
        });
        /*layer.prompt({title: '申请退款,请填写原因', formType: 2}, function(pass, index){
            $.ajax({
                type: 'post',
                url: "<?php echo U('Home/Myorder/refundOrder');?>",
                data: {order_id:orderIds,user_refuse_reason:pass},
                success: function (data) {
                    if(data.status == 1){
                        layer.open({
                            content: data.info
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                            ,end:function () {
                                window.location.href = data.url;
                            }
                        });
                    }else{
                        layer.open({
                            content: data.info
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                            ,end: function() {
                                $(this).attr('disable','false');
                                layer.close(index);
                            }
                        });
                    }
                }
            })
        });*/
    });
    // 取消
    $(".lxkhBtn").click(function () {
        $(this).attr('disable','true');
        var orderIds = $("#orderIds").val();
        layer.open({
            content: '您确定要取消订单吗？'
            ,btn: ['确定', '不要']
            ,yes: function(index){
                $.ajax({
                        url:"<?php echo U('Home/Myorder/qxOrder');?>",
                        data:{
                            order_id:orderIds
                    },
                    success:function(data) {
                        // console.log(data);return false
                        if(data.code == 1) {
                            layer.open({
                                type: 0,
                                time: 1,
                                content: data.info,
                                skin: 'msg'
                            });
                            return false;
                            $(this).attr('disable','false');
                        }else{
                            layer.open({
                                type: 0,
                                time: 1,
                                content: '取消成功！',
                                skin: 'msg',
                                end:function(){
                                    window.location.reload();
                                }
                            });
                        }
                    }
                });
            }
        });
    });
    // 送达
    $(".bcwcBtn").click(function () {
        $(this).attr('disable','true');
        var orderIds = $("#orderIds").val();
        layer.open({
            content: '确定订单已送达？'
            ,btn: ['确定', '不要']
            ,yes: function(index){
                $.ajax({
                    url:"<?php echo U('Home/Myorder/sdOrder');?>",
                    data:{
                        order_id:orderIds
                    },
                    success:function(data) {
                        if (data.code == 1) {
                            layer.open({
                                type: 0,
                                time: 1,
                                content: data.info,
                                skin: 'msg'
                            });
                            return false;
                            $(this).attr('disable', 'false');
                        } else {
                            layer.open({
                                type: 0,
                                time: 1,
                                content: '收货成功！',
                                skin: 'msg',
                                end: function () {
                                    window.location.reload();
                                }
                            });
                        }
                    }
                })
            }
        });
    });
    // 付款
    $(".paynow").click(function () {
        var maInfo = $(this).attr('maInfo');
        var startTime = $(".startTime").val();
        var endTime = $(".endTime").val();
        var orderIds = $("#orderIds").val();
        if(maInfo != 1) {
            //询问框
            layer.open({
                content: '餐厅已打烊！餐厅营业时间为：'+startTime+'～'+endTime+'！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }else{
            $.ajax({
                type: 'post',
                url: "<?php echo U('Home/Myorder/passOrder');?>",
                data: {order_id:orderIds},
                success: function (data) {
                    if (data.code == 1) {
                        layer.open({
                            type: 0,
                            time: 1,
                            content: data.info,
                            skin: 'msg'
                        });
                        return false;
                    } else {
                        window.location.href = data.url;
                        /*layer.open({
                            type: 0,
                            time: 1,
                            content: data.info,
                            skin: 'msg',
                            end: function () {
                                window.location.href = data.url;
                            }
                        });*/
                    }
                }
                //window.location.href = "/index.php/Home/Stall/payNow/order_id/"+id;
            });
            //window.location.href = "<?php echo U('Home/Stall/payNow',array('order_id'=>$infos['order_id']));?>";
        }
    })
</script>
</body>
</html>