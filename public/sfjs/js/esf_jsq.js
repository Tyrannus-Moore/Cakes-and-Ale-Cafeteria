/**
 * 应用于楼盘网二手房详情计算器
 * esf_jsq.js
 * author liuzhiliang
 */
$(function() {
    /* 计算税费 */
    taxes_count();
    
    /* 切换计征方式 */
    chenage_levy_method();
    
    /* 切换房屋类型 */
    change_housing_type();
    housing_type();
    
    /* 税费计算提交 */
    taxes_form_submit();
    
    /* 切换利率 */
    lilv_select();
    change_lilv_select();
    
    /* 选择按揭成数 */
    change_loan_price();
    
    /* 计算贷款提交 */
    loan_count();
    loan_form_submit();
    
});


/**
 * set_iframe_height
 * 重新设置父级页面iframe高度
 */
function set_iframe_height() {
    height = $(".left").height();
    $(window.parent.document).find("#dkjsq").css('height', height);
}


/**
 * tab切换
 */
;(function($){
	$.fn.extend({
		'tabbox' : function(options){
			var box = $(this),
				item = box.find('.tab-click > li');

			var opts = $.extend({}, defaluts, options);

			return item.each(function(){

				var that = $(this),
					event = opts.even;

				that.on(event,function(){
					// console.log(that.html());
					var i = that.index();
					if(!that.hasClass('cur')){
						that.siblings('li').removeClass('cur');
						that.addClass('cur');
						that.parent().siblings('.tab-text').children().removeClass('cur');
						that.parent().siblings('.tab-text').children().eq(i).addClass('cur');
                        
                        /* 重新设置父级页面iframe高度 */
                        set_iframe_height();
					}
				});
			});
		}
	});

	var defaluts = {
		even : 'mouseover'
	};

})(window.jQuery);


/**
 * 计算器切换
 */
$('.odt-count').tabbox({
    even:'click'
});


/**
 * 税费饼图
 */
function tax_chart() {
    var deed_tax = $("#deed_tax").val();
    var personal_tax = $("#personal_tax").val();
    var business_tax = $("#business_tax").val();

    var myChart = echarts.init(document.getElementById('charts'));
    option = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            x: 'center',
            data:['契税','营业税','个税']
        },
        series: [
            {
                name:'税费',
                type:'pie',
                radius: ['43', '83'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: false,
                        textStyle: {
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:[
                    {value:deed_tax, name:'契税'},
                    {value:business_tax, name:'营业税'},
                    {value:personal_tax, name:'个税'}
                ],
                minAngle:1,
                color:['#c4aaeb','#71eaed','#ffc888']
            }
        ]
    };
    myChart.setOption(option);
}


/**
 * 切换房屋类型
 */
function change_housing_type () {
    $("#housing_type").on('change', function() {
        housing_type();
    });
}


/**
 * 房屋类型
 */
function housing_type() {
    if ($("#housing_type").val() == '1') {
        $("#levy_method_1").css("display", "");
        $("#difference").val(0);
    }
    else {
        $("#levy_method").val(2);
        $("#levy_method_1").css("display", "none");
        $("#difference_show").css("display", "");
        $("#difference").addClass("required");
    }
    
    /* 重新设置父级页面iframe高度 */
    set_iframe_height();
}


/**
 * 切换计征方式
 */
function chenage_levy_method() {
    $("#levy_method").on('change', function() {
        if ($(this).val() == '1') {
            $("#difference_show").css("display", "none");
            $("#difference").removeClass("required");
            $("#difference").val(0);
        }
        else {
            $("#difference_show").css("display", "");
            $("#difference").addClass("required");
        }
        
        /* 重新设置父级页面iframe高度 */
        set_iframe_height();
    });
}


/**
 * 表单验证
 * @name check_form
 * @param object my_form 表单对象
 * @return boolean
 * @author liuzhiliang
 */
function check_form(my_form) {
    var result = true;
    var obj = my_form.find(".required");

    obj.each(function(n) {
        if($(this).val() == "") {
            alert($(this).attr("placeholder"));
                
            result = false;
            return false; //跳出循环
        }
    });

    return result;
}


/**
 * 税费计算提交
 * @name taxes_form_submit
 * @author liuzhiliang
 */
function taxes_form_submit() {
    $("#taxes_form_submit").on('click', function() {
        var my_form = $(this).parents("form");
        
        // 验证表单
        if (check_form(my_form) == true)
        {
            // 开始计算
            taxes_count();
        }
    });
}


/**
 * 税费计算
 * @name taxes_count
 * @author liuzhiliang
 */
function taxes_count() {
    var housing_type = parseInt($("#housing_type").val());  // 住宅类型
    var seller_only = parseInt($("#seller_only").val());  // 买房家庭唯一
    var merchandise_time = parseInt($("#merchandise_time").val());  // 距上次交易
    var buyer_one = parseInt($("#buyer_one").val());  // 买房家庭首套
    var levy_method = parseInt($("#levy_method").val());  // 计征方式
    var area = parseInt($("#area").val());  // 房屋面积
    var total_price = parseInt($("#total_price").val()) * 10000;  // 房屋总价，转为元
    var difference = parseInt($("#difference").val()) * 10000;  // 买卖差价

    // 取绝对值
    total_price=Math.abs(total_price);
    difference=Math.abs(difference);

    /**
     * 契税
     *
     * 1、首次购买普通住宅 90㎡ 以下1%。
     * 2、首次购买普通住宅 90㎡-140㎡的房子1.5%。
     * 3、二次购买以及非普通住宅、超过140㎡契税都是3%。
     */
    var deed_tax = 0;
    
    if ((levy_method == 1 && area > 0 && total_price > 0) || 
        (levy_method == 2 && area > 0 && total_price > 0 && difference > 0))
    {
        if (housing_type == 1 && buyer_one == 1 && area <= 90) {
            deed_tax = total_price * 0.01;
        }
        else if (housing_type == 1 && buyer_one == 1 && area > 90 && area <= 140) {
            deed_tax = total_price * 0.015;
        }
        else if (housing_type == 2 || buyer_one == 2 || area > 140) {
            deed_tax = total_price * 0.03;
        }
    }

    $("#deed_tax").val(deed_tax);
    
    if (deed_tax > 0) {
        $("#deed_tax_show").text(deed_tax + "元");
    }
    else {
        $("#deed_tax_show").text("免征");
    }
    
    /**
     * 个人所得税
     * 1、普通住宅1%。非普通住宅差额20%。
     * 2、房产证超过5年（含5年）并且是唯一住房的可以免除。
     */
    var personal_tax = 0;
    
    // 普通住宅，核定征收税率按1%，或差额20%。
    if (housing_type == 1) {
        if (difference > 0) {
            personal_tax = difference * 0.2;
        }
        else {
            personal_tax = total_price * 0.01;
        }
    }
    else {
        personal_tax = difference * 0.2;
    }
    
    // 证超过5年（含5年）并且是唯一住房的可以免除。
    if (merchandise_time >= 5 && seller_only == 1) {
        personal_tax = 0;
    }
    else {
        if (difference > 0) {
            personal_tax = difference * 0.2;
        }
        else {
            personal_tax = total_price * 0.01;
        }
    }
    
    $("#personal_tax").val(personal_tax);
    
    if (personal_tax > 0) {
        $("#personal_tax_show").text(personal_tax + "元");
    }
    else {
        $("#personal_tax_show").text("免征");
    }
    
    /**
     * 营业税
     * 1、不满2年, 营业税=合同价×5.6%。
     * 2、2年以上（含2年）的普通住房，免征营业税。
     * 3、2年以上（含2年）的非普通住房，按照其销售收入减去购买房屋的价款后的差额征收营业税。
     */
    var business_tax = 0;
    
    // 不满2年
    if (merchandise_time < 2) {
        business_tax = total_price * 0.056;
    }
    else {
        // 满2年
        if (housing_type == 1) {
            // 普通住房
            business_tax = 0;
        }
        else {
            // 非普通住宅
            business_tax = difference * 0.056;
        }
    }

    $("#business_tax").val(business_tax);
    
    if (business_tax > 0) {
        $("#business_tax_show").text(business_tax + "元");
    }
    else {
        $("#business_tax_show").text("免征");
    }
    
    /**
     * 合计
     */
    total_tax = deed_tax + personal_tax + business_tax;
    
    if (total_tax > 0) {
        $("#total_tax_show").text(total_tax + "元");
    }
    else {
        $("#total_tax_show").text("免征");
    }
    
    // 税费饼图
    tax_chart();
}


/**
 * 选择按揭成数
 */
function change_loan_price() {
    $("#loan_price_mortgage").on('change', function() {
        var mortgage = $("#loan_price_mortgage").val();
        var housing_price = $("#housing_price").val();
        mortgage = accMul(mortgage, 0.1);
        var loan_price = accMul(housing_price, mortgage);
        
        $("#loan_price").val(loan_price);
    });
}


/**
 * 切换贷款类型、贷款年限、利率折扣时计算变更利率
 * @name change_lilv_select
 * @author liuzhiliang/20161019
 */
function change_lilv_select() {
    $("#loan_type").on('change', function() {
        lilv_select();
    });
    $("#lilv_select_1").on('change', function() {
        lilv_select();
    });
    $("#lilv_select_2").on('change', function() {
        lilv_select();
    });
    $("#years").on('change', function() {
        lilv_select();
    });
}


/**
 * 选择利率
 * @name lilv_select
 * @author liuzhiliang/20161019
 */
function lilv_select() {
    var loan_type = $("#loan_type").val();
    var years = $("#years").val();
    
    if (loan_type == 1) {
        // 商贷
        var lilv_select_1 = $("#lilv_select_1").val();
        
        $("#row_loan_price").css("display", "");
        $("#row_lilv_select_1").css("display", "");
        $("#row_lilv_select_2").css("display", "none");
        $("#row_loan_price_1").css("display", "none");
        $("#row_loan_price_2").css("display", "none");
        
        $("#lilv_1").addClass("required");
        $("#lilv_2").removeClass("required");
        $("#loan_price").addClass("required");
        $("#loan_price_1").removeClass("required");
        $("#loan_price_2").removeClass("required");
        
        // 获取利率
        var lilv_1 = get_lilv(lilv_select_1, loan_type, years);
        lilv_1 = accMul(lilv_1, 100);
        
        $("#lilv_1").val(lilv_1);
        $("#lilv_2").val(0);
    }
    else if (loan_type == 2) {
        // 公积金
        var lilv_select_2 = $("#lilv_select_2").val();
        
        $("#row_loan_price").css("display", "");
        $("#row_lilv_select_1").css("display", "none");
        $("#row_lilv_select_2").css("display", "");
        $("#row_loan_price_1").css("display", "none");
        $("#row_loan_price_2").css("display", "none");
        
        $("#lilv_1").removeClass("required");
        $("#lilv_2").addClass("required");
        $("#loan_price").addClass("required");
        $("#loan_price_1").removeClass("required");
        $("#loan_price_2").removeClass("required");
        
        // 获取利率
        var lilv_2 = get_lilv(lilv_select_2, loan_type, years);
        lilv_2 = accMul(lilv_2, 100);
        
        $("#lilv_1").val(0);
        $("#lilv_2").val(lilv_2);
    }
    else {
        // 组合贷款
        $("#row_loan_price").css("display", "none");
        $("#row_lilv_select_1").css("display", "");
        $("#row_lilv_select_2").css("display", "");
        $("#row_loan_price_1").css("display", "");
        $("#row_loan_price_2").css("display", "");
        
        $("#lilv_1").addClass("required");
        $("#lilv_2").addClass("required");
        $("#loan_price").removeClass("required");
        $("#loan_price_1").addClass("required");
        $("#loan_price_2").addClass("required");
        
        
        // 获取利率
        
        // 商贷
        var lilv_select_1 = $("#lilv_select_1").val();
        var lilv_1 = get_lilv(lilv_select_1, 1, years);
        lilv_1 = accMul(lilv_1, 100);
        $("#lilv_1").val(lilv_1);
        
        // 公积金
        var lilv_select_2 = $("#lilv_select_2").val();
        var lilv_2 = get_lilv(lilv_select_2, 2, years);
        lilv_2 = accMul(lilv_2, 100);
        $("#lilv_2").val(lilv_2);
    }
    
    /* 重新设置父级页面iframe高度 */
    set_iframe_height();
}


/**
 * 贷款计算提交
 * @name loan_form_submit
 * @author liuzhiliang
 */
function loan_form_submit() {
    $("#loan_form_submit").on('click', function() {
        var my_form = $(this).parents("form");
        
        // 验证表单
        if (check_form(my_form) == true)
        {
            // 开始计算
            loan_count();
        }
    });
}


/**
 * 计算贷款
 * @name loan_count
 */
function loan_count() {
    var loan_type = $("#loan_type").val(); /* 贷款类型 */
	var years = $("#years").val(); /* 按揭年限 */
	var month = years * 12; /* 还款月数 */
    var lilv_1 = $("#lilv_1").val(); /* 商贷利率 */
    var lilv_2 = $("#lilv_2").val(); /* 公积金贷款利率 */
    var huankuan = 0; /* 总还款数 */
    
    /* 本金还款变量 */
    var all_total2 = 0; /* 总还款数 */
    var month_money2 = 0; /* 月还款数 */
    var accrual2 = 0; /* 支付利息款 */
    var month_money_array = new Array();
    
    /* 本息还款 */
    var all_total1 = 0; /* 总还款数 */
    var month_money1 = 0; /* 月还款数 */
    var accrual1 = 0; /* 支付利息款 */
    
    var fangkuan_total = 0; /* 房款总额 */
    var money_first = 0; /* 首期付款 */
    
    if (loan_type == 3) {
		/*  组合型贷款(组合型贷款的计算，只和商业贷款额、和公积金贷款额有关，和按贷款总额计算无关) */
        var loan_price_1 = $("#loan_price_1").val(); /* 商业贷款金额 */
        var loan_price_2 = $("#loan_price_2").val(); /* 公积金贷款金额 */
        
		if (!reg_num(loan_price_1)) {
            alert("请填写商业贷款金额");
            return false;
        }
		if (!reg_num(loan_price_2)) {
            alert("请填写公积金贷款金额");
            return false;
        }
        if (!reg_num(lilv_1)) {
            alert("请选择商业贷款利率");
            return false;
        }
		if (!reg_num(lilv_2)) {
            alert("请选择公积金贷款利率");
            return false;
        }

		/* 贷款总额 */
		loan_price_1 = parseInt(loan_price_1) * 10000;
		loan_price_2 = parseInt(loan_price_2) * 10000;
		var daikuan_total = loan_price_1 + loan_price_2;

        lilv_2 = lilv_2 / 100;
        lilv_1 = lilv_1 / 100;

		/* 1.本金还款 */
			for (j = 0; j < month; j++) {
				//调用函数计算: 本金月还款额
				huankuan = getMonthMoney2(lilv_1, loan_price_1, month, j) + getMonthMoney2(lilv_2, loan_price_2, month, j);
				all_total2 += huankuan;
				huankuan = Math.round(huankuan * 100) / 100;
                month_money_array[j] = huankuan;
			}
            
            month_money2 = month_money_array[0];
            
            /* 月还款差额 */
            var month_money_difference = month_money_array[0] - month_money_array[1];
            month_money_difference = Math.round(month_money_difference * 100) / 100;

			/* 还款总额 */
			all_total2 = Math.round(all_total2 * 100) / 100;
			/* 支付利息款 */
			accrual2 = Math.round((all_total2 - daikuan_total) * 100) / 100;
            
		/* 2.本息还款 */
			/* 月均还款 */
			month_money1 = getMonthMoney1(lilv_1, loan_price_1, month) + getMonthMoney1(lilv_2, loan_price_2, month);
			month_money1 = Math.round(month_money1 * 100) / 100;
            
			/* 还款总额 */
			all_total1 = month_money1 * month;
			all_total1 = Math.round(all_total1 * 100) / 100;
            
			/* 支付利息款 */
			accrual1 = Math.round((all_total1 - daikuan_total) * 100) / 100;
	}
    else {
		/* 商业贷款、公积金贷款 */
        var loan_price = $("#loan_price").val();
		var lilv = (loan_type == 1) ? lilv_1 : lilv_2;

		if (!reg_num(loan_price)) {
            alert("请填写贷款金额");
            return false;
        }
        if (!reg_num(lilv)) {
            alert("请选择贷款利率");
            return false;
        }
        
        loan_price = parseInt(loan_price) * 10000;
        lilv = lilv / 100;

		/* 1.本金还款 */
			var all_total2 = 0; /* 总还款数 */
			var month_money2 = 0; /* 月还款数 */
            var accrual2 = 0; /* 支付利息款 */
            var month_money_array = new Array();
            
			for (j = 0; j < month; j++) {
				/* 调用函数计算: 本金月还款额 */
				huankuan = getMonthMoney2(lilv, loan_price, month, j);
				all_total2 += huankuan;
				huankuan = Math.round(huankuan * 100) / 100;
				month_money_array[j] = huankuan;
			}
            
            month_money2 = month_money_array[0];
            
            /* 月还款差额 */
            var month_money_difference = month_money_array[0] - month_money_array[1];
            month_money_difference = Math.round(month_money_difference * 100) / 100;
            
			//还款总额
			all_total2 = Math.round(all_total2 * 100) / 100;
            
			//支付利息款
			accrual2 = Math.round( (all_total2 - loan_price) * 100) / 100;

		/* 2.本息还款 */
			/* 月均还款 */
			var month_money1 = getMonthMoney1(lilv, loan_price, month);
			month_money1 = Math.round(month_money1 * 100) / 100;
            
			/* 还款总额 */
			var all_total1 = month_money1 * month;
			all_total1 = Math.round(all_total1 * 100) / 100;
            
			/* 支付利息款 */
			accrual1 = Math.round((all_total1 - loan_price) * 100) / 100;
	}
    
    /* 显示计算结果 */
    loan_show(month, all_total1, month_money1, accrual1, all_total2, month_money2, accrual2, month_money_difference);
}


/**
 * 显示贷款计算结果
 * @name loan_show
 * @param int month  还款月数
 * @param float all_total1  本息还款总数
 * @param float month_money1  本息月供
 * @param float accrual1  本息还款利息总数
 * @param float all_total2  本金还款总数
 * @param float month_money2  本金月供
 * @param float accrual2  本金还款利息总数
 * @param float month_money_difference  本金还款月供差额
 */
function loan_show(month, all_total1, month_money1, accrual1, all_total2, month_money2, accrual2, month_money_difference) {
    $("#month1").text(month + "个月");
    $("#month2").text(month + "个月");
    $("#all_total1").text(all_total1 + "元");
    $("#month_money1").text(month_money1 + "元");
    $("#accrual1").text(accrual1 + "元");
    
    $("#all_total2").text(all_total2 + "元");
    $("#month_money2").text(month_money2 + "元");
    $("#accrual2").text(accrual2 + "元");
    $("#month_money_difference").text(month_money_difference);
}


/**
 * 因为js浮点数相乘会出现不精确的bug，这里找到一个函数可以解决
 * accMul
 */
function accMul(arg1, arg2) {
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    
    try {m += s1.split(".")[1].length} catch(e) {}
    try {m += s2.split(".")[1].length} catch(e) {}
    
    return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
}


/**
 * 验证是否为数字
 * @name reg_num
 * @param string str 要验证的数字
 */
function reg_num(str){
	if (str.length == 0) {
        return false;
    }
    
	var Letters = "1234567890.";

	for (i = 0; i < str.length; i++) {
		var CheckChar = str.charAt(i);
        
		if (Letters.indexOf(CheckChar) == -1) {
            return false;
        }
	}
    
	return true;
}



/**
 * 等额本金还款月还款额
 * @name getMonthMoney2
 * @param float lilv 年利率
 * @param float total 贷款总额
 * @param int month 贷款总月份
 * @param int cur_month 贷款当前月 0～length-1
 * @return float 月还款金额
 */
function getMonthMoney2(lilv, total, month, cur_month){
	var lilv_month = lilv / 12; /* 月利率 */
	//return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
	var benjin_money = total / month;
    
	return (total - benjin_money * cur_month) * lilv_month + benjin_money;
}


/**
 * 本息还款的月还款额
 * @name getMonthMoney1
 * @param float lilv 年利率
 * @param float total 贷款总额
 * @param int month 贷款总月份
 * @return float 月还款金额
 */
function getMonthMoney1(lilv, total, month){
	var lilv_month = lilv / 12; /* 月利率 */

	return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
}


/**
 * 取得利率
 * @name get_lilv
 * @param int lilv_select  利率行
 * @param int loan_type  贷款类型
 * @param int years  贷款年限
 * @author liuzhiliang/20161019
 */
function get_lilv(lilv_select, loan_type, years) {
    if (loan_type == 1) {
        if (years <= 1) {
            years = 1;
        }
        else if (years <= 3) {
            years = 3;
        }
        else if (years <= 5) {
            years = 5;
        }
        else {
            years = 10;
        }
    }
    else {
        if (years <= 5) {
            years = 5;
        }
        else {
            years = 10;
        }
    }

    var lilv = lilv_array[lilv_select][loan_type][years];
    lilv = lilv;
    
    return lilv;
}


/**
 * 贷款利率数组
 */
var lilv_array = new Array;
/* 15年10月24日利率上限（1.1倍） */
lilv_array[1] = new Array;
lilv_array[1][1] = new Array;
lilv_array[1][2] = new Array;
lilv_array[1][1][1] = 0.04785; /* 商贷 1年 */
lilv_array[1][1][3] = 0.05225; /* 商贷 1-3年 */
lilv_array[1][1][5] = 0.05225; /* 商贷 3～5年 */
lilv_array[1][1][10] = 0.0539; /* 商贷 5-30年 */
lilv_array[1][2][5] = 0.03025; /* 公积金 1～5年  */
lilv_array[1][2][10] = 0.03575; /* 公积金 5-30年 */
/* 15年10月24日利率下限（95折） */
lilv_array[2] = new Array;
lilv_array[2][1] = new Array;
lilv_array[2][2] = new Array;
lilv_array[2][1][1] = 0.041325;
lilv_array[2][1][3] = 0.045125;
lilv_array[2][1][5] = 0.045125;
lilv_array[2][1][10] = 0.04655;
lilv_array[2][2][5] = 0.026125;
lilv_array[2][2][10] = 0.030875;
/* 15年10月24日利率下限（9折） */
lilv_array[3] = new Array;
lilv_array[3][1] = new Array;
lilv_array[3][2] = new Array;
lilv_array[3][1][1] = 0.03915;
lilv_array[3][1][3] = 0.04275;
lilv_array[3][1][5] = 0.04275;
lilv_array[3][1][10] = 0.0441;
lilv_array[3][2][5] = 0.02475;
lilv_array[3][2][10] = 0.02925;
/* 15年10月24日利率下限（88折） */
lilv_array[4] = new Array;
lilv_array[4][1] = new Array;
lilv_array[4][2] = new Array;
lilv_array[4][1][1] = 0.03828;
lilv_array[4][1][3] = 0.0418;
lilv_array[4][1][5] = 0.0418;
lilv_array[4][1][10] = 0.04312;
lilv_array[4][2][5] = 0.0242;
lilv_array[4][2][10] = 0.0286;
/* 15年10月24日利率下限（85折） */
lilv_array[5] = new Array;
lilv_array[5][1] = new Array;
lilv_array[5][2] = new Array;
lilv_array[5][1][1] = 0.036975;
lilv_array[5][1][3] = 0.040375;
lilv_array[5][1][5] = 0.040375;
lilv_array[5][1][10] = 0.04165;
lilv_array[5][2][5] = 0.023375;
lilv_array[5][2][10] = 0.027625;
/* 15年10月24日利率下限（7折） */
lilv_array[6] = new Array;
lilv_array[6][1] = new Array;
lilv_array[6][2] = new Array;
lilv_array[6][1][1] = 0.03045;
lilv_array[6][1][3] = 0.03325;
lilv_array[6][1][5] = 0.03325;
lilv_array[6][1][10] = 0.0343;
lilv_array[6][2][5] = 0.01925;
lilv_array[6][2][10] = 0.02275;
/* 15年10月24日基准利率 */
lilv_array[7] = new Array;
lilv_array[7][1] = new Array;
lilv_array[7][2] = new Array;
lilv_array[7][1][1] = 0.0435; /* 商贷 1年 */
lilv_array[7][1][3] = 0.0475; /* 商贷 1-3年 */
lilv_array[7][1][5] = 0.0475; /* 商贷 3～5年 */
lilv_array[7][1][10] = 0.049; /* 商贷 5-30年 */
lilv_array[7][2][5] = 0.0275; /* 公积金 1～5年  */
lilv_array[7][2][10] = 0.0325; /* 公积金 5-30年 */









