<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title><?php echo (session('cfg_webname')); ?></title>

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
			<a href="<?php echo U('Admin/Index/index');?>" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					<?php echo ($_SESSION['sysconfig']['cfg_webname']); ?>
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
						<?php if($_SESSION['admin_avatar'] != ''): ?><img class="nav-user-photo" src="/data/upload/avatar/<?php echo ($_SESSION['admin_avatar']); ?>" alt="<?php echo ($_SESSION['admin_username']); ?>" />
							<?php else: ?>
							<img class="nav-user-photo" src="/public/img/girl.jpg" alt="<?php echo ($_SESSION['admin_username']); ?>" /><?php endif; ?>
								<span class="user-info">
									<small>欢迎,</small>
									<?php echo ($_SESSION['admin_username']); ?>
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo U('Admin/Sys/profile');?>">
								<i class="ace-icon fa fa-user"></i>
								会员中心
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo U('Admin/Login/logout');?>"  data-info="你确定要退出吗？" class="confirm-btn">
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
	<div id="sidebar" class="sidebar responsive sidebar-fixed">
	<div class="sidebar-shortcuts" id="sidebar-shortcuts">
		<!--左侧顶端按钮-->
		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
			<a class="btn btn-success" href="" role="button" title=""><i class="ace-icon fa fa-signal"></i></a>
			<a class="btn btn-info" href="" role="button" title=""><i class="ace-icon fa fa-pencil"></i></a>
			<a class="btn btn-warning" href="" role="button" title=""><i class="ace-icon fa fa-users"></i></a>
			<a class="btn btn-danger" href="" role="button" title=""><i class="ace-icon fa fa-cogs"></i></a>
		</div>
		<!--左侧顶端按钮（手机）-->
		<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
			<a class="btn btn-success" href="" role="button" title=""></a>
			<a class="btn btn-info" href="" role="button" title=""></a>
			<a class="btn btn-warning" href="" role="button" title=""></a>
			<a class="btn btn-danger" href="" role="button" title=""></a>
		</div>
	</div>
	<!-- 菜单列表开始 -->
	<ul class="nav nav-list">
		<!--一级菜单遍历开始-->
		<?php if(is_array($menus)): foreach($menus as $key=>$v): if(!empty($v['_child'])): ?><li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>open<?php endif; ?>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa <?php echo ($v["css"]); ?>"></i>
					<span class="menu-text"><?php echo ($v["title"]); ?></span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu">
				<!--二级菜单遍历开始-->
				<?php if(is_array($v["_child"])): foreach($v["_child"] as $key=>$vv): if(!empty($vv['_child'])): ?><li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active open<?php endif; ?>">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo ($vv["title"]); ?>
						<b class="arrow fa fa-angle-down"></b>
					</a>
					<b class="arrow"></b>
					<ul class="submenu">
						<!--三级菜单遍历开始-->
						<?php if(is_array($vv["_child"])): foreach($vv["_child"] as $key=>$vvv): ?><li class="<?php if((count($menus_curr) >= 3) AND ($menus_curr[2] == $vvv['id'])): ?>active<?php endif; ?>">
							<a href="<?php echo U($vvv['name']);?>">
								<i class="menu-icon fa fa-caret-right dis-ib"></i>
									<?php echo ($vvv["title"]); ?>
							</a>
							<b class="arrow"></b>
							</li><?php endforeach; endif; ?><!--三级菜单遍历结束-->
					</ul>
					</li>
					<?php else: ?>
					<li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active<?php endif; ?>">
					<a href="<?php echo U($vv['name']);?>">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo ($vv["title"]); ?>
					</a>
					<b class="arrow"></b>
					</li><?php endif; endforeach; endif; ?><!--二级菜单遍历结束-->
			</ul>
			</li>
			<?php else: ?>
			<li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>active<?php endif; ?>">
			<a href="<?php echo U($v['name']);?>">
				<i class="menu-icon fa fa-caret-right"></i>
				<?php echo ($v["title"]); ?>
			</a>
			<b class="arrow"></b>
			</li><?php endif; endforeach; endif; ?><!--一级菜单遍历结束-->
	</ul><!-- 菜单列表结束 -->

	<!-- 菜单栏缩进开始 -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div><!-- 菜单栏缩进结束 -->
</div>
	<!-- 菜单栏结束 -->

	<!-- 主要内容开始 -->
	<div class="main-content">
		<div class="main-content-inner">
			<!-- 右侧主要内容页顶部标题栏开始 -->
			<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse sidebar-fixed menu-min" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true" style="position:static;">
	<div class="nav-wrap-up pos-rel">
		<div class="nav-wrap">
			<ul class="nav nav-list">
				<?php if($id_curr != ''): if(is_array($menus_child)): foreach($menus_child as $key=>$k): ?><li>
					<a href="<?php echo U(''.$k['name'].'');?>">
					<o class="font12 <?php if($id_curr == $k['id']): ?>rigbg<?php endif; ?>"><?php echo ($k["title"]); ?></o>
					</a>
					<b class="arrow"></b>
				</li><?php endforeach; endif; ?>
				<?php else: ?>
				<li>
					<a href="<?php echo U('Admin/Index/index');?>">
						<o class="font12">欢迎进入<?php echo ($_SESSION['sysconfig']['cfg_webname']); ?>后台管理系统</o>
					</a>
					<b class="arrow"></b>
				</li><?php endif; ?>
			</ul>
		</div>
	</div><!-- /.nav-list -->
</div>
			<!-- 右侧主要内容页顶部标题栏结束 -->

			<!-- 右侧下主要内容开始 -->
			
            <div class="page-content">
                <!--主题-->
                <div class="page-header">
                    <h1>
                        您当前操作
                        <small>
                            <i class="ace-icon fa fa-angle-double-right"></i>
                            个人中心
                        </small>
                    </h1>
                </div>
                <div>
                    <div id="user-profile-1" class="user-profile row">
                        <div class="col-xs-12 col-sm-3 center">
                            <div>
                                <!-- #section:pages/profile.picture -->
								<span class="profile-picture">
									<a href="#">
                                        <?php if($admin["admin_avatar"] != ''): ?><img id="avatar" class="editable img-responsive" src="/data/upload/avatar/<?php echo ($admin['admin_avatar']); ?>" width="150"  data-toggle="modal" data-target="#myModal" />
                                            <?php else: ?>
                                            <img id="avatar" class="editable img-responsive" src="/public/img/girl.jpg" width="150"  data-toggle="modal" data-target="#myModal" /><?php endif; ?>
                                    </a>
								</span>

                                <!-- /section:pages/profile.picture -->
                                <div class="space-4"></div>
                                <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                    <div class="inline position-relative">
                                       <!--   <a href="<?php echo U('Sys/admin_group_access',array('id'=>$admin['id']));?>" class="user-title-label" title="配置权限">-->
                                       <a href="javascript:;" class="user-title-label">
                                            <i class="ace-icon fa fa-circle light-green"></i>
                                            &nbsp;
                                            <span class="white"><?php echo ($admin['title']); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="space-6"></div>

                            <!-- #section:pages/profile.contact -->
                            <div class="profile-contact-info">
                                <div class="profile-contact-links align-left">
                                    <span id="edit" class="btn btn-link" data-toggle="modal" data-target="#myModaledit">
                                        <i class="ace-icon fa fa-pencil bigger-120 green"></i>
                                        修改个人信息
                                    </span>
								</div>
                                <div class="space-6"></div>
                            </div>

                            <!-- /section:pages/profile.contact -->
                            <div class="hr hr12 dotted"></div>

                        </div>

                        <div class="col-xs-12 col-sm-9">

                            <!-- #section:pages/profile.info -->
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 用户名 </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="username"><?php echo ($admin['admin_username']); ?></span>													</div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 联系电话 </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="tel"><?php echo ((isset($admin['admin_tel']) && ($admin['admin_tel'] !== ""))?($admin['admin_tel']):'未设置'); ?></span>													</div>
                                </div>


                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 上次登录时间 </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="lasttime"><?php if($admin["admin_last_time"] == ''): ?>未登陆过<?php else: echo (date("Y-m-d H:i:s",$admin["admin_last_time"])); endif; ?></span>													</div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 上次登录IP </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="lastip"><?php if($admin["admin_ip"] == ''): ?>未登陆过<?php else: echo ($admin["admin_ip"]); endif; ?></span>													</div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 本次登录IP </div>

                                    <div class="profile-info-value">
                                        <span class="editable" id="loginip"><?php echo ($admin["admin_ip"]); ?></span>													</div>
                                </div>
                            </div>

                            <!-- /section:pages/profile.info -->
                            <div class="space-20"></div>


                        </div>
                    </div>

                </div>
                <link href="/public/shearphoto/css/ShearPhoto.css" rel="stylesheet" type="text/css" media="all">
                <script  type="text/javascript" src="/public/shearphoto/js/ShearPhoto.js" ></script>
                <script  type="text/javascript"  src="/public/shearphoto/js/alloyimage.js" ></script>
                <script  type="text/javascript"  src="/public/shearphoto/js/handle.js" ></script>
                <script type="text/javascript">
                    var SHEAR = {
                        PATH_RES: '/public',
                        PATH_AVATAR: '/data/upload/avatar',
                        URL:"<?php echo U('Sys/avatar');?>",
                    };
                </script>
                <!-- 显示模态框（Modal） -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:70%;">
                        <div class="modal-content"  style="min-width:450px;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    会员头像
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div id="shearphoto_loading">程序加载中......</div><!--这是2.2版本加入的缓冲效果，JS方法加载前显示的等待效果-->
                                        <div id="shearphoto_main">
                                            <!--效果开始.............如果你不要特效，可以直接删了........-->
                                            <div class="Effects" id="shearphoto_Effects" autocomplete="off">
                                                <strong class="EffectsStrong">截图效果</strong>
                                                <a href="javascript:;" StrEvent="原图" class="Aclick"><img src="/public/shearphoto/images/Effects/e0.jpg"/>原图</a>
                                                <a href="javascript:;" StrEvent="美肤"><img src="/public/shearphoto/images/Effects/e1.jpg"/>美肤效果</a>
                                                <a href="javascript:;" StrEvent="素描"><img src="/public/shearphoto/images/Effects/e2.jpg"/>素描效果</a>
                                                <a href="javascript:;" StrEvent="自然增强"><img src="/public/shearphoto/images/Effects/e3.jpg" />自然增强</a>
                                                <a href="javascript:;" StrEvent="紫调"><img src="/public/shearphoto/images/Effects/e4.jpg" />紫调效果</a>
                                                <a href="javascript:;" StrEvent="柔焦"><img src="/public/shearphoto/images/Effects/e5.jpg"/>柔焦效果</a>
                                                <a href="javascript:;" StrEvent="复古"><img src="/public/shearphoto/images/Effects/e6.jpg"/>复古效果</a>
                                                <a href="javascript:;" StrEvent="黑白"><img src="/public/shearphoto/images/Effects/e7.jpg"/>黑白效果</a>
                                                <a href="javascript:;" StrEvent="仿lomo"><img src="/public/shearphoto/images/Effects/e8.jpg"/>仿lomo</a>
                                                <a href="javascript:;" StrEvent="亮白增强"><img src="/public/shearphoto/images/Effects/e9.jpg"/>亮白增强</a>
                                                <a href="javascript:;" StrEvent="灰白"><img src="/public/shearphoto/images/Effects/e10.jpg"/>灰白效果</a>
                                                <a href="javascript:;" StrEvent="灰色"><img src="/public/shearphoto/images/Effects/e11.jpg"/>灰色效果</a>
                                                <a href="javascript:;" StrEvent="暖秋"><img src="/public/shearphoto/images/Effects/e12.jpg"/>暖秋效果</a>
                                                <a href="javascript:;" StrEvent="木雕"><img src="/public/shearphoto/images/Effects/e13.jpg"/>木雕效果</a>
                                                <a href="javascript:;" StrEvent="粗糙"><img src="/public/shearphoto/images/Effects/e14.jpg"/>粗糙效果</a>
                                            </div>
                                            <!--效果结束...........................如果你不要特效，可以直接删了.....................................................-->
                                            <!--primary范围开始-->
                                            <div class="primary">
                                                <!--main范围开始-->
                                                <div id="main">
                                                    <div class="point">
                                                    </div>
                                                    <!--选择加载图片方式开始-->

                                                    <div id="SelectBox">
                                                        <form id="ShearPhotoForm" enctype="multipart/form-data" method="post"  target="POSTiframe">
                                                            <input name="shearphoto" type="hidden" value="123" autocomplete="off">
                                                            <a href="javascript:;" id="selectImage"><input type="file"  name="UpFile" autocomplete="off"/></a>
                                                        </form>
                                                        <a href="javascript:;" id="PhotoLoading"></a>
                                                    </div>
                                                    <!--选择加载图片方式结束--->
                                                    <div id="relat">
                                                        <div id="black">
                                                        </div>
                                                        <div id="movebox">
                                                            <div id="smallbox">
                                                                <img src="/public/shearphoto/images/default.gif" class="MoveImg" /><!--截框上的小图-->
                                                            </div>
                                                            <!--动态边框开始-->
                                                            <i id="borderTop">
                                                            </i>

                                                            <i id="borderLeft">
                                                            </i>

                                                            <i id="borderRight">
                                                            </i>

                                                            <i id="borderBottom">
                                                            </i>
                                                            <!--动态边框结束-->
                                                            <i id="BottomRight">
                                                            </i>
                                                            <i id="TopRight">
                                                            </i>
                                                            <i id="Bottomleft">
                                                            </i>
                                                            <i id="Topleft">
                                                            </i>
                                                            <i id="Topmiddle">
                                                            </i>
                                                            <i id="leftmiddle">
                                                            </i>
                                                            <i id="Rightmiddle">
                                                            </i>
                                                            <i id="Bottommiddle">
                                                            </i>
                                                        </div>
                                                        <img src="/public/shearphoto/images/default.gif" class="BigImg" /><!--MAIN上的大图-->
                                                    </div>
                                                </div>
                                                <!--main范围结束-->
                                                <div style="clear: both"></div>
                                                <!--工具条开始-->
                                                <div id="Shearbar">
                                                    <a id="LeftRotate" href="javascript:;">
                                                        <em>
                                                        </em>
                                                        向左旋转
                                                    </a>
                                                    <em class="hint L">
                                                    </em>
                                                    <div class="ZoomDist" id="ZoomDist">
                                                        <div id="Zoomcentre">
                                                        </div>
                                                        <div id="ZoomBar">
                                                        </div>
                        <span class="progress">
                        </span>
                                                    </div>
                                                    <em class="hint R">
                                                    </em>
                                                    <a id="RightRotate" href="javascript:;">
                                                        向右旋转
                                                        <em>
                                                        </em>
                                                    </a>
                                                    <p class="Psava">
                                                        <a id="againIMG"  href="javascript:;">重新选择</a>
                                                        <a id="saveShear" href="javascript:;">保存截图</a>
                                                    </p>
                                                </div>
                                                <!--工具条结束-->
                                            </div>
                                            <!--primary范围结束-->
                                            <div style="clear: both"></div>
                                        </div>
                                        <!--shearphoto_main范围结束-->

                                        <!--主功能部份 主功能部份的标签请勿随意删除，除非你对shearphoto的原理了如指掌，否则JS找不到DOM对象，会给你抱出错误-->
                                        <!--相册-->
                                        <div id="photoalbum"><!--假如你不要这个相册功能。把相册标签删除了，JS会抱出一个console.log()给你，注意查收，console.log的内容是告诉，某个DOM对象找不到-->
                                            <h1>请从相册中选择</h1>
                                            <i id="close"></i>
                                            <ul>
                                                <li><img src="/public/shearphoto/file/photo/1.jpg" serveUrl="file/photo/1.jpg" /></li>
                                                <li><img src="/public/shearphoto/file/photo/2.jpg" serveUrl="file/photo/2.jpg" /></li>
                                                <li><img src="/public/shearphoto/file/photo/3.jpg" serveUrl="file/photo/3.jpg" /></li>
                                                <li><img src="/public/shearphoto/file/photo/4.jpg" serveUrl="file/photo/4.jpg" /></li>
                                            </ul>
                                        </div>
                                        <!--相册-->
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!-- 显示修改资料模态框（Modal） -->
                <div class="modal fade in" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <form class="form-horizontal userEdit" name="admin_runedit" method="post" action="<?php echo U('Rule/Rule/admin_runedit');?>">
                        <input type="hidden" name="admin_id" value="<?php echo ($admin["admin_id"]); ?>" />
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" id="gb"  data-dismiss="modal"
                                            aria-hidden="true">×
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel2">
                                        修改用户信息
                                    </h4>
                                </div>
                                <div class="modal-body">


                                    <div class="row">
                                        <div class="col-xs-12">


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 用户邮箱：  </label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="admin_email" id="admin_email" value="<?php echo ($admin["admin_email"]); ?>" class="col-xs-10 col-sm-5" required/>
                                                    <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                                                </div>
                                            </div>
                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 登录密码：  </label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="admin_pwd" id="admin_pwd" maxlength="20" minlength="6" placeholder="为空不修改密码" class="col-xs-10 col-sm-5" />
                                                    <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span>密码为6-20位数字、字母、字符组合</span>
                                                </div>
                                            </div>
                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 手机号码：  </label>
                                                <div class="col-sm-10">
                                                    <input type="tel" name="admin_tel" id="admin_tel" placeholder="输入手机号" value="<?php echo ($admin["admin_tel"]); ?>" class="col-xs-10 col-sm-5" required/>
                                                    <span class="lbl col-xs-12 col-sm-7"><span class="red">*</span></span>
                                                </div>
                                            </div>

                                            <div class="space-4"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 是否审核： </label>
                                                <div class="col-sm-10" style="padding-top:5px;">
                                                    <input name="admin_open" id="admin_open" <?php if($admin['admin_open'] == 1): ?>checked<?php endif; ?>  value="1" class="ace ace-switch" type="checkbox" />
                                                    <span class="lbl">默认关闭</span>
                                                </div>
                                            </div>
                                            <div class="space-4"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        提交保存
                                    </button>
                                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">
                                        关闭-->
                                    </button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </form>
                </div><!-- /.modal -->
            </div>

			<!-- 右侧下主要内容结束 -->
		</div>
	</div><!-- 主要内容结束 -->
	<!-- 页脚开始 -->
	<div class="footer">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120">
				<span class="blue bolder"><a href="javascript:;" target="_ablank"><?php echo ($_SESSION['sysconfig']['cfg_webname']); ?></a></span>
				后台管理系统 &copy; 2016-<?php echo date('Y');?>
			</span>
			<span class="bigger-120">
				<a target="_blank" href="http://zhanzhang.baidu.com/">站长统计</a>
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

	<script>
	$(function(){
	
		 $("#admin_pwd").blur(function(){
				var pwd =$(this).val();
				
				//var reg =  /^[0-9a-zA-Z]*$/g;  //判断字符串是否为数字和字母组合     //判断正整数 /^[1-9]+[0-9]*]*$/
				 //var reg=/^(([0-9]+)|([a-z]+)|([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i;
				if($.trim(pwd)){
					var reg= /(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,20}$/;
					if(!reg.test(pwd)){
					 	layer.alert("密码为6-20位数字、字母、字符组合");
				        $(this).val("");
				        return false; 
					}
				}
			});
 
	})
	
	</script>

<!-- 与此页相关的js -->
</body>
</html>