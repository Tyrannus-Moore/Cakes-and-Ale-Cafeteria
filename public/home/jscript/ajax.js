/************************************************************* 所有带确认的ajax提交btn ********************************************************/
/* get执行并返回结果，执行后带跳转 */
$(function () {
    $('.confirm-rst-url-btn').click(function () {
        var $url = this.href,
            $info = $(this).data('info');
        layer.open({
            content: $info
            ,btn: ['确定', '不要']
            ,hade: 'background-color: rgba(0,0,0,.3)'
            ,yes: function(index){
                $.get($url, function (data) {
                    if (data.status) {
                        layer.open({
                            type: 0,
                            time: 1,
                            content: data.info,
                            skin: 'msg',
                            end: function() {
                                location.href = data.url;
                            }
                        });
                    } else {
                        layer.open({
                            type: 0,
                            time: 1,
                            content: data.info,
                            skin: 'msg',
                            end: function() {
                                layer.close(index);
                            }
                        });
                    }
                }, "json");
            }
        });
        return false;
    });
});

/*************************************************************************** 所有ajaxForm提交 ********************************************************/
$(function(){
    $('.ajaxForm').ajaxForm({
        beforeSubmit:mask,
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
//反馈验证
$(function(){
    $('.feedbackForm').ajaxForm({
        beforeSubmit:feedbackAjaxForm,
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
//个人资料验证
$(function(){
    $('.personalForm').ajaxForm({
        beforeSubmit:personalAjaxForm,
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
//收货地址验证
$(function(){
    $('.addressForm').ajaxForm({
        beforeSubmit:addressAjaxForm,
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
//配送员入驻验证
$(function(){
    $('.deliveryForm').ajaxForm({
        beforeSubmit:deliveryAjaxForm,
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
var  zzz ='';

/*************************************************************************** 所有ajax提交验证 ********************************************************/
//遮罩
function mask(){
    zzz = layer.open({
        type: 2
        ,content: '加载中'
        ,shadeClose: false,
    });
}

//反馈验证
function feedbackAjaxForm() {
    var telphone = $("#telphone").val();
    if(!telphone.match(/^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/)){
        layer.open({
            content: '手机号码格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#telphone").focus();
        return false;
    }
    layer.open({
        type: 2
        ,content: '加载中'
        ,shadeClose: false,
    });
}
//个人资料验证
function personalAjaxForm() {
    if($("#member_list_nickname").val() == ''){
        layer.open({
            content: '昵称不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    if($("#member_list_sex").val() == 3){
        layer.open({
            content: '性别必填！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    var birthary_time = $("#birthary_time").val();
    if(birthary_time == ''){
        layer.open({
            content: '生日不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    var telphone = $("#telphone").val();
    if(telphone == ''){
        layer.open({
            content: '手机号不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    if(!telphone.match(/^(((11[0-9]{1})|(12[0-9]{1})|(13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/)){
        layer.open({
            content: '手机号码格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#telphone").focus();
        return false;
    }
    var faculty = $("#faculty").val();
    if(faculty == ''){
        layer.open({
            content: '专业不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    var member_class = $("#member_class").val();
    if(member_class == ''){
        layer.open({
            content: '班级不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    //身高
    var stature = $("#stature").val();
    if(stature != ''){
        if(stature < 100 || stature > 200){
            layer.open({
                content: '身高填写不正确！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
    }
    //体重
    var weight = $("#weight").val();
    if(weight != ''){
        if(weight < 30 || weight > 120){
            layer.open({
                content: '体重填写不正确！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
    }

    layer.open({
        type: 2
        ,content: '加载中'
    });
    $(".save").attr('disabled',true);
}
//收货地址验证
function addressAjaxForm() {
    //姓名
    if($("#name").val() == ''){
        layer.open({
            content: '姓名不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    //手机号
    var phone = $("#phone").val();
    if(!phone.match(/^(((11[0-9]{1})|(12[0-9]{1})|(13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/)){
        layer.open({
            content: '手机号码格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#phone").focus();
        return false;
    }
    var cityid = $("#cityid").val();
    if(cityid == ''){
        layer.open({
            content: '服务地址不能为空！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        return false;
    }
    layer.open({
        type: 2
        ,content: '加载中'
        ,shadeClose: false,
    });
    $(".save").attr('disabled',true);
}
//配送员入驻验证
function deliveryAjaxForm() {
    var member_name = $("#member_name").val();
    if(!member_name.match(/^[\u4e00-\u9fa5]{2,4}$/)){
        layer.open({
            content: '姓名格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#member_name").focus();
        return false;
    }
    var telphone = $("#telphone").val();
    if(!telphone.match(/^(((11[0-9]{1})|(12[0-9]{1})|(13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/)){
        layer.open({
            content: '手机号码格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#telphone").focus();
        return false;
    }
    var id_card = $("#id_card").val();
    if(!id_card.match(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/)){
        layer.open({
            content: '身份证格式不正确！'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
        //$("#id_card").focus();
        return false;
    }
    var aaa = $("#aaa").val();
    if(aaa == 2){
    	var card_zheng = $("#card_zheng").val();
        var card_fan = $("#card_fan").val();
        if(card_zheng == '' || card_fan == ''){
            layer.open({
                content: '身份证照片必填！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
    }
    
    layer.open({
        type: 2
        ,content: '加载中'
        ,shadeClose:false
    });
    $(".save").attr('disabled',true);
}

/*************************************************************************** 所有ajax提交返回操作 ********************************************************/
//失败不跳转
function complete2(data) {
    if(data.status == 1){
        layer.open({
            type: 0,
            time: 1,
            content: data.info,
            skin: 'msg',
            end: function() {
                location.href = data.url;
            }
        });
    }else{
        layer.open({
            type: 0,
            time: 2,
            content: data.info,
            skin: 'msg',
            end: function() {
            	layer.closeAll();
                $(".save").attr('disabled',false);
            }
        });
    }
}

//直接跳转
function complete(data){
    if(data.status == 1){
        location.href = data.url;
    }
}