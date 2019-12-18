/************************************************************* 所有带确认的ajax提交btn ********************************************************/
/* get执行并返回结果，执行后不带跳转 */
$(function () {
    $('.rst-btn').click(function () {
        var $url = this.href;
        $.get($url, function (data) {
            if (data.status == 1) {
                layer.alert(data.info, {icon: 6});
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
/* get执行并返回结果，执行后带跳转 */
$(function () {
    $('.rst-url-btn').click(function () {
        var $url = this.href;
        $.get($url, function (data) {
            if (data.status) {
                layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
                    layer.close(index);
                    window.location.href = data.url;
                });
            } else {
                layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
                    layer.close(index);
                });
            }
        }, "json");
        return false;
    });
});
/* 直接跳转 */
$(function () {
    $(".confirm-btn").click(function () {
        var $url = this.href,
            $info = $(this).data('info');
        layer.confirm($info, {icon: 3}, function (index) {
            layer.close(index);
            window.location.href = $url;
        });
        return false;
    });
});
/* post执行并返回结果，执行后不带跳转 */
$(function () {
    $('.confirm-rst-btn').click(function () {
        var $url = this.href,
            $info = $(this).data('info');
        layer.confirm($info, {icon: 3}, function (index) {
            layer.close(index);
            $.post($url, {}, function (data) {
                layer.alert(data.info, {icon: 6});
            }, "json");
        });
        return false;
    });
});
/* get执行并返回结果，执行后带跳转 */
$(function () {
    $('.confirm-rst-url-btn').click(function () {
        var $url = this.href,
            $info = $(this).data('info');
        layer.confirm($info, {icon: 3}, function (index) {
            layer.close(index);
            $.get($url, function (data) {
                if (data.status) {
                    layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
                        layer.close(index);
                        window.location.href = data.url;
                    });
                } else {
                    layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
                        layer.close(index);
                    });
                }
            }, "json");
        });
        return false;
    });
});
/* 显示状态操作 */
$(function () {
    $(".display-btn").click(function () {
        var $url = this.href,
            val = $(this).data('id');
        $.post($url, {x: val}, function (data) {
            if (data.status) {
                if (data.info == '状态禁止') {
                    var a = '<button class="btn btn-minier btn-danger">隐藏</button>';
                    $('#zt' + val).html(a);
                    return false;
                } else {
                    var b = '<button class="btn btn-minier btn-yellow">显示</button>';
                    $('#zt' + val).html(b);
                    return false;
                }
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });

});
//管理的开启和禁用
$(function () {
    $(".open-btn").click(function () {
        var $url = this.href,
            val = $(this).data('id');
        $.post($url, {x: val}, function (data) {
            if (data.status) {
                if (data.info == '状态禁止') {
                    var a = '<button class="btn btn-minier btn-danger">禁用</button>';
                    $('#zt' + val).html(a);
                    return false;
                } else {
                    var b = '<button class="btn btn-minier btn-yellow">开启</button>';
                    $('#zt' + val).html(b);
                    return false;
                }
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});

//商家的开启和禁用
$(function () {
    $(".business-btn").click(function () {
        var $url = this.href,
            val = $(this).data('id');
        $.post($url, {x: val}, function (data) {
            if (data.status) {
                if (data.info == '状态冻结') {
                    var a = '<button class="btn btn-minier btn-danger">冻结</button>';
                    $('#zt' + val).html(a);
                    return false;
                } else {
                    var b = '<button class="btn btn-minier btn-yellow">正常</button>';
                    $('#zt' + val).html(b);
                    return false;
                }
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
//会员组注册是否需要手动认证
$(function () {
    $(".che-btn").click(function () {
        var $url = this.href,
            val = $(this).data('id');
        $.post($url, {x: val}, function (data) {
            if (data.status) {
                if (data.info == '否') {
                    var a = '<button class="btn btn-minier btn-danger">否</button>';
                    $('#ch' + val).html(a);
                    return false;
                } else {
                    var b = '<button class="btn btn-minier btn-yellow">是</button>';
                    $('#ch' + val).html(b);
                    return false;
                }
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
/*************************************************************************** 弹框********************************************************/
$(function(){
    $(".class-btn").click(function(){
        var $url = this.href,
            val = $(this).data('id');
        $.post($url, {new_cate_id: val}, function (data) {
            if (data.status == 1) {
                $("#schooledits").show(300);
                $("input[name='cate_name']").val(data.cate_name);
                $("input[name='new_cate_id']").val(val);
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
$(function(){
    $(".school-btn").click(function(){
        var $url = this.href,
        val = $(this).data('id');
        $.post($url, {school_id: val}, function (data) {
            if (data.status == 1) {
                $("#schooledits").show(300);
                $("input[name='name']").val(data.name);                
                $("input[name='address']").val(data.address);                
                $("input[name='school_id']").val(val);                               
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
$(function(){
    $(".faculty-btn").click(function(){
        var $url = this.href,
        val = $(this).data('id');
        $.post($url, {faculty_id: val}, function (data) {
            if (data.status == 1) {
                $("#facultyedits").show(300);
                $("input[name='faculty_name']").val(data.faculty_name);                
                $("input[name='num']").val(data.num);                
                $("input[name='faculty_id']").val(val);                               
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
$(function(){
    $(".category-btn").click(function(){
        var $url = this.href,
        val = $(this).data('id');
        $.post($url, {cat_id: val}, function (data) {
            if (data.status == 1) {
                $("#categoryedits").show(300);
                $("input[name='cat_name']").val(data.cat_name);                               
                $("input[name='cat_id']").val(val);                               
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});
//模态框状态
$(document).ready(function () {
    $("#schooledits").hide();
    $("#gbb1").click(function () {
        $("#schooledits").hide(200);
    });
    $("#facultyedits").hide();
    $("#gbb2").click(function () {
        $("#facultyedits").hide(200);
    });
    $("#proviceedits").hide();
    $("#gbb3").click(function () {
        $("#categoryedits").hide(200);
    });
    $("#dayins").hide();
    $("#gbb4").click(function () {
        $("#dayins").hide(200);
    });
});
/*************************************************************************** 所有状态类的ajax提交btn ********************************************************/
function CheckAll(form) {
    for (var i = 0; i < form.elements.length; i++) {
        var e = form.elements[i];
        if (e.Name != 'chkAll' && e.disabled == false) {
            e.checked = form.chkAll.checked;
        }
    }
}

//弹窗确定功能
 $('#btnsubmitz').click(function(){
        layer.confirm('确认此操作', {
            btn: ['确定','取消'], //按钮
            shade: false //不显示遮罩
        }, function(index){
            $('.ajaxForm').submit();
        }, function(index){
            layer.close(index);
        })
});

/*************************************************************************** 所有ajaxForm提交 ********************************************************/
/* 通用表单不带检查操作，失败不跳转 */
$(function(){
    $('.ajaxForm').ajaxForm({
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 登录专业 */
$(function(){
    $('.inlogin').ajaxForm({
        success: inlogin, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 通用表单不带检查操作，失败跳转 */
$(function(){
    $('.ajaxForm3').ajaxForm({
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
$(function () {
    $('.ajaxForm2').ajaxForm({
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 通用表单检查操作，失败不跳转 */
$(function(){
    $('.newForm').ajaxForm({
        beforeSubmit: newBeforeForm,
        success: successComplete,
        dataType: 'json'
    });
});

/* 修改个人信息表单检查 */
$(function () {
    $('.userEdit').ajaxForm({
        beforeSubmit: userEditForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 站点设置 */
$(function () {
    $('.setting').ajaxForm({
        beforeSubmit: settingForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 管理员的添加和修改 */
$(function () {
    $('.adminform').ajaxForm({
        beforeSubmit: adminformForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});

/* 会员编辑表单，带检查 */
$(function () {
    $('.memberform').ajaxForm({
        beforeSubmit: checkmemberForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 管理员的添加和修改 */
$(function () {
    $('.regionform').ajaxForm({
        // beforeSubmit: adminformForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 广告修改 */
$(function () {
    $('.plug').ajaxForm({
        beforeSubmit: plugForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: complete, // 这是提交后的方法
        dataType: 'json'
    });
});
/*地区添加修改*/
$(function () {
    $('.areaForm').ajaxForm({
        success: complete2, // 这是提交后的方法
        dataType: 'json'
    });
});
/* 轮播图修改 */
$(function(){
    $('.banner').ajaxForm({
        beforeSubmit: bannerForm,
        success: successComplete,
        dataType: 'json'
    });
});
/* 轮播图修改 */
$(function(){
    $('.deliver').ajaxForm({
        beforeSubmit: deliverForm,
        success: completez,
        dataType: 'json'
    });
});

/* 审核验证 */
$(function () {
    $('.shenhe').ajaxForm({
        // beforeSubmit: adminformForm, // 此方法主要是提交前执行的方法，根据需要设置
        success: completel8, // 这是提交后的方法
        dataType: 'json'
    });
});


var  zzz ='';
//遮罩
function checkmemberFormz(){
    zzz = layer.load(1, {shade: [0.8, '#393D49']});
}

//##################################################################提交之前的验证##########################################################
//admin表单检查
function checkadminForm() {
    var admin_username = $.trim($('input[name="admin_username"]').val()); //获取INPUT值
    var myReg = /^[\u4e00-\u9fa5]+$/;//验证中文
    if (admin_username.indexOf(" ") >= 0) {
        layer.alert('登录用户名包含了空格，请重新输入', {icon: 5}, function (index) {
            layer.close(index);
            $('#admin_username').focus();
        });
        return false;
    }
    if (myReg.test(admin_username)) {
        layer.alert('用户名必须是字母，数字，符号', {icon: 5}, function (index) {
            layer.close(index);
            $('#admin_username').focus();
        });
        return false;
    }
    if (!$("#admin_tel").val().match(/^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/)) {
        layer.alert('电话号码格式不正确', {icon: 5}, function (index) {
            layer.close(index);
            $('#admin_tel').focus();
        });
        return false;
    }
}

//商家提交前验证
function newBeforeForm() {
    var ma_account = $("#ma_account").val();
    var malen = ma_account.length;
    if(malen<6 || malen>20){
        layer.alert("账号应为6-20位的大小写字母或数字组合");
        $(this).val("");
        return false;
    }
}
//商家提交后
function successComplete(data) {
    if (data.status == 1) {
        layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
            layer.close(index);
            window.location.href = data.url;
        });
    } else {
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            layer.close(index);

        });
    }
}
//修改个人信息表单检查
function userEditForm() {
	
	var pwd =$("#admin_pwd").val();
	
	//var reg =  /^[0-9a-zA-Z]*$/g;  //判断字符串是否为数字和字母组合     //判断正整数 /^[1-9]+[0-9]*]*$/
	 //var reg=/^(([0-9]+)|([a-z]+)|([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i;
	if($.trim(pwd)){
		 var reg= /^[0-9a-zA-Z]*$/g;
		 var len = pwd.length;
	     if(reg.test(pwd) && len>5 && len<20)  
	     {  
	    	 return true; 
	     }else{
	        layer.alert("密码应为6-20位的大小写字母或数字组合");
	        $(this).val("");
	        return false; 
	     } 
	}
    var admin_tel = $('#admin_tel').val();
	if (!$("#admin_tel").val().match(/^1[34578]\d{9}$/)) {
        layer.alert('手机号码格式不正确', {icon: 5}, function (index) {
            layer.close(index);
            $('#admin_tel').focus();
        });
        return false;
    }
    
}
//站点设置
function settingForm() {
	
	// var cfg_basehost =$("#cfg_basehost").val();
	// if (!$("#cfg_basehost").val().match(/^http:\/\/([a-zA-Z\d][a-zA-Z\d-_]+\.)+[a-zA-Z\d-_][^ ]*$/)) {
 //        layer.alert('网址格式不正确', {icon: 5}, function (index) {
 //            layer.close(index);
 //            $('#cfg_basehost').focus();
 //        });
 //        return false;
 //    }
 //    var cfg_tel = $('#cfg_tel').val();
	// if (!$("#cfg_tel").val().match(/^1[34578]\d{9}$/)) {
 //        layer.alert('手机号码格式不正确', {icon: 5}, function (index) {
 //            layer.close(index);
 //            $('#cfg_tel').focus();
 //        });
 //        return false;
 //    }
    
}
//管理员的添加和修改
function adminformForm() {
    var admin_tel = $('#admin_tel').val();

	if (!$("#admin_tel").val().match(/^1[34578]\d{9}$/)) {
        layer.alert('手机号码格式不正确', {icon: 5}, function (index) {
            layer.close(index);
            $('#admin_tel').focus();
        });
        return false;
    }
    
}

//管理员的修改
function checkmemberForm() {
    //验证手机号格式是否正确
	if (!$("#member_list_account").val().match(/^1[34578]\d{9}$/)) {
        layer.alert('手机号码格式不正确', {icon: 5}, function (index) {
            layer.close(index);
            $('#member_list_account').focus();
        });
        return false;
    }
	var incardno = $("#idcardno").val();
	if(incardno){
		//验证身份证号格式是否正确
		if (!incardno.match(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/)) {
	        layer.alert('身份证号码格式不正确', {icon: 5}, function (index) {
	            layer.close(index);
	            $('#idcardno').focus();
	        });
	        return false;
	    }
	}
    
}
//轮播图修改
function bannerForm(){
    if (!$("#url").val().match(/^http:\/\/([a-zA-Z\d][a-zA-Z\d-_]+\.)+[a-zA-Z\d-_][^ ]*$/)) {
        layer.alert('链接地址格式不正确', {icon: 5}, function (index) {
            layer.close(index);
            $('#url').focus();
        });
        return false;
    }
    //遮罩
    var  zzz ='';
    zzz = layer.load(1, {shade: [0.8, '#393D49']});    
}
//广告的修改
function plugForm() {
    //验证链接地址是否正确
	// if (!$("#plug_ad_url").val().match(/^http:\/\/([a-zA-Z\d][a-zA-Z\d-_]+\.)+[a-zA-Z\d-_][^ ]*$/)) {
 //        layer.alert('链接地址格式不正确', {icon: 5}, function (index) {
 //            layer.close(index);
 //            $('#plug_ad_url').focus();
 //        });
 //        return false;
 //    }
	// //遮罩
	// var  zzz ='';
	// zzz = layer.load(1, {shade: [0.8, '#393D49']});    
}
//遮罩
//var  zzz  = layer.load(1, {shade: [0.8, '#393D49']});

function ajaxFormzzForm(){
	var desc = $('#desc').val();
	if(desc.length > 100){
		layer.alert('文字超过一百字限制');
		return false;
	}
}
function deliverForm(){
    //判断是托运的时候 手机号格式
    var type = $(".type  option:selected").val();
    if(type == 2){
        var telphone = $("input[name=telphone]").val();
        if(telphone.length!=11){
            layer.msg('手机号为11位数字');
            return false;
        }
    }
}
//##################################################################提交成功##########################################################
//失败跳转
function completez(data){
    if (data.status == 1) {
         layer.close(zzz);
        layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
            layer.close(index);    
            layer.close(zzz);
            var index2 = parent.layer.getFrameIndex(window.name); //获取窗口索引         
            //layer.index(index2-1).reload();
           // parent.layer.closeAll();//关闭所有layer窗口
            //parent.location.href = data.url;
              parent.layer.close(index2-1);
            //parent.location.reload();//刷新父窗口    
           // parent.ifranmek( data.url,'添加相册')
            window.location.href = data.url;
            parent.location.reload();//刷新父窗口  
            parent.layer.close(index2);
        });
    } else {
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            layer.close(index);
             layer.close(zzz);
           $("#erhuo").attr("disabled",false);
        });
        return false;
    }
}
//登录专用
function inlogin(data){
    if(data.status == 1){
		layer.msg(data.info, {icon: 1,time: 1000}, function(){
			// layer.close(index);
            window.location.href = data.url;
		}); 
    }else{
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            // layer.close(index);
            window.location.href = data.url;
        });
        return false;
    }
}

//失败跳转
function complete(data){
    if (data.status == 1) {
        layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
        	
            layer.close(index);
            window.location.href = data.url;
        });
    } else {
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            layer.close(index);
            window.location.href = data.url;
        });
        return false;
    }
}
//失败不跳转
function complete2(data) {
    var  zzz  = layer.load(1, {shade: [0.8, '#393D49']});
    if (data.status == 1) {
        layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
            layer.close(index);
            layer.close(zzz);
            window.location.href = data.url;
        });
    } else {
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            layer.close(index);
            layer.close(zzz);
        });
    }
}
//失败不跳转,验证码刷新
function complete3(data) {
    if (data.status == 1) {
        window.location.href = data.url;
    } else {
        $("#verify").val('');
        $("#verify_img").click();
        layer.alert(data.info, {icon: 5});
    }
}
//关闭窗口
function completel8(data){
    if (data.status == 1){
        //layer.close(zzz);
        layer.msg(data.info, {time:1000,icon: 1},function(){
            var index2 = parent.layer.getFrameIndex(window.name); //获取窗口索引
            //layer.index(index2-1).reload();
            //parent.layer.closeAll();//关闭所有layer窗口
            parent.location.href = data.url;
            //parent.location.reload();//刷新父窗口
            //parent.layer.close(index2);
        });
    } else {
        layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
            // layer.close(index);
            // parent.layer.close(index2);
            parent.location.reload();//刷新父窗口
            window.location.href = data.url;
        });
        return false;
    }
}
/*************************************************************************** 所有css操作 ********************************************************/
/* 菜单样式 */
$(function () {
    //插入header-nav
    $('#sidebar2').insertBefore('.page-content');
    $('.navbar-toggle[data-target="#sidebar2"]').insertAfter('#menu-toggler');
    //固定
    $(document).on('settings.ace.two_menu', function (e, event_name, event_val) {
        if (event_name == 'sidebar_fixed') {
            if ($('#sidebar').hasClass('sidebar-fixed')) {
                $('#sidebar2').addClass('sidebar-fixed');
                $('#navbar').addClass('h-navbar');
            }
            else {
                $('#sidebar2').removeClass('sidebar-fixed');
                $('#navbar').removeClass('h-navbar');
            }
        }
    }).triggerHandler('settings.ace.two_menu', ['sidebar_fixed', $('#sidebar').hasClass('sidebar-fixed')]);
});
/* 多选判断 */
function unselectall() {
    if (document.myform.chkAll.checked) {
        document.myform.chkAll.checked = document.myform.chkAll.checked & 0;
    }
}
function CheckAll(form) {
    for (var i = 0; i < form.elements.length; i++) {
        var e = form.elements[i];
        if (e.Name != 'chkAll' && e.disabled == false) {
            e.checked = form.chkAll.checked;
        }
    }
}
/* 权限配置 */
$(function () {
    //动态选择框，上下级选中状态变化
    $('input.checkbox-parent').on('change', function () {
        var dataid = $(this).attr("dataid");
        $('input[dataid^=' + dataid + ']').prop('checked', $(this).is(':checked'));
    });
    $('input.checkbox-child').on('change', function () {
        var dataid = $(this).attr("dataid");
        dataid = dataid.substring(0, dataid.lastIndexOf("-"));
        var parent = $('input[dataid=' + dataid + ']');
        if ($(this).is(':checked')) {
            parent.prop('checked', true);
            //循环到顶级
            while (dataid.lastIndexOf("-") != 2) {
                dataid = dataid.substring(0, dataid.lastIndexOf("-"));
                parent = $('input[dataid=' + dataid + ']');
                parent.prop('checked', true);
            }
        } else {
            //父级
            if ($('input[dataid^=' + dataid + '-]:checked').length == 0) {
                parent.prop('checked', false);
                //循环到顶级
                while (dataid.lastIndexOf("-") != 2) {
                    dataid = dataid.substring(0, dataid.lastIndexOf("-"));
                    parent = $('input[dataid=' + dataid + ']');
                    if ($('input[dataid^=' + dataid + '-]:checked').length == 0) {
                        parent.prop('checked', false);
                    }
                }
            }
        }
    });
});
//模态框状态
$(document).ready(function () {
    $("#myModaledit").hide();
    $("#gb").click(function () {
        $("#myModaledit").hide(200);
    });
    $("#gbb").click(function () {
        $("#myModaledit").hide(200);
    });
    $("#gbbb").click(function () {
        $("#myModaledit").hide(200);
    });
});
$(document).ready(function () {
    $("#myModal").hide();
    $("#gb").click(function () {
        $("#myModal").hide(200);
    });
    $("#gbb").click(function () {
        $("#myModal").hide(200);
    });
    $("#gbbb").click(function () {
        $("#myModal").hide(200);
    });
});
/*************************************************************************** 所有ajax获取编辑数据 ********************************************************/
/* 会员组修改操作 */
$(function () {
    $(".memberedit-btn").click(function () {
        var $url = this.href,
             val = $(this).data('id');
        $.post($url, {member_group_id: val}, function (data) {
            if (data.status == 1) {
                $("#myModaledit").show(300);
                $("#member_lvl_id").val(data.member_lvl_id);
                $("#member_lvl_name").val(data.member_lvl_name);
                $("#member_lvl_royalties").val(data.member_lvl_royalties);
                $("#member_lvl_level").val(data.member_lvl_level);
               // $("#editmember_group_bomlimit").val(data.member_group_bomlimit);
            } else {
                layer.alert(data.info, {icon: 5});
            }
        }, "json");
        return false;
    });
});


/*************************************************************************** 单图/多图操作********************************************************/
/* 单图上传 */
$("#file0").change(function () {
    var objUrl = getObjectURL(this.files[0]);
    console.log("objUrl = " + objUrl);
    if (objUrl) {
        $("#img0").attr("src", objUrl);
    }
});
$("#file1").change(function () {
    var objUrl = getObjectURLs(this.files[0]);
    console.log("objUrl = " + objUrl);
    if (objUrl) {
        $("#img1").attr("src", objUrl);
    }
});
$("#file2").change(function () {
    var objUrl = getObjectURLs2(this.files[0]);
    console.log("objUrl = " + objUrl);
    if (objUrl) {
        $("#img2").attr("src", objUrl);
    }
});
function getObjectURL(file){
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        $("#oldcheckpic").val("nopic");
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        $("#oldcheckpic").val("nopic");
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        $("#oldcheckpic").val("nopic");
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}
function getObjectURLs(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        $("#oldcheckpic1").val("nopic");
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        $("#oldcheckpic1").val("nopic");
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        $("#oldcheckpic1").val("nopic");
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}
function getObjectURLs2(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        $("#oldcheckpic2").val("nopic");
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        $("#oldcheckpic2").val("nopic");
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        $("#oldcheckpic2").val("nopic");
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

function backpic(picurl) {
    $("#img0").attr("src", picurl);//还原修改前的图片
    $("input[name='file0']").val("");//清空文本框的值
    $("input[name='oldcheckpic']").val(picurl);//清空文本框的值
}
function backpic1(picurl){
    $("#img1").attr("src",picurl);//还原修改前的图片
    $("input[name='file1']").val("");//清空文本框的值
    $("input[name='oldcheckpic1']").val(picurl);//清空文本框的值
}
function backpic2(picurl){
    $("#img2").attr("src",picurl);//还原修改前的图片
    $("input[name='file2']").val("");//清空文本框的值
    $("input[name='oldcheckpic2']").val(picurl);//清空文本框的值
}



/*************************************************************************** 数据备份还原********************************************************/
/* 数据库备份、优化、修复 */
(function ($) {
    $("a[id^=optimize_]").click(function () {
        $.get(this.href, function (data) {
            if (data.status) {
                layer.alert(data.info, {icon: 6});
            } else {
                layer.alert(data.info, {icon: 5});
            }
        });
        return false;
    });
    $("a[id^=repair_]").click(function () {
        $.get(this.href, function (data) {
            if (data.status) {
                layer.alert(data.info, {icon: 6});
            } else {
                layer.alert(data.info, {icon: 5});
            }
        });
        return false;
    });

    var $form = $("#export-form"), $export = $("#export"), tables
    $optimize = $("#optimize"), $repair = $("#repair");

    $optimize.add($repair).click(function () {
        $.post(this.href, $form.serialize(), function (data) {
            if (data.status) {
                layer.alert(data.info, {icon: 6,closeBtn:0}, function (index) {
                    layer.close(index);
                });
            } else {
                layer.alert(data.info, {icon: 5,closeBtn:0}, function (index) {
                    layer.close(index);
                });
            }
            setTimeout(function () {
                $('#top-alert').find('button').click();
                $(that).removeClass('disabled').prop('disabled', false);
            }, 1500);
        }, "json");
        return false;
    });

    $export.click(function () {
        $export.children().addClass("disabled");
        $export.children().text("正在发送备份请求...");
        $.post(
            $form.attr("action"),
            $form.serialize(),
            function (data) {
                if (data.status) {
                    tables = data.tables;
                    $export.children().text(data.info + "开始备份，请不要关闭本页面！");
                    backup(data.tab);
                    window.onbeforeunload = function () {
                        return "正在备份数据库，请不要关闭！"
                    }
                } else {
                    layer.alert(data.info, {icon: 5});
                    $export.children().removeClass("disabled");
                    $export.children().text("立即备份");
                    setTimeout(function () {
                        $('#top-alert').find('button').click();
                        $(that).removeClass('disabled').prop('disabled', false);
                    }, 1500);
                }
            },
            "json"
        );
        return false;
    });

    function backup(tab, status) {
        status && showmsg(tab.id, "开始备份...(0%)");
        $.get($form.attr("action"), tab, function (data) {
            if (data.status) {
                showmsg(tab.id, data.info);
                if (!$.isPlainObject(data.tab)) {
                    $export.children().removeClass("disabled");
                    $export.children().text("备份完成，点击重新备份");
                    window.onbeforeunload = null;
                }
                backup(data.tab, tab.id != data.tab.id);
            } else {
                updateAlert(data.info, 'alert-error');
                $export.children().removeClass("disabled");
                $export.children().text("立即备份");
                setTimeout(function () {
                    $('#top-alert').find('button').click();
                    $(that).removeClass('disabled').prop('disabled', false);
                }, 1500);
            }
        }, "json");

    }

    function showmsg(id, msg) {
        $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg);
    }
})(jQuery);
/*************************************************************************** 其它********************************************************/
/* textarea字数提示 */
$(function () {
    $('textarea.limited').maxlength({
        'feedback': '.charsLeft',
    });
    $('textarea.limited1').maxlength({
        'feedback': '.charsLeft1',
    });
    $('textarea.limited2').maxlength({
        'feedback': '.charsLeft2',
    });
    $('textarea.limited3').maxlength({
        'feedback': '.charsLeft3',
    });
    $('textarea.limited4').maxlength({
        'feedback': '.charsLeft4',
    });
    $('textarea.limited5').maxlength({
        'feedback': '.charsLeft5',
    });
});
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});

