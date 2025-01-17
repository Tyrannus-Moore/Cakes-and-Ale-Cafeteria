<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8" />
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>全部评价</title>
    <base href="/public/home/" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <script src="js/jquery-2.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="layermobile/need/layer.css"/>
    <script src="layermobile/layer.js" type="text/javascript" charset="utf-8"></script>
</head>
<style>
    .itemlisto {
        padding-bottom: 0;
    }

    .imgs {
        width: 100%;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .imgs img {
        width: 10rem;
        height: 6rem;
    }
</style>
<body>
<div class="itemlisto">
    <input type="hidden" class="totalPage" value="">
</div>

<input type="hidden" class="dishes_id" value="<?php echo ($dishes_id); ?>">
<script>
var dishes_id = $('.dishes_id').val();
// $(function(){ pushHistory(); window.addEventListener("popstate", function(e) { 
// //回调函数中实现需要的功能 
// location.href="<?php echo U('dishesDetail');?>&dishes_id="+dishes_id; 
// //在这里指定其返回的地址 
// }, false); }); function pushHistory() { var state = { title: "title", url: "/index.php?m=Home&amp;c=Stall&amp;a=evaluationG&amp;dishes_id=159" }; window.history.pushState(state, state.title, state.url); }


    
    //分页
    var p; //设置当前页数
    $(function () {
        p = 1;
        $.ajax({
            url:"<?php echo U('evaluationD');?>",
            data:{
                dishes_id:dishes_id
            },
            success:function(data) {
                // console.log(data);return false
                addList(data);
                totalPage = data.data.totalPage;
                $(".totalPage").val(totalPage);
            }
        });
    });
    // 评价
    function addList(data) {
        var str = '';
        if(data.data.list == ''){
            str += ' <div class="imgs">';
            str += ' <img src="/public/home/images/icon_wjg.png" alt="" />';
            str += '  <p class="greyColor">暂无评价</p>';
            str += '  </div>';
        }else{
            $.each(data.data.list,function(index,item){
                console.log(item.images);
                str += '<div class="userEveListBox p-10 bac-fff">';
                str += '<div class="userEveListTop clearfix">';
                str += '<div class="userHeadImgBox fl">';
                str += '<img class="userHeadImg" src="'+item.member_list_headpic+'" />';
                str += '</div>';
                str += '<div class="userEveUserName fl">'+item.member_list_nickname+'</div>';
                str += '<div class="xingBox fr clearfix mt-7">';
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
                str += '</div>';
                str += '<div class="userEveCon">'+item.content+'</div>';
                str += '<div class="userEveImgBox mt-5">';
                if(item.images != null){
                    for( var j=0; j< item.images.length;j++){
                        str += '<img class="userEveImg" src="'+item.images[j]+'" />';
                    }
                }
                str += '</div>';
                str += '</div>';
            });
        }
        $(".itemlisto").append(str);
        var userEveImgBox = $(".userEveImgBox");
        for(var i = 0; i < userEveImgBox.length; i++){
            var size = $(userEveImgBox[i]).find("Img").size();
            console.log(size);
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
                    url: "<?php echo U('eitemListG');?>",
                    data: {
                        k: p,
                        dishes_id:dishes_id
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
</script>
<script type="text/javascript">
    $(".imgs").height($(window).height() - 50);
</script>
</body>

</html>