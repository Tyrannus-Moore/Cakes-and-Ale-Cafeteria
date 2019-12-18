/*订单中心 tab切换*/
/*$(".orderCenterTopTabBox > span").click(function() {
    $(this).addClass("c-zhu");
    $(this).parent().siblings().find("span").removeClass("c-zhu");
    $(this).siblings(".orderCenterTopTabLine").show();
    $(this).parent().siblings().find(".orderCenterTopTabLine").hide();
    var index = $(this).parent().index();
    //$("#itemIndex").val(index);
    $(".orderCenterContent").hide();
    $(".orderCenterContent").eq(index).show();
    qc = index+1;
    history.pushState({title:"login"}, "login", "/index.php?m=Stall&c=Ptorder&a=order&qc="+qc);
})*/
/*显示取餐码弹窗*/
$('body').on('click','.qcBtn',function () {
    var order_id = $(this).attr("data-id");
    var deliver_type = $(this).attr("data-type");
    $("#order_id").val(order_id);
    $("#deliver_type").val(deliver_type);
    $(".mask").show();
    $(".qcmLay").fadeIn();
    $(".qcmInpBox > input").eq(0).focus();
    return false;
})

/*取消弹窗*/
//$(".qcmCancle").click(function() {
$('body').on('click','.qcmCancle',function () {
    $(".mask").hide();
    $(".qcmLay").fadeOut();
    qingkong();
})

/*取货码输入监听*/
function input(e) {
    if($(e).val().length > 1) {
        $(e).val("")
    }
    if($(e).val().length <= 0){
        $(e).prev().focus()
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

/*$(".qcmInpBox > input").focus(function(){
    console.log("1111")
    var aaa = $(".qcmInpBox > input");
    var arr = [];
    for (var i = 0; i <= aaa.length; i++) {
        if($(aaa[i]).val() == ''){
            if(i == 0){
                return false;
            }else{
                $(aaa[i]).prev().focus();
            }
            
        }
    }
})*/