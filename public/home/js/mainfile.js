/*订单中心 tab切换*/
$(".orderCenterTopTabBox > span").click(function() {
	$(this).addClass("c-zhu");
	$(this).parent().siblings().find("span").removeClass("c-zhu");
	$(this).siblings(".orderCenterTopTabLine").show();
	$(this).parent().siblings().find(".orderCenterTopTabLine").hide();
	var index = $(this).parent().index();
	$(".orderCenterContent").hide();
	$(".orderCenterContent").eq(index).show();
});
/*显示取餐码弹窗*/

$(".qcBtn").click(function() {
	var id = $(this).attr("data-id");
    $("#orderIds").val(id);
	$(".mask").show();
	$(".qcmLay").fadeIn();
	$(".qcmInpBox > input").eq(0).focus();
});

/*取消弹窗*/
$(".qcmCancle").click(function() {
	$(".mask").hide();
	$(".qcmLay").fadeOut();
});

/*取货码输入监听*/
function input(e) {
	if($(e).val().length > 1) {
		$(e).val("")
	}
	var index = $(e).index();
	if(index == 3) {
		$(e).blur()
	} else {
		if($(e).val().length > 0) {
			$(e).next().focus()
		}
	}
}

/*周餐订单 tab切换*/

$(".orderCenterTopOneTabBox > span").click(function() {
	$(this).addClass("c-zhu");
	$(this).parent().siblings().find("span").removeClass("c-zhu");
	$(this).siblings(".orderCenterTopTabLine").show();
	$(this).parent().siblings().find(".orderCenterTopTabLine").hide();
	var index = $(this).parent().index();
	if(index != 0){
		$(".orderCenterTopTowTab").hide();
	}else{
		$(".orderCenterTopTowTab").show();
	}
	$(".tcMainTabBox").hide();
	$(".tcMainTabBox").eq(index).show();
});

$(".orderCenterTopTowTabBox > span").click(function(){
	$(this).addClass("c-zhu");
	$(this).parent().siblings().find("span").removeClass("c-zhu");
	var index = $(this).parent().index();
	$(".ysx").hide();
	$(".ysx").eq(index).show();
});



/*配送员任务tab切换*/
$(".psyTopTabBox > span").click(function() {
	$(this).addClass("c-zhu");
	$(this).parent().siblings().find("span").removeClass("c-zhu");
	$(this).siblings(".orderCenterTopTabLine").show();
	$(this).parent().siblings().find(".orderCenterTopTabLine").hide();
	var index = $(this).parent().index();
	$(".psyConBox").hide();
	$(".psyConBox").eq(index).show();
});

/*配送员 类型和性别切换*/

$(".psyTypeBox").click(function(){
	$(this).find("img").attr("src","../images/check_button_s.png");	
	$(this).siblings(".psyTypeBox").find("img").attr("src","../images/check_button_uns.png")
});



/*配送员入驻上传图片*/
//选择图片，马上预览
function xmTanUploadImg(obj) {
    var file = obj.files[0];
    
    console.log(obj);
    console.log(file);
    console.log("file.size = " + file.size);  //file.size 单位为byte

    var reader = new FileReader();

    //读取文件过程方法
    reader.onloadstart = function (e) {
        console.log("开始读取....");
    };
    reader.onprogress = function (e) {
        console.log("正在读取中....");
    };
    reader.onabort = function (e) {
        console.log("中断读取....");
    };
    reader.onerror = function (e) {
        console.log("读取异常....");
    };
    reader.onload = function (e) {
        console.log("成功读取....");
        //var img = document.getElementById("uploadImg");
       $(obj).siblings("img").attr("src",e.target.result)
        //img.src = e.target.result;
        //或者 img.src = this.result;  //e.target == this
    };

    reader.readAsDataURL(file);
    console.log(file);
}







/*档口二级分类切换*/
$(".ptdkMainBottomLeft > div").click(function(){
	$(this).addClass("lxSel").siblings().removeClass("lxSel")
});


var width = $(".goodsDetailImg").width();
$(".goodsDetailImg").width(width + "px")


/*确认订单切换*/
$(".confirmOrderTopTitleList").click(function(){
	console.log(1);
	$(this).addClass("confirmOrderTabSel").siblings().removeClass("confirmOrderTabSel")
	var index = $(this).index();
    var peisong = $('.express_money').val();
    //var allmoney = $('.allmoney').val();
    var allmoneys = $('.allmoneys').val();
	var type = $(this).attr('type');
	
    if(type == 1){
        $(".confirmOrderTimeSelBox").show();
        $(".ptype").val(1);
        $(".confirmOrderTabConA").show();
        $(".confirmOrderTabConD").hide();
        $(".peisong").show();
        var money = parseFloat(allmoneys)+parseFloat(peisong);
        //$(".allmoney").val(parseFloat(allmoney)+parseFloat(peisong));
        $(".allmoneys").val(money.toFixed(2));
        $(".allMoneys").text(money.toFixed(2));
    }else{
		$(".confirmOrderTimeSelBox").hide();
        $(".ptype").val(2);
        $(".confirmOrderTabConD").show();
        $(".confirmOrderTabConA").hide();
        $(".peisong").hide();
        var money = parseFloat(allmoneys)-parseFloat(peisong);
        //$(".allmoney").val(parseFloat(allmoney)-parseFloat(peisong));
        $(".allmoneys").val(money.toFixed(2));
        $(".allMoneys").text(money.toFixed(2));
    }
});


/*我的订单 tab切换*/

$(".myOrderType").click(function(){
	$(this).addClass("myOrderTypeSel").siblings().removeClass("myOrderTypeSel");
	var index = $(this).index();
	$(".myOrderListBox").hide();
	$(".myOrderListBox").eq(index).show();
});



/*评价上传图片*/
//选择图片，马上预览
var files = [];
function xmTanUploadImg1(obj) {
    var file = obj.files[0];
    var reader = new FileReader();
    //读取文件过程方法
    reader.onloadstart = function (e) {
        //console.log("开始读取....");
    };
    reader.onprogress = function (e) {
        //console.log("正在读取中....");
    };
    reader.onabort = function (e) {
        //console.log("中断读取....");
    };
    reader.onerror = function (e) {
        //console.log("读取异常....");
    };
    reader.onload = function (e) {
        //console.log("成功读取....");
        var length = files.length;
        
        /*最大上传5张*/
        if(length == 4){
			$(".hideUploadImgBox").hide();
		}
		var str = '<div class="uploadImgImgBox">'+
			'<img class="uploadImgImg" src="'+ e.target.result +'"/>'+
			'<img class="delImg" onclick="delFile(this)" src="/public/home/images/img_del.png"/>'+
			'</div>';
		$(obj).parent().before(str);
        var width = $(".uploadImgImgBox").width();
        $(".uploadImgImgBox ").height(width + "px")
		var formdata = new FormData();
		formdata.append('file',file);
		$.ajax({
			type:'post',
			url:'/index.php?m=Home&c=Myorder&a=upload',
			data:formdata,
			processData: false,
			contentType : false,
			success:function(res){
				var url= res.data.url;
				files.push(url);
				$("#files").val(files);
			}
		});
    };
    reader.readAsDataURL(file);
    $(".uploadImgImgFile").val("");
}

function delFile(e){
    var index = $(e).parents().index();
    $.ajax({
        type:'post',
        url:'/index.php?m=Home&c=Myorder&a=delFile',
        data:{imageUrl:files[index]},
        success:function(res){
        	if(res == 1){
                $(e).parent().remove();
                files.splice(index,1);
                $("#files").val(files);
                var length = files.length;
                if(length <= 5){
                    $(".hideUploadImgBox").show();
                }
			}
        }
    });
}

/*删除已上传图片*/
function delImg(obj){
	$(this).parent(".uploadImgImgBox").remove();
}

$(".eveSenderEveXingBox img").click(function () {
    $(this).attr("src","/public/home/images/star_icon24.png");
    $(this).prevAll().attr("src","/public/home/images/star_icon24.png");
    $(this).nextAll().attr("src","/public/home/images/star_icon40px_uns.png");
    var index = $(this).index() + 1;
    $("#eveSenderEveXing").val(index);
});

$(".productsXing img").click(function () {
    $(this).attr("src","/public/home/images/star_icon24.png");
    $(this).prevAll().attr("src","/public/home/images/star_icon24.png");
    $(this).nextAll().attr("src","/public/home/images/star_icon40px_uns.png");
    var index = $(this).index() + 1;
    $("#productsXing").val(index);
});

$(".serviceXing img").click(function () {
    $(this).attr("src","/public/home/images/star_icon24.png");
    $(this).prevAll().attr("src","/public/home/images/star_icon24.png");
    $(this).nextAll().attr("src","/public/home/images/star_icon40px_uns.png");
    var index = $(this).index() + 1;
    $("#serviceXing").val(index);
});

