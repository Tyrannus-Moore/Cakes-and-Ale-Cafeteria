/*************************************************************************** 所有ajaxForm提交 ********************************************************/
$(function(){
    $('.ajaxForm').ajaxForm({
        success: complete,
        dataType: 'json'
    });
});

$(function(){
    $('.ajaxForm1').ajaxForm({
        //beforeSubmit:mask,
        success: complete1,
        dataType: 'json'
    });
});

/*************************************************************************** 提交成功 ********************************************************/
//失败不跳转
function complete(data) {
    if(data.status == 1){
        layer.open({
            content: data.info
            ,skin: 'msg'
            ,time: 2
            ,end:function() {
                window.location.href = data.url;
            }
        });
    }else{
        layer.open({
            content: data.info
            ,skin: 'msg'
            ,time: 2
        });
    }
}

//失败跳转
function complete1(data) {
    if(data.status == 1){
        layer.open({
            content: data.info
            ,skin: 'msg'
            ,time: 2
            ,end:function() {
                window.location.href = data.url;
            }
        });
    }else{
        layer.open({
            content: data.info
            ,skin: 'msg'
            ,time: 2
            ,end:function() {
                window.location.href = data.url;
            }
        });
    }
}