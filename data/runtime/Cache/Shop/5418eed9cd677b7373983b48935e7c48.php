<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>订单打印</title>
    <style>
        * {
			font-family: '微软雅黑';
            padding: 0;
            margin: 0;
        }
        body {
            background-color: #ccc;
        }
        .print-item {
            padding: 10px;
            width: 184px;
            margin: 0 auto;
            background-color: #fff;
            margin-bottom: 20px;
        }
        .time {
            font-size: 10px;
           
            border-left: 0;
            border-right: 0;
            padding: 5px 4px;
            vertical-align: middle;
        }
        .order-id {
            font-size: 13px;
            font-weight: bold;
            float: right;
            line-height: 15px;
        }
        .code-box {
            padding: 10px 0;
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .code-box .company {
            font-size: 20px;
            font-weight: bold;
        }
        .code-box .school {
            font-size: 14px;
        }
        .code-box img {
            display: block;
            width: 92px;
            margin: 0 auto;
        }
        .tx-center {
            text-align: center;
        }
        .font-size-10 {
            font-size: 10px;
        }
        .order-num {
            margin-top: 10px;
        }
		.dangkou {
			font-size: 14px;
			font-weight: bold;
			margin: 6px 0;
		}
        .order-time {
            margin-bottom: 10px;
        }
        .menu {
            font-size: 12px;
            padding: 4px 0;
        }
        .menu .menu-head {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .menu .menu-head span {
            flex: 1;
            text-align: center;
            font-weight: bold;

        }
		.menu .menu-head span:first-child {
            flex: 2;
        }
        .menu .menu-body .menu-item {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 4px 0;
        }
        .menu .menu-body .menu-item span {
            flex: 1;
            text-align: center;
        }
		.menu .menu-body .menu-item span:first-child {
			flex: 2;
		}
        .express {
            margin-top: 10px;
            font-size: 12px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        .origin {
            font-size: 12px;
            text-align: right;
            padding-top: 8px;
            margin-bottom: 4px;
			display: flex;
			justify-content: space-between;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            border-bottom: 1px solid #000;
            padding-bottom: 8px;
        }
        .comments {
            font-size: 15px;
            font-weight: bold;
            padding: 8px 0;
            border-bottom: 1px solid #000;
        }
        .comments span:first-child {
            font-size: 13px;
            font-weight: 400;
        }
        .user-info {
            font-size: 15px;
            font-weight: bold;
            padding: 8px 0;
        }
        .user-info div span:first-child {
            font-size: 13px;
            font-weight: 400;
        }
        .finish {
            padding: 6px 0;
            
            border-left: 0;
            border-right: 0;
            text-align: center;
        }
        .hidden-more {
            text-overflow: clip;
            white-space: nowrap;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <!--startprint-->
        <?php if(is_array($infos)): foreach($infos as $key=>$v): ?><div class="print-item">
                <div class="time">
					<div class="hidden-more">-----------------------------------</div>
                    <span class="top_time"></span>
                    <span class="order-id">#<?php echo ($v['row'][0]['meal_code']); ?></span>
					<div class="hidden-more">-----------------------------------</div>
                </div>
                <div class="code-box">
                    <div class="company">梦诚餐饮</div>
                    <div class="school">聊城技师学院</div>
                    <img src="/public/img/code.jpg" alt="">
                </div>
				<div class="dangkou tx-center">档口名称：<?php echo ($v["stall_name"]); ?></div>
                <div class="order-num tx-center font-size-10">订单号:<?php echo ($v["order_no"]); ?></div>
                <div id="order_time" class="order-time tx-center font-size-10">下单时间:<?php echo (date('Y-m-d H:i:s',$v["payment_time"])); ?></div>
                <div class="menu">
                    <div class="menu-head">
                        <span>菜品</span>
                        <span>数量</span>
                        <span>单价</span>
						<span>折后价</span>
                    </div>
                    <div class="menu-body">
                        <?php if(is_array($v["row"])): foreach($v["row"] as $key=>$vv): ?><div class="menu-item">
                                <span><?php echo ($vv["dishes_name"]); ?></span>
                                <span>x<?php echo ($vv["dishes_nums"]); ?></span>
                                <span><?php echo ($vv["dishes_price"]); ?></span>
								<span><?php echo ($vv["discount_price"]); ?></span>
                            </div><?php endforeach; endif; ?>
                    </div>
                </div>
				<div class="hidden-more">--------------------------</div>
                <div class="origin">
					<span>
						配送费: <?php echo ($v["express_money"]); ?>元
					</span>
					<span>
						合计: <?php echo ($v["total_money"]); ?>元
					</span>
				</div>
                <div class="total">实付: <?php echo ($v["real_money"]); ?>元</div>
                <div class="comments">
                    <span>备注:</span>
                    <span><?php echo ($v["order_note"]); ?></span>
                </div>
                <div class="user-info">
                    <div class="address">
                        <span>送达:</span>
                        <span><?php echo ($v["address"]); ?></span>
                    </div>
                    <div class="phone">
                        <span>电话:</span>
                        <span><?php echo ($v["phone"]); ?></span>
                    </div>
                    <div class="name">
                        <span>姓名:</span>
                        <span><?php echo ($v["username"]); ?></span>
                    </div>
                </div>
                <div class="finish">
					<div class="hidden-more">--------------------------</div>
                    ******* 完 ******
					<div class="hidden-more">--------------------------</div>
                </div>
            </div><?php endforeach; endif; ?>
        <!--endprint-->
    </div>
</body>
</html>

<script>
    window.onload = function () {
        events();

        // 获取时间
        function getTime () {
            var _date = new Date();
            return {
                year: _date.getFullYear(),
                month : _date.getMonth() + 1 < 10 ? '0' + (_date.getMonth()+1) : _date.getMonth() + 1,
                date : _date.getDate() < 10 ? '0' + _date.getDate() : _date.getDate(),
                hour : _date.getHours() < 10 ? '0' + _date.getHours() : _date.getHours(),
                minute : _date.getMinutes() < 10 ? '0' + _date.getMinutes() : _date.getMinutes(),
                second : _date.getSeconds() < 10 ? '0' + _date.getSeconds() : _date.getSeconds()
            }
        }
        // dom操作
        function events () {
            var time = getTime();
            // 顶部时间
            var top_time = document.querySelectorAll('.top_time');
            
            for(var i = 0, len = top_time.length; i < len; i++) {
                top_time[i].innerHTML = time.year + '年' + time.month + '月' + time.date + '日' + ' ' + time.hour + ':' + time.minute;
            }
        }

        window.print();

    }
</script>