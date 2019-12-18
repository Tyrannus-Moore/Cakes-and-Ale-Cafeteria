$(function () {
    var page = 1;
    var psize = 10;
    var tabLoadEndArray = [false, false, false];
    var tabLenghtArray = [count1, count2, count3];
    var tabScroolTopArray = [0, 0, 0];

    // dropload
    var dropload = $('.khfxWarp').dropload({
        scrollArea: window,
        domUp : {
            domClass : 'dropload-up',
            domRefresh : '<div class="dropload-refresh">↓下拉刷新</div>',
            domUpdate : '<div class="dropload-update">↑释放更新</div>',
            domLoad : '<div class="dropload-load"><span class="loading"></span>加载中...</div>'
        },
        domDown: {
            domClass: 'dropload-down',
            domRefresh: '<div class="dropload-refresh"></div>',
            domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
            domNoData: '<div class="dropload-noData"></div>'
        },
        loadUpFn : function(me){
            //下拉刷新需要调用的函数
            $.ajax({
                type: 'GET',
                data: {itemIndex: itemIndex,psize:psize},
                url: '/index.php?m=Stall&c=Csorder&a=orders',
                dataType: 'json',
                success: function (res) {
                    if(res.code == 1) {
                        var data = res.list;
                        console.log(data);
                        setTimeout(function () {
                            var result = '';
                            for (var i = 0; i < parseInt(data.length); i++) {
                                if (itemIndex == 0) {
                                    result
                                        += '<div class="orderCenterListBox bac-fff mb-15">'
                                        + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id=' + data[i].order_id + '">'
                                        + '  <div class="orderCenterListTime p-10">'
                                        + '    <span>订单编号</span>'
                                        + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                        + '  </div>'
                                        + '  <div class="orderCenterListContent">';
                                    $.each(data[i].goodlist, function (index, value) {
                                        result
                                            += '    <div class="orderCenterProListBox clearfix">'
                                            + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                            + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                            + '      <div class="fr">'
                                            + '        <span>×</span>'
                                            + '        <span>' + value.dishes_nums + '</span>'
                                            + '      </div>'
                                            + '    </div>';
                                    });
                                    result
                                        += '  </div>'
                                        + '</a>'
                                        + '  <div class="orderCenterListBtnBox clearfix">'
                                        + '    <input class="fr orderCenterListBtn bcwcBtn bcwcOnclick" type="button" data-id="' + data[i].order_id + '" data-type="' + data[i].deliver_type + '" value="备餐完成" />';
                                    if (data[i].deliver_type == 1) {
                                        result += '<a href="tel:' + data[i].phone + '"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                    } else {
                                        result += '<a href="tel:' + data[i].telphone + '"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                    }
                                    result
                                        += '  </div>'
                                        + '</div>';
                                } else if (itemIndex == 1) {
                                    result
                                        += '<div class="orderCenterListBox bac-fff mb-15">'
                                        + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id=' + data[i].order_id + '">'
                                        + '  <div class="orderCenterListTime p-10">'
                                        + '    <span>订单编号</span>'
                                        + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                        + '  </div>'
                                        + '  <div class="orderCenterListContent">';
                                    $.each(data[i].goodlist, function (index, value) {
                                        result
                                            += '    <div class="orderCenterProListBox clearfix">'
                                            + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                            + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                            + '      <div class="fr">'
                                            + '        <span>×</span>'
                                            + '        <span>' + value.dishes_nums + '</span>'
                                            + '      </div>'
                                            + '    </div>';
                                    });
                                    result
                                        += '  </div>'
                                        + '</a>'
                                        + '  <div class="orderCenterListBtnBox clearfix">'
                                        + '    <input class="fr orderCenterListBtn bcwcBtn qcBtn" data-id="' + data[i].order_id + '" data-type="' + data[i].deliver_type + '" type="button" value="取餐" />';
                                    if(data[i].qccs == 2){
                                        result+='<input class="fr orderCenterListBtn bcwcBtn qccsBtn" data-id="'+data[i].order_id+'" type="button" value="取餐超时" />';
                                    }
                                    if (data[i].deliver_type == 1) {
                                        result += '<a href="tel:' + data[i].phone + '"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                    } else {
                                        result += '<a href="tel:' + data[i].telphone + '"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                    }
                                    result
                                        += '  </div>'
                                        + '</div>';
                                } else if (itemIndex == 2) {
                                    result
                                        += '<div class="orderCenterListBox bac-fff mb-15">'
                                        + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id=' + data[i].order_id + '">'
                                        + '  <div class="orderCenterListTime p-10">'
                                        + '    <span>订单编号</span>'
                                        + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                        + '  </div>'
                                        + '  <div class="orderCenterListContent">';
                                    $.each(data[i].goodlist, function (index, value) {
                                        result
                                            += '    <div class="orderCenterProListBox clearfix">'
                                            + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                            + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                            + '      <div class="fr">'
                                            + '        <span>×</span>'
                                            + '        <span>' + value.dishes_nums + '</span>'
                                            + '      </div>'
                                            + '    </div>';
                                    });
                                    result
                                        += '  </div>'
                                        + '</a>'
                                        + '</div>';
                                }
                            }
                            $('.orderCenterContent').eq(itemIndex).html(result);
                            //重置下拉刷新
                            me.resetload();
                            // 重置页数，重新获取loadDownFn的数据
                            page = 2;
                            // 解锁loadDownFn里锁定的情况
                            tabLoadEndArray[itemIndex] = false;
                            if (itemIndex == 1) {
                                tabLenghtArray[itemIndex] = count2 - psize;
                            } else if (itemIndex == 2) {
                                tabLenghtArray[itemIndex] = count3 - psize;
                            } else {
                                tabLenghtArray[itemIndex] = count1 - psize;
                            }
                            //console.log(tabLenghtArray[itemIndex]);
                            me.unlock();
                            me.noData(false);
                            dropload.resetload();
                        },0);
                    }else{
                        me.resetload();
                        return;
                    }
                },
                error: function(xhr, type){
                    //alert('error!');
                    // 即使加载出错，也得重置
                    me.resetload();
                }
            });

        },
        loadDownFn: function (me) {
            //console.log(tabLenghtArray[itemIndex]);
            if (tabLenghtArray[itemIndex] > 0) {
                //console.log(page);
                $.ajax({
                    type: 'GET',
                    data: {itemIndex: itemIndex,page:page,psize:psize},
                    url: '/index.php?m=Stall&c=Csorder&a=orders',
                    dataType: 'json',
                    success: function (res) {
                        if(res.code == 1){
                            var data = res.list;
                            //console.log(data);
                            setTimeout(function () {
                                if (tabLoadEndArray[itemIndex]) {
                                    me.resetload();
                                    me.lock();
                                    me.noData();
                                    me.resetload();
                                    return;
                                }
                                page++;
                                var result = '';
                                console.log(page);
                                console.log(data);
                                for (var i = 0; i < psize; i++) {
                                    if (tabLenghtArray[itemIndex] > 0) {
                                        tabLenghtArray[itemIndex]--;
                                    } else {
                                        tabLoadEndArray[itemIndex] = true;
                                        break;
                                    }
                                    if (itemIndex == 0) {
                                        result
                                            +='<div class="orderCenterListBox bac-fff mb-15">'
                                            + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id='+data[i].order_id+'">'
                                            + '  <div class="orderCenterListTime p-10">'
                                            + '    <span>订单编号</span>'
                                            + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                            + '  </div>'
                                            + '  <div class="orderCenterListContent">';
                                        $.each(data[i].goodlist,function(index,value) {
                                            result
                                                +='    <div class="orderCenterProListBox clearfix">'
                                                + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                                + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                                + '      <div class="fr">'
                                                + '        <span>×</span>'
                                                + '        <span>' + value.dishes_nums + '</span>'
                                                + '      </div>'
                                                + '    </div>';
                                        });
                                        result
                                            +='  </div>'
                                            + '</a>'
                                            + '  <div class="orderCenterListBtnBox clearfix">'
                                            + '    <input class="fr orderCenterListBtn bcwcBtn bcwcOnclick" type="button" data-id="'+data[i].order_id+'" data-type="'+data[i].deliver_type+'" value="备餐完成" />';
                                        if(data[i].deliver_type == 1){
                                            result+='<a href="tel:'+data[i].phone+'"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                        }else{
                                            result+='<a href="tel:'+data[i].telphone+'"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                        }
                                        result
                                            +='  </div>'
                                            + '</div>';
                                    } else if (itemIndex == 1) {
                                        result
                                            +='<div class="orderCenterListBox bac-fff mb-15">'
                                            + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id='+data[i].order_id+'">'
                                            + '  <div class="orderCenterListTime p-10">'
                                            + '    <span>订单编号</span>'
                                            + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                            + '  </div>'
                                            + '  <div class="orderCenterListContent">';
                                        $.each(data[i].goodlist,function(index,value) {
                                            result
                                                +='    <div class="orderCenterProListBox clearfix">'
                                                + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                                + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                                + '      <div class="fr">'
                                                + '        <span>×</span>'
                                                + '        <span>' + value.dishes_nums + '</span>'
                                                + '      </div>'
                                                + '    </div>';
                                        });
                                        result
                                            +='  </div>'
                                            + '</a>'
                                            + '  <div class="orderCenterListBtnBox clearfix">'
                                            + '    <input class="fr orderCenterListBtn bcwcBtn qcBtn" data-id="'+data[i].order_id+'" data-type="'+data[i].deliver_type+'" type="button" value="取餐" />';
                                        if(data[i].qccs == 2){
                                            result+='<input class="fr orderCenterListBtn bcwcBtn qccsBtn" data-id="'+data[i].order_id+'" type="button" value="取餐超时" />';
                                        }
                                        if(data[i].deliver_type == 1){
                                            result+='<a href="tel:'+data[i].phone+'"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                        }else{
                                            result+='<a href="tel:'+data[i].telphone+'"><input class="fr orderCenterListBtn lxkhBtn" type="button" value="联系客户" /></a>';
                                        }
                                        result
                                            +='  </div>'
                                            + '</div>';
                                    } else if (itemIndex == 2) {
                                        result
                                            +='<div class="orderCenterListBox bac-fff mb-15">'
                                            + '<a href="/index.php?m=Stall&c=Csorder&a=order_xq&order_id='+data[i].order_id+'">'
                                            + '  <div class="orderCenterListTime p-10">'
                                            + '    <span>订单编号</span>'
                                            + '    <span>'+data[i].order_no+'</span><span style="float:right;">'+data[i].create_time+'</span>'
                                            + '  </div>'
                                            + '  <div class="orderCenterListContent">';
                                        $.each(data[i].goodlist,function(index,value) {
                                            result
                                                +='    <div class="orderCenterProListBox clearfix">'
                                                + '      <img class="orderCenterProImg fl" src="' + value.pic_url + '"/>'
                                                + '      <span class="orderCenterProTitle fl">' + value.dishes_name + '</span>'
                                                + '      <div class="fr">'
                                                + '        <span>×</span>'
                                                + '        <span>' + value.dishes_nums + '</span>'
                                                + '      </div>'
                                                + '    </div>';
                                        });
                                        result
                                            +='  </div>'
                                            + '</a>'
                                            + '</div>';
                                    }
                                }
                                $('.orderCenterContent').eq(itemIndex).append(result);
                                me.resetload();
                            },0);
                        }else{
                            me.lock();
                            me.noData();
                            me.resetload();
                            return;
                        }
                    },
                    error: function(xhr, type){
                        alert('error!');
                        // 即使加载出错，也得重置
                        me.resetload();
                    }
                });
            } else {
                me.lock();
                me.noData();
                me.resetload();
                return false;
            }
        }
    });


    /*订单中心 tab切换*/
    $(".orderCenterTopTabBox > span").click(function() {

        /*-----------------------------------------------------------*/
        $(this).addClass("c-zhu");
        $(this).parent().siblings().find("span").removeClass("c-zhu");
        $(this).siblings(".orderCenterTopTabLine").show();
        $(this).parent().siblings().find(".orderCenterTopTabLine").hide();
        var index = $(this).parent().index();
        //$("#itemIndex").val(index);
        $(".orderCenterContent").hide();
        $(".orderCenterContent").eq(index).show();
        qc = index+1;
        history.replaceState({title:"login"}, "login", "/index.php?m=Stall&c=Csorder&a=order&qc="+qc);
        //history.pushState({title:"login"}, "login", "/index.php?m=Stall&c=Csorder&a=order&qc="+qc);
        /*-----------------------------------------------------------*/
        tabScroolTopArray[itemIndex] = $(window).scrollTop();
        $(window).scrollTop(tabScroolTopArray[itemIndex]);
        itemIndex = index;
        page = 1;
        if (!tabLoadEndArray[itemIndex]) {
            dropload.unlock();
            dropload.noData(false);
        } else {
            dropload.lock('down');
            dropload.noData();
        }
        dropload.resetload();
    })
});