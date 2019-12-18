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
			
    <div class="page-content" style="padding-top: 50px">
        <!--主题-->
        <div class="page-header">
            <h1>您当前操作
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>编辑菜品
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form class="form-horizontal ajaxFormFo" name="admin_list_add" method="post" action="<?php echo U('foodEdit');?>">
                    <input type="hidden" name="dishes_id" value="<?php echo ($infos["dishes_id"]); ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 菜品类型：  </label>
                        <div class="col-sm-6" style="display: flex;">
                            <select name="type" class="form-control type" style="width:100px" required>
                                <option value="1" <?php if($infos['type'] == 1): ?>selected<?php endif; ?>>普通菜品</option>
                                <option value="2" <?php if($infos['type'] == 2): ?>selected<?php endif; ?>>周餐菜品</option>
                            </select>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <?php if($infos['type'] == 2): ?><div class="form-group taocan">
                            <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 商品信息： </label>
                            <div class="col-sm-9">
                                <table class="table table-striped table-bordered table-hover" style="width:1050px;" id="dynamic-table">
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs"  style="text-align: center">周/餐</th>
                                        <th class="hidden-xs" style="text-align: center">早餐</th>
                                        <th class="hidden-xs" style="text-align: center">午餐</th>
                                        <th class="hidden-xs" style="text-align: center">晚餐</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周一</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[0][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[0][dishes_ids])): ?>无<?php else: echo ($dishes_meal[0][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="11" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>11));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[1][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[1][dishes_ids])): ?>无<?php else: echo ($dishes_meal[1][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="12" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>12));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[2][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[2][dishes_ids])): ?>无<?php else: echo ($dishes_meal[2][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="13" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>13));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周二</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[3][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[3][dishes_ids])): ?>无<?php else: echo ($dishes_meal[3][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="21" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>21));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[4][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[4][dishes_ids])): ?>无<?php else: echo ($dishes_meal[4][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="22" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>22));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[5][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[5][dishes_ids])): ?>无<?php else: echo ($dishes_meal[5][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="23" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>23));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周三</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[6][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[6][dishes_ids])): ?>无<?php else: echo ($dishes_meal[6][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="31" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>31));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[7][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[7][dishes_ids])): ?>无<?php else: echo ($dishes_meal[7][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="32" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>32));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[8][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[8][dishes_ids])): ?>无<?php else: echo ($dishes_meal[8][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="33" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>33));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周四</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[9][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[9][dishes_ids])): ?>无<?php else: echo ($dishes_meal[9][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="41" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>41));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[10][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[10][dishes_ids])): ?>无<?php else: echo ($dishes_meal[10][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="42" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>42));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[11][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[11][dishes_ids])): ?>无<?php else: echo ($dishes_meal[11][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="43" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>43));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周五</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[12][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[12][dishes_ids])): ?>无<?php else: echo ($dishes_meal[12][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="51" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>51));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[13][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[13][dishes_ids])): ?>无<?php else: echo ($dishes_meal[13][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="52" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>52));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[14][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[14][dishes_ids])): ?>无<?php else: echo ($dishes_meal[14][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="53" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>53));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周六</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[15][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[15][dishes_ids])): ?>无<?php else: echo ($dishes_meal[15][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="61" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>61));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[16][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[16][dishes_ids])): ?>无<?php else: echo ($dishes_meal[16][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="62" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>62));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[17][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[17][dishes_ids])): ?>无<?php else: echo ($dishes_meal[17][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="63" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>63));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hidden-xs" style="text-align: center">周日</td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[18][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[18][dishes_ids])): ?>无<?php else: echo ($dishes_meal[18][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="71" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>71));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[19][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[19][dishes_ids])): ?>无<?php else: echo ($dishes_meal[19][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="72" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>72));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                        <td class="hidden-xs">
                                            <input type="hidden" name="ids[]" value="<?php echo ($dishes_meal[20][dishes_ids]); ?>">
                                            <p style="float: left">
                                                <?php if(empty($dishes_meal[20][dishes_ids])): ?>无<?php else: echo ($dishes_meal[20][dishes_name]); endif; ?>
                                            </p>
                                            <a class="btn btn-minier btn-success" style="float: right" id="73" href="javascript:;" onclick="iframek('<?php echo U('choose',array('nid'=>73));?>','选择')" title="选择" >
                                                选择
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="space-4"></div><?php endif; ?>
                   <!--  <?php if(($infos["type"]) == "1"): ?><div class="form-group mode">
                            <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 配送方式：  </label>
                            <div class="checkbox">
                                <ul>
                                    <label>
                                        <input class="ace ace-checkbox-2" name="status[]" <?php if(in_array(($a), is_array($infos['status'])?$infos['status']:explode(',',$infos['status']))): ?>checked<?php endif; ?> value="1" type="checkbox" >
                                        <span class="lbl">配送</span>
                                    </label>
                                    <label>
                                        <input class="ace ace-checkbox-2" name="status[]" value="2" type="checkbox" <?php if(in_array(($b), is_array($infos["status"])?$infos["status"]:explode(',',$infos["status"]))): ?>checked<?php endif; ?> >
                                        <span class="lbl">自提</span>
                                    </label>
                                </ul>
                                
                            </div>
                        </div><?php endif; ?> -->
                    
                    <div class="form-group" id="pic_list">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">菜品图片： </label>
                        <div class="col-sm-10">
                            <a href="javascript:;" class="file" title="点击选择所要上传的图片">
                                <input type="file" name="img" id="file0" multiple="multiple"/>
                                选择上传文件
                            </a>&nbsp;&nbsp;
                            <a href="javascript:;" onclick="return backpic('<?php if($infos["pic_url"] == ''): ?>/public/img/no_img.jpg<?php else: ?><?php echo ($infos["pic_url"]); endif; ?>');" title="还原修改前的图片" class="file">
                            撤销上传
                            </a>
                            <div><img src="<?php if($infos["pic_url"] != ''): ?><?php echo ($infos["pic_url"]); else: ?>/public/img/no_img.jpg<?php endif; ?>" height="70" id="img0" ></div>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*图片比例218×218px</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 菜品名称：  </label>
                        <div class="col-sm-6">
                            <input type="text" name="dishes_name" value="<?php echo ($infos["dishes_name"]); ?>" maxlength="20" class="col-xs-10 col-sm-5" required/>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 档口名称：  </label>
                        <div class="col-sm-6" style="display: flex;">
                            <select name="stall" class="form-control stall" style="width:200px" required>
                                <?php if(is_array($stall_list)): foreach($stall_list as $key=>$v): ?><option value="<?php echo ($v['stall_id']); ?>" <?php if($infos['stall'] == $v['stall_id']): ?>selected<?php endif; ?>><?php echo ($v['stall_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 分类名称：  </label>
                        <div class="col-sm-6" style="display: flex;">
                            <select name="dishes_cate_id" class="form-control" style="width:100px" required>
                                <?php if(is_array($dishes_cate_list)): foreach($dishes_cate_list as $key=>$v): ?><option value="<?php echo ($v['dishes_cate_id']); ?>" <?php if($infos['dishes_cate_id'] == $v['dishes_cate_id']): ?>selected<?php endif; ?>><?php echo ($v['cat_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 菜品价格：  </label>
                        <div class="col-sm-6">
                            <input type="text" name="price" value="<?php echo ($infos["price"]); ?>" class="col-xs-10 col-sm-5" required onkeyup="this.value=this.value.replace(/^(\d*\.?\d{0,2}).*/,'$1')"  />
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 热量值：  </label>
                        <div class="col-sm-6">
                            <input type="number" name="hot" step="1" min="1" value="<?php echo ($infos["hot"]); ?>" class="col-xs-10 col-sm-5" required/>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 库存：  </label>
                        <div class="col-sm-6">
                            <input type="number" name="num" step="1" min="1" value="<?php echo ($infos["num"]); ?>" class="col-xs-10 col-sm-5" required/>
                            <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 活动类型：  </label>
                        <div class="col-sm-6" style="display: flex;">
                            <select name="statue" class="form-control" id="statue" style="width:100px" required>
                                <option value="1" <?php if($infos['statue'] == 1): ?>selected<?php endif; ?>>普通菜品</option>
                                <option value="2" <?php if($infos['statue'] == 2): ?>selected<?php endif; ?>>精选推荐</option>
                                <option value="3" <?php if($infos['statue'] == 3): ?>selected<?php endif; ?>>限时折扣</option>
                            </select>
                            <span class="lbl col-xs-1"><span class="red">*</span></span>
                            <div <?php if(($infos["statue"]) == "2"): ?>style="display: block" <?php else: ?> style="display: none"<?php endif; ?> class="discount">    
                                <input type="number" name="discount" style="width: 80px;" step="0.1" min="0.1" value="<?php echo ($infos['discount']); ?>" max="10" placeholder="折扣"  /> 折
                            </div>
                            
                        </div>
                    </div>
                    <div class="space-4"></div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 上/下架： </label>
                        <div class="col-sm-3">
                            <input type="radio" name="state" value="1" <?php if($infos['state'] == 1): ?>checked<?php endif; ?> />上架
                            <input type="radio" name="state" value="2" <?php if($infos['state'] == 2): ?>checked<?php endif; ?>/>下架
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 菜品描述： </label>
                        <div class="col-sm-6">
                            <script src="/public/ueditor/ueditor.config.js" type="text/javascript"></script>
                            <script src="/public/ueditor/ueditor.all.js" type="text/javascript"></script>
                            <textarea name="content" rows="100%" style="width:100%;height:350px;" id="myEditor"><?php echo ($infos["content"]); ?></textarea>
                            <script type="text/javascript">
                                var editor = new UE.ui.Editor();
                                editor.render("myEditor");
                            </script>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 排序：  </label>
                        <div class="col-sm-7">
                            <input type="number" min="1" name="sort" value="<?php echo ($infos["sort"]); ?>" placeholder="排序" class="col-xs-10 col-sm-5" required/>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                保存
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <a href="javascript:history.back(-1)">
                                <i class="ace-icon fa fa-undo bigger-110"></i> 返回
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.page-content -->
    <script>
        //iframe框架
        function iframek(url,title){
            layer.open({
                type: 2,
                title: title,
                maxmin: true,
                area: ['1200px', '700px'],
                content: url,
            });
        };
        //菜品切换
        $('#statue').change(function(){
            var statue = $(this).val();
            if(statue == 2){
                $(".discount").css('display','block');//表示设置为可用
            }else{
                $(".discount").css('display','none');//表示设置为可用
            }
        })
        $(".type").change(function () {
            var val = $(this).val();
            if(val == 1){
                // $('.mode').show();
                $(".taocan").hide();
                $.ajax({
                    url:"<?php echo U('type');?>",
                    data:{
                        type:1
                    },
                    success:function(data) {
                        addList(data);
                    }
                });
            }else{
                // $('.mode').hide();
                $(".taocan").show();
                $.ajax({
                    url:"<?php echo U('type');?>",
                    data:{
                        type:2
                    },
                    success:function(data) {
                        addList(data);
                    }
                });
            }
        });
        function addList(data) {
            var str = '';
            $.each(data,function(i){
                str += '<option value="'+data[i]['stall_id']+'">'+data[i]['stall_name']+'</option>';
            });
            $(".stall").empty();
            $(".stall").append(str);
        };
        $(function(){
            $('.ajaxFormFo').ajaxForm({
                beforeSubmit: BeforeFormF,
                success: complete2, // 这是提交后的方法
                dataType: 'json'
            });
        });
        var  zzf;
        //商家提交前验证
        function BeforeFormF() {
            var type = $(".type").val();
            if(type == 2){
                var arr = [];
                var ids = $('input[name="ids[]"]');
                for(var i = 0; i < ids.length; i ++){
                    if($(ids[i]).val() != ''){
                        arr.push($(ids[i]).val())
                    }
                }
                if(arr.length == 0){
                    layer.alert('周餐必须添加菜品！', {icon: 5});
                    return false;
                }
            }else{
                // var ids = []; 

                // $('input[name="status[]"]:checked').each(function(){
                //     ids.push($(this).val);
                // });
                // if(ids.length<1){
                //     layer.alert('请选择配送方式', {icon: 5});
                //     return false;
                // }
            }
            zzf  = layer.load(1, {shade: [0.8, '#393D49']});
        }
        //失败不跳转
        function complete2(data) {
            if (data.status == 1) {
                layer.alert(data.info, {icon: 6}, function (index) {
                    layer.close(index);
                    layer.close(zzf);
                    window.location.href = data.url;
                });
            } else {
                layer.alert(data.info, {icon: 5}, function (index) {
                    layer.close(index);
                    layer.close(zzf);
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