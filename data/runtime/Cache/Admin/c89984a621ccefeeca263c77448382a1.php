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
					站点设置
				</small>
			</h1>
		</div>
		<div class="row">

			<div class="tabbable">
				<ul class="nav nav-tabs" id="myTab">
					<li class="active">
						<a data-toggle="tab" href="#basic">
							基本设置
						</a>
					</li>

					<!-- <li>
						<a data-toggle="tab" href="#contact">
							联系方式
						</a>
					</li>

					<li class="dropdown">
						<a data-toggle="tab" href="#seo">
							SEO设置
						</a>
					</li> -->
				</ul>
				<form class="form-horizontal setting" name="sys" method="post" action="<?php echo U('runsys');?>">
					<fieldset>
						<div class="tab-content">
							<div id="basic" class="tab-pane fade in active">
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 站点名称 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_webname" id="cfg_webname" onkeyup="this.value=this.value.replace(/\s+/g,'')" value="<?php echo ($info["cfg_webname"]); ?>" class="col-xs-10 col-sm-5" required/>
										<span class="lbl col-xs-12 col-sm-7">
											<span class="red" id="resone">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<!-- <div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 站点网址 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_basehost" id="cfg_basehost" value="<?php echo ($info["cfg_basehost"]); ?>"  class="col-xs-10 col-sm-5" required/>
										<span class="lbl col-xs-12 col-sm-7">
											<span class="red" id="restwo">*</span><span>请输入正确URL地址以http://开头</span>
										</span>
									</div>
								</div>
								 
								<div class="space-4"></div>

								<div class="form-group" id="pic_list">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 网站LOGO </label>
									<div class="col-sm-9">
										<input type="hidden" name="checkpic" id="checkpic" value="<?php echo ($info["cfg_medias_dir"]); ?>" />
										<input type="hidden" name="oldcheckpic" id="oldcheckpic" value="<?php echo ($info["cfg_medias_dir"]); ?>" />
										<a href="javascript:;" class="file" title="点击选择所要上传的图片">
											<input type="file" name="file0" id="file0" multiple="multiple"/>
											选择上传文件
										</a>
										&nbsp;&nbsp;<a href="javascript:;" onclick="return backpic('<?php if($info["cfg_medias_dir"] == ''): ?>/public/img/no_img.jpg<?php else: ?><?php echo ($info["cfg_medias_dir"]); endif; ?>');" title="还原修改前的图片" class="file">
										撤销上传
										</a>
										<div><img src="<?php if($info["cfg_medias_dir"] != ''): ?><?php echo ($info["cfg_medias_dir"]); else: ?>/public/img/no_img.jpg<?php endif; ?>" height="70" id="img0" ></div>
									</div>
								</div>
								<div class="space-4"></div>
								
                                <div class="form-group" id="pic_list">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 水印 </label>
									<div class="col-sm-9">
										<input type="hidden" name="checkpic1" id="checkpic1" value="<?php echo ($info["cfg_watermark"]); ?>" />
										<input type="hidden" name="oldcheckpic1" id="oldcheckpic1" value="<?php echo ($info["cfg_watermark"]); ?>" />

										<a href="javascript:;" class="file" title="点击选择所要上传的图片">
											<input type="file" name="file1" id="file1" multiple="multiple"/>
											选择上传文件
										</a>
										&nbsp;&nbsp;<a href="javascript:;" onclick="return backpic1('<?php if($info["cfg_watermark"] == ''): ?>/public/img/no_img.jpg<?php else: ?><?php echo ($info["cfg_watermark"]); endif; ?>');" title="还原修改前的图片" class="file">
										撤销上传
										</a>
										<div><img src="<?php if($info["cfg_watermark"] != ''): ?><?php echo ($info["cfg_watermark"]); else: ?>/public/img/no_img.jpg<?php endif; ?>" height="70" id="img1" ></div>
									</div>
								</div>
								<div class="space-4"></div>
					
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 首页视频弹窗 </label>
									<div class="col-sm-9">
										<!-- <input type="text" name="cfg_video" id="cfg_video" value="<?php echo ($info["cfg_video"]); ?>"  class="col-xs-10 col-sm-5" required/> -->
										<!-- <textarea  name="cfg_video" cols="20" rows="2" class="col-xs-10 col-sm-7" required><?php echo ($info["cfg_video"]); ?></textarea>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="resone">*</span><span>请输入上传视频网站的通用代码(如腾讯，爱奇艺，优酷等)</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 备案信息 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_beian" id="cfg_beian" onkeyup="this.value=this.value.replace(/\s+/g,'')" value="<?php echo ($info["cfg_beian"]); ?>"  class="col-xs-10 col-sm-5" required/>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="resone">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 统计代码 </label>
									<div class="col-sm-9">
										<textarea  name="cfg_statistics" cols="20" rows="2" class="col-xs-10 col-sm-7 limited" onkeyup="this.value=this.value.replace(/\s+/g,'')"  id="form-field-9"  maxlength="100" required><?php echo ($info["cfg_statistics"]); ?></textarea>
										<input type="hidden" name="maxlengthone" value="100" />
								<span class="lbl  col-xs-5 col-sm-5">
									还可以输入 <span class="middle charsLeft"></span> 个字符
								</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 版权信息 </label>
									<div class="col-sm-9">
										<textarea  name="cfg_powerby" cols="20" rows="3" onkeyup="this.value=this.value.replace(/\s+/g,'')" class="col-xs-10 col-sm-7 limited1"   id="form-field-10"  maxlength="150" required><?php echo ($info["cfg_powerby"]); ?></textarea>
										<input type="hidden" name="maxlengthone" value="150" />
								<span class="lbl  col-xs-5 col-sm-5">
									还可以输入 <span class="middle charsLeft1" style="color:red;"></span> 个字符
								</span>
									</div>
								</div>
								<div class="space-4"></div>
							</div>

							<div id="contact" class="tab-pane fade">
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 公司名称 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_company" id="cfg_company" onkeyup="this.value=this.value.replace(/\s+/g,'')" value="<?php echo ($info["cfg_company"]); ?>" class="col-xs-10 col-sm-5" required/>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="resone">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 公司地址 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_address" id="cfg_address" onkeyup="this.value=this.value.replace(/\s+/g,'')" value="<?php echo ($info["cfg_address"]); ?>"  class="col-xs-10 col-sm-5" required/>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="restwo">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 联系电话 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_tel" id="cfg_tel" onkeyup="this.value=this.value.replace(/\s+/g,'')" value="<?php echo ($info["cfg_tel"]); ?>"  class="col-xs-10 col-sm-5" required/>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="restwo">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

							</div>

							<div id="seo" class="tab-pane fade">
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 首页SEO标题 </label>
									<div class="col-sm-9">
										<input type="text" name="cfg_title" id="cfg_title" value="<?php echo ($info["cfg_title"]); ?>"  class="col-xs-10 col-sm-5" required/>
										<span class="lbl  col-xs-12 col-sm-7">
											<span class="red" id="resthr">*</span>
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 首页SEO关键字 </label>
									<div class="col-sm-9">
										<textarea  name="cfg_keywords" cols="20" class="col-xs-10 col-sm-7 limited2"  id="form-field-11"  maxlength="100" required><?php echo ($info["cfg_keywords"]); ?></textarea>
										<input type="hidden" name="maxlength" value="100" />
										<span class="lbl  col-xs-5 col-sm-5">
											还可以输入 <span class="middle charsLeft2"></span> 个字符,以英文 , 号隔开
										</span>
									</div>
								</div>
								<div class="space-4"></div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 首页SEO描述 </label>
									<div class="col-sm-9">
										<textarea  name="cfg_description" cols="20" rows="4" class="col-xs-10 col-sm-7 limited3"   id="form-field-12"  maxlength="200" required><?php echo ($info["cfg_description"]); ?></textarea>
										<input type="hidden" name="maxlengthone" value="200" />
										<span class="lbl  col-xs-5 col-sm-5">
											还可以输入 <span class="middle charsLeft3" style="color:red;"></span> 个字符
										</span>
									</div>
								</div>
								<div class="space-4"></div> --> 
							</div>
						</div>
						<div class="clearfix form-actions">
							<div class="col-sm-offset-3 col-sm-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									保存
								</button>

							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>

	</div><!-- /.page-content -->

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

<!-- 与此页相关的js -->
</body>
</html>