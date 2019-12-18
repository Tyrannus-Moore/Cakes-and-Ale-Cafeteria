<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>我的订单</title>
    <link rel="stylesheet" type="text/css" href="/public/home/css/common.css" />
    <link rel="stylesheet" type="text/css" href="/public/home/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/public/newLayer/mobile/need/layer.css"/>
</head>
<body>
<div class="myOrderTopBox">
    <div class="clearfix myOrderTopTab">
        <div class="fl myOrderType myOrderTypeSel">普通订单</div>
        <div class="fl myOrderType">周餐订单</div>
    </div>
</div>
<!--普通-->
<div class="myOrderListBox mt-15">
    <?php if(is_array($orderData)): $i = 0; $__LIST__ = $orderData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['order_type'] == 1): ?><div class="myOrderList bac-fff mb-15">
                <div class="myOrderListTop clearfix">
                    <img class="myOrderListImg fl" src="<?php echo ($v["image"]); ?>" />
                    <div class="myOrderListName fl"><?php echo ($v["stall_name"]); ?></div>
                    <div class="myOrderListStauts fr c66">
                        <?php if(($v["order_status"]) == "1"): ?>待付款<?php endif; ?>
                        <?php if(($v["order_status"]) == "2"): if(($v["refund"]) == "1"): ?>退款申请中
                            <?php else: ?>备餐中<?php endif; endif; ?>
                        <?php if(($v["order_status"]) == "3"): ?>待取餐<?php endif; ?>
                        <?php if(($v["order_status"]) == "4"): ?>配送中<?php endif; ?>
                        <?php if(($v["order_status"]) == "5"): ?>已完成<?php endif; ?>
                        <?php if(($v["order_status"]) == "6"): ?>已完成<?php endif; ?>
                        <?php if(($v["order_status"]) == "7"): ?>已取消<?php endif; ?>
                        <?php if(($v["order_status"]) == "8"): ?>退款成功<?php endif; ?>
                    </div>
                </div>
                <a href="<?php echo U('Home/Myorder/orderDetail',array('order_id'=>$v[order_id]));?>">
                <div class="bor mt-5 pt-5 pb-5">
                    <?php if(is_array($v["goodsData"])): $i = 0; $__LIST__ = $v["goodsData"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="orderDetailProList clearfix" style="margin: 0;line-height: 25px;padding: 0 10px;">
                            <span class="psyorderCenterProTitle fl c66"><?php echo ($vo["dishes_name"]); ?></span>
                            <div class="fl">
                                <span>×</span>
                                <span><?php echo ($vo["dishes_nums"]); ?></span>
                            </div>
                            <div class="fr">
                                <span>￥</span>
                                <span><?php echo ($vo["discount_price"]); ?></span>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                </a>

                <?php if(($v["order_status"]) == "1"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl" style="margin-top: 5px;">实付金额</div>
                        <div class="fl c-zhu" style="margin-top: 5px;">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                        <input class="fr orderCenterListBtn bcwcBtn paynow" type="button" maInfo="<?php echo ($maInfo['is_yy']); ?>" id="<?php echo ($v["order_id"]); ?>" value="去付款" />
                        <input type="hidden" class="startTime" value="<?php echo (date('H:i',$maInfo['start_time'])); ?>">
                        <input type="hidden" class="endTime" value="<?php echo (date('H:i',$maInfo['end_time'])); ?>">
                        <input class="fr orderCenterListBtn lxkhBtn" type="button" onclick="cancelOrder(<?php echo ($v["order_id"]); ?>)" value="取消" />
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "2"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl">实付金额</div>
                        <div class="fl c-zhu">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                        <?php if(($v["refund"]) == "0"): ?><input class="fr orderCenterListBtn lxkhBtn" type="button" onclick="refundOrder(<?php echo ($v["order_id"]); ?>)" value="申请退款" /><?php endif; ?>
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "3"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl">实付金额</div>
                        <div class="fl c-zhu">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "8"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl">实付金额</div>
                        <div class="fl c-zhu">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "7"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl">实付金额</div>
                        <div class="fl c-zhu">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "4"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl" style="margin-top: 5px;">实付金额</div>
                        <div class="fl c-zhu" style="margin-top: 5px;">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                        <input class="fr orderCenterListBtn bcwcBtn" type="button" onclick="deliveryOrder(<?php echo ($v["order_id"]); ?>)" value="已送达" />
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "5"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl" style="margin-top: 5px;">实付金额</div>
                        <div class="fl c-zhu" style="margin-top: 5px;">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                        <input class="fr orderCenterListBtn bcwcBtn" type="button" onclick="evaluationOrder(<?php echo ($v["order_id"]); ?>)" value="去评价" />
                    </div><?php endif; ?>
                <?php if(($v["order_status"]) == "6"): ?><div class="orderCenterListBtnBox clearfix">
                        <div class="fl" style="margin-top: 5px;">实付金额</div>
                        <div class="fl c-zhu" style="margin-top: 5px;">
                            <span>￥</span>
                            <span><?php echo ($v["real_money"]); ?></span>
                        </div>
                        <input class="fr orderCenterListBtn lxkhBtn" type="button" value="已评价" />
                    </div><?php endif; ?>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<!--周餐-->
<div class="myOrderListBox mt-15 dis-n">
    <?php if(is_array($orderData)): $i = 0; $__LIST__ = $orderData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['order_type'] == 2): ?><div class="myOrderList bac-fff mb-15">
                <div class="myOrderListTop clearfix">
                    <img class="myOrderListImg fl" src="<?php echo ($v["image"]); ?>" />
                    <div class="myOrderListName fl"><?php echo ($v["stall_name"]); ?></div>
                    <div class="myOrderListStauts fr c66">
                        <?php if(($v["order_status"]) == "1"): ?><div style="color: red;">待付款</div><?php endif; ?>
                        <?php if(($v["order_status"]) == "2"): if(($v["refund"]) == "1"): ?><div>退款申请中</div>
                                <?php else: ?><div>已支付</div><?php endif; endif; ?>
                        <?php if(($v["order_status"]) == "4"): ?><div style="color: green;">已生效</div><?php endif; ?>
                        <?php if(($v["order_status"]) == "5"): ?><div>已完成</div><?php endif; ?>
                        <?php if(($v["order_status"]) == "6"): ?><div>已完成</div><?php endif; ?>
                        <?php if(($v["order_status"]) == "7"): ?><div>已取消</div><?php endif; ?>
                        <?php if(($v["order_status"]) == "8"): ?><div>退款成功</div><?php endif; ?>
                    </div>
                </div>
                <a href="<?php echo U('orderDetails',array('order_id'=>$v[order_id]));?>">
                <div class="bor mt-5 pt-5 pb-5">
                    <div class="clearfix">
                        <div class="tcTitle p-10 fl">
                            <?php if(($v["weekTypeTwo"]) == "1"): ?>周一 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "2"): ?>周二 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "3"): ?>周三 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "4"): ?>周四 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "5"): ?>周五 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "6"): ?>周六 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                            <?php if(($v["weekTypeTwo"]) == "7"): ?>周日 <?php switch($v["diningTypeTwo"]): case "1": ?>早餐<?php break; case "2": ?>午餐<?php break; case "3": ?>晚餐<?php break; endswitch; endif; ?>
                        </div>
                        <!--<div class="c-zhu fr" style="padding-right: 10px;line-height: 39px;">
                            <?php if(($v["state"]) == "1"): ?>备餐中<?php endif; ?>
                            <?php if(($v["state"]) == "2"): ?>待取餐<?php endif; ?>
                            <?php if(($v["state"]) == "3"): ?>已完成<?php endif; ?>
                            <?php if(($v["state"]) == "4"): ?>已取消<?php endif; ?>
                            <?php if(($v["state"]) == "5"): ?>待付款<?php endif; ?>
                            <?php if(($v["state"]) == "6"): ?>已付款<?php endif; ?>
                        </div>-->
                    </div>
                    <?php if(is_array($v["goodsData"])): $i = 0; $__LIST__ = $v["goodsData"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="orderDetailProList clearfix" style="margin: 0;line-height: 25px;padding: 0 10px;">
                            <span class="psyorderCenterProTitle fl c66"><?php echo ($vo["dishes_name"]); ?></span>
                            <div class="fr c99">
                                <span>×</span>
                                <span><?php echo ($vo["dishes_nums"]); ?></span>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                </a>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>
<div style="margin-top: 65px;"></div>
<ul class="footer" id="nav">
    <li>
        <a href="<?php echo U('Drink/index');?>" id="order">
            <span class="mui-icon order"></span>
            <span class="mui-tab-label">点餐</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Integral/shop');?>" id="shop">
            <span class="mui-icon shop"></span>
            <span class="mui-tab-label">积分商城</span>
        </a>
    </li>
    <?php if(($infos["status"]) == "2"): ?><li>
            <a href="<?php echo U('Clerk/task');?>" id="task">
                <span class="mui-icon task"></span>
                <span class="mui-tab-label">我的任务</span>
            </a>
        </li><?php endif; ?>
    <li>
        <a href="<?php echo U('Myorder/index');?>" id="myOrder" class="active">
            <span class="mui-icon myOrder active"></span>
            <span class="mui-tab-label">我的订单</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Personal/index');?>" id="mine" >
            <span class="mui-icon mine "></span>
            <span class="mui-tab-label">我的</span>
        </a>
    </li>
</ul>
<script src="/public/home/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/home/js/mainfile.js" type="text/javascript" charset="utf-8"></script>
<script src="/public/others/jquery.form.js"></script>
<script src="/public/newLayer/mobile/layer.js" type="text/javascript" charset="utf-8"></script>
<!--<script src="/public/newLayer/layer.js" type="text/javascript" charset="utf-8"></script>-->
<script src="/public/home/jscript/pass.js" type="text/javascript"></script>


<style>
    /*.popuo-refund{width:70% !important;}*/
    .popuo-refund .layui-m-layercont{padding:20px 10px 10px;}
    .popuo-refund textarea{width:240px;}

</style>
</body>
<script>
    sessionStorage.clear();
    // $(function(){
    //     //清空浏览器历史记录
    //     pushHistory();
    //     //监听浏览器后退事件
    //     window.addEventListener("popstate",
    //         function(e) {
    //             //转向指定的URL
    //             location.href = '/index.php/Home/Personal/index';
    //         }, false);
    //     //清空浏览器历史记录
    //     function pushHistory() {
    //         var url = "#";
    //         var state = {
    //             title: "title",
    //             url: "#"
    //         };
    //         window.history.pushState(state, "title", "#");
    //     }
    // });

    //取消订单
    function cancelOrder(id) {
        $(this).attr("disabled","disabled");
        layer.open({
            content: '确认要取消订单吗？'
            ,btn: ['确定', '不要']
            ,hade: 'background-color: rgba(0,0,0,.3)'
            ,yes: function(index){
                $.ajax({
                    type: 'post',
                    url: "<?php echo U('Home/Myorder/qxOrder');?>",
                    data: {order_id:id},
                    success: function (data) {
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
                        /*if(data.status == 1){
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
                                    layer.close(index);
                                }
                            });
                        }*/
                    }
                })
            }
        });
    }
    
    //申请退款
    function refundOrder(id) {
        /*var str = '';
        str += '   <div class="layui-layer-shade" id="layui-layer-shade1" times="1" style="z-index: 19891014; background-color: rgb(0, 0, 0); opacity: 0.3;"></div>';
        str += '   <div class="layui-layer layui-layer-page layui-layer-prompt" id="layui-layer1" type="page" times="1" showtime="0" contype="string" style="z-index: 19891015; top: 347.5px; left: 238.5px;">';
        str += '   <div class="layui-layer-title" style="cursor: move;">申请退款,请填写原因</div>';
        str += '   <div id="" class="layui-layer-content"><textarea class="layui-layer-input"></textarea></div>';
        str += '   <span class="layui-layer-setwin"><a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;"></a></span>';
        str += '   <div class="layui-layer-btn layui-layer-btn-"><a class="layui-layer-btn0">确定</a><a class="layui-layer-btn1">取消</a></div></div>';
        str += '   <div class="layui-layer-move" style="cursor: move; display: none;"></div>';*/

        layer.open({
            className: 'popuo-refund',
            content: '<p>申请退款,请填写原因</p><textarea id="textareaText" style="width: 100%;height: 100px" required></textarea>'
            //content:str
            ,btn: ['确定', '取消']
            ,yes: function(index,call){
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
                    data: {order_id:id,user_refuse_reason:$("#textareaText").val()},
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
                            });
                        }
                    }
                });
            }
        });
        /*layer.prompt({title: '申请退款,请填写原因', formType: 2}, function(pass, index){
            console.log(pass);

            $.ajax({
                type: 'post',
                url: "<?php echo U('Home/Myorder/refundOrder');?>",
                data: {order_id:id},
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
        /*layer.open({
            content: '确认要申请退款吗？'
            ,btn: ['确定', '不要']
            ,hade: 'background-color: rgba(0,0,0,.3)'
            ,yes: function(index){
                $.ajax({
                    type: 'post',
                    url: "<?php echo U('Home/Myorder/refundOrder');?>",
                    data: {order_id:id},
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
            }
        });*/
    }

    //订单送达
    function deliveryOrder(id) {
        $(this).attr("disabled","disabled");
        layer.open({
            content: '确定订单已送达？'
            ,btn: ['确定', '不要']
            ,hade: 'background-color: rgba(0,0,0,.3)'
            ,yes: function(index){
                $.ajax({
                    type: 'post',
                    url: "<?php echo U('Home/Myorder/sdOrder');?>",
                    data: {order_id:id},
                    success: function (data) {
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
                        /*if(data.status == 1){
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
                                    layer.close(index);
                                }
                            });
                        }*/
                    }
                })
            }
        });
    }

    //去付款
    $(".paynow").click(function () {
        var id = $(this).attr('id');
        var maInfo = $(this).attr('maInfo');
        var startTime = $(".startTime").val();
        var endTime = $(".endTime").val();
        if (maInfo != 1) {
            //询问框
            layer.open({
                content: '餐厅已打烊！餐厅营业时间为：' + startTime + '～' + endTime + '！'
                , skin: 'msg'
                , time: 2 //2秒后自动关闭
            });
            return false;
        }
        $.ajax({
            type: 'post',
            url: "<?php echo U('Home/Myorder/passOrder');?>",
            data: {order_id: id},
            success: function (data) {
                console.log(data);
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
    });
    //去评价
    function evaluationOrder(id) {
        window.location.href = "/index.php/Home/Myorder/evaluation/order_id/"+id+"/type/1";
    }
</script>
</html>