<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>确认订单</title>
    <base href="/public/home/" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <link rel="stylesheet" type="text/css" href="layermobile/need/layer.css"/>
    <script src="layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/mui.min.js"></script>
    <script src="js/jquery-2.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mui.picker.min.css" />
    <script src="js/mui.picker.min.js"></script>
    <link href="css/mui.poppicker.css" rel="stylesheet" />
    <script src="js/mui.poppicker.js"></script>
</head>

<body>
    <style>
        .confirmOrderTopTitleList{font-weight: bold;}
    </style>
<div class="confirmOrderTopBox">
    <div class="confirmOrderTopTitle clearfix">
        <?php if($a == 1): ?><div type="1" class="confirmOrderTopTitleList <?php if($a == 1 && $b == 1): ?>qiehuan<?php endif; ?>  fl <?php if(($a == 1 && $b == 2) || $address_id): ?>confirmOrderTabSel<?php endif; ?>">捎带</div><?php endif; ?>
        <?php if($b == 1): ?><div type="2" class="confirmOrderTopTitleList <?php if($a == 1 && $b == 1): ?>qiehuan<?php endif; ?> fl <?php if(empty($address_id)): ?>confirmOrderTabSel<?php endif; ?>">自取</div><?php endif; ?>
    </div>
    <?php if($a == 1): ?><div class="confirmOrderTabCon confirmOrderTabConA ass <?php if(($a == 1 && $b == 1) || $address_id): ?>dis-n<?php endif; ?>">
            <div class="confirmOrderHasAddr">
                <p><?php echo ($address["name"]); ?> &nbsp;&nbsp;&nbsp; <?php echo ($address["phone"]); ?></p>
                <p class="mt-5" style="height: 50px;line-height: 25px;overflow: hidden"><?php echo ($address["proviceid"]); ?>&nbsp;&nbsp;<?php echo ($address["cityid"]); ?>&nbsp;&nbsp;<?php echo ($address["countyid"]); ?>&nbsp;&nbsp;<?php echo ($address["address"]); ?></p>
                <a href="<?php echo U('address_list',array('stall_id'=>$stallInfo['stall_id']));?>"><img class="confirmOrderMore" src="images/more_right.png" /></a>
            </div>
        </div><?php endif; ?>
    <?php if($b == 1): ?><div class="confirmOrderTabCon confirmOrderTabConD <?php if(!empty($address_id)): ?>dis-n<?php endif; ?> ">
            <div class="confirmOrderZqAddr">
                <div class="c66">取餐地址</div>
                <div><?php echo ($stallInfo["address"]); ?></div>
                <div>
                    <span class="c66">档口电话</span>
                    <span><?php echo ($stallInfo["stall_tel"]); ?></span>
                </div>
            </div>
        </div><?php endif; ?>
    <!--没有配送地址-->
    <div>
        <?php if($a == 1 && $address == ''): ?><div class="confirmOrderTabCon confirmOrderTabConA bss <?php if($b == 1): ?>dis-n<?php endif; ?> ">
                <a href="<?php echo U('address_list',array('stall_id'=>$stallInfo['stall_id']));?>">
                    <div class="confirmOrderNoAddr">
                        <img class="addrSiteImg" src="images/icon_site.png" />
                        <span class="c99">添加配送地址</span>
                        <img class="confirmOrderMore" src="images/more_right.png" />
                    </div>
                </a>
            </div><?php endif; ?>
    </div>
</div>
<!-- 配送 -->
<?php if($a == 1): ?><div class="confirmOrderTimeSelBox clearfix" <?php if($b == 1 || $address_id): ?>style="display: none;"<?php endif; ?>>
        <div class="fl">送达时间</div>
        <div class="fr" style="color:#d5b630">
            <span id='putInTime' class="selectEle date" data-options='{}'>尽快</span>
            <img class="fr timeSelMore ml-10" src="images/more_right.png" />
            <input type="hidden" class="start_h" value="<?php echo (date('H',$merchant["start_time"])); ?>">
            <input type="hidden" class="start_m" value="<?php echo (date('i',$merchant["end_time"])); ?>">
            <input type="hidden" class="end_h" value="<?php echo (date('H',$merchant["end_time"])); ?>">
            <input type="hidden" class="end_m" value="<?php echo (date('i',$merchant["end_time"])); ?>">
        </div>
    </div><?php endif; ?>
<div class="confirmOrderInfoBox">
    <div class="confirmOrderInfoTitle font16 c00"><?php echo ($stallInfo["stall_name"]); ?></div>
    <div class="">
        <?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="orderDetailProList clearfix">
                <img class="orderDetailProImg fl" src="<?php echo ($v["pic_url"]); ?>" />
                <span class="psyorderCenterProTitle fl"><?php echo ($v["dishes_name"]); ?></span>
                <div class="fl">
                    <span>×</span>
                    <span><?php echo ($v["number"]); ?></span>
                </div>
                <div class="fr">
                    <span>￥</span>
                    <span><?php echo ($v["real"]); ?></span>
                </div>
            </div><?php endforeach; endif; ?>
        <?php if($memInfo["stature"] != 0 && $memInfo["weight"] != 0): if($res["start"] > $all): ?><span style="color:red">摄入热量小于推荐<?php echo ($res["start"]); ?>热量！</span><?php endif; ?>
            <?php if($res["end"] < $all): ?><span style="color:red">摄入热量大于推荐<?php echo ($res["end"]); ?>热量！</span><?php endif; endif; ?>
    </div>
    
</div>
<div class="mt-15 bac-fff">
    <?php if($a == 1): ?><div class="orderDetailPriceList clearfix peisong" <?php if($b == 1 || $address_id): ?>style="display: none;"<?php endif; ?> >
            <div class="fl">配送费</div>
            <div class="fr">
                <span>￥</span>
                <span><?php echo ($stallInfo["p_money"]); ?></span>
            </div>
        </div><?php endif; ?>
    <div class="orderDetailPriceList clearfix">
        <div class="fl">获得积分</div>
        <div class="fr">
            <?php echo ($stallInfo["integral"]); ?>
        </div>
    </div>
</div>
<form class="form-horizontal ajaxF" method="post" autocomplete="off" action="<?php echo U('confirmOrder');?>">
    <div class="confirmOrderRemark mt-15 bac-fff p-10">
        <p>
            <span>备注</span>
            <span class="c99 font12">（选填）</span>
        </p>
        <textarea class="confirmOrderRemarkText" name="order_note" maxlength="200" placeholder="限制输入200字"></textarea>
    </div>
    <?php if($address_id != ''): ?><input class="ptype" name="deliver_type" type="hidden" value="1">
    <?php else: ?>
        <?php if($a == 1 && $b == 1): ?><input class="ptype" name="deliver_type" type="hidden" value="2">
        <?php elseif($a == 1 && $b == 2): ?>
            <input class="ptype" name="deliver_type" type="hidden" value="1">
        <?php elseif($a == 2 && $b == 1): ?>
            <input class="ptype" name="deliver_type" type="hidden" value="2"><?php endif; endif; ?>
    
    
    <input name="stall_id" type="hidden" value="<?php echo ($stallInfo["stall_id"]); ?>" >
    <input name="username" class="username" type="hidden" value="<?php echo ($address["name"]); ?>">
    <input name="phone" type="hidden" value="<?php echo ($address["phone"]); ?>">
    <input name="address_id" type="hidden" value="<?php echo ($address["address_id"]); ?>">
    <input name="address" type="hidden" value="<?php echo ($address["proviceid"]); ?> <?php echo ($address["cityid"]); ?> <?php echo ($address["countyid"]); ?> <?php echo ($address["address"]); ?>">
    <input name="express_time" class="express_time" type="hidden" value="">
    <input name="total_money" type="hidden" class="allmoney" value="<?php echo ($stallInfo["allmoney"]); ?>" autocomplete="off">
    <input name="express_money" class="express_money" type="hidden" value="<?php echo ($stallInfo["p_money"]); ?>" autocomplete="off">
    <input name="discount" type="hidden" value="<?php echo ($count); ?>">
    <input name="real_money" type="hidden" class="allmoneys" value="<?php echo ($stallInfo["allmoneys"]); ?>" autocomplete="off">
    <input name="integral" type="hidden" value="<?php echo ($stallInfo["integral"]); ?>">
    <input name="stall_address" type="hidden" value="<?php echo ($stallInfo["address"]); ?>">
    <input name="stall_tel" type="hidden" value="<?php echo ($stallInfo["stall_tel"]); ?>">
    <input name="peisongMoney" type="hidden" value="<?php echo ($stallInfo['allmoney']+$stallInfo['p_money']); ?>" autocomplete="off">
    <input name="zitiMoney" type="hidden" value="<?php echo ($stallInfo['allmoney']); ?>" autocomplete="off">
    <button class="quzhifu" type="submit">去支付 ￥<p style="display: inline;" class="realAllMoneys"><?php echo ($stallInfo["allmoneys"]); ?></p></button>
</form>

<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<!-- <script src="js/mainfile.js" type="text/javascript" charset="utf-8"></script> -->
<script src="/public/others/jquery.form.js"></script>
<!-- <script type="text/javascript">
    $(document).ready(function(e) {   
            var counter = 0;  
            if (window.history && window.history.pushState) {  
                             $(window).on('popstate', function () {  
                                            window.history.pushState('forward', null, '#');  
                                            window.history.forward(1);  
                                          //alert("不可回退");  
                                             location.replace(document.referrer);//刷新
                                });  
              }  

              window.history.pushState('forward', null, '#'); //在IE中必须得有这两行  
              window.history.forward(1);  
}); 
</script> -->

<script>
    var addresss = "<?php echo ($address); ?>";
    // console.log(addresss);
    $(".qiehuan").click(function(){
        
            $(this).addClass("confirmOrderTabSel").siblings().removeClass("confirmOrderTabSel")
            var index = $(this).index();
            var peisong = $('.express_money').val();
            //var allmoney = $('.allmoney').val();
            // var allmoneys = $('.allmoneys').val();
            var allmoneys = "<?php echo ($stallInfo["allmoneys"]); ?>";
            var type = $(this).attr('type');
            
            if(type == 1){

                $(".confirmOrderTimeSelBox").show();
                $(".ptype").val(1);
                
                if(addresss){
                    $(".ass").removeClass('dis-n');
                }else{
                    $(".bss").removeClass('dis-n');
                }
                
                $(".confirmOrderTabConD").hide();
                $(".peisong").show();
                var money = $("input[name=peisongMoney]").val();
                // alert(money);
                //$(".allmoney").val(parseFloat(allmoney)+parseFloat(peisong));
                $(".allmoneys").val(intToFloat(money));
                $(".realAllMoneys").text(intToFloat(money));
            }else{
                $(".confirmOrderTimeSelBox").hide();
                $(".ptype").val(2);
                $(".confirmOrderTabConD").show();
                if(addresss){
                    $(".ass").addClass('dis-n');
                }else{
                    $(".bss").addClass('dis-n');
                }
                // $(".confirmOrderTabConA").hide();
                $(".peisong").hide();
                var money = $("input[name=zitiMoney]").val();
                //$(".allmoney").val(parseFloat(allmoney)-parseFloat(peisong));
                
                $(".allmoneys").val(intToFloat(money));
                $(".realAllMoneys").text(intToFloat(money));
            }
        
        
    });
    var address = "<?php echo ($address_id); ?>";
    if(address){

        $('.confirmOrderTabConD').addClass('dis-n');
        $('.peisong').css('display','block');
        // $('.peisong').css('display','block');
        $('.ass').css('display','block');
    }
    function intToFloat(val){
        return new Number(val).toFixed(2);
    }
    mui(".confirmOrderTimeSelBox").on('tap', '#putInTime', function() {
        var _self = this;
        var date1 = new Date();
        var start_h = $(".start_h").val();
        var start_m = $(".start_m").val();
        var end_h = $(".end_h").val();
        var end__m = $(".end_m").val();
        if(_self.picker) {
            _self.picker.show(function(rs) {
                var hour = new Date().getHours();
                var minutes = new Date().getMinutes();
                if(minutes < 10){
                    minutes = '0'+minutes;
                }
                var currentTime = rs.text.split(":");
                if(date1.setHours(currentTime[0],currentTime[1]) < date1.setHours(hour,minutes)) //当前时间大于选中时间
                {
                    $(_self).text(hour+":"+minutes);
                    $(".express_time").val(hour+":"+minutes);
                }else if(date1.setHours(currentTime[0],currentTime[1]) < date1.setHours(start_h,start_m) || date1.setHours(currentTime[0],currentTime[1]) > date1.setHours(end_h,end__m)){
                    layer.open({
                        type: 0,
                        time: 1,
                        content: '超出营业时间！',
                        skin: 'msg'
                    });
                    $(_self).text(hour+":"+minutes);
                    $(".express_time").val(hour+":"+minutes);
                }else{
                    $(_self).text(rs.text);
                    $(".express_time").val(rs.text);
                }
                _self.picker.dispose();
                _self.picker = null;
            });
        }else {
            _self.picker = new mui.DtPicker({
                type:"time"
            });
            _self.picker.show(function(rs) {
                var hour = new Date().getHours();
                var minutes = new Date().getMinutes();
                if(minutes < 10){
                    minutes = '0'+minutes;
                }
                var currentTime = rs.text.split(":");
                var date1 = new Date();
                if(date1.setHours(currentTime[0],currentTime[1]) < date1.setHours(hour,minutes)) //当前时间大于选中时间
                {
                    $(_self).text(hour+":"+minutes);
                    $(".express_time").val(hour+":"+minutes);
                }else if(date1.setHours(currentTime[0],currentTime[1]) < date1.setHours(start_h,start_m) || date1.setHours(currentTime[0],currentTime[1]) > date1.setHours(end_h,end__m)){
                    layer.open({
                        type: 0,
                        time: 1,
                        content: '超出营业时间！',
                        skin: 'msg'
                    });
                    $(_self).text(hour+":"+minutes);
                    $(".express_time").val(hour+":"+minutes);
                }else{
                    $(_self).text(rs.text);
                    $(".express_time").val(rs.text);
                }
                _self.picker.dispose();
                _self.picker = null;
            });
        }
    });
    // var  zzz ='';
    $(function(){
        $('.ajaxF').ajaxForm({
            beforeSubmit: shenheF,
            success: complete2, // 这是提交后的方法
            dataType: 'json'
        });
    });

    // 提交前验证
    function shenheF() {
        var ptype = $(".ptype").val();
        var username = $(".username").val();
        if(ptype == 1){
            if(username == ''){
                layer.open({
                    type: 0,
                    time: 1,
                    content: '请选择地址！',
                    skin: 'msg'
                });
                return false;
            }
        }
        $('.quzhifu').attr('type', 'button'); 
        // zzz = layer.open({type: 2,shadeClose:false});

    };
    //失败不跳转
    function complete2(data) {
        
        // console.log(data);
        if(data.status == 1) {
            // layer.close(zzz);
            // return false;
            window.location.href="/index.php?m=Home&c=Stall&a=payNow&order_id="+data.url;
        }else{
            $('.quzhifu').attr('type', 'submit'); 
            layer.open({
                type: 0,
                time: 2,
                content: data.info,
                skin: 'msg'
            });
            return false;
        }
    };
</script>
</body>

</html>