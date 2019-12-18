<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>时光餐厅商家后台管理</title>

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome必须的css -->
	<link rel="stylesheet" href="/public/ace/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/public/font-awesome/css/font-awesome.min.css" />

	<!-- 此页插件css -->

	<!-- ace的css -->
	<link rel="stylesheet" href="/public/ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<!-- IE版本小于9的ace的css -->
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="/public/ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="/public/ace/css/ace-ie.css" />
	<![endif]-->

	<link rel="stylesheet" href="/public/yfcmf/yfcmf.css" />
	<!-- 此页相关css -->

	<!-- ace设置处理的css -->

	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="/public/others/html5shiv.min.js"></script>
	<script src="/public/others/respond.min.js"></script>
	<![endif]-->
    <!-- 引入基本的js -->
    <script type="text/javascript">
        var admin_ueditor_handle = "<?php echo U('Admin/Sys/upload');?>";
        var admin_ueditor_lang ='zh-cn';
    </script>
    <!--[if !IE]> -->
    <script src="/public/others/jquery.min-2.2.1.js"></script>
    <!-- <![endif]-->
    <!-- 如果为IE,则引入jq1.12.1 -->
    <!--[if IE]>
    <script src="/public/others/jquery.min-1.12.1.js"></script>
    <![endif]-->

    <!-- 如果为触屏,则引入jquery.mobile -->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='/public/others/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="/public/others/bootstrap.min.js"></script>
</head>

<body class="no-skin">
<!-- 导航栏开始 -->
<div id="navbar" class="navbar navbar-default navbar-fixed-top">
	<div class="navbar-container" id="navbar-container">
		<!-- 导航左侧按钮手机样式开始 -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button><!-- 导航左侧按钮手机样式结束 -->
		<button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
			<span class="sr-only">Toggle sidebar</span>
			<i class="ace-icon fa fa-dashboard white bigger-125"></i>
		</button>
		<!-- 导航左侧正常样式开始 -->
		<div class="navbar-header pull-left">
			<!-- logo -->
			<a href="#" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					时光餐厅
				</small>
			</a>
		</div><!-- 导航左侧正常样式结束 -->

		<!-- 导航栏开始 -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="purple">
					<a data-info="确定要清理缓存吗？" class="confirm-rst-btn" href="<?php echo U('Admin/Sys/clear');?>">
						清除缓存
					</a>
				</li>

				<!-- 用户菜单开始 -->
				<li class="light-blue dropdown-modal">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="/public/img/girl.jpg" alt="<?php echo ($_SESSION['ma_account']); ?>" />
                        <span class="user-info">
                            <small>欢迎</small>
                            <p><?php echo ($_SESSION['ma_account']); ?></p>
                        </span>
						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo U('Shop/Sys/profile');?>">
								<i class="ace-icon fa fa-user"></i>
								商家中心
							</a>
						</li>

						<li>
							<a href="<?php echo U('Shop/Login/logout');?>"  data-info="你确定要退出吗？" class="confirm-btn">
								<i class="ace-icon fa fa-power-off"></i>
								注销
							</a>
						</li>
					</ul>
				</li><!-- 用户菜单结束 -->
			</ul>
		</div><!-- 导航栏结束 -->
	</div><!-- 导航栏容器结束 -->
</div><!-- 导航栏结束 -->

<!-- 整个页面内容开始 -->
<div class="main-container" id="main-container">
	<!-- 菜单栏开始 -->
	<style type="text/css">
    @media only screen and (min-width: 992px) {
        .h-navbar.navbar-fixed-top + .main-container .sidebar:not(.h-sidebar):before {
            top: 0px;
        }
    }
</style>
<div id="sidebar" class="sidebar responsive sidebar-fixed">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <!--左侧顶端按钮-->
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <a class="btn btn-danger" href="#" role="button" title=""><i class="ace-icon fa fa-cogs"></i></a>
            <a class="btn btn-success" href="#" role="button" title=""><i class="ace-icon fa fa-signal"></i></a>
            <a class="btn btn-warning" href="#" role="button" title=""><i class="ace-icon fa fa-users"></i></a>
            <a class="btn btn-info" href="#" role="button" title=""><i class="ace-icon fa fa-pencil"></i></a>
        </div>
        <!--左侧顶端按钮（手机）-->
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <a class="btn btn-success" href="<?php echo U('Shop/Sys/profile');?>" role="button" title="商家中心"></a>
            <a class="btn btn-info" href="<?php echo U('Shop/Order/orderList');?>" role="button" title="订单管理"></a>
            <a class="btn btn-warning" href="<?php echo U('Shop/Technician/technician');?>" role="button" title="技师管理"></a>
            <a class="btn btn-danger" href="<?php echo U('Shop/ShopProject/projectList');?>" role="button" title="项目管理"></a>
        </div>
    </div>
	<!-- 菜单列表开始 -->
	<!-- 一级菜单 -->
	<ul class="nav nav-list">
        <!--<li <?php if($controllername == "user" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-list"></i>
                <span class="menu-text">权限管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                &lt;!&ndash;二级菜单遍历开始&ndash;&gt;
                <li <?php if($actionname == "userList"): ?>class="active open"<?php endif; ?>>
                    <a href="<?php echo U('Shop/User/userList');?>" >
                        <i class="menu-icon fa fa-caret-right"></i>
                        用户管理
                    </a>
                    <b class="arrow"></b>
                </li>&lt;!&ndash;二级菜单遍历结束&ndash;&gt;
            </ul>
            <ul class="submenu">
                &lt;!&ndash;二级菜单遍历开始&ndash;&gt;
                <li <?php if($actionname == "xianzhi"): ?>class="active open"<?php endif; ?>>
                    <a href="<?php echo U('Shop/User/xianzhi');?>" >
                        <i class="menu-icon fa fa-caret-right"></i>
                        限制时间管理
                    </a>
                    <b class="arrow"></b>
                </li>&lt;!&ndash;二级菜单遍历结束&ndash;&gt;
            </ul>
        </li>-->

        <li <?php if($controllername == "home" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-building"></i>
                <span class="menu-text">首页管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                    <a href="<?php echo U('Shop/Home/banner');?>" >
                        <i class="menu-icon fa fa-caret-right"></i>
                        轮播图管理
                    </a>
                    <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/saturated');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    餐厅饱和度
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/distribution');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    配送费设置
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/timeout');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    超时期限
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/discount');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    折扣设置
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/help');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    帮助中心
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "home"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Home/addressList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    地址菜单管理
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "foodtype" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-database"></i>
                <span class="menu-text">菜品管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                    <li <?php if($actionname == "typeList"): ?>class="active open"<?php endif; ?>>
                        <a href="<?php echo U('Shop/Foodtype/typeList');?>" >
                            <i class="menu-icon fa fa-caret-right"></i>
                            分类列表
                        </a>
                        <b class="arrow"></b>
                    </li>
                <!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "foodList"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Foodtype/foodList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    菜品列表
                </a>
                <b class="arrow"></b>
                </li>
                <!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "comment" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-commenting"></i>
                <span class="menu-text">评论管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "commentList"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Comment/commentList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    评论列表
                </a>
                <b class="arrow"></b>
                </li>
                <!--二级菜单遍历结束-->
            </ul>
        </li>

        <li <?php if($controllername == "stall" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-newspaper-o"></i>
                <span class="menu-text">档口管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "stalllist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Stall/stallList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>
                    档口列表
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "settlement" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-line-chart"></i>
                <span class="menu-text">财务审计管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "settlementlist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Settlement/settlementList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>商户结算
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "member" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-user"></i>
                <span class="menu-text">用户管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "memberlist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Member/memberList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>用户列表
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "marki" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-truck"></i>
                <span class="menu-text">配送员管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "applylist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Marki/applyList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>认证申请
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "markilist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Marki/markiList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>人员管理
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "withdrawal_list"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Marki/withdrawal_list');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>提现管理
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "feedback" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-tags"></i>
                <span class="menu-text">意见管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "feedbacklist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Feedback/feedbackList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>反馈列表
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
        <li <?php if($controllername == "order" ): ?>class="open"<?php endif; ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-stack-overflow"></i>
                <span class="menu-text">订单管理</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <ul class="submenu">
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "ordinarylist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Order/ordinaryList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>普通菜品订单列表
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
                <!--二级菜单遍历开始-->
                <li <?php if($actionname == "packagelist"): ?>class="active open"<?php endif; ?>>
                <a href="<?php echo U('Shop/Order/packageList');?>" >
                    <i class="menu-icon fa fa-caret-right"></i>周餐订单列表
                </a>
                <b class="arrow"></b>
                </li><!--二级菜单遍历结束-->
            </ul>
        </li>
    </ul>
</div>
	<!-- 菜单栏结束 -->

	<!-- 主要内容开始 -->
	<div class="main-content">
		<div class="main-content-inner">
			<!-- 右侧主要内容页顶部标题栏开始 -->
			<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse sidebar-fixed menu-min" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
	<div class="nav-wrap-up pos-rel">
		<div class="nav-wrap">
            <ul class="nav nav-list">
                <?php if($controllername == "home"): ?><li>
                        <a href="<?php echo U('Shop/home/banner');?>">
                            <o class="font12 <?php if($actionname == "banner" or $actionname == "banneradd" or $actionname == "banneredit"): ?>rigbg<?php endif; ?>">轮播图管理</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/saturated');?>">
                            <o class="font12 <?php if($actionname == "saturated"): ?>rigbg<?php endif; ?>">餐厅饱和度</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/distribution');?>">
                            <o class="font12 <?php if($actionname == "distribution"): ?>rigbg<?php endif; ?>">配送费设置</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/timeout');?>">
                            <o class="font12 <?php if($actionname == "timeout"): ?>rigbg<?php endif; ?>">超时期限</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/discount');?>">
                            <o class="font12 <?php if($actionname == "discount" or $actionname == "discountadd" or $actionname == "discountedit"): ?>rigbg<?php endif; ?>">折扣设置</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/help');?>">
                            <o class="font12 <?php if($actionname == "help"): ?>rigbg<?php endif; ?>">帮助中心</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/home/addressList');?>">
                            <o class="font12 <?php if($actionname == "addresslist"): ?>rigbg<?php endif; ?>">地址菜单管理</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'foodtype'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Foodtype/typeList');?>">
                            <o class="font12 <?php if($actionname == "typelist"): ?>rigbg<?php endif; ?>">分类列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/Foodtype/foodList');?>">
                            <o class="font12 <?php if($actionname == "foodlist"): ?>rigbg<?php endif; ?>">菜品列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'comment'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Comment/commentList');?>">
                            <o class="font12 <?php if($actionname == "commentList"): ?>rigbg<?php endif; ?>">评论列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'technician'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Technician/technician');?>">
                            <o class="font12 <?php if($actionname == "index"): ?>rigbg<?php endif; ?>">技师列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'stall'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Stall/stallList');?>">
                            <o class="font12 <?php if($actionname == "stalllist"): ?>rigbg<?php endif; ?>">档口列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'member'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Member/memberList');?>">
                            <o class="font12 <?php if($actionname == "memberlist"): ?>rigbg<?php endif; ?>">用户列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'marki'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Marki/applyList');?>">
                            <o class="font12 <?php if($actionname == "applylist"): ?>rigbg<?php endif; ?>">认证申请</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/Marki/markiList');?>">
                            <o class="font12 <?php if($actionname == "markilist"): ?>rigbg<?php endif; ?>">人员管理</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'feedback'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Feedback/feedbackList');?>">
                            <o class="font12 <?php if($actionname == "feedbacklist"): ?>rigbg<?php endif; ?>">反馈列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'settlement'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Settlement/settlementList');?>">
                            <o class="font12 <?php if($actionname == "settlementlist"): ?>rigbg<?php endif; ?>">商户结算</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php elseif($controllername == 'order'): ?>
                    <li>
                        <a href="<?php echo U('Shop/Order/ordinaryList');?>">
                            <o class="font12 <?php if($actionname == "ordinarylist"): ?>rigbg<?php endif; ?>">普通菜品订单列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="<?php echo U('Shop/Order/packageList');?>">
                            <o class="font12 <?php if($actionname == "packagelist"): ?>rigbg<?php endif; ?>">周餐订单列表</o>
                        </a>
                        <b class="arrow"></b>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo U('Shop/Sys/profile');?>">
                            <o class="font12">欢迎进入个人中心</o>
                        </a>
                        <b class="arrow"></b>
                    </li><?php endif; ?>
            </ul>
		</div>
	</div><!-- /.nav-list -->
</div>
			<!-- 右侧主要内容页顶部标题栏结束 -->

			<!-- 右侧下主要内容开始 -->
			
<div class="page-content" style="padding-top: 50px;">
    <link href="/public/content/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <div class="row maintop">
        <div class="col-xs-5 col-sm-3">
            <form name="admin_list_sea" class="form-search" method="post" action="<?php echo U('ordinaryList');?>">
                <div class="row maintop">
                    <div class="col-xs-12 col-sm-3 btn-sespan">
                        <div class="input-group">
                            <span class="input-group-addon">订单编号：</span>
                            <input type="text" name="search[order_no]"  class="form-control" value="<?php echo ($search["order_no"]); ?>" placeholder="订单编号" style="width:150px;" />
                            <span class="input-group-addon">手机号：</span>
                            <input type="text" name="search[phone]"  class="form-control" value="<?php echo ($search["phone"]); ?>" placeholder="手机号" style="width:120px;" />
                            <span class="input-group-addon">档口名称：</span>
                            <input type="text" name="search[stall_name]"  class="form-control" value="<?php echo ($search["stall_name"]); ?>" placeholder="档口名称" style="width:120px;" />
                            <span class="input-group-addon">订单状态：</span>
                            <select name="search[order_status]" class="form-control" style="width:80px">
                                <option value="">请选择</option>
                                <option value="1" <?php if($search['order_status'] == 1): ?>selected<?php endif; ?>>待付款</option>
                                <option value="2" <?php if($search['order_status'] == 2): ?>selected<?php endif; ?>>备餐中</option>
                                <option value="3" <?php if($search['order_status'] == 3): ?>selected<?php endif; ?>>待取餐</option>
                                <option value="4" <?php if($search['order_status'] == 4): ?>selected<?php endif; ?>>配送中</option>
                                <option value="5" <?php if($search['order_status'] == 5): ?>selected<?php endif; ?>>已完成</option>
                                <option value="6" <?php if($search['order_status'] == 6): ?>selected<?php endif; ?>>已评价</option>
                                <option value="7" <?php if($search['order_status'] == 7): ?>selected<?php endif; ?>>已取消</option>
                                <option value="8" <?php if($search['order_status'] == 8): ?>selected<?php endif; ?>>已退款</option>
                            </select>
                            <span class="input-group-addon">配送方式：</span>
                            <select name="search[deliver_type]" class="form-control" style="width:80px">
                                <option value="">请选择</option>
                                <option value="1" <?php if($search['deliver_type'] == 1): ?>selected<?php endif; ?>>外卖配送</option>
                                <option value="2" <?php if($search['deliver_type'] == 2): ?>selected<?php endif; ?>>档口自提</option>
                            </select>
                        </div>
                        <div class="input-group" style="padding-top: 10px;">
                            <span class="input-group-addon">开始时间：</span>
                            <input name="search[start_time]" id="start_time" class="form-control" value="<?php echo ($search["start_time"]); ?>" type="text" required="required" readonly="readonly" style="width:150px;">
                            <span class="input-group-addon">结束时间：</span>
                            <input name="search[end_time]" id="end_time" class="form-control" type="text"  value="<?php echo ($search["end_time"]); ?>" required="required" readonly="readonly" style="width:150px;">
                            <span class="input-group-addon">抢单状态：</span>
                            <select name="search[is_grab]" class="form-control" style="width:80px">
                                <option value="">请选择</option>
                                <option value="1" <?php if($search['is_grab'] == 1): ?>selected<?php endif; ?>>待抢单</option>
                                <option value="2" <?php if($search['is_grab'] == 2): ?>selected<?php endif; ?>>已抢单</option>
                            </select>
                            <span class="input-group-addon">退款状态：</span>
                            <select name="search[refund]" class="form-control" style="width:120px">
                                <option value="">请选择</option>
                                <option value="1" <?php if($search['refund'] == 1): ?>selected<?php endif; ?>>退款中</option>
                                <option value="2" <?php if($search['refund'] == 2): ?>selected<?php endif; ?>>退款成功</option>
                                <option value="3" <?php if($search['refund'] == 3): ?>selected<?php endif; ?>>退款失败</option>
                            </select>
                            <span class="input-group-btn btn-group-sm" style="padding-left:0px;">
                            <button type="submit" class="btn btn-xs  btn-purple">
                                <span class="ace-icon fa fa-search"></span>搜索
                            </button>
                            </span>
                            <span class="input-group-btn btn-group-sm" style="padding-left:10px;">
                                <a href=" <?php echo U('ordinaryList');?>">
                                    <button type="button" class="btn btn-sm  btn-purple">
                                        <span class="ace-icon fa fa-globe icon-on-right bigger-110"></span>显示全部
                                    </button>
                                </a >
                            </span>
                            <span class="input-group-btn btn-group-sm" style="padding-left:10px;">
                                <a onclick="prinerOrder()" >
                                    <button type="button" class="btn btn-sm  btn-primary">
                                        <span class="ace-icon fa fa-list icon-on-right bigger-110"></span>打印订单
                                    </button>
                                </a >
                            </span>
                            <span class="input-group-btn btn-group-sm" style="padding-left:10px;">
                                <a onclick="paidanOrder()" >
                                    <button type="button" class="btn btn-sm  btn-primary">
                                        <span class="ace-icon fa fa-bolt icon-on-right bigger-120"></span>一键派单
                                    </button>
                                </a >
                            </span>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--列表-->
    <div class="row">
        <div class="col-xs-12">
            <div>
                <form id="leftnav" name="leftnav" method="post" action="" >
                    <input type="hidden" name="checkk" id="checkk" value="1" /><!--用于判断操作类型-->
                    <strong>订单总金额：<?php echo ((isset($money) && ($money !== ""))?($money):0); ?>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><strong>退款金额：<?php echo ((isset($refundMoney) && ($refundMoney !== ""))?($refundMoney):0); ?>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><strong>骑手金额：<?php echo ((isset($qishouMoney) && ($qishouMoney !== ""))?($qishouMoney):0); ?>元</strong>
                    <table class="table table-striped table-bordered table-hover" id="dynamic-table">
                        <thead>
                            <tr>
                                <th>选择</th>
                                <th>订单编号</th>
                                <th>用户昵称</th>
                                <th>手机号</th>
                                <th>档口名称</th>
                                <th>订单金额</th>
                                <th>订单状态</th>
                                <th>退款状态</th>
                                <th>骑手金额</th>
                                <th>抢单状态</th>
                                <th>配送方式</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                                <td align="center">
                                    <input type="checkbox" value="<?php echo ($v["order_id"]); ?>">
                                </td>
                                <td><?php echo ($v["order_no"]); ?></td>
                                <td class="hidden-sm hidden-xs"><?php echo ($v["member_list_nickname"]); ?></td>
                                <td class="hidden-sm hidden-xs">
                                        <?php echo ($v["yh_telphone"]); ?>
                                </td>
                                <td class="hidden-sm hidden-xs"><?php echo ($v["stall_name"]); ?></td>
                                <td class="hidden-sm hidden-xs"><?php echo ($v["total_money"]); ?></td>
                                <td class="hidden-sm hidden-xs">
                                    <?php switch($v["order_status"]): case "1": ?>待付款<?php break;?>
                                        <?php case "2": ?>备餐中<?php break;?>
                                        <?php case "3": ?>待取餐<?php break;?>
                                        <?php case "4": ?>配送中<?php break;?>
                                        <?php case "5": ?>已完成<?php break;?>
                                        <?php case "6": ?>已评价<?php break;?>
                                        <?php case "7": ?>已取消<?php break;?>
                                        <?php case "8": ?>已退款<?php break; endswitch;?>
                                </td>
                                <td class="hidden-sm hidden-xs">
                                    <?php switch($v["refund"]): case "0": break;?>
                                        <?php case "1": ?>退款中<?php break;?>
                                        <?php case "2": ?>退款成功<?php break;?>
                                        <?php case "3": ?>退款失败<?php break; endswitch;?>
                                </td>
                                <td class="hidden-sm hidden-xs"><?php if(($v["express_money"]) != "0"): echo ($v["express_money"]); endif; ?></td>
                                <td>
                                    <?php if(($v["is_grab"]) == "1"): ?>待抢单<?php endif; ?>
                                    <?php if(($v["is_grab"]) == "2"): ?>已抢单<?php endif; ?>
                                </td>
                                <td>
                                    <?php if(($v["deliver_type"]) == "1"): ?>外卖配送<?php endif; ?>
                                    <?php if(($v["deliver_type"]) == "2"): ?>档口自提<?php endif; ?>
                                </td>
                                <td><?php echo (date("Y-m-d H:i:s",$v["create_time"])); ?></td>
                                <td>
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="btn btn-minier btn-yellow" href="<?php echo U('ordinaryDetail',array('order_id'=>$v['order_id'],'p'=>I('p',1)));?>" title="查看详情">
                                            查看详情
                                        </a >
                                        <?php if(($v["deliver_type"]) == "1"): if(($v["is_grab"]) == "1"): if(($v["order_status"]) == "3"): ?><a class="btn btn-minier btn-success" style="color: white;" href="javascript:;" onclick="ifranmek('<?php echo U('markiList',array('order_id'=>$v['order_id'],'p'=>I('p',1)));?>','派单')" title="派单" >
                                            派单
                                        </a ><?php endif; endif; endif; ?>
                                    </div>
                                </td>
                            </tr><?php endforeach; endif; ?>
                        <tr>
                            <td align="center">
                                <input id="selectAll" type="button" value="全选" style="margin-bottom: 4px;">
                                <br>
                                <input id="selectNone" type="button" value="全不选">
                            </td>
                            <td height="50" colspan="13" align="left"><?php echo ($page); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

</div><!-- /.page-content -->
<script src="/public/content/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/public/content/js/bootstrap-datetimepicker.zh-CN.js" type="text/javascript"></script>
<script type="text/javascript">
    /*时间插件*/
    $('#start_time').datetimepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN', /*加载日历语言包，可自定义*/
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView:'month',
        forceParse: 0
    }).on("click",function(ev){
        $("#start_time").datetimepicker("setEndDate", $("#end_time").val());
    });
    /*时间插件*/
    $('#end_time').datetimepicker({
        format: 'yyyy-mm-dd',
        language: 'zh-CN', /*加载日历语言包，可自定义*/
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView:'month',
        forceParse: 0
    }).on("click", function (ev) {
        $("#end_time").datetimepicker("setStartDate", $("#start_time").val());
    });
    $(".close").click(function(){
        $(".shade").fadeOut();
        $(".tanc").fadeOut();
    });
    //iframe框架
    function ifranmek(url,title){
        layer.open({
            type: 2,
            title: title,
            maxmin: true,
            area: ['65%', '90%'],
            content: url,
        });
    }
    // 全选 全不选
    // 全局变量储存选中的order_id
    var select_order_id = [];
    $("#selectAll").click(function(){
        select_order_id = [];
        // 全部重置为选中
        $("input:checkbox").prop("checked","checked");
        // 把每一个value储存起来
        $.each($('input:checkbox:checked'),function(){
            select_order_id.push($(this).val());
        });
        // console.log(select_order_id);
    });
    $("#selectNone").click(function(){
        // 全部重置为未选中
        $("input:checkbox").removeAttr("checked");
        // 清空数组
        select_order_id = [];
    });


    //打印跳转
    function prinerOrder(){
        select_order_id = [];
        $.each($('input:checkbox:checked'),function(){
            select_order_id.push($(this).val());
        });
        if (select_order_id.length == 0){
            layer.alert('请选择订单！');
        }else{
          var url = '/index.php?m=Shop&c=Order&a=prinerOrder&order_id='+select_order_id;
            window.open(url);  
        }
    }


     //一键派单
    function paidanOrder(){
        select_order_id = [];
        $.each($('input:checkbox:checked'),function(){
            select_order_id.push($(this).val());
        });

        if (select_order_id.length == 0){
            layer.alert('请选择订单！');
        }else{
            var url = '/index.php?m=Shop&c=Order&a=markiList&order_id='+select_order_id+'&p=1';
            layer.open({
                type: 2,
                title: '派单',
                maxmin: true,
                area: ['65%', '90%'],
                content: url,
            });
        }
    }

</script>

			<!-- 右侧下主要内容结束 -->
		</div>
	</div><!-- 主要内容结束 -->
	<!-- 页脚开始 -->
	<div class="footer">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120">
				<span class="blue bolder"><a href="" target="_blank">时光餐厅</a></span>
				商家管理系统 &copy; 2016-<?php echo date('Y');?>
			</span>
		</div>
	</div>
</div>

	<!-- 页脚结束 -->
	<!-- 返回顶端开始 -->
	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
	<!-- 返回顶端结束 -->
</div><!-- 整个页面内结束 -->

<!-- ace的js,可以通过打包生成,避免引入文件数多 -->
<script src="/public/ace/js/ace.min.js"></script>
<!-- jquery.form、layer、yfcmf的js -->
<script src="/public/others/jquery.form.js"></script>
<script src="/public/others/maxlength.js"></script>
<script src="/public/layer/layer.js"></script>
<?php $t=time(); ?>
<script src="/public/sanmi/sanmi.js?<?php echo ($t); ?>"></script>
<!-- 此页相关插件js -->

<!-- 与此页相关的js -->
</body>
</html>