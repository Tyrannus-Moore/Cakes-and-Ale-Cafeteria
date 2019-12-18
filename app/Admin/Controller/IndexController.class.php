<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
class IndexController extends AuthController {
	public function index(){
		//未登录
		if (empty($_SESSION['aid'])){
			$this->redirect('Admin/Login/login');
		}
		//系统信息
		$info = array(
			'PCTYPE'=>PHP_OS,
			'RUNTYPE'=>$_SERVER["SERVER_SOFTWARE"],
			'ONLOAD'=>ini_get('upload_max_filesize'),
			'ThinkPHPTYE'=>THINK_VERSION,
		);
		$this->assign('info',$info);
		 
		//渲染模板
		$this->display();
	}
}