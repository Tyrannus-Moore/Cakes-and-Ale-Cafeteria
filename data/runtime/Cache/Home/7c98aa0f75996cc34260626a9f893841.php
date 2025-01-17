<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>菜品详情</title>
    <base href="/public/home/" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <script src="js/jquery-2.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="layermobile/need/layer.css"/>
    <script src="layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
</head>
<style>
	.goodsDetaiMs img{max-width:100%;width:100%;}
	.zhe{margin-right: 2px;}
</style>
<body>
<div class="goodsDetailMainBox">
    <div class="goodsDetailTopBox">
        <img class="goodsDetailImg" src="<?php echo ($infos["pic_url"]); ?>" />
        <div class="goodsDetailCon p-10 bac-fff">
            <p style="font-weight: bold;font-size: 20px"><?php echo ($infos["dishes_name"]); ?></p>
            <div class="clearfix mt-5 mb-5">
                <div class="fl c99">
                	<?php if(!empty($infos["real"])): ?><img class="zhe fl" src="images/icon_zhe.png" />                      
                        <span><?php echo ($infos["discount"]); ?><span>折</span>&nbsp;<?php if(($infos["statue"]) == "3"): echo ($infos["start_time"]); ?>~<?php echo ($infos["end_time"]); endif; ?></span><?php endif; ?>
                    <span>月销</span>
                    <span><?php echo ($infos["on_the_pin"]); ?></span>
                    <span>评分</span>
                    <span><?php echo ($infos["score"]); ?></span>
                </div>
            </div>
            <div class="clearfix">
                <div class="fl">
                    <?php if(empty($infos["real"])): ?><span class="font20 c-zhu">￥<?php echo ($infos["price"]); ?></span>
                        <?php else: ?>
                        <span class="font20  c-zhu">￥<?php echo ($infos["real"]); ?></span>
                        <del class="c99">￥<?php echo ($infos["price"]); ?></del><?php endif; ?>
                </div>
                <div class="fr goodsCon">                  
                    <span><?php echo ($infos["hot"]); ?>卡路里</span>
                </div>
            </div>
        </div>
    </div>

    <div class="goodsBuyNumBox clearfix">
        <div class="fl">购买数量</div>
        <div class="clearfix fr mt-10">
            <img class="relImg fl reduce" d-id="<?php echo ($infos["dishes_id"]); ?>" src="images/icon_jian.png" />
            <span class="num fl onum"><?php echo ($infos["number"]); ?></span>
            <img class="addImg fl plus" d-id="<?php echo ($infos["dishes_id"]); ?>" src="images/icon_jia.png" />
        </div>
    </div>

    <div class="goodsDetailEveBox bac-fff mb-15">
        <div class="clearfix goodsDetailEveTitle">
            <div class="fl font16 fontw c00">评价</div>
            
            <a href="<?php echo U('evaluationG',array('dishes_id'=>$infos['dishes_id']));?>"><div class="fr c99 font12">全部评价 ></div></a>
        </div>
        <?php if(empty($evaluation)): ?><div class="userEveListBox p-10 bac-fff">
                暂无评价
            </div>
           <?php else: ?>
            <?php if(is_array($evaluation)): foreach($evaluation as $key=>$v): ?><div class="userEveListBox p-10 bac-fff">
                    <div class="userEveListTop clearfix">
                        <div class="userHeadImgBox fl">
                            <img class="userHeadImg" src="<?php echo ($v["member_list_headpic"]); ?>" />
                        </div>
                        <div class="userEveUserName fl"><?php echo ($v["member_list_nickname"]); ?></div>
                        <div class="xingBox fr clearfix mt-7">
                            <div class="clearfix fl ml-10">
                                <?php $__FOR_START_29976__=0;$__FOR_END_29976__=$v["score"];for($i=$__FOR_START_29976__;$i < $__FOR_END_29976__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon24.png" /><?php } ?>
                                <?php $__FOR_START_23409__=0;$__FOR_END_23409__=$v["endscore"];for($i=$__FOR_START_23409__;$i < $__FOR_END_23409__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon25.png" /><?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix userEveTime c99">
                        <div class="c99 fl"><?php echo ($v["addtime"]); ?>&nbsp;&nbsp;&nbsp;</div>
                    </div>
                    <div class="userEveCon"><?php echo ($v["content"]); ?></div>
                    <div class="userEveImgBox mt-5">
                        <?php if(!empty($v["images"])): if(is_array($v["images"])): foreach($v["images"] as $key=>$vo): ?><img class="userEveImg" src="<?php echo ($vo); ?>" /><?php endforeach; endif; endif; ?>
                    </div>
                </div><?php endforeach; endif; endif; ?>
    </div>

    <div class="goodsDetailMsBox bac-fff pb-60">
        <div class="clearfix goodsDetailEveTitle">
            <div class="fl font16 fontw c00">菜品描述</div>
        </div>
        <div class="goodsDetaiMs p-10">
            <?php echo (htmlspecialchars_decode($infos["content"])); ?>
        </div>
    </div>
</div>


<div class="shopCartBox clearfix">
    <div class="po-rela fl" style="margin-top:2px;" onclick="getCart(this)">
        <div class="po-abso gwcsl"></div>
        <img class="shopCartImg" src="images/tabber_gwc.png" />
    </div>
    <div class="jiesuan fr" is_p="<?php echo ($is_p); ?>" maInfo="<?php echo ($maInfo['is_yy']); ?>">结算</div>
    <input type="hidden" class="startTime" value="<?php echo (date('H:i',$maInfo['start_time'])); ?>">
    <input type="hidden" class="endTime" value="<?php echo (date('H:i',$maInfo['end_time'])); ?>">
    <div class="fr shopCartMoney">
        <span>￥</span>
        <span class="font20 fontw allmoney"></span>
    </div>
</div>

<div class="mask dis-n"></div>
<div class="gwcListBox dis-n">
    <div class="gwcListTop clearfix">
        <div class="fl c99">已选餐品</div>
        <div class="fr clearAll">清空</div>
    </div>
    <div class="itemlist"></div>
</div>
<input type="hidden" class="stall_id" value="<?php echo ($infos["stall"]); ?>">
<input type="hidden" class="dishes_id" value="<?php echo ($infos["dishes_id"]); ?>">
<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    var stall_id = $('.stall_id').val();
    var dishes_id = $('.dishes_id').val();
    /*档口二级分类切换*/
    $(".ptdkMainBottomLeft > div").click(function(){
        $(this).addClass("lxSel").siblings().removeClass("lxSel")
    });
    function getCart(e) {
        var that = e;
        if($(that).hasClass("show")){
            $(".gwcListBox").hide();
            $(".mask").hide();
            $(that).removeClass("show");
            window.location.reload();
        }else{
            $(that).addClass("show");
            $(".gwcListBox").show();
            $(".mask").show();
        }
    }

    /*普通档口 购物车加减*/

    $(".relImg").click(function(){
        var num = parseInt($(this).siblings(".num").text());
        if(num == 0){
            return false;
        }
        num -- ;
        $(this).siblings(".num").text(num);
    });

    $(".addImg").click(function(){
        var num = parseInt($(this).siblings(".num").text());
        num ++ ;
        $(this).siblings(".num").text(num);
    });
    $(function () {
        addCar();
    });
    // 购物车减
    $(".reduce").click(function(){
        var num = parseInt($(this).siblings(".num").text());
        var dishes_id = $(this).attr('d-id');
        if(num == 0){
            $.ajax({
                url: "<?php echo U('delCar');?>",
                data: {dishes_id: dishes_id,stall_id: stall_id},
                success:function (data) {
                    addCar();
                }
            });
        }else{
            $.ajax({
                url: "<?php echo U('decCar');?>",
                data: {dishes_id: dishes_id,stall_id: stall_id},
                success:function (data) {
                    addCar();
                }
            });
        }
    });
    // 购物车加
    $(".plus").click(function(){
        var num = parseInt($(this).siblings(".num").text());
        var dishes_id = $(this).attr('d-id');
        if(num == 1){
            $.ajax({
                url: "<?php echo U('addCar');?>",
                data: {dishes_id: dishes_id,stall_id: stall_id},
                success:function (data) {
                    addCar();
                }
            });
        }else{
            $.ajax({
                url: "<?php echo U('incCar');?>",
                data: {dishes_id: dishes_id,stall_id: stall_id},
                success:function (data) {
                    addCar();
                }
            });
        }
    });
    // 清空购物车
    $(".clearAll").click(function () {
        layer.open({
            content: '您确定清空购物车吗？'
            ,btn: ['确定', '不要']
            ,yes: function(index){
                $.ajax({
                    url: "<?php echo U('clearCar');?>",
                    data: {stall_id: stall_id},
                    success:function (data) {
                        layer.close(index);
                        $.ajax({
                            url: "<?php echo U('clearCar');?>",
                            data: {stall_id: stall_id},
                            success:function (data) {
                                addCar();
                                $(".gwcListBox").hide();
                                $(".mask").hide();
                                $(".shopCartImg").removeClass("show");
                                $(".onum").html(0);
                            }
                        });
                    }
                });
            }
        });
    });
    // 购物车
    function addCar() {
        $.ajax({
            url: "<?php echo U('cart');?>",
            data: {stall_id: stall_id},
            success:function (data) {
                var str = '';
                $.each(data,function(index,item){
                    str += '<div class="gwcList clearfix hot" hot="'+item.hot+'">';
                    str += '<div class="fl gwcListName">'+item.dishes_name+'</div>';
                    str += '<div class="fl">';
                    if(item.real == undefined){
                        str += '<span class="zhu" style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                        str += '&nbsp;&nbsp;';
                        str += '<span class="c-zhu">￥</span>';
                        str += '<span class="c-zhu money">'+item.price+'</span>';
                    }else{
                        str += '<del class="c99">￥'+item.price+'</del>';
                        str += '&nbsp;&nbsp;';
                        str += '<span class="c-zhu">￥</span>';
                        str += '<span class="c-zhu money">'+item.real+'</span>';
                    }
                    str += '</div>';
                    str += '<div class="gwcListAddNum fr clearfix">';
                    str += '<img class="relImg fl reduceo" d-id="'+item.dishes_id+'" src="images/icon_jian.png" />';
                    str += '<span class="num fl numo">'+item.number+'</span>';
                    str += '<img class="addImg fl pluso" d-id="'+item.dishes_id+'" src="images/icon_jia.png" />';
                    str += '</div>';
                    str += '</div>';
                });
                $(".itemlist").empty();
                $(".itemlist").append(str);
                // $(".gwcsl").html(data.length);
                var all = 0;
                var moneys = [];
                var nums = [];
                var num = 0;
                $.each($(".money"),function(index,item){
                    moneys.push($(item).html());
                });
                $.each($(".numo"),function(index,item){
                    nums.push($(item).html());
                });
                for (var i=0;i<moneys.length;i++){
                    all += parseFloat(moneys[i])*parseFloat(nums[i]);
                    num += parseFloat(nums[i]);
                }
                $(".allmoney").html(all.toFixed(2));
                if(num == 0){
                    $(".shopCartImg").attr("src","images/tabber_gwc1.png")
                    $('.shopCartImg').parent().removeAttr('onclick');
                    $('.shopCartImg').prev().hide();
                    $(".gwcListBox").hide();
                    $(".mask").hide();
                    $('.shopCartImg').parent().removeClass("show");
                }else{
                    $(".shopCartImg").attr("src","images/tabber_gwc.png")
                    $('.shopCartImg').parent().attr("onclick","getCart(this)");
                    $(".gwcsl").html(num);
                    $('.shopCartImg').prev().show();
                }
                // 购物车减
                $(".reduceo").click(function(){
                    var num = parseInt($(this).siblings(".numo").text());
                    var dishes_id = $(this).attr('d-id');
                    num -- ;
                    if(num == 0){
                        $.ajax({
                            url: "<?php echo U('delCar');?>",
                            data: {dishes_id: dishes_id,stall_id: stall_id},
                            success:function (data) {
                                if(data == 0){
                                    window.location.reload();
                                }else{
                                    addCar();
                                }
                            }
                        });
                        return false;
                    }
                    $(this).siblings(".numo").text(num);
                    $.ajax({
                        url: "<?php echo U('decCar');?>",
                        data: {dishes_id: dishes_id,stall_id: stall_id},
                        success:function (data) {
                            addCar();
                        }
                    });
                });
                // 购物车加
                $(".pluso").click(function(){
                    var num = parseInt($(this).siblings(".numo").text());
                    var dishes_id = $(this).attr('d-id');
                    num ++ ;
                    $(this).siblings(".numo").text(num);
                    $.ajax({
                        url: "<?php echo U('incCar');?>",
                        data: {dishes_id: dishes_id,stall_id: stall_id},
                        success:function (data) {
                            addCar();
                        }
                    });
                });
                var userEveImgBox = $(".userEveImgBox");
                for(var i = 0; i < userEveImgBox.length; i++){
                    var size = $(userEveImgBox[i]).find("Img").size();
                    var s = 6 - size%6
                    var str = '';
                    for(var j = 0; j < s; j++){
                        str += '<div style="width:50px;margin-right:5px"></div>'
                    }
                    $(userEveImgBox[i]).append(str)
                }
            }
        });
    };
    // 结算
    $(".jiesuan").click(function () {
        var maInfo = $(this).attr('maInfo');
        var startTime = $(".startTime").val();
        var endTime = $(".endTime").val();
        var num = "<?php echo ($infos["num"]); ?>";
        if(num == 0){
            layer.open({
                content: '菜品已售罄！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        if(maInfo != 1) {
            //询问框
            layer.open({
                content: '餐厅已打烊！餐厅营业时间为：'+startTime+'～'+endTime+'！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        var is_p = $(this).attr('is_p');
        if(is_p == 2){
            //询问框
            layer.open({
                content: '请先完善资料！'
                ,btn: ['好的', '取消']
                ,yes: function(index){
                    layer.close(index);
                    window.location.href = "/index.php?m=Home&c=Personal&a=personal&href=/index.php/Home/Stall/dishesDetail/dishes_id/"+dishes_id;
                }
            });
            return false;
        }else {
            var all = 0;
            var hots = [];
            var nums = [];
            $.each($(".hot"), function (index, item) {
                hots.push($(item).attr('hot'));
            });
            $.each($(".numo"), function (index, item) {
                nums.push($(item).html());
            });
            for (var i = 0; i < hots.length; i++) {
                all += parseInt(hots[i]) * parseInt(nums[i])
            }
            if(all == 0){
                layer.open({
                    content: '请选择菜品！'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                return false;
            }
            window.location.href = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id=" + stall_id +"&all="+all;

            // $.ajax({
            //     url: "<?php echo U('getHot');?>",
            //     data: {},
            //     success: function (data) {
            //         if (data.start > all) {
            //             //询问框
            //             layer.open({
            //                 content: '摄入热量小于推荐' + data.start + '热量！'
            //                 , btn: ['忽略', '确定']
            //                 , yes: function (index) {
            //                     layer.close(index);
            //                     window.location.href = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id=" + stall_id;
            //                 }
            //             });
            //         } else if (data.end < all) {
            //             //询问框
            //             layer.open({
            //                 content: '摄入热量大于推荐' + data.end + '热量！'
            //                 , btn: ['忽略', '确定']
            //                 , yes: function (index) {
            //                     layer.close(index);
            //                     window.location.href = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id=" + stall_id;
            //                 }
            //             });
            //         } else {
            //             window.location.href = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id=" + stall_id;
            //         }
            //     }
            // });
        }
    })
</script>
</body>

</html>