<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>普通档口</title>
    <base href="/public/home/" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <script src="js/jquery-2.2.1.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="layermobile/need/layer.css"/>
    <script src="layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
</head>
<style>
	.shopCartBox{border-top: 1px solid #e6e6e6;}
	.relImg, .addImg{width:20px;height: 20px;}
	.num{width: 25px;height: 20px;line-height: 20px;}
	.gwcListName{min-width:0px;width:120px;}
	.ptdkCpListInfoBox a{min-height:80px;display: flex;flex-direction: column;justify-content: space-between;}
	</style>
</style>
<body>
<div class="ptdkTop bac-fff">
    <img class="ptdkTopImg" src="<?php echo ($stallInfo["image"]); ?>" />
    <p class="c00 font16 fontw mt-10 mb-10"><?php echo ($stallInfo["stall_name"]); ?></p>
    <div class="ptdkTopPjBox clearfix">
        <div class="ptdkXingBox clearfix fl">
            <?php $__FOR_START_20352__=0;$__FOR_END_20352__=$stallInfo["score"];for($i=$__FOR_START_20352__;$i < $__FOR_END_20352__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon24.png" /><?php } ?>
            <?php $__FOR_START_2489__=0;$__FOR_END_2489__=$end;for($i=$__FOR_START_2489__;$i < $__FOR_END_2489__;$i+=1){ ?><img class="ptdkXing" src="images/star_icon25.png" /><?php } ?>
        </div>
        <div class="fl c99 ml-5">
            <span><?php echo ($stallInfo["score"]); ?></span>
            <span>月销</span>
            <span><?php echo ($stallInfo["on_the_pin"]); ?></span>
        </div>
    </div>

        <div class="lxdkBox clearfix">
            <a href="tel:<?php echo ($stallInfo["stall_tel"]); ?>">
                <img class="lxdkImg fl" src="images/icon_lxdk.png" />
                <div class="lxdkText fl">联系档口</div>
            </a>
        </div>
        <img class="ptdkScImg"  src="" />
</div>

<div class="ptdkMainBox bac-fff">
    <div class="ptdkTabBox clearfix po-rela">
        <div class="ptdkTabList fl ptdkTabSel">
            <span>菜品</span>
        </div>
        <div class="ptdkTabList fl">
            <span>评价</span>
        </div>
        <div class="ptdkTabxian"></div>
    </div>

    <!--餐品-->
    <div class="ptdkMainBottomBox clearfix">
        <div class="ggListBox clearfix">
            <div class="ggListScrollBox clearfix">
                <?php if(is_array($tuijian)): foreach($tuijian as $key=>$v): if($v["num"] != 0): ?><a href="<?php echo U('dishesDetail',array('dishes_id'=>$v['dishes_id']));?>">
	                        <div class="ptdkGgList">
	                            <img class="ptdkGgImg" src="<?php echo ($v["pic_url"]); ?>" />
	                            <div class="ptdkGgText"><span style="background-color: rgba(0,0,0,0.4);padding: 2px 5px;"><?php echo (msubstr($v["dishes_name"],0,5)); ?></span></div>
	                        </div>
	                    </a>
                    <?php else: ?>
                    	<a href="<?php echo U('dishesDetail',array('dishes_id'=>$v['dishes_id']));?>">
	                        <div class="ptdkGgList">
	                        	<div class="po-rela" style="width:100%;height:100%;">
	                        		<div style="position: absolute;top: 0;left: 0;background: rgba(0,0,0,0.6);z-index: 99;width:100%;height: 100%;"></div>
	                        		<img class="ptdkGgImg" src="<?php echo ($v["pic_url"]); ?>" />
	                        		<img class="po-abso" style="top: 0; left: 0;z-index: 100;width:100%;height: 100%;" src="images/icon_ysq.png" alt="">
	                        	</div>	                        		                        		                            
	                            <div class="ptdkGgText"><span style="background-color: rgba(0,0,0,0.4);padding: 2px 5px;"><?php echo (msubstr($v["dishes_name"],0,5)); ?></span></div>
	                            
	                        </div>
	                    </a><?php endif; endforeach; endif; ?>
            </div>
        </div>
        <div class="ptdkMainBottomLeft fl">
            <?php if(is_array($type)): foreach($type as $key=>$v): ?><div class="" id="types"><a href="<?php echo U('Home/Stall/stallDetail');?>&stall_id=<?php echo ($stallInfo['stall_id']); ?>#<?php echo ($v["dishes_cate_id"]); ?>"><?php echo ($v["cat_name"]); ?></a></div><?php endforeach; endif; ?>
        </div>

        <div class="ptdkMainBottomRight fl">
            <?php if(is_array($type)): foreach($type as $key=>$v): ?><div class="ptdkCpListBox" id="<?php echo ($v["dishes_cate_id"]); ?>">
                    <?php if(is_array($v["dishes"])): foreach($v["dishes"] as $key=>$vo): if($vo["num"] != 0): ?><div class="ptdkCpList clearfix">
                                <a href="<?php echo U('dishesDetail',array('dishes_id'=>$vo['dishes_id']));?>"><img class="ptdkCpListImg fl" src="<?php echo ($vo["pic_url"]); ?>" /></a>
                                <div class="ptdkCpListInfoBox fl">
                                    <a href="<?php echo U('dishesDetail',array('dishes_id'=>$vo['dishes_id']));?>">
                                    <p class="font16 c00" style="line-height: 13px;"><?php echo (msubstr($vo["dishes_name"],0,5)); ?></p>
                                    <div class="clearfix" style="color: #999;font-size:12px;margin-top:8px;">
                                        <div class="fl">
                                            <span>评分</span>
                                            <span><?php echo ($vo["score"]); ?></span>&nbsp;&nbsp;
                                            <span>月销</span>
                                            <span><?php echo ($vo["on_the_pin"]); ?></span>
                                        </div>
                                    </div>
                                    <?php if(!empty($vo["real"])): ?><div style="color: #999;display: flex;align-items: center;font-size:12px;">                                                                               
                                            <img class="zhe fl" style="width: 14px;height: 14px;margin:0px;" src="images/icon_zhe.png" />&nbsp;
                                            <div>
                                                <span><?php echo ($vo["discount"]); ?></span>
                                                 <span>折&nbsp;&nbsp;<?php if(($vo["statue"]) == "3"): echo ($vo["start_time"]); ?>~<?php echo ($vo["end_time"]); endif; ?></span>&nbsp;&nbsp;
                                            </div>                                                                                   
                                    	</div><?php endif; ?>  
                                    <p style="color: #999;font-size:12px;margin-bottom: 3px;"><?php echo ($vo["hot"]); ?>卡路里</p>
                                    <div class="c-zhu" style="height:20px;line-height:20px;">
                                        <span>￥</span>
                                        <?php if(empty($vo["real"])): ?><span class="font16"><?php echo ($vo["price"]); ?></span>
                                        <?php else: ?>
                                            <span class="font16"><?php echo ($vo["real"]); ?>&nbsp;</span>
                                            <del class="c99">￥<?php echo ($vo["price"]); ?></del><?php endif; ?>
                                    </div>
                                    </a>
                                    <div class="numAdd clearfix">
                                        <img class="relImg fl reduce" d-id="<?php echo ($vo["dishes_id"]); ?>" src="images/icon_jian.png" />
                                        <span class="num fl"><?php echo ($vo["number"]); ?></span>
                                        <img class="addImg fl plus" d-id="<?php echo ($vo["dishes_id"]); ?>" src="images/icon_jia.png" />
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="ptdkCpList clearfix po-rela">
                                <div style="position: absolute;top: 0;left: 0;width: 80px;height: 80px;background: rgba(0,0,0,0.6);z-index: 99;"></div>
                                <a href="<?php echo U('dishesDetail',array('dishes_id'=>$vo['dishes_id']));?>"><img class="po-abso" style="width: 80px;height: 80px;top: 0; left: 0;z-index: 100;" src="images/icon_ysq.png" alt="">
                                <img class="ptdkCpListImg fl" src="<?php echo ($vo["pic_url"]); ?>" /></a>
                                <div class="ptdkCpListInfoBox fl">
                                    <p class="font16 c99" style="line-height: 12px;"><?php echo (msubstr($vo["dishes_name"],0,5)); ?></p>
                                    <div class="clearfix" style="color: #999;font-size:12px;margin-top:8px;">
                                        <div class="fl">
                                            <span>评分</span>
                                            <span><?php echo ($vo["score"]); ?></span>&nbsp;&nbsp;
                                            <span>月销</span>
                                            <span><?php echo ($vo["on_the_pin"]); ?></span>
                                        </div>
                                    </div>
                                                     
                                    <?php if(!empty($vo["real"])): ?><div style="color: #999;display: flex;align-items: center;font-size:12px;">                       
                                        <img class="zhe fl" style="margin:0;width: 14px;height: 14px" src="images/icon_zhe.png" />&nbsp;
                                        <div class="fl">
                                            <span><?php echo ($vo["discount"]); ?></span>
                                            <span>折</span>&nbsp;&nbsp;
                                        </div>
                                        </div><?php endif; ?>
                                    
                                    <p style="color: #999;font-size:12px;margin-bottom: 3px;"><?php echo ($vo["hot"]); ?>卡路里</p>
                                    <div class="c-zhu" style="height:20px;line-height:20px;">
                                        <span>￥</span>
                                        <?php if(empty($vo["real"])): ?><span class="font16"><?php echo ($vo["price"]); ?></span>
                                            <?php else: ?>
                                            <span class="font16"><?php echo ($vo["real"]); ?>&nbsp;</span>
                                            <del class="c99">￥<?php echo ($vo["price"]); ?></del><?php endif; ?>
                                    </div>
                                    <div class="numAdd clearfix">
                                        <img class="fl" d-id="<?php echo ($vo["dishes_id"]); ?>" src="images/icon_jian.png" style="width: 20px;height: 20px;"/>
                                        <span class="num fl"><?php echo ($vo["number"]); ?></span>
                                        <img class="fl" d-id="<?php echo ($vo["dishes_id"]); ?>" src="images/icon_jia.png" style="width: 20px;height: 20px;"/>
                                    </div>
                                </div>
                            </div><?php endif; endforeach; endif; ?>
                </div><?php endforeach; endif; ?>
        </div>
    </div>

    <div class="ptdkMainBottomBox dis-n itemlisto">
        <input type="hidden" class="totalPage" value="">
    </div>
</div>



<div class="shopCartBox clearfix" style="z-index: 999">
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
<div class="gwcListBox dis-n" style="z-index: 999">
    <div class="gwcListTop clearfix">
        <div class="fl c99">已选餐品</div>
        <div class="fr clearAll">清空</div>
    </div>
    <div class="itemlist"></div>
</div>
<input type="hidden" class="stall_id" value="<?php echo ($stallInfo["stall_id"]); ?>">
<script src="js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.cookie.js"></script>

<script>
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
    /*档口餐品和评价切换*/
    $(".ptdkTabList").click(function(){
        $(this).addClass("ptdkTabSel").siblings(".ptdkTabList").removeClass("ptdkTabSel");
        var index = $(this).index();
        $(".ptdkMainBottomBox").hide();
        $(".ptdkMainBottomBox").eq(index).show();
    })
    $(".addImg").click(function(){
        var num = parseInt($(this).siblings(".num").text());
        num ++ ;
        $(this).siblings(".num").text(num);
    });
    var stall_id = $('.stall_id').val();
    //分页
    var p; //设置当前页数
    $(function () {
        p = 1;
        $.ajax({
            url:"<?php echo U('evaluationList');?>",
            data:{
                stall_id:stall_id
            },
            success:function(data) {
                // console.log(data);return false
                addList(data);
                totalPage = data.data.totalPage;
                $(".totalPage").val(totalPage);
            }
        });
    });
    $(function () {
        // alert(3);
        $('.ptdkMainBottomLeft').children(":first").addClass("lxSel");
        if(<?php echo ($stallInfo["is_sc"]); ?> == 1){
            $('.ptdkScImg').attr('src','images/icon_shouc_uns.png');
        }else{
            $('.ptdkScImg').attr('src','images/icon_shouc_s.png');
        }
        addCar();
    });
    // 收藏
    $('.ptdkScImg').click(function () {
        $.ajax({
            url: "<?php echo U('collection');?>",
            data: {stall_id: stall_id},
            success:function (data) {
                if(data == 1){
                    layer.open({
                        content: '收藏成功！'
                        ,skin: 'msg'
                        ,time: 1 //2秒后自动关闭
                    });
                    $('.ptdkScImg').attr('src','images/icon_shouc_uns.png');
                }else{
                    layer.open({
                        content: '取消收藏！'
                        ,skin: 'msg'
                        ,time: 1 //2秒后自动关闭
                    });
                    $('.ptdkScImg').attr('src','images/icon_shouc_s.png');
                }
            }
        });
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
                        addCar();
                        $(".gwcListBox").hide();
                        $(".mask").hide();
                        $(".shopCartImg").removeClass("show");
                        setTimeout(window.location.reload(),500);
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
                    str += '<div class="gwcList clearfix hot" hot="'+item.hot+'" >';
                    str += '<div class="fl gwcListName">'+splitStr(item.dishes_name)+'</div>';
                    str += '<div class="fl" style="width:120px;text-align:right;">';
                    if(item.real == undefined){
                        str += '<span class="zhu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
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
    // 评价
    function addList(data) {
        var str = '';
        if(data.data.list.length > 0){
	        $.each(data.data.list,function(index,item){
	            str += '<div class="userEveListBox p-10 bac-fff">';
	            str += '<div class="userEveListTop clearfix">';
	            str += '<div class="userHeadImgBox fl">';
	            str += '<img class="userHeadImg" src="'+item.member_list_headpic+'" />';
	            str += '</div>';
	            str += '<div class="userEveUserName fl">'+item.member_list_nickname+'</div>';
	            str += '<div class="xingBox fr clearfix mt-15">';
	            str += '<div class="clearfix fl ml-10">';
	            for( var i=0; i< item.score;i++){
	                str += '<img class="fl" src="images/star_icon24.png" />';
	            }
	            for( var i=0; i< item.endscore;i++){
	                str += '<img class="fl" src="images/star_icon25.png" />';
	            }
	            str += '</div>';
	            str += '</div>';
	            str += '</div>';
	            str += '<div class="clearfix userEveTime c99">';
	            str += '<div class="c99 fl">'+item.addtime+'&nbsp;&nbsp;&nbsp;</div>';
	            str += '<div class="fl">'+item.names+'</div>';
	            str += ' </div>';
	            str += '<div class="userEveCon">'+item.content+'</div>';
	            str += '<div class="userEveImgBox mt-5">';
	            if(item.images != null){
	                for( var j=0; j< item.images.length;j++){
	                    str += '<img class="userEveImg" src="'+item.images[j]+'" />';
	                }
	            }
	            str += '</div>';
	            str += '</div>';
	        })
        }else{
            str = "<p style='text-align:center;'>暂无评价<p>";
        }
        $(".itemlisto").append(str);
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
    };
    $(window).scroll(function(){
        var winH = $(window).height(); //页面可视区域高度
        var scrollTop=$(window).scrollTop();//滚动条top
        var bodyHeight=$(document).height();
        if(scrollTop + winH>=bodyHeight) {
            totalPage = $(".totalPage").val();
            if (p < totalPage) {
                $.ajax({
                    type: "post",
                    url: "<?php echo U('eitemList');?>",
                    data: {
                        k: p,
                        stall_id:stall_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        addList(data);
                    }
                });
                p++;
            }
        }
    });
   



    function splitStr(e){
        var str = '';
        if(e.length>5){
            str = e.substring(0,5) + "..."
        }else{
            str = e;
        }
        return str
    }

    var width = $(".ptdkGgList img").width();
    $(".ptdkGgList").width(width + "px");
    $(".ptdkGgList").height(width + "px");
    

</script>
<script type="text/javascript">
     // 结算
    $(".jiesuan").click(function () {
        
        var maInfo = $(this).attr('maInfo');
        var startTime = $(".startTime").val();
        var endTime = $(".endTime").val();
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
                    window.location.href = "/index.php?m=Home&c=Personal&a=personal_add&href=/index.php/Home/Stall/StallDetail/stall_id/"+stall_id;
                }
            });
            return false;
        }else{
            var all = 0;
            var hots = [];
            var nums = [];
            $.each($(".hot"),function(index,item){
                hots.push($(item).attr('hot'));
            });
            $.each($(".numo"),function(index,item){
                nums.push($(item).html());
            });
            for (var i=0;i<hots.length;i++){
                all += parseInt(hots[i])*parseInt(nums[i])
            }
            if(all == 0){
                layer.open({
                    content: '请选择菜品！'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                return false;
            }
        //     var url = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id="+stall_id+"&all="+all;
        // console.log(url);
        // return false;
            window.location.href = "/index.php?m=Home&c=Stall&a=confirmOrder&stall_id="+stall_id+"&all="+all;
        }
    })
</script>
</body>

</html>